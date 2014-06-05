#!/bin/bash

ssh root@188.226.186.117 '/site/scripts/_db_dumper.sh'
scp root@188.226.186.117:/root/local.sql .
ssh root@188.226.186.117 'rm /root/local.sql'

mysqladmin -u root -f drop airsoft
mysqladmin -u root create airsoft
mysql -uroot -D airsoft < local.sql
rm local.sql
