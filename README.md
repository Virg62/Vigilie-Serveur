# Vigilie

Accès Rapide :
- [Vigilie Client](https://github.com/Virg62/Vigilie-Client)
- [Vigilie Client Conseil Municipal](https://github.com/Virg62/Vigilie-Client-CM)
- [Vigilie Serveur](https://github.com/Virg62/Vigilie-Serveur)

Sommaire :

1- [Sujet](#sujet)
2- [Matériel Requis](#matériel-requis)
3- [Installation](#installation)
4- [Utilisation](#utilisation)

## Sujet

Vigilie est un système participatif permettant d'informer les citoyens en cas de dangers / pour informer mis en place par une mairie.
Ainsi, si un utilisateur trouve un potentiel danger (ex: Arbre couché sur une chaussée / Innondation d'une route /...), celui-ci peut le signaler et après validation par un membre du conseil municipal (ou d'un administrateur tiers), cette information peut être transmise à l'ensemble des utilisateurs de l'application via une notification.

L'application peut également servir au conseil municipal d'informer les citoyens (ex: fête du village prévue...)

Des modules peuvent être ajoutés à l'application (ex: Carte avec points d'eau potable publique / Sondages / ...) afin de la rendre plus fonctionnelle.

*Ces modules seront bientôt en développement !*

## Matériel Requis

Afin d'avoir le meilleur cadre d'utilisation de l'application, il serait préférable que le serveur hébergeant l'application se trouve au sein de la mairie. Toutefois, l'application peut aussi être placée sur un hébergeur standard (OVH / Kimsufi / ...)

*Le serveur peut être mis en place sur une machine Linux (Ubuntu / Debian / Mint) ou un pc Windows standard*

Le serveur doit disposer de : 
- Une connexion internet
- PHP 7.0 ou plus (avec le module PDO et CURL)
- MySQL
- Git
- Composer (lien pour le télécharger [ici](https://getcomposer.org/))

## Installation

- Tout d'abord cloner le repository du projet

```bash
    git clone https://github.com/Virg62/Vigilie-Serveur
```

- Naviguer dans le dossier créé

```bash
    cd Vigilie-Serveur
```

- Installer les dépendances du projet (via Composer)

```bash
    composer install
```

NOTE : La commande permet d'installer le module reallysimplejwt ([doc ici](https://github.com/RobDWaller/ReallySimpleJWT)), qui permet de gérer la connexion des utilisateurs via un token.

- A partir d'ici, il est possible de copier / déplacer les fichiers du Serveur dans la racine (ou ailleurs, mais il faut noter l'emplacement car important pour les clients).

- Créer une base de données sur le serveur MySQL avec un nom à votre convenance (le noter car important pour la suite).

- Importer les fichier [vigilie.sql](vigilie.sql) dans votre base de données fraichement créée.

- Récupérer une clé API Google Firebase (Notification) via la console :
* Créer un projet
* Ajouter une application (Android / IOS)
* Entrez le nom du package (défaut : `fr.virgile62150.vigilie.client`) pour le client
* Télécharger les fichiers de configuration (**OBLIGATOIRE POUR LE CLIENT**)
* Ajouter une application (Android / IOS) pour le client conseil municipal
* Idem que pour ci-dessus (nom du package par défaut : `fr.virgile62150.vigilie.clientcm`)
* Vous pouvez récuperer votre clé API dans 'Paramètres du Projet', dans l'onglet 'Cloud Messaging' (c'est la clé du serveur).

- Modifiez votre clé API dans le fichier [utilz/notify.php](utilz/notify.php)

- Modifier le fichier [utilz/db.php](utilz/db.php) afin d'y renseigner votre nom d'utilisateur de base de données, le mot de passe et le nom de la base de données.

- Il est maintenant possible de se connecter à l'application en créant un compte via le client.

- Ensuite se connecter à l'application Conseil Municipal avec les identifiants suivant : (`root` pour le nom d'utilisateur et le mot de passe.) pour accepter l'utilisateur et le définir comme Administrateur.

Il est ensuite recommandé de se déconnecter du compte `root`, se connecter avec le nouveau compte d'utilisateur, de déstituer le compte `root` et le supprimer pour une sécurité optimale.

## Utilisation

Normalement, aucune intervention (excepté mise à jour) n'est à faire sur cette partie. 

Les clients intéragissent avec le serveur via des requêtes. L'utilisateur ne peut intéragir directement avec cette partie.