# Genera un certificado HTTPS autofirmado para manovecina.test (válido 365 días).
# Requiere Docker. Se ejecuta desde cualquier carpeta; deja los archivos junto a este script.
#   powershell -ExecutionPolicy Bypass -File .\docker\certs\generar-certificado.ps1
param([string]$Dominio = 'manovecina.test')

$carpeta = $PSScriptRoot
docker run --rm -v "${carpeta}:/certs" alpine/openssl req -x509 -nodes -newkey rsa:2048 -days 365 `
    -keyout /certs/manovecina.key -out /certs/manovecina.crt `
    -subj "/CN=$Dominio" -addext "subjectAltName=DNS:$Dominio,DNS:localhost,IP:127.0.0.1"

if (Test-Path (Join-Path $carpeta 'manovecina.crt')) {
    Write-Host "Certificado creado en $carpeta" -ForegroundColor Green
} else {
    Write-Host 'No se pudo crear el certificado. Comprueba que Docker Desktop esté abierto.' -ForegroundColor Red
}
