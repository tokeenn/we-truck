# We-truck

Projet en symfony pour l'entreprise we-truck.fr

## Prérequis
### Composer
Tout d'abord, vérifiez que votre environnement de développement est compatible avec Composer. Pour utiliser Composer, vous avez besoin d'une version de PHP 5.3.2 ou supérieure installée sur votre machine.

Téléchargez le fichier d'installation de Composer depuis le site officiel (https://getcomposer.org/download/). Il existe plusieurs options pour télécharger Composer, mais l'option recommandée est de télécharger l'installeur en ligne de commande.

Ouvrez votre terminal ou invite de commande et naviguez jusqu'au dossier où vous avez téléchargé l'installeur de Composer.


`Exécutez la commande suivante pour installer Composer :`
```bash
php installer.php
```

Une fois l'installation terminée, vous devriez pouvoir exécuter la commande `composer` dans votre terminal pour vérifier que Composer est correctement installé. Si tout fonctionne, vous devriez voir une liste des commandes disponibles avec Composer.
### Eslint
Il est important d'avoir un code clean pour que n'importe qu'elle personne qui passe derrière puisse le comprendre et/ou le modifier dans un environnement propre. Eslint est un plug in qui va vous corriger quand vous aurez des erreurs de syntaxe ou par soucis de clarité dans votre code.

## Installation

Une fois les prérequis installer, executé cette commande pour initialiser le projet

```bash
cd myproject
```
```bash
composer install
```

Exécuter cette commande pour lancer le projet sur votre réseau local 
```bash
symfony server:start
```
