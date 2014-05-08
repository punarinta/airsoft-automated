#!/bin/bash

# Drop old database
mysqladmin -u root -f drop test

# Create database
mysqladmin -u root create test

# Import database
mysql -uroot -D test < ~/src/airsoft-automated/app/database/dumps/`ls -l ~/src/airsoft-automated/app/database/dumps | awk '{ f=$NF };END{ print f }'`

# Flush redis
# redis-cli -p 16379 flushall
