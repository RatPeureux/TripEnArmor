# Manipulations pour le TD BDD-WEB R3.07

* récupérer l'archive docker.zip
* Faire
 `docker image pull r408-php:ctrltp-apache-pdo`

 `docker image build -t apache-pdo:1.0 -f Dockerfile-apache-pdo .`

* Lancer 

`docker-compose up`

* Se connecter au conteneur avec VSC avec Open a Remore Window (bouton bleu en bas à gauche), puis 'Attach to running container...' dans le répertoire `/var/www/html` (par défaut normalement)

* En fin de séance :
`docker-compose down -v`

