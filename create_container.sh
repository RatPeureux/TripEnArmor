#!/bin/bash

# Tirez l'image Docker spécifiée à partir du registre
docker image pull bigpapoo/r301-php:8.2-apache &&

# Construisez une nouvelle image Docker à partir du Dockerfile spécifié
docker image build -t apache-pdo:1.0 -f dockerfile-apache-pdo . &&

# Lancez les services définis dans le fichier docker-compose en mode détaché
docker-compose up -d

# Attendez 5 secondes pour donner le temps aux services de démarrer
sleep 5

# Récupérez l'ID du conteneur en cours d'exécution à partir de docker-compose
CONTAINER_NAME=$(docker-compose ps -q)

# Affichez un message à l'utilisateur pour lui indiquer comment accéder au conteneur
echo "Pour vous connecter au conteneur, exécutez la commande suivante dans un terminal :"
echo "docker exec -it $CONTAINER_NAME /bin/bash"

# Définissez un piège pour arrêter les services Docker lorsque le script est interrompu
trap 'docker-compose down -v' SIGINT SIGTERM

# Boucle infinie pour garder le script en cours d'exécution
while :; do
    sleep 1
done
