# Caching-Proxy for OpenStreetMap
## Description
This script works like a small caching proxy for OpenStreetMap. It's caching all tiles from OpenStreetMap servers to your own webserver.

## System Requirements
You need a own virtual machine or a shared hosting system with PHP. The script was tested with Apache, but it should also work with NGINX.

## Installation
Download the script:
```
sudo git clone https://github.com/Longjogger/osm-caching-proxy.git /var/www/osm-caching-proxy
sudo chown -R www-data:www-data /var/www/osm-caching-proxy
```
Example for Apache VirtualHost:
```
DocumentRoot /var/www/osm-caching-proxy
DirectoryIndex index.php
<Directory /var/www/osm-caching-proxy>
    Options FollowSymLinks
    AllowOverride All
    Order allow,deny
    Allow from all
</Directory>
AccessFileName .htaccess
```

