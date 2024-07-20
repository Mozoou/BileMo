# BileMo REST API

Bienvenue dans le projet BileMo ! Ce projet vise à développer une API de haute qualité pour la vente de téléphones mobiles haut de gamme de l’entreprise BileMo. Contrairement à une plateforme de vente directe, BileMo fournit à ses partenaires un accès à son catalogue via une API, facilitant les transactions B2B.

## Contexte

BileMo est une entreprise spécialisée dans les téléphones mobiles premium. Notre modèle d'affaires repose sur la vente de nos produits à travers diverses plateformes web grâce à une API dédiée. Vous êtes chargé de créer cette API, qui permettra aux partenaires de BileMo d'accéder aux données et de réaliser diverses opérations, telles que :

-   Consulter la liste des produits BileMo
-   Consulter les détails d’un produit
-   Gérer les utilisateurs inscrits liés à un client

Seuls les clients authentifiés pourront utiliser cette API, avec une authentification via OAuth ou JWT.

## Besoins du Client

Notre premier partenaire a signé et nous devons rapidement répondre à ses exigences. Les fonctionnalités demandées incluent :

1.  Consultation de la liste des produits
2.  Consultation des détails d'un produit
3.  Gestion des utilisateurs liés à un client (consultation, ajout, suppression)

Le partenaire exige également que l’API respecte les niveaux 1, 2 et 3 du modèle de Richardson et que les données soient servies en JSON, avec une mise en cache des réponses pour des performances optimales.
## Prérequis
-   PHP 8.2.0;
- Composer
-   Et les prérequis [Symfony](https://symfony.com/doc/current/setup.html#technical-requirements).
## Installation
### Cloner le projet
    git clone git@github.com:Mozoou/BileMo.git
### Installer les dépendances
    composer install
### Générer les keypair
    php bin/console lexik:jwt:generate-keypair

## Utilisation
### Lancer le serveur (avec un terminal à la racine du projet)
    php -S localhost:(port) -t ./public

Une fois le serveur en route, vous pourrez accéder à l'url suivante (documentation)

> localhost:(port)/api/doc
