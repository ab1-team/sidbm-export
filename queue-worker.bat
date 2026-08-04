@echo off
php artisan queue:work database --queue=export --tries=1 --timeout=900
pause