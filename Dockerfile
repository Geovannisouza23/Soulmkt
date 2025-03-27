# Usar a imagem oficial do PHP com Apache
FROM php:8.1-apache

# Copiar o arquivo de configuração do Apache
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Instalar extensões PHP necessárias (ex: CSV)
RUN docker-php-ext-install pdo pdo_mysql

# Definir o diretório de trabalho
WORKDIR /var/www/html

# Copiar os arquivos do seu projeto para o container
COPY . /var/www/html

# Expor a porta 80
EXPOSE 80
