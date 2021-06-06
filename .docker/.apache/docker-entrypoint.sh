#!/bin/bash
USUARIO=sel

service apache2 start

sudo /usr/sbin/cron -f

sudo chmod -R 775 storage/logs

sudo -u $USUARIO /bin/bash
