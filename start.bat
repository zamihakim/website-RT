@echo off
title Aplikasi Iuran RT - Launcher
echo ==========================================
echo   Aplikasi Iuran RT
echo   http://localhost/aplikasi-rt/
echo ==========================================
echo.

REM --- Langkah 1: Cek Apache & MySQL (XAMPP) ---
netstat -ano | findstr ":80" | findstr "LISTENING" >NUL
if %ERRORLEVEL% EQU 0 (
  echo [1/2] Apache sudah berjalan
) else (
  echo [1/2] Apache belum jalan! Jalankan XAMPP Control Panel & nyalakan Apache.
  echo        Tekan Enter setelah Apache nyala...
  pause >NUL
)

netstat -ano | findstr ":3306" | findstr "LISTENING" >NUL
if %ERRORLEVEL% EQU 0 (
  echo       MySQL sudah berjalan
) else (
  echo       MySQL belum jalan! Nyalakan MySQL dari XAMPP Control Panel.
  echo       Tekan Enter setelah MySQL nyala...
  pause >NUL
)

echo.
echo Membuka browser ke http://localhost/aplikasi-rt/ ...
start "" http://localhost/aplikasi-rt/
echo Selesai.
pause
