# Deploy do Lambda — process_photo

## Pré-requisitos
- AWS CLI configurado (`aws configure`)
- Python 3.12 instalado
- pip instalado

## 1. Instalar dependências na pasta do Lambda

```bash
cd lambda
pip install -r requirements.txt -t ./package
cp process_photo.py ./package/
```

## 2. Criar o arquivo ZIP

```bash
cd package
zip -r ../function.zip .
cd ..
```

No Windows (PowerShell):
```powershell
cd lambda\package
Compress-Archive -Path * -DestinationPath ..\function.zip -Force
cd ..\..
```

## 3. Criar a função Lambda na AWS

```bash
aws lambda create-function \
  --function-name hero-process-photo \
  --runtime python3.12 \
  --role arn:aws:iam::133665990566:role/hero-lambda-s3-role \
  --handler process_photo.lambda_handler \
  --zip-file fileb://lambda/function.zip \
  --timeout 60 \
  --memory-size 512 \
  --environment "Variables={WATERMARK_KEY=assets/logo.png,PROXY_MAX_SIZE=1500,PROXY_QUALITY=75,WATERMARK_OPACITY=0.35}"
```

## 4. Atualizar código (após mudanças)

```bash
aws lambda update-function-code \
  --function-name hero-process-photo \
  --zip-file fileb://lambda/function.zip
```

## 5. Configurar o S3 Trigger

### Via Console AWS:
1. Abrir o bucket `marcosantofoto-133665990566-us-east-2-an`
2. Aba **Properties** → **Event notifications** → **Create event notification**
3. Configurar:
   - **Name**: trigger-process-photo
   - **Prefix**: `originals/`
   - **Event type**: `s3:ObjectCreated:*`
   - **Destination**: Lambda → `hero-process-photo`

### Via CLI:
```bash
aws s3api put-bucket-notification-configuration \
  --bucket marcosantofoto-133665990566-us-east-2-an \
  --notification-configuration '{
    "LambdaFunctionConfigurations": [{
      "LambdaFunctionArn": "arn:aws:lambda:us-east-2:133665990566:function:hero-process-photo",
      "Events": ["s3:ObjectCreated:*"],
      "Filter": {
        "Key": {
          "FilterRules": [{"Name": "prefix", "Value": "originals/"}]
        }
      }
    }]
  }'
```

## 6. IAM Role necessária

A role `hero-lambda-s3-role` precisa de:
- `AWSLambdaBasicExecutionRole` (logs no CloudWatch)
- Política inline com acesso ao bucket:

```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Action": ["s3:GetObject"],
      "Resource": "arn:aws:s3:::marcosantofoto-133665990566-us-east-2-an/originals/*"
    },
    {
      "Effect": "Allow",
      "Action": ["s3:PutObject"],
      "Resource": "arn:aws:s3:::marcosantofoto-133665990566-us-east-2-an/proxies/*"
    },
    {
      "Effect": "Allow",
      "Action": ["s3:GetObject"],
      "Resource": "arn:aws:s3:::marcosantofoto-133665990566-us-east-2-an/assets/*"
    }
  ]
}
```

## 7. Fazer upload do logo (marca d'água)

```bash
aws s3 cp seu-logo.png s3://marcosantofoto-133665990566-us-east-2-an/assets/logo.png
```

## 8. Testar manualmente

```bash
# Subir uma foto de teste para originals/
aws s3 cp foto-teste.jpg s3://marcosantofoto-133665990566-us-east-2-an/originals/1/foto-teste.jpg

# Verificar se o proxy foi criado
aws s3 ls s3://marcosantofoto-133665990566-us-east-2-an/proxies/1/

# Ver logs do Lambda
aws logs tail /aws/lambda/hero-process-photo --follow
```
