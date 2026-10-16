@echo off
title Sua loi khoi dong MySQL (Aria Crash)
echo ========================================================
echo  DANG SUA LOI ARIA CRASH VA PHAN QUYEN MYSQL TRONG XAMPP
echo ========================================================
echo.

del /f /q "C:\Program Files\xampp\mysql\data\aria_log*" 2>nul
echo [OK] Da don dep cac file aria_log cu.

icacls "C:\Program Files\xampp\mysql\data" /grant Users:(OI)(CI)F /T >nul 2>&1
echo [OK] Da cap quyen ghi thu muc MySQL.

echo [..] Dang khoi dong MariaDB / MySQL...
start "" "C:\Program Files\xampp\mysql\bin\mysqld.exe" --defaults-file="C:\Program Files\xampp\mysql\bin\my.ini" --standalone

echo.
echo ========================================================
echo  HOAN TAT! MySQL dang khoi dong.
echo  Trang web dang chay tai: http://localhost:8080/
echo ========================================================
timeout /t 3
