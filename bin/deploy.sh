#! /bin/bash

composer install

docker-composer build
docker-composer up -d
