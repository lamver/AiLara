FROM php:8.3-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \

    apt-transport-https \
    software-properties-common \
    openssl \
    libgmp-dev \
    apt-utils \
     man \
     curl \
     git \
     bash \
     vim \
     zip unzip \
     acl \
     iproute2 \
     dnsutils \
     fonts-freefont-ttf \
     fontconfig \
     dbus \
     openssh-client \
     sendmail \
     libfreetype6-dev \
     libjpeg62-turbo-dev \
     icu-devtools \
     libicu-dev \
     libmcrypt4 \
     libmcrypt-dev \
     libpng-dev \
     zlib1g-dev \
     libxml2-dev \
     libzip-dev \
     libonig-dev \
     graphviz \
     libcurl4-openssl-dev \
     pkg-config \
     libldap2-dev \
     libpq-dev \
    && docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd pgsql pdo_pgsql

#RUN sudo update-ca-certificates
RUN echo "cacert=/etc/ssl/certs/ca-certificates.crt" >> ~/.curlrc
# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
#RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip mysqli gmp && docker-php-ext-enable mysqli && docker-php-ext-configure intl
#RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && docker-php-ext-install pgsql pdo_pgsql
#RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
#    --with-gd \
#    --with-jpeg-dir \
#    --with-png-dir \
#    --with-zlib-dir
#RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN apt-get update && apt-get install -y libmagickwand-dev --no-install-recommends && rm -rf /var/lib/apt/lists/*
RUN apt-get update && apt-get install -y ca-certificates openssl
#RUN  pecl install imagick
#RUN docker-php-ext-enable imagick
#RUN docker-php-ext-enable gd

RUN apt-get update && apt-get install -y libmagickwand-dev --no-install-recommends && rm -rf /var/lib/apt/lists/*
RUN mkdir -p /usr/src/php/ext/imagick; \
    curl -fsSL https://github.com/Imagick/imagick/archive/06116aa24b76edaf6b1693198f79e6c295eda8a9.tar.gz | tar xvz -C "/usr/src/php/ext/imagick" --strip 1; \
    docker-php-ext-install imagick;

#COPY ./etc/cacert.pem /etc/ssl/certs/ca-certificates.crt
ADD docker-compose/php/fpm/www.conf /usr/local/etc/php/custom.d/fpm/www.conf
ADD docker-compose/php/php.ini /usr/local/etc/php/conf.d/40-custom.ini
# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user
RUN chown -R www-data:www-data /var/www
COPY ./docker-compose/php/php.ini /usr/local/etc/php/
COPY --chown=www-data:www-data . /var/www

# Set working directory
WORKDIR /var/www
#COPY composer.* ./
#RUN composer install
#RUN chmod -R 777 /var/www/vendor
#COPY --from=composer /usr/bin/composer /usr/bin/composer
#RUN composer install

USER $user
