#!/bin/bash

apt-get install -y apache2 apache2.2 apache2-doc apache2-utils
apt-get install -y mysql-client mysql-common mysql-server 
apt-get install -y libapache2-mod-auth-mysql 
apt-get install -y php5-mysql php5 phpmyadmin php5-gd php5-tidy php5-cli php5-curl php5-common

# install and enable the required modules:
a2enmod rewrite deflate expires php5 vhost_alias

## the diff/patch includes a change which assumes phppgadmin (for postgresql)
## has been installed as well: hence this line is disabled here.
#patch -lu /etc/apache2/sites-available/default diff-4-phpDBadmin.patch

/etc/init.d/apache2 restart
