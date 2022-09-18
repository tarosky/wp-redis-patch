FROM php:7.4-cli

WORKDIR /code

RUN pecl install redis && \
  pecl install igbinary && \
  docker-php-ext-enable redis igbinary
RUN apt-get update && apt-get install -y \
  unzip \
  wait-for-it \
  wget
COPY test/build /build
RUN /build/download-phpunit
RUN /build/install-composer
RUN rm -fr /build

CMD ["test/run"]
