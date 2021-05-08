#!/bin/bash
USUARIO=sel

sudo -u $USUARIO /bin/bash

service apache2 start

sudo /usr/sbin/cron -f

alias phptest="./vendor/phpunit/phpunit/phpunit"

chmod -R 775 storage/logs

