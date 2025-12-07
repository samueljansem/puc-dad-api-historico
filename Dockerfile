FROM php:8.2-apache

# Install MySQL PDO extension and curl
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache mod_rewrite for cleaner URLs
RUN a2enmod rewrite

# Copy application files to Apache document root
COPY . /var/www/html/

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Configure Apache to serve from /var/www/html
WORKDIR /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]
