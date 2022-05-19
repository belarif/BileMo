## Installation du projet
### Copie du projet en local

1. Cliquez sur le bouton "code", puis sur la section HTTPS qui affiche l'url suivante :
####
                https://github.com/belarif/bile-mo.git 

copiez cette url à utiliser pour installer le projet en local.

2. Ouvrez le terminal de votre IDE. Si vous utilisez le server WampServer64, positionnez vous sur le chemin c:/wamp64/www comme suit :
####
                cd c:/wamp64/www
si vous utilisez un server autre que WampServer64, positionnez vous sur le chemin qui permettra l'exécution de l'API.

 3. Sur le même chemin, tapez la commande suivante pour cloner le projet : 
####
                git clone https://github.com/belarif/bile-mo.git

 Après exécution de la commande, le projet sera copié dans le répertoire www

### Installation des dépendances
                composer install

### Création de la base de données
1. Créer votre base de données en local
2. Modifier le fichier .env pour adapater les accès à votre SGBD
3. Créer le schéma de votre base de données: 
####
                php bin/console doctrine:migrations:migrate

### Chargement des fixtures
                php bin/console doctrine:fixtures:load
### Lancement de l'API
                php -S localhost:8000 -t public/
