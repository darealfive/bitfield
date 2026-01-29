# syntax=docker/dockerfile:1.6.0
# The above line freezes dockerfile frontend syntax/features being available when using buildkit: https://hub.docker.com/r/docker/dockerfile

# Specifies the composer image used to install PHP dependencies
ARG COMPOSER_VERSION=2.7.6
# Specifies the PHP image used to execute tests
ARG PHP_VERSION=8.1.31-cli-alpine3.21
############
# composer #
############
# Builder stage to specify the composer version to install PHP dependencies
# - turns "composer" image into an alias pointing to our desired image
FROM composer:$COMPOSER_VERSION AS composer


##############
# PHP - base #
##############
FROM php:$PHP_VERSION AS base
ARG WORKING_DIR
LABEL authors="Sebastian Krein"
# Specifies the build directory where we want to develop/test
WORKDIR $WORKING_DIR
# Provide composer to install dependencies directly within the app container
COPY --from=composer /usr/bin/composer /usr/local/bin/composer
RUN apk add --no-cache git linux-headers $PHPIZE_DEPS \
    && pecl install xdebug-3.4.4 \
    && docker-php-ext-enable xdebug
ENTRYPOINT ["/usr/local/bin/docker-php-entrypoint"]
# Runs a shell by default
CMD ["/bin/sh"]
ENV XDEBUG_MODE=coverage


###################################
# PHP - used to run phpunit tests #
###################################
FROM base AS tester
LABEL authors="Sebastian Krein"