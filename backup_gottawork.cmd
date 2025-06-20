@echo off
setlocal enabledelayedexpansion

set "backupDir=D:\AppData\Laragon\www\GottaWork-main\backup"
set "mysqlDir=D:\AppData\Laragon\bin\mysql\mysql-8.0.30-winx64\bin"

:: Buat folder backup jika belum ada
if not exist "%backupDir%" mkdir "%backupDir%"

:: Format tanggal dan jam (untuk regional Indonesia/UK)
for /f "tokens=2-4 delims=/ " %%a in ('date /t') do (
    set "day=%%a"
    set "month=%%b"
    set "year=%%c"
)
for /f "tokens=1-2 delims=: " %%a in ("%time%") do (
    set "hour=%%a"
    set "minute=%%b"
)
:: Hilangkan spasi di jam
if "!hour:~0,1!"==" " set "hour=0!hour:~1,1!"

set "timestamp=!year!-!month!-!day!_!hour!-!minute!"

:: Jalankan mysqldump
"%mysqlDir%\mysqldump.exe" -u adm_backup -padmin123 database_gottawork > "%backupDir%\backup_gottawork_!timestamp!.sql"

if exist "%backupDir%\backup_gottawork_!timestamp!.sql" (
    echo Backup sukses: %backupDir%\backup_gottawork_!timestamp!.sql
) else (
    echo Backup gagal! Cek user, password, path mysqldump, dan hak akses folder.
)

endlocal
pause