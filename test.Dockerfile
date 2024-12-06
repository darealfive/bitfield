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
FROM composer:$COMPOSER_VERSION as composer


####################
# PHP - base image #
####################
FROM php:$PHP_VERSION as base
LABEL authors="Sebastian Krein"


###########################################
# PHP - the image to install dependencies #
###########################################
FROM base as installer_base
LABEL authors="Sebastian Krein"
# Specifies the build directory where we install the PHP dependecies.
WORKDIR /build
# Provide composer to install dependencies directly within the app container
COPY --from=composer /usr/bin/composer /usr/local/bin/composer
# Copy composer.{json|lock} files to the working directory
COPY /composer.* .


#########################
# PHP - DEV environment #
#########################
FROM installer_base as installer_dev
LABEL authors="Sebastian Krein"
# Install dev dependencies
RUN composer install --dev


#########################
# PHP - MIN environment #
#########################
FROM installer_base as installer_min
LABEL authors="Sebastian Krein"
# Install minimum dependencies
RUN composer install


################
# PHP - Runner #
################
FROM base as base_runner
LABEL authors="Sebastian Krein"
# Specifies the working directory for the test image. Also the path where the source code lives.
ARG WORKING_DIR=/php_library
WORKDIR $WORKING_DIR


####################################
# PHP - the image to execute tests #
####################################
FROM base_runner as tester
LABEL authors="Sebastian Krein"
# Copy installed dependencies into our (right now) empty working directory. "--link" is used to prevent build cache being invalidated.
COPY --link --from=installer_dev /build/vendor vendor
# Copy source code to the working directory
COPY / .
# Passes all arguments to phpunit
ENTRYPOINT ["/usr/local/bin/docker-php-entrypoint", "vendor/bin/phpunit"]
# Runs all tests per default
CMD ["tests"]


###################################
# PHP - the image to run commands #
###################################
FROM base_runner as runner
LABEL authors="Sebastian Krein"
# Copy installed dependencies into our (right now) empty working directory. "--link" is used to prevent build cache being invalidated.
COPY --link --from=installer_min /build/vendor vendor
# Copy source code to the working directory
COPY / .
# Passes all arguments to phpunit
ENTRYPOINT ["/usr/local/bin/docker-php-entrypoint", "-f"]
# Runs the demo per default
CMD ["cli_demo.php"]