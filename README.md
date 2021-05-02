# Introduction

Welcome to SEL's Documentation .

# Installation

## Requirements

-   Git
-   Docker
-   Docker-Composer

## Download e Docker Image's Installation

Enter the command in the folder of your preference:

1. Enter yours project's folder:  
   `cd your_directory/your_folder/`

2. Clone the repository to your folder (Enter your BitBucket password when prompted.):  
   `https://github.com/aryangomes/sel.git`

3. Enter the folder that was created:  
   `cd sel`

4. Build the Docker image:  
   `docker-compose -p sel -f .docker/docker-compose.yml up -d --build`

## Start Apache and MariaDB Docker (Mysql)

### Apache

-   `docker start sel-apache`

### MariaDB (Mysql database)

-   `docker start sel-mariadb`

## Access the Apache prompt

-   `docker exec -it sel-apache bash -c"/docker-entrypoint.sh"`

## Access the MariaDB prompt (Mysql database)

-   `docker exec -it sel-mariadb bash`

## Install Laravel dependencies

**Remember to run the following commands from the Apache prompt!**

1. Create the Laravel development variables file by copying it from a pre-defined example:  
   `cp .env.example .env`

2. Install the Laravel facilities with the composer:  
   composer install

3. Generate the Laravel local application key:  
   `php artisan key: generate`

## Migrate / Generate database

**Copy and paste the following variables into the file** _.env_

1. Copy and paste according to the database variable settings:  
   `DB_CONNECTION=mysql`  
   `DB_HOST=db-app-leilao-skysoft`  
   `DB_PORT=3306`  
   `DB_DATABASE=leilao_skysoft`  
   `DB_USERNAME=skysoft`  
   `DB_PASSWORD=localskysoft`

2. Copy and paste as variable settings from the default password of users (how default passwords can be changed):  
   `APP_URL = http://localhost:8042`

3. Run the command to generate the database with some information already prepared:  
   `php artisan migrate --seed`

4. Install the Passport package for generating the token for User authentication:  
   `php artisan passport: install`

## Useful Commands

-   Delete and re-create the database:  
     `php artisan migrate: fresh`

-   Delete and re-create the database, populating the bank with some previously registered information:  
     `php artisan migrate --seed`

-   Populate the database with some previously registered information:  
     `php artisan db: seed`

-   Give read and write permission for mass storage ():  
     `chmod -R 775 storage/`

# Changelog

-   [Changelog](/CHANGELOG.MD)
