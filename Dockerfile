FROM php:7.4-cli-alpine

# Install dependencies

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


RUN apk update \
    && apk upgrade \
    && apk add --no-cache php7-pear php7-dev gcc musl-dev make \
    && pecl install xdebug \
    && PHPINI_PATH=$(php -i | grep 'Configuration File (php.ini) Path'|awk '{print $6}') \
    && echo "zend_extension=/usr/local/lib/php/extensions/no-debug-non-zts-20190902/xdebug.so" >> "${PHPINI_PATH}/php.ini" \
    && composer global require friendsofphp/php-cs-fixer 

ENV PATH="/root/.composer/vendor/bin:${PATH}"

# Install and setup the bee game

COPY ./ /beegame

WORKDIR /beegame

RUN composer install

RUN chmod +x beesinthetrap

CMD [ "./beesinthetrap"]
