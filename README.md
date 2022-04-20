## Installation du projet
### Copie de la branche BM2 en local
                git clone https://github.com/belarif/bile-mo.git
### Installation des dépendances
                composer install

### Création de la base de données
1. Créer votre base de données en local
2. Modifier le fichier .env pour adapater les accès à votre SGBD
3. Création du schéma de la BD: php bin/console doctrine:migrations:migrate

### Charger les fixtures
                php bin/console doctrine:fixtures:load
### Lancement de l'application
                php -S localhost:8000 -t public/
## Les ressources
### Connexion et obtention du token : 
La ressource : 
                
                GET http://localhost:8000/api/login

Depuis le body d'envoi de requete de Postman, utilisez le format json suivant:
                {
                    "username":"b.ocine@live.fr",
                    "password":"user1"
                }

### Creation d'un produit : 
                POST http://localhost:8000/api/products