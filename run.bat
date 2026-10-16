@echo off
title Web Truyen Zhihu - Master Launcher
cd /d "%~dp0"

echo ======================================================================
echo           WEB DOC TRUYEN: MUOP DANG CUNG CO VI NGOT
echo ======================================================================
echo.

:: 1. Kiem tra va khoi dong MySQL
netstat -ano | findstr ":3306" | findstr "LISTENING" >nul
if errorlevel 1 (
    echo [..] Dang khoi dong MySQL Server (Port 3306)...
    if not exist "%~dp0tmp" mkdir "%~dp0tmp"
    start /b "" "C:\Program Files\xampp\mysql\bin\mysqld.exe" --defaults-file="C:\Program Files\xampp\mysql\bin\my.ini" --tmpdir="%~dp0tmp"
    timeout /t 2 >nul
    netstat -ano | findstr ":3306" | findstr "LISTENING" >nul
    if errorlevel 1 (
        echo [CANH BAO] Chua the khoi dong MySQL tu dong. Vui long kiem tra XAMPP.
    ) else (
        echo [OK] MySQL da duoc khoi dong thanh cong!
    )
) else (
    echo [OK] MySQL dang hoat dong (Port 3306).
)

:: 2. Kiem tra va khoi dong PHP Web Server tren 0.0.0.0:8080
netstat -ano | findstr ":8080" | findstr "LISTENING" >nul
if errorlevel 1 (
    echo [..] Dang khoi dong PHP Web Server tren 0.0.0.0:8080...
    start /b "" php -S 0.0.0.0:8080 router.php
    timeout /t 1 >nul
    echo [OK] Web server da san sang!
) else (
    echo [OK] PHP Web Server dang hoat dong (Port 8080).
)

:: 3. Lay IP mang LAN de vao bang dien thoai
for /f "tokens=*" %%i in ('powershell -Command "(Test-Connection -ComputerName $env:COMPUTERNAME -Count 1).IPV4Address.IPAddressToString"') do set LOCAL_IP=%%i
if "%LOCAL_IP%"=="" set LOCAL_IP=192.168.1.182

echo.
echo ======================================================================
echo   [PC]       May tinh:    http://localhost:8080/
echo   [MOBILE]   Dien thoai:  http://%LOCAL_IP%:8080/
echo ======================================================================
echo.
echo [*] Dang mo trinh duyet va ma QR...
start http://localhost:8080/
start http://localhost:8080/utilities/mobile.html?ip=%LOCAL_IP%

echo.
echo [*] Huong dan:
echo  - Tren may tinh: Web da duoc mo tai http://localhost:8080/
echo  - Tren dien thoai: Quet ma QR tren man hinh de vao ngay!
echo.
