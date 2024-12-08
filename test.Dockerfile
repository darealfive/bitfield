# syntax=docker/dockerfile:1.6.0
# The above line freezes dockerfile frontend syntax/features being available when using buildkit: https://hub.docker.com/r/docker/dockerfile

# Specifies the composer image used to install PHP dependencies
ARG COMPOSER_VERSION=2.7.6
# Specifies the PHP image used to execute tests
ARG PHP_VERSION=8.2.19-cli-alpine3.20
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


###############################
# PHP - execute phpunit tests #
###############################
FROM base AS tester
LABEL authors="Sebastian Krein"
# Passes all arguments to phpunit
ENTRYPOINT ["/usr/local/bin/docker-php-entrypoint", "vendor/bin/phpunit"]
# Runs all tests per default
CMD ["tests"]