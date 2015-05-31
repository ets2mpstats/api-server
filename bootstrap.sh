#!/usr/bin/env bash


apt-get update
apt-get install -y nginx-full php5-fpm php5 php5-mysql php5-imagick php5-mcrypt php5-memcached php5-xdebug memcached


if ! [ -L /var/www ]; then
  rm -rf /var/www
  ln -fs /vagrant_data /var/www
fi

if ! [ -L /etc/nginx/sites-available/default ]; then
  rm /etc/nginx/sites-available/default
  ln -fs /vagrant_data/exampleconfigs/default /etc/nginx/sites-available/default
fi


service nginx restart
service php5-fpm restart

mkdir /var/log/app/
chown www-data:www-data /var/log/app

cp /vagrant_data/exampleconfigs/app.yml /vagrant_data/conf/


echo "Webserver is now listening to http://localhost:8080/ "
