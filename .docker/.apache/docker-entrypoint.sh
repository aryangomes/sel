#!/bin/bash
USUARIO=sel

sudo -u $USUARIO /bin/bash

service apache2 start

sudo /usr/sbin/cron -f

chmod -R 775 storage/logs

