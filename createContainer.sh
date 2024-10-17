#!/bin/bash

docker image pull r408-php:ctrltp-apache-pdo &&
docker image build -t apache-pdo:1.0 -f Dockerfile-apache-pdo . &&

docker-compose up -d

sleep 5

CONTAINER_NAME=$(docker-compose ps -q)

echo "Pour vous connecter au conteneur, exécutez la commande suivante dans un terminal :"
echo "docker exec -it $CONTAINER_NAME /bin/bash"

code .

docker exec -it $CONTAINER_NAME /bin/bash -c "php -S localhost:8080 &"

./initialisation


open http://localhost:8080/TripEnArmor/dockerBDD/connexion/membre/login_membre.php


trap 'docker-compose down -v' SIGINT SIGTERM

while :; do
    sleep 1
done
