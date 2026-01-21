#sudo docker run -it -v /home/tomas/Documents/prace/Asisto:/opt/asisto -p 8000:8000  php:7.1 /bin/bash
from php:latest

RUN apt update && apt install -y libtidy-dev zlib1g-dev libzip-dev libpng-dev unzip

RUN docker-php-ext-install mysqli tidy zip gd

WORKDIR /var/www/wp

RUN curl -s https://wordpress.org/latest.zip -o latest.zip

RUN unzip latest.zip

CMD php -S 0.0.0.0:8000 -t /var/www/wp/wordpress
