@echo off
REM ============================================================
REM  AgroTrace BTC — Configuration LNbits
REM  Usage : ouvrir ce script dans un terminal, editer les
REM  3 variables ci-dessous avec les valeurs du hackathon,
REM  puis executer.
REM ============================================================

REM --- EDITER ICI avec les valeurs du hackathon BMM2026 ---
set LNBITS_URL=https://lnbits.ton-hackathon.org
set LNBITS_INVOICE_READ_KEY=ta_cle_invoice_ici
set LNBITS_ADMIN_KEY=ta_cle_admin_ici
REM -------------------------------------------------------

set ENV_FILE=C:\xampp\htdocs\AgroTrace-BTC\AgroTraceLaravel\.env

echo.
echo === Patch du fichier .env ===
echo.

REM Backup
if exist "%ENV_FILE%" (
    copy /Y "%ENV_FILE%" "%ENV_FILE%.bak" >nul
    echo Backup cree : %ENV_FILE%.bak
)

REM Suppression des anciennes lignes
powershell -NoProfile -Command ^
  "$p='%ENV_FILE%'; (Get-Content $p) | Where-Object { $_ -notmatch '^LNBITS_' } | Set-Content $p"

REM Ajout des nouvelles lignes
echo.>> "%ENV_FILE%"
echo LNBITS_URL=%LNBITS_URL%>> "%ENV_FILE%"
echo LNBITS_INVOICE_READ_KEY=%LNBITS_INVOICE_READ_KEY%>> "%ENV_FILE%"
echo LNBITS_ADMIN_KEY=%LNBITS_ADMIN_KEY%>> "%ENV_FILE%"

echo.
echo === Vidage des caches Laravel ===
cd /d C:\xampp\htdocs\AgroTrace-BTC\AgroTraceLaravel
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo.
echo === Test de connexion a LNbits ===
echo URL : %LNBITS_URL%
curl -s -o NUL -w "Status HTTP : %%{http_code}^^n" ^
  -H "X-Api-Key: %LNBITS_INVOICE_READ_KEY%" ^
  "%LNBITS_URL%/api/v1/wallet"

echo.
echo.
echo ============================================================
echo  Termine. Relance le serveur si besoin :
echo    cd C:\xampp\htdocs\AgroTrace-BTC\AgroTraceLaravel
echo    php artisan serve
echo.
echo  Puis clique sur Investir dans le navigateur.
echo ============================================================
echo.
pause
