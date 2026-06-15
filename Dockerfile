FROM php:8.2-apache

# 1. Installation des dépendances système et des extensions PHP pour PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip bcmath

# 2. Activation du module de réécriture d'Apache (indispensable pour les routes Laravel)
RUN a2enmod rewrite

# 3. Modification du dossier racine d'Apache pour pointer vers /public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Récupération de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Copie de tout le code du projet dans le serveur
COPY . /var/www/html

# 6. Définition du dossier de travail
WORKDIR /var/www/html

# 7. Installation des dépendances PHP de production
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 8. Configuration des permissions pour Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 9. Le serveur écoute sur le port 80
EXPOSE 80