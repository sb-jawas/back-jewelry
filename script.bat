@echo off
php artisan migrate:refresh
php artisan db:seed --class=RolSeeder
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=ComponentesSeeder
php artisan db:seed --class=StatusCodeSeeder