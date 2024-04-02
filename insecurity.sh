#!/bin/bash

wget https://edshare.gcu.ac.uk/id/document/59001 -O insecurity.tar.gz
tar -xzvf insecurity.tar.gz
mysql -u root < ./insecurity/security.sql
rm ./insecurity/security.sql
rm -rf /var/www/insecurity
cp -R ./insecurity /var/www/
