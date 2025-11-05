FROM php:8.0-cli

# Instala dependências necessárias para compilar extensões e rodar o Laravel
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql zip gd

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Define diretório da aplicação
WORKDIR /var/www/html

COPY ./ /var/www/html

# Instala dependências do Laravel
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Expondo a porta
EXPOSE 8000

CMD ["tail", "-f", "/dev/null"]

# Comando para rodar Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
