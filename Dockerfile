# Usar uma imagem base do PHP com Apache
FROM php:8.0-apache

# Habilitar o mod_rewrite (caso precise)
RUN a2enmod rewrite

# Copiar os arquivos da aplicação para o contêiner
COPY . /var/www/html

# Configuração do Apache
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Instalar as dependências para o PHP (caso tenha alguma biblioteca ou extensão extra que precise)
RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Expor a porta do servidor web (padrão 80)
EXPOSE 80
