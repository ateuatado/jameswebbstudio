"""
Lambda: process_photo
======================
Trigger: S3 Event — s3:ObjectCreated:* no prefixo originals/{project_id}/

O que faz:
  1. Lê a foto original do S3 (originals/{project_id}/filename.jpg)
  2. Redimensiona para no máximo 1500px na maior dimensão
  3. Converte para JPEG com 75% de qualidade (proxy leve)
  4. Aplica marca d'água (logo.png) no canto inferior direito
  5. Salva em proxies/{project_id}/filename.jpg

Dependências (Lambda Layer ou requirements.txt):
  - Pillow >= 10.0.0
  - boto3 (já incluso no runtime AWS)

Variáveis de Ambiente Lambda:
  - WATERMARK_KEY   : chave S3 do logo (ex: assets/logo.png)
  - PROXY_MAX_SIZE  : tamanho máximo da maior dimensão em px (default: 1500)
  - PROXY_QUALITY   : qualidade JPEG 1-95 (default: 75)
  - WATERMARK_OPACITY: opacidade da marca d'água 0.0–1.0 (default: 0.35)
"""

import os
import io
import logging
import json
import urllib.parse
import urllib.request
import boto3
from PIL import Image, ImageDraw, ImageFont

logger = logging.getLogger()
logger.setLevel(logging.INFO)

s3 = boto3.client("s3")

# ─── Configurações via variáveis de ambiente ──────────────────────────────────
WATERMARK_KEY      = os.environ.get("WATERMARK_KEY", "assets/logo.png")
PROXY_MAX_SIZE     = int(os.environ.get("PROXY_MAX_SIZE", "900"))
PROXY_QUALITY      = int(os.environ.get("PROXY_QUALITY", "65"))
WATERMARK_OPACITY  = float(os.environ.get("WATERMARK_OPACITY", "0.35"))
WATERMARK_SCALE    = float(os.environ.get("WATERMARK_SCALE", "0.20"))  # 20% da largura da foto

# Configurações de API e IA
HERO_API_URL       = os.environ.get("HERO_API_URL")
HERO_API_TOKEN     = os.environ.get("HERO_API_TOKEN")
ENABLE_AI          = os.environ.get("ENABLE_AI", "true").lower() == "true"

# Dicionário de Tradução de Etiquetas do Rekognition (Inglês -> Português)
TRANSLATION_MAP = {
    "person": "pessoa",
    "human": "pessoa",
    "people": "pessoas",
    "woman": "mulher",
    "female": "mulher",
    "lady": "mulher",
    "man": "homem",
    "male": "homem",
    "child": "criança",
    "kid": "criança",
    "boy": "menino",
    "girl": "menina",
    "baby": "bebê",
    "infant": "bebê",
    "toddler": "bebê",
    "dog": "cachorro",
    "canine": "cão",
    "pet": "pet",
    "cat": "gato",
    "feline": "gato",
    "sitting": "sentado",
    "seated": "sentado",
    "standing": "em pé",
    "smiling": "sorrindo",
    "smile": "sorriso",
    "laughter": "risada",
    "happy": "feliz",
    "clothing": "roupa",
    "apparel": "roupa",
    "garment": "roupa",
    "shirt": "blusa/camisa",
    "t-shirt": "camiseta",
    "blouse": "blusa",
    "pants": "calça",
    "trousers": "calça",
    "jeans": "jeans",
    "denim": "jeans",
    "dress": "vestido",
    "gown": "vestido",
    "skirt": "saia",
    "coat": "casaco",
    "jacket": "jaqueta",
    "sweater": "casaco",
    "suit": "terno",
    "tuxedo": "terno",
    "blazer": "blazer",
    "footwear": "calçado",
    "shoe": "sapato",
    "boot": "bota",
    "sneakers": "tênis",
    "hat": "chapéu",
    "cap": "boné",
    "glasses": "óculos",
    "eyeglasses": "óculos",
    "sunglasses": "óculos escuros",
    "hair": "cabelo",
    "cake": "bolo",
    "birthday cake": "bolo de aniversário",
    "flower": "flor",
    "flowers": "flores",
    "flora": "flor",
    "blossom": "flor",
    "plant": "planta",
    "vegetation": "planta",
    "garden": "jardim",
    "tree": "árvore",
    "grass": "grama",
    "beach": "praia",
    "sand": "areia",
    "sea": "mar",
    "ocean": "mar",
    "water": "água",
    "sky": "céu",
    "sun": "sol",
    "sunny": "sol",
    "sunlight": "luz solar",
    "studio": "estúdio",
    "indoors": "interno",
    "outdoors": "externo",
    "nature": "natureza",
    "furniture": "móveis",
    "chair": "cadeira",
    "sofa": "sofá",
    "couch": "sofá",
    "table": "mesa",
    "desk": "mesa",
    "piano": "piano",
    "keyboard": "teclado",
    "musical instrument": "instrumento musical",
    "guitar": "violão/guitarra",
    "bed": "cama",
    "bedroom": "quarto",
    "kitchen": "cozinha",
    "sink": "pia",
    "toy": "brinquedo",
    "balloon": "balão",
    "car": "carro",
    "vehicle": "veículo",
    "food": "comida",
    "fruit": "fruta",
    "wine": "vinho",
    "glass": "taça/copo",
    "drink": "bebida",
    "beverage": "bebida",
    "ring": "anel",
    "jewelry": "joia",
    "book": "livro",
    "computer": "computador",
    "laptop": "notebook",
    "phone": "celular",
    "mobile phone": "celular",
    "wall": "parede",
    "brick": "tijolo",
    "leather": "couro",
    "wood": "madeira",
    "white": "branco",
    "black": "preto",
    "red": "vermelho",
    "blue": "azul",
    "green": "verde",
    "yellow": "amarelo",
    "pink": "rosa",
    "purple": "roxo",
    "brown": "marrom",
    "grey": "cinza",
    "gray": "cinza",
    "orange": "laranja"
}


