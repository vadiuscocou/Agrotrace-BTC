@echo off
REM ============================================================
REM  AgroTrace BTC — Test rapide de la cle LNbits
REM  Edite LNBITS_URL et LNBITS_INVOICE_READ_KEY ci-dessous,
REM  puis lance le script. Il verifie 3 choses :
REM   1) Le wallet existe (GET /api/v1/wallet)
REM   2) On peut creer une facture test (POST /api/v1/payments)
REM   3) On peut consulter son statut (GET /api/v1/payments/{hash})
REM ============================================================

set LNBITS_URL=https://lnbits.ton-hackathon.org
set LNBITS_INVOICE_READ_KEY=ta_cle_invoice_ici
set AMOUNT_SATS=1000

echo.
echo === 1/3 Test du wallet ===
curl -s -w "  -> Status HTTP : %%{http_code}^^n" ^
  -H "X-Api-Key: %LNBITS_INVOICE_READ_KEY%" ^
  "%LNBITS_URL%/api/v1/wallet"
echo.

echo === 2/3 Creation d'une facture test de %AMOUNT_SATS% sats ===
for /f "delims=" %%R in ('curl -s ^
  -H "X-Api-Key: %LNBITS_INVOICE_READ_KEY%" ^
  -H "Content-Type: application/json" ^
  -X POST ^
  -d "{\"out\":false,\"amount\":%AMOUNT_SATS%,\"memo\":\"test agrotrace\"}" ^
  "%LNBITS_URL%/api/v1/payments"') do set RESPONSE=%%R

echo Reponse brute : %RESPONSE%
echo.

REM Extraction du payment_hash avec un script PowerShell inline
for /f "delims=" %%H in ('powershell -NoProfile -Command ^
  "$r='%RESPONSE%'; try { $j=$r | ConvertFrom-Json; $j.payment_hash } catch { '' }"') do set PAYMENT_HASH=%%H

if "%PAYMENT_HASH%"=="" (
    echo.
    echo [!] Aucune reponse JSON valide. Verifie l'URL et la cle.
    goto :eof
)

echo Payment hash : %PAYMENT_HASH%
echo.

echo === 3/3 Verification du statut de la facture ===
curl -s -w "  -> Status HTTP : %%{http_code}^^n" ^
  -H "X-Api-Key: %LNBITS_INVOICE_READ_KEY%" ^
  "%LNBITS_URL%/api/v1/payments/%PAYMENT_HASH%"
echo.
echo.
pause
