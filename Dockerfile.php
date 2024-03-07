
FROM php:7.1

RUN apt-get update \
  && apt-get install -y \
  git \
  zip \
  unzip \
  && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=1.10.22
RUN docker-php-ext-install pdo pdo_mysql mysqli

WORKDIR /app
COPY . .

EXPOSE 8080   

CMD ["php", "yii", "serve", "0.0.0.0"]