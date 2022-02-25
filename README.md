# Panel de Withelist ArmA 3

Production : Loic Harrang / Sharywan

##INSTALLATION (ToDo : Install apache + php7.3)

1-> Installer composer (https://getcomposer.org/download/) et passer à la version 1 : `composer self-update --1`

2-> Installer NodeJS version 12 (https://nodejs.org/es/blog/release/v12.13.0/)

3-> Récuperer ce repository sur votre machine (git clone https://github.com/LoicHarrang/WithelistPanel.git)

4-> Une fois à la racine du repository, installer les dépendances avec composer : `composer install`

5-> Ensuite, copier le .env.example et renommer le .env. Vous pouvez editer les informations à l'intérieur

6-> Retourner sur votre console, et faite la génération de la clé d'environnement : `php artisan key:generate`

7-> Ensuite, installer les dépendances JS : `npm install`

8-> Il vous suffit ensuite de compiler le projet : `npm run dev`

9 -> ATTENTION : Ne pas oublier de configurer le mod rewrite sur apache ainsi que de pointer le DocumentRoot vers le dossier /public de notre repository
