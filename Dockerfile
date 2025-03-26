# Usar a imagem oficial do PHP com Apache
FROM php:8.1-apache

# Instalar extensões PHP necessárias (ex: CSV)
RUN docker-php-ext-install pdo pdo_mysql

# Definir o diretório de trabalho
WORKDIR /var/www/html

# Copiar os arquivos do seu projeto para o container
COPY . /var/www/html

# Expor a porta 80
EXPOSE 80
