# Introduction

Welcome to SEL's Documentation .

# Installation

## Requirements

- Git
- Docker
- Docker-Composer

## Download e Docker Image's Installation

Enter the command in the folder of your preference:

1. Clone the repository to your folder.

3. Enter the folder that was created:  
   `cd sel`

4. Build the Docker image:  
   `docker compose -f ".docker/docker-compose.yml" --env-file=".env" -p sel up -d --build`

## Start Apache and MariaDB Docker (Mysql)

### Apache

- `docker start sel-apache`

### MariaDB (Mysql database)

- `docker start sel-mariadb`

## Access the Apache prompt

- `docker exec -it -u sel sel-apache bash`

## Access the MariaDB prompt (Mysql database)

- `docker exec -it sel-mariadb bash`

## Install Laravel dependencies

**Remember to run the following commands from the Apache prompt!**

1. Create the Laravel development variables file by copying it from a pre-defined example:  
   `cp .env.example .env`

2. Install the Laravel facilities with the composer:  
   `composer install`

3. Generate the Laravel local application key:  
   `php artisan key: generate`

4. Link storage directory:  
   `php artisan storage:link`

## Migrate / Generate database

**Copy and paste the following variables into the file** _.env_

1. Copy and paste according to the database variable settings:  
   `DB_CONNECTION=mysql`  
   `DB_HOST=db`  
   `DB_PORT=3306`  
   `DB_DATABASE=`  
   `DB_USERNAME=`  
   `DB_PASSWORD=`
   `MYSQL_ROOT_PASSWORD=`

2. Copy and paste as variable settings from the default password of users (how default passwords can be changed):  
   `APP_URL = http://localhost:8042`

3. Run the command to generate the database with some information already prepared:  
   `php artisan migrate --seed`

4. Install the Passport package for generating the token for User authentication:

   `php artisan passport: install`

## Useful Commands

- Delete and re-create the database:  
     `php artisan migrate: fresh`

- Delete and re-create the database, populating the bank with some previously registered information:  
     `php artisan migrate --seed`

- Populate the database with some previously registered information:  
     `php artisan db: seed`

- Give read and write permission for mass storage:  
     `chmod o+w /var/www/html/storage/ -R`

# Changelog

- [Changelog](/CHANGELOG.MD)
