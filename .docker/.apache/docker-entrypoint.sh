#!/bin/bash
service apache2 start

/usr/sbin/cron -f

chown -R www-data storage/


