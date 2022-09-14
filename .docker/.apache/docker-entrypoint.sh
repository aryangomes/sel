#!/bin/bash
USUARIO=sel

service apache2 start

sudo /usr/sbin/cron -f

sudo chmod o+w /var/www/html/storage/ -R

sudo -u $USUARIO /bin/bash
