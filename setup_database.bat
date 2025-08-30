@echo off
echo Creating HRMS Database...
echo.

REM إنشاء قاعدة البيانات
mysql -u root -e "CREATE DATABASE IF NOT EXISTS hrms_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

REM تنفيذ ملف SQL
mysql -u root hrms_db < "C:\xampp1\htdocs\hrms\database\hrms_database.sql"

echo.
echo Database created successfully!
echo You can now login with:
echo Username: admin
echo Password: password
echo.
pause