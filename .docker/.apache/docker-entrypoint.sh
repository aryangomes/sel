#!/bin/bash
USUARIO=sel

sudo -u $USUARIO /bin/bash

service apache2 start

sudo /usr/sbin/cron -f

alias phptest="./vendor/phpunit/phpunit/phpunit"

chown -R $USUARIO:www-data storage

chown -R $USUARIO:www-data bootstrap/cache

