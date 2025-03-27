FROM php:8.0-apache

# Instalar as dependências para o PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Configuração do Apache
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Habilitar o mod_rewrite
RUN a2enmod rewrite

# Definir o ServerName
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Expor a porta 80 para o servidor web
EXPOSE 810

# Copiar os arquivos da API para o contêiner
COPY . /var/www/html

# Iniciar o Apache
CMD ["apache2-foreground"]
