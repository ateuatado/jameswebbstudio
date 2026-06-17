# Script de Deploy do Lambda hero-process-photo
# Executar este script em uma janela do PowerShell onde o AWS CLI esteja configurado.

$ErrorActionPreference = "Stop"

Write-Host "==============================================" -ForegroundColor Yellow
Write-Host "   DEPLOIANDO LAMBDA: hero-lambda-s3-role     " -ForegroundColor Yellow
Write-Host "==============================================" -ForegroundColor Yellow

# Garante que estamos na pasta correta
$ScriptPath = Split-Path -Parent $MyInvocation.MyCommand.Definition
if ($ScriptPath) {
    Set-Location $ScriptPath
}

# Define nomes dos arquivos
$ZipFile = "function.zip"
$SourceFiles = @("process_photo.py", "Roboto-Regular.ttf")

# Remove zip antigo se existir
if (Test-Path $ZipFile) {
    Remove-Item $ZipFile -Force
}

Write-Host "[+] Compactando arquivos de codigo e fonte para $ZipFile..." -ForegroundColor Cyan
# Cria arquivo zip contendo o script do Lambda e a fonte de texto
Compress-Archive -Path $SourceFiles -DestinationPath $ZipFile -Force

# Tenta localizar o executavel do aws CLI
$AwsCmd = "aws"
if (!(Get-Command "aws" -ErrorAction SilentlyContinue)) {
    $CommonPath = "C:\Program Files\Amazon\AWSCLIV2\aws.exe"
    if (Test-Path $CommonPath) {
        $AwsCmd = $CommonPath
    } else {
        Write-Host "[!] AWS CLI nao encontrado no PATH nem no caminho padrao." -ForegroundColor Red
        return
    }
}

Write-Host "[+] Atualizando codigo do Lambda na AWS..." -ForegroundColor Cyan
try {
    & $AwsCmd lambda update-function-code `
      --function-name hero-lambda-s3-role `
      --zip-file fileb://$ZipFile `
      --region us-east-2
    
    # Verifica a variavel especial $LASTEXITCODE para verificar sucesso do executavel externo
    if ($LASTEXITCODE -eq 0) {
        Write-Host "[+] SUCESSO! O codigo do Lambda foi atualizado no AWS." -ForegroundColor Green
    } else {
        Write-Host "[!] ERRO: O comando do AWS CLI falhou (Exit Code: $LASTEXITCODE)." -ForegroundColor Red
    }
} catch {
    Write-Host "[!] ERRO critico ao executar script: $_" -ForegroundColor Red
}

# Limpeza
if (Test-Path $ZipFile) {
    Remove-Item $ZipFile -Force
}

Write-Host "==============================================" -ForegroundColor Yellow
