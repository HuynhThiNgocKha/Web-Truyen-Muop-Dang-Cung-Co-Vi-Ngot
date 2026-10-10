@echo off
title Sua Loi MySQL XAMPP
:: Kiem tra va xin quyen Administrator tu dong
>nul 2>&1 "%SYSTEMROOT%\system32\cacls.exe" "%SYSTEMROOT%\system32\config\system"
if '%errorlevel%' NEQ '0' (
    echo Dang yeu cau quyen Administrator...
    echo Set UAC = CreateObject^("Shell.Application"^) > "%temp%\getadmin.vbs"
    echo UAC.ShellExecute "cmd.exe", "/c ""%~s0""", "", "runas", 1 >> "%temp%\getadmin.vbs"
    "%temp%\getadmin.vbs"
    del "%temp%\getadmin.vbs"
    exit /B
)

cd /d "%~dp0"
echo ======================================================================
echo             SUA LOI ARIA RECOVERY / QUYEN GHI MYSQL XAMPP
echo ======================================================================
echo.

echo [1/3] Dang don dep cac file aria_log bi loi cua MariaDB...
del /f /q "C:\Program Files\xampp\mysql\data\aria_log*" 2>nul
del /f /q "C:\Program Files\xampp\mysql\data\aria_log_control" 2>nul
del /f /q "C:\Program Files\xampp\mysql\data\*.lower-test" 2>nul

echo [2/3] Cap quyen day du cho thu muc mysql/data tren Windows...
icacls "C:\Program Files\xampp\mysql\data" /grant Users:(OI)(CI)F /T /C >nul 2>&1

echo [3/3] Hoan tat!
echo.
echo ======================================================================
echo  DA SUA XONG!
echo  Hay quay lai XAMPP Control Panel va nhan nut [Start] tai MySQL.
echo ======================================================================
echo.
pause
