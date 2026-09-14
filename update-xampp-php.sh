#!/bin/bash

sudo mv /opt/lampp/bin/php /opt/lampp/bin/php.old
sudo mv /opt/lampp/bin/php-cgi /opt/lampp/bin/php-cgi.old
sudo mv /opt/lampp/bin/phpize /opt/lampp/bin/phpize.old
sudo mv /opt/lampp/bin/php-config /opt/lampp/bin/php-config.old

# Ссылки на бинарники
sudo ln -s /usr/bin/php8.3 /opt/lampp/bin/php
sudo ln -s /usr/bin/php8.3 /opt/lampp/bin/php-cgi  # php8.3 может работать как cgi

# Для phpize и php-config (если нужны)
sudo ln -s /usr/bin/phpize8.3 /opt/lampp/bin/phpize 2>/dev/null
sudo ln -s /usr/bin/php-config8.3 /opt/lampp/bin/php-config 2>/dev/null

# Скопируйте модуль в XAMPP
sudo cp /usr/lib/apache2/modules/libphp8.3.so /opt/lampp/modules/