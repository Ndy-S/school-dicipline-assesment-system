# School Discipline Assessment System
The School Discipline Assessment System is a web application designed for teachers to assess student discipline scores and apply penalties when students violate rules. The system streamlines the process of tracking student behavior and maintaining disciplinary records.

<img src="https://github.com/Ndy-S/school-dicipline-assesment-system/blob/main/public/img/overview.png" alt="Project Overview">

## Prerequisites
Before setting up the project, ensure that you have the following software installed on your system:
- Apache
- PHP
- Database: MySQL
- Composer: Download from [getcomposer.org](https://getcomposer.org)
- Node.js: Download from [nodejs.org](https://nodejs.org)
- npm

## Setup Instructions
1. **Copy the Project**
    ```
    git clone https://github.com/Ndy-S/school-dicipline-assesment-system.git
    cd school-dicipline-assesment-system
    ```
2. **Install Laravel CLI**
    ```
    composer global require laravel/installer
    ```
3. **Install PHP Dependencies** To update dependencies later, use `composer update`.
    ```
    composer install
    ```
4. **Install JavaScript Dependencies**
    ```
    npm install
    ```
5. **Install Vite**
    ```
    npm install --save-dev vite
    ```
6. **Create Environment File**
    ```
    cp .env.example .env
    ```
7. **Generate Application Key**
    ```
    php artisan key:generate
    ```
8. **Database Migration**
    ```
    php artisan migrate
    ```
9. **Database Seeding** If you have seeders set up, populate the database with initial data:
    ```
    php artisan db:seed
    ```
11. **Compile Assets**
    ```
    npm run dev
    ```

## Starter Account
You can use the following credentials to access the starter account:
- **Token**: 0000
- **Password**: 0000

## License
MIT
