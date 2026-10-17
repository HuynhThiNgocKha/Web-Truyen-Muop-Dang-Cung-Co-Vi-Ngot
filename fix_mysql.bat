@echo off
chcp 65001 >nul
echo ===================================================
echo     DANG SUA LOI MYSQL XAMPP (ARIA RECOVERY FAILED)
echo ===================================================

net session >nul 2>&1
if %errorLevel% neq 0 (
    echo [!] Vui long chay file nay bang quyen Administrator:
    echo     Click chuot phai vao file -> Chon 'Run as administrator'
    echo.
    pause
    exit /b 1
)

echo 1. Dung tien trinh MySQL neu con ton tai...
taskkill /F /IM mysqld.exe >nul 2>&1

echo 2. Xoa file PID ton dong...
if exist "C:\Program Files\xampp\mysql\data\mysql.pid" (
    del /f /q "C:\Program Files\xampp\mysql\data\mysql.pid"
)

echo 3. Sao luu va xoa file aria log bi loi...
set DATA_DIR=C:\Program Files\xampp\mysql\data
set BACKUP_DIR=C:\Program Files\xampp\mysql\data\aria_corrupted_backup

if not exist "%BACKUP_DIR%" mkdir "%BACKUP_DIR%"

if exist "%DATA_DIR%\aria_log.*" (
    copy /y "%DATA_DIR%\aria_log.*" "%BACKUP_DIR%\" >nul
    del /f /q "%DATA_DIR%\aria_log.*"
)

if exist "%DATA_DIR%\aria_log_control" (
    copy /y "%DATA_DIR%\aria_log_control" "%BACKUP_DIR%\" >nul
    del /f /q "%DATA_DIR%\aria_log_control"
)

echo 4. Sua chua cac bang he thong Aria...
cd /d "%DATA_DIR%"
"C:\Program Files\xampp\mysql\bin\aria_chk.exe" -r "%DATA_DIR%\mysql\*.MAI"

echo 5. Cap quyen ghi cho thu muc mysql\data de tranh loi UAC...
icacls "%DATA_DIR%" /grant "Users":(OI)(CI)F /T /Q >nul

echo 6. Cap quyen ghi cho thu muc xampp\tmp de tranh loi upload...
if not exist "C:\Program Files\xampp\tmp" mkdir "C:\Program Files\xampp\tmp" >nul 2>&1
icacls "C:\Program Files\xampp\tmp" /grant "Users":(OI)(CI)F /T /Q >nul 2>&1

echo ===================================================
echo  HOAN TAT!
echo  Ban co the mo XAMPP Control Panel va bam Start MySQL!
echo ===================================================
pause
