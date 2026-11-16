@echo off
setlocal
cd /d "%~dp0"
echo === Setup Sistem Perpustakaan Digital Modul 1-6 ===
if not exist .env (
  copy .env.example .env
  echo .env dibuat. Silakan edit DB_DATABASE, DB_USERNAME, DB_PASSWORD dan APP_URL.
) else (
  echo .env sudah ada, tidak ditimpa.
)
echo Periksa konfigurasi .env dan pastikan database MySQL db_perpustakaan sudah dibuat.
pause
call composer install
if errorlevel 1 goto gagal
call php artisan key:generate
if errorlevel 1 goto gagal
call npm install
if errorlevel 1 goto gagal
call php artisan optimize:clear
if errorlevel 1 goto gagal
call php artisan migrate
if errorlevel 1 goto gagal
call php artisan db:seed --class=RolePermissionSeeder
if errorlevel 1 goto gagal
call php artisan db:seed --class=UserSeeder
if errorlevel 1 goto gagal
call npm run build
if errorlevel 1 goto gagal
echo.
echo Setup selesai. Jalankan php artisan serve dan npm run dev.
pause
exit /b 0
:gagal
echo Setup gagal. Baca pesan error di atas dan periksa README.md dan README_MODUL5.md.
pause
exit /b 1
