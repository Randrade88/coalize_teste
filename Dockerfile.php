# Use the official PHP 7.1 image
FROM php:7.1

# Install dependencies
RUN apt-get update \
    && apt-get install -y \
        git \
        zip \
        unzip \
    && rm -rf /var/lib/apt/lists/*


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=1.10.22
RUN docker-php-ext-install pdo pdo_mysql mysqli
# Set working directory
WORKDIR /app

# Copy the application code
COPY . .

# Install PHP extensions if needed (e.g., pdo_mysql, gd, etc.)
# RUN docker-php-ext-install pdo_mysql

# Expose port if needed
# EXPOSE 8080   

# Command to run the application
# CMD ["php", "index.php"]