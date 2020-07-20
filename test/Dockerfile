FROM php:7.3-cli

ARG wp_redis_version

WORKDIR /code

RUN pecl install redis && \
  pecl install igbinary && \
  docker-php-ext-enable redis igbinary
RUN apt-get update && apt-get install \
  unzip \
  wait-for-it
COPY test/build /build
RUN /build/download-oc "${wp_redis_version}"
RUN /build/download-phpunit
RUN /build/setup-wp
RUN rm -fr /build

CMD ["test/run"]
