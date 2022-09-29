ARG PHP_VERSION
FROM php:${PHP_VERSION}-cli

WORKDIR /code

RUN pecl install redis && \
  pecl install igbinary && \
  docker-php-ext-enable redis igbinary
RUN apt-get update && apt-get install -y \
  unzip \
  wait-for-it \
  wget
COPY test/build /build

ARG PHPUNIT_VERSION
ENV PHPUNIT_VERSION $PHPUNIT_VERSION
RUN /build/download-phpunit

RUN /build/install-composer
RUN rm -fr /build

CMD ["test/run"]
