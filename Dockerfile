# Use the official PHP image as the base
FROM php:8.3-cli

WORKDIR /app

VOLUME /app/var/

# Install necessary dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
	acl \
	file \
	gettext \
	git \
	unzip \
	libicu-dev \
	libzip-dev \
	&& rm -rf /var/lib/apt/lists/*

# Install PHP extensions and Composer
RUN docker-php-ext-install \
	intl \
	opcache \
	zip \
	; \
	curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set environment variables for Composer
ENV COMPOSER_ALLOW_SUPERUSER=1

###> Application-specific setup ###
# Copy the entire application
COPY . ./

# Run composer install after copying the full project
RUN set -eux; \
	composer install --no-cache --prefer-dist --no-dev --no-progress

# Ensure permissions are set correctly for runtime directories
RUN set -eux; \
	mkdir -p var/cache var/log; \
	chmod +x bin/console; sync;
###< Application-specific setup ###

# Command to run the Symfony CLI application
CMD ["php", "bin/console"]
