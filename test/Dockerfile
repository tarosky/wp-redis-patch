FROM php:7.4-cli

ARG wp_redis_version

WORKDIR /code

RUN pecl install redis && \
  pecl install igbinary && \
  docker-php-ext-enable redis igbinary
RUN apt-get update && apt-get install -y \
  unzip \
  wait-for-it \
  wget
COPY test/build /build
RUN /build/download-oc "${wp_redis_version}"
RUN /build/download-phpunit
RUN /build/setup-wp
RUN /build/install-composer
RUN rm -fr /build

CMD ["test/run"]
