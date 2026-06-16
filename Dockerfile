FROM php:8.4-apache

# 1. Récupération du script mlocati via son image Docker (Méthode 100% fiable anti-404)
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo pdo_pgsql pgsql zip bcmath

# 2. Installation des dépendances système légères
RUN apt-get update && apt-get install -y zip unzip git && rm -rf /var/lib/apt/lists/*

# 3. Activation du module de réécriture d'Apache (indispensable pour les routes Laravel)
RUN a2enmod rewrite

# 4. Modification du dossier racine d'Apache pour pointer vers /public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Récupération de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Copie de tout le code du projet dans le serveur
COPY . /var/www/html

# 7. Définition du dossier de travail
WORKDIR /var/www/html

# 8. Installation des dépendances PHP de production
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 9. Configuration des permissions pour Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 10. Le serveur écoute sur le port 80
EXPOSE 80

# 11. Commande de démarrage : création du lien de stockage, exécution des migrations puis lance Apache
CMD php artisan storage:link && php artisan migrate --force && apache2-foreground