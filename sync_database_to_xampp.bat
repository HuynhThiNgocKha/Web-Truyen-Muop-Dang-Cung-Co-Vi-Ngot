@echo off
title Dong bo Database sang XAMPP
echo ======================================================
echo    DONG BO DU LIEU WEB TRUYEN SANG MYSQL CUA XAMPP
echo ======================================================
echo.
netstat -ano | findstr ":3306" | findstr "LISTENING" >nul
if errorlevel 1 (
    echo [THONG BAO] MySQL chua chay tren XAMPP!
    echo Vui long mo XAMPP Control Panel va nhan nut [Start] tai MySQL truoc.
    echo.
    pause
    exit /b 1
)

echo [..] Dang dong bo du lieu tu backup_latest.sql vao database webtruyen_zhihu cua XAMPP...
"C:\Program Files\xampp\mysql\bin\mysql.exe" -u root webtruyen_zhihu < "%~dp0backup_latest.sql"
if errorlevel 1 (
    echo [LOI] Co loi khi import vao MySQL!
) else (
    echo [OK] Da dong bo toan bo du lieu moi nhat (truyen, chuong, tu truyen, team dich...) vao XAMPP thanh cong!
)
echo.
pause
