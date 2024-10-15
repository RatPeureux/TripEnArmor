#!/bin/bash

docker image pull r408-php:ctrltp-apache-pdo &&
docker image build -t apache-pdo:1.0 -f Dockerfile-apache-pdo . &&
docker-compose up