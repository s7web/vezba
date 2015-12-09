FROM chriswayg/apache-php
MAINTAINER Nenadpaic <npaic@s7designcreative.com>

RUN apt-get update -y
# Install curl extension
RUN apt-get install curl php5-curl php5-gd php5-mysql -y

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Move files to html directory
ADD . /var/www/html

ADD /deploy/run.sh /var/www/html/run.sh

RUN chmod +x /var/www/html/run.sh

# Change apache config
ADD deploy/000-default.conf /etc/apache2/sites-available/000-default.conf

# Expose needed ports
EXPOSE 22
EXPOSE 80
EXPOSE 443

WORKDIR /var/www/html

RUN mkdir log
RUN chmod 777 log