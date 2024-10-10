# Utiliser une image PHP avec Apache
FROM php:7.4-apache

# Installer les extensions nécessaires (ajustez selon vos besoins)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copier les fichiers du projet vers le conteneur
COPY . /var/www/html

# Donner les droits appropriés aux fichiers
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Exposer le port 80 pour le serveur web
EXPOSE 80