def translate_label(label: str) -> str:
    """Traduz etiquetas detectadas pelo Rekognition para português usando o mapa local."""
    label_lower = label.lower().strip()
    return TRANSLATION_MAP.get(label_lower, label_lower)


def analyze_image_with_rekognition(bucket: str, key: str) -> tuple:
    """
    Analisa a imagem no S3 usando o Amazon Rekognition.
    Retorna uma tupla (descricao, lista_de_tags).
    """
    try:
        rek = boto3.client("rekognition", region_name=os.environ.get("AWS_REGION", "us-east-2"))
        
        # 1. Detecta labels/objetos
        labels_resp = rek.detect_labels(
            Image={"S3Object": {"Bucket": bucket, "Name": key}},
            MaxLabels=15,
            MinConfidence=70.0
        )
        
        raw_labels = [lbl["Name"] for lbl in labels_resp.get("Labels", [])]
        logger.info(f"Labels brutos detectados: {raw_labels}")
        
        # Traduz e limpa duplicatas
        translated_tags = []
        seen = set()
        for label in raw_labels:
            trans = translate_label(label)
            # Se a tradução contiver "/", divide em múltiplas tags
            for part in trans.split("/"):
                part_clean = part.strip().lower()
                if part_clean and part_clean not in seen:
                    seen.add(part_clean)
                    translated_tags.append(part_clean)

        # 2. Detecta texto (opcional, ex: palavras escritas em camisetas ou objetos)
        try:
            text_resp = rek.detect_text(
                Image={"S3Object": {"Bucket": bucket, "Name": key}}
            )
            for text_detection in text_resp.get("TextDetections", []):
                # Pega palavras (WORD) com boa confiança
                if text_detection["Type"] == "WORD" and text_detection["Confidence"] > 80.0:
                    text_word = text_detection["DetectedText"].lower().strip()
                    # Filtra palavras muito curtas ou comuns para evitar lixo
                    if len(text_word) > 2 and text_word not in seen:
                        seen.add(text_word)
                        translated_tags.append(text_word)
        except Exception as text_err:
            logger.warning(f"Erro ao detectar texto com Rekognition: {text_err}")

        # Monta descrição amigável
        if translated_tags:
            # Capitaliza as tags para a frase de exibição
            display_tags = [t.capitalize() for t in translated_tags]
            # Limita a descrição às primeiras 10 tags
            desc_elements = ", ".join(display_tags[:10])
            description = f"Elementos identificados: {desc_elements}."
        else:
            description = "Nenhum elemento específico identificado na foto."

        return description, translated_tags

    except Exception as e:
        logger.error(f"Erro ao analisar com Rekognition: {e}", exc_info=True)
        return "Erro ao analisar imagem com a IA.", []


def send_metadata_to_site(s3_key: str, description: str, tags: list) -> bool:
    """
    Envia a descrição e as tags extraídas pela IA de volta para o banco de dados do site.
    """
    if not HERO_API_URL or not HERO_API_TOKEN:
        logger.warning("HERO_API_URL ou HERO_API_TOKEN não definidos. Ignorando envio de metadados.")
        return False

    payload = {
        "s3_key": s3_key,
        "ai_description": description,
        "ai_tags": tags
    }

    try:
        data = json.dumps(payload).encode("utf-8")
        req = urllib.request.Request(
            HERO_API_URL,
            data=data,
            headers={
                "Content-Type": "application/json",
                "X-Hero-Token": HERO_API_TOKEN
            },
            method="POST"
        )
        
        with urllib.request.urlopen(req, timeout=10) as resp:
            resp_body = resp.read().decode("utf-8")
            logger.info(f"Metadados enviados com sucesso para o site. Resposta: {resp_body}")
            return True

    except Exception as e:
        logger.error(f"Erro ao enviar metadados para o site ({HERO_API_URL}): {e}")
        return False



def load_watermark(bucket: str) -> Image.Image | None:
    """Carrega o logo do S3 e retorna como imagem RGBA."""
    try:
        resp = s3.get_object(Bucket=bucket, Key=WATERMARK_KEY)
        wm = Image.open(io.BytesIO(resp["Body"].read())).convert("RGBA")
        return wm
    except Exception as e:
        logger.warning(f"Marca d'água não encontrada ({WATERMARK_KEY}): {e}")
        return None


def draw_rotated_text(text: str, font: ImageFont.ImageFont, angle: float) -> Image.Image:
    """Gera uma imagem RGBA com o texto desenhado e rotacionado."""
    # Calcula tamanho necessário criando uma imagem temporária
    dummy = Image.new("RGBA", (1, 1))
    draw = ImageDraw.Draw(dummy)
    bbox = draw.textbbox((0, 0), text, font=font)
    text_w = bbox[2] - bbox[0] + 16
    text_h = bbox[3] - bbox[1] + 16
    
    txt_img = Image.new("RGBA", (text_w, text_h), (0, 0, 0, 0))
    d = ImageDraw.Draw(txt_img)
    
    # Desenha texto centralizado com borda/sombra suave para legibilidade
    x_pos = 8
    y_pos = 8
    d.text((x_pos + 1, y_pos + 1), text, fill=(0, 0, 0, 35), font=font)
    d.text((x_pos, y_pos), text, fill=(255, 255, 255, 45), font=font)
    
    # Rotaciona
    return txt_img.rotate(angle, resample=Image.BICUBIC, expand=True)


def apply_watermark(photo: Image.Image, watermark: Image.Image) -> Image.Image:
    """
    Aplica o watermark rotacionado diagonalmente no centro da foto
    e adiciona a mensagem 'IMAGEM PARA APROVAÇÃO' em todos os quadrantes da imagem.
    """
    photo = photo.convert("RGBA")
    min_dim = min(photo.width, photo.height)
    
    # ─── 1. APLICAR LOGO CENTRALIZADO E ROTACIONADO ───────────────────────────
    target_w = int(min_dim * 0.50)
    ratio     = target_w / watermark.width
    target_h  = int(watermark.height * ratio)
    wm_resized = watermark.resize((target_w, target_h), Image.LANCZOS)

    r, g, b, a = wm_resized.split()
    a = a.point(lambda x: int(x * 0.22))
    wm_resized = Image.merge("RGBA", (r, g, b, a))

    wm_rotated = wm_resized.rotate(30, resample=Image.BICUBIC, expand=True)
    pos_x = (photo.width - wm_rotated.width) // 2
    pos_y = (photo.height - wm_rotated.height) // 2

    layer = Image.new("RGBA", photo.size, (0, 0, 0, 0))
    layer.paste(wm_rotated, (pos_x, pos_y))
    
    # ─── 2. ADICIONAR TEXTOS DE APROVAÇÃO NOS QUADRANTES ─────────────────────
    font_size = max(14, int(min_dim * 0.030))
    try:
        font_path = os.path.join(os.path.dirname(__file__), "Roboto-Regular.ttf")
        font = ImageFont.truetype(font_path, font_size)
    except Exception:
        font = ImageFont.load_default()

    text = "IMAGEM PARA APROVAÇÃO"
    txt_rotated = draw_rotated_text(text, font, 20)

    # Quadrantes: Top-Left, Top-Right, Bottom-Left, Bottom-Right
    quadrants = [
        (photo.width // 4, photo.height // 4),
        (3 * photo.width // 4, photo.height // 4),
        (photo.width // 4, 3 * photo.height // 4),
        (3 * photo.width // 4, 3 * photo.height // 4)
    ]

    for qx, qy in quadrants:
        px = qx - txt_rotated.width // 2
        py = qy - txt_rotated.height // 2
        layer.paste(txt_rotated, (px, py))

    # Composição final
    result = Image.alpha_composite(photo, layer)
    return result.convert("RGB")


def resize_photo(photo: Image.Image, max_size: int) -> Image.Image:
    """
    Redimensiona mantendo proporção: a maior dimensão fica em max_size pixels.
    Se a foto já for menor, não amplia.
    """
    w, h = photo.size
    if max(w, h) <= max_size:
        return photo
    
    if w >= h:
        new_w = max_size
        new_h = int(h * max_size / w)
    else:
        new_h = max_size
        new_w = int(w * max_size / h)
    
    return photo.resize((new_w, new_h), Image.LANCZOS)


def lambda_handler(event, context):
    """
    Entry point do Lambda.
    Processa cada objeto criado no evento S3.
    """
    watermark = None  # Carregado uma vez por invocação

    for record in event.get("Records", []):
        bucket   = record["s3"]["bucket"]["name"]
        raw_key  = record["s3"]["object"]["key"]
        src_key  = urllib.parse.unquote_plus(raw_key)

        logger.info(f"Processando: s3://{bucket}/{src_key}")

        # Valida prefixo — só processa originals/
        if not src_key.startswith("originals/"):
            logger.info(f"Ignorando (fora de originals/): {src_key}")
            continue

        # Extrai project_id e filename
        # Esperado: originals/{project_id}/filename.ext
        parts = src_key.split("/")
        if len(parts) < 3:
            logger.warning(f"Caminho inesperado: {src_key}")
            continue

        project_id = parts[1]
        filename   = parts[-1]

        # Ignora arquivos não-imagem
        ext = filename.rsplit(".", 1)[-1].lower()
        if ext not in ("jpg", "jpeg", "png", "tif", "tiff", "webp", "heic"):
            logger.info(f"Ignorando arquivo não-imagem: {filename}")
            continue

        dst_key = f"proxies/{project_id}/{os.path.splitext(filename)[0]}.jpg"

        try:
            # 1. Lê original do S3
            obj      = s3.get_object(Bucket=bucket, Key=src_key)
            img_data = obj["Body"].read()
            photo    = Image.open(io.BytesIO(img_data))

            # Corrige orientação EXIF (evita fotos giradas)
            try:
                from PIL import ImageOps
                photo = ImageOps.exif_transpose(photo)
            except Exception:
                pass

            photo = photo.convert("RGB")

            # 2. Redimensiona
            photo = resize_photo(photo, PROXY_MAX_SIZE)

            # 3. Aplica marca d'água (carrega só uma vez)
            if watermark is None:
                watermark = load_watermark(bucket)

            if watermark:
                photo = apply_watermark(photo, watermark)

            # 4. Exporta como JPEG em memória
            buffer = io.BytesIO()
            photo.save(buffer, format="JPEG", quality=PROXY_QUALITY, optimize=True)
            buffer.seek(0)

            # 5. Salva proxy no S3
            s3.put_object(
                Bucket=bucket,
                Key=dst_key,
                Body=buffer,
                ContentType="image/jpeg",
            )

            logger.info(f"Proxy salvo: s3://{bucket}/{dst_key}")

            # ─── 6. AUTO-TAGGING & DESCRIÇÃO COM IA NATIVA ───────────────────
            if ENABLE_AI:
                try:
                    logger.info(f"Iniciando análise com Amazon Rekognition para: s3://{bucket}/{src_key}")
                    desc, tags = analyze_image_with_rekognition(bucket, src_key)
                    logger.info(f"Análise concluída. Descrição: {desc} | Tags: {tags}")
                    
                    # Envia metadados de volta para o site (usando a key do proxy para corresponder ao proxy_url no banco)
                    send_metadata_to_site(dst_key, desc, tags)
                except Exception as ai_err:
                    logger.error(f"Erro ao rodar etapa de IA para {src_key}: {ai_err}", exc_info=True)

        except Exception as e:
            logger.error(f"Erro ao processar {src_key}: {e}", exc_info=True)
            # Não levanta exceção para não re-processar em loop
            continue

    return {"statusCode": 200, "body": "OK"}
