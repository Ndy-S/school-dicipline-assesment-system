# Project Overview
<img src="https://github.com/Ndy-S/school-dicipline-assesment-system/blob/main/public/img/overview.png" alt="Project Overview">

## Project Setup Guide Prerequisites
Before you begin, ensure that you have the following software installed on your system:
- Apache
- PHP
- Database: MySQL
- Composer: [getcomposer.org](https://getcomposer.org)
- Node.js: [nodejs.org](https://nodejs.org)
- npm

## Setup Instructions
### 0. Copy the Project
```
git clone https://github.com/Ndy-S/school-dicipline-assesment-system.git
cd school-dicipline-assesment-system
```

### 1. Install Laravel CLI
```
composer global require laravel/installer
```

### 2. Install PHP Dependencies
Run the following command to install PHP dependencies:
```
composer install
```
If you want to update your dependencies later, you can use `composer update`.

### 3. Install JavaScript Dependencies
Install JavaScript dependencies using npm:
```
npm install
```

### 4. Install Vite
Install vite using npm:
```
npm install --save-dev vite
```

### 5. Create Environment File
Copy the .env.example file to .env:
```
cp .env.example .env
```

### 6. Generate Application Key
Generate a new application key by running:
```
php artisan key:generate
```

### 7. Database Migration
Migrate the database tables using the following command:
```
php artisan migrate
```

### 8. Database Seeding
If you have seeders set up, populate the database with initial data:
```
php artisan db:seed
```

### 9. Compile Assets
```
npm run dev
```

## Starter Account
You can use the following credentials to access the starter account:
- **Token**: 0000
- **Password**: 0000
