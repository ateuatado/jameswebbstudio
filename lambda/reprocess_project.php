<?php

// Script CLI para reprocessar fotos de um projeto no S3 (re-disparando o AWS Lambda)
// Uso: php lambda/reprocess_project.php <project_id>

if (php_sapi_name() !== 'cli') {
    die("Este script so pode ser executado via linha de comando (CLI).\n");
}

if ($argc < 2) {
    die("Erro: ID do projeto nao fornecido.\nUso: php lambda/reprocess_project.php <project_id>\n");
}

$projectId = (int)$argv[1];
if ($projectId <= 0) {
    die("Erro: ID do projeto invalido.\n");
}

// Carrega o autoloader
$autoloadPath = dirname(__DIR__) . '/vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    die("Erro: autoloader do Composer nao encontrado.\n");
}
require $autoloadPath;

// Carrega .env
$envPath = dirname(__DIR__) . '/.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) continue;
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $name = trim($parts[0]);
            $value = trim($parts[1], " \t\n\r\0\x0B\"'");
            putenv("$name=$value");
        }
    }
}

$bucket = getenv('AWS_S3_BUCKET');
$region = getenv('AWS_REGION') ?: 'us-east-2';
$key    = getenv('AWS_ACCESS_KEY_ID');
$secret = getenv('AWS_SECRET_ACCESS_KEY');

if (empty($bucket) || empty($key) || empty($secret)) {
    die("Erro: Credenciais do AWS S3 nao configuradas no arquivo .env.\n");
}

use Aws\S3\S3Client;

$s3 = new S3Client([
    'version'     => 'latest',
    'region'      => $region,
    'credentials' => [
        'key'    => $key,
        'secret' => $secret,
    ],
]);

// Busca s3_folder no banco se possivel, caso contrario usa o ID
$s3Folder = (string)$projectId;
try {
    $dbHost = getenv('database.default.hostname') ?: 'localhost';
    $dbName = getenv('database.default.database') ?: 'hero';
    $dbUser = getenv('database.default.username') ?: 'root';
    $dbPass = getenv('database.default.password') ?: '';
    
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    
    $stmt = $pdo->prepare("SELECT s3_folder FROM client_projects WHERE id = :id");
    $stmt->execute(['id' => $projectId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!empty($row['s3_folder'])) {
        $s3Folder = $row['s3_folder'];
    }
} catch (\Exception $dbEx) {
    // Ignora erro e mantem fallback no ID
}

echo "========================================================\n";
echo "   REPROCESSANDO FOTOS DO ENSAIO (ID: $projectId)      \n";
echo "========================================================\n";
echo "Bucket: $bucket\n";
echo "Prefixo S3: originals/$s3Folder/\n";
echo "--------------------------------------------------------\n";

try {
    echo "[+] Listando fotos em originals/$s3Folder/...\n";
    $result = $s3->listObjectsV2([
        'Bucket' => $bucket,
        'Prefix' => "originals/$s3Folder/",
    ]);

    $count = 0;
    foreach ($result['Contents'] ?? [] as $object) {
        $fileKey = $object['Key'];
        // Ignora a pasta em si
        if ($fileKey === "originals/$s3Folder/") continue;

        // Apenas arquivos de imagem ou formato RAW comum
        $ext = strtolower(pathinfo($fileKey, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'tif', 'tiff', 'webp', 'cr3'])) continue;

        echo "[+] Tocando s3://$bucket/$fileKey...\n";
        
        // Copia o objeto sobre ele mesmo para disparar o s3:ObjectCreated:Copy no AWS Lambda
        $s3->copyObject([
            'Bucket'            => $bucket,
            'Key'               => $fileKey,
            'CopySource'        => urlencode("$bucket/$fileKey"),
            'MetadataDirective' => 'REPLACE',
        ]);
        
        $count++;
    }

    echo "--------------------------------------------------------\n";
    echo "SUCESSO! Trigger disparado para $count foto(s).\n";
    echo "Aguarde cerca de 5-10 segundos para que a AWS Lambda processe as fotos.\n";
    echo "========================================================\n";
} catch (\Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
