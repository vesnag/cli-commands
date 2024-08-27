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
	cron \
	procps \
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
	composer install --no-cache --prefer-dist --no-dev --no-progress --no-scripts

# Ensure permissions are set correctly for runtime directories
RUN set -eux; \
	mkdir -p var/cache var/log; \
	chmod +x bin/console; sync;
###< Application-specific setup ###

# Add crontab file
COPY crontab /etc/cron.d/my-cron-job

# Give execution rights on the cron job
RUN chmod 0644 /etc/cron.d/my-cron-job

# Apply cron job
RUN crontab /etc/cron.d/my-cron-job

# Create the log file to be able to run tail
RUN touch /var/log/cron.log

# Copy entrypoint script
COPY entrypoint.sh /usr/local/bin/entrypoint.sh

# Give execution rights on the entrypoint script
RUN chmod +x /usr/local/bin/entrypoint.sh

# Set the entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Default command (can be overridden by providing a command)
CMD ["php", "bin/console"]
