 # TaskFlow Pro - Command Cheat Sheet

## 🚀 Server Commands (সবসময় চালু রাখো)

### Terminal: Vite Dev Server
npm run dev

### Terminal: Laravel Server
php artisan serve


## 📦 Migration Commands

### নতুন Migration তৈরি
php artisan make:migration migration_name --table=table_name

### Migration চালাও
php artisan migrate

### সব মুছে আবার migrate করো
php artisan migrate:fresh

### Fresh + Seed
php artisan migrate:fresh --seed


## 🏗️ Make Commands

### Model + Migration + Controller + Factory + Seeder (All-in-one)
php artisan make:model ModelName -mcfs

### শুধু Model
php artisan make:model ModelName

### Resource Controller (CRUD সব method)
php artisan make:controller ControllerName --resource

### Middleware
php artisan make:middleware MiddlewareName

### Form Request (Validation)
php artisan make:request StoreNameRequest

### Policy (Authorization)
php artisan make:policy NamePolicy --model=ModelName


## 🔍 Debug Commands

### সব Route এর list
php artisan route:list

### Config cache clear
php artisan config:clear

### সব cache clear
php artisan optimize:clear


## 📦 Composer & NPM

### নতুন Package
composer require vendor/package

### NPM Packages install
npm install

### Production build
npm run build
