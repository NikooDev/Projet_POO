# Projet Symfony

## Utilisation de Git

**1- Définitions :**

``` git fetch ``` : Récupérer des branches et/ou des étiquettes (collectivement, "réfs") depuis un ou plusieurs autres dépôts, ainsi que les objets nécessaires pour compléter leur historique. Les branches de suivi à distance sont mises à jour

``` git pull ``` : Intègre les modifications d’un dépôt distant dans la branche actuelle. Dans son mode par défaut, git pull est l’abréviation de git fetch suivi de git merge FETCH_HEAD.

``` git checkout <nom de la branche> ``` : Permet de changer de branche

**2- Avant toute modification du code dans une branche :**

- Faire un ```git fetch```
- Récupérer les dernières mises à jour de la branche sur laquelle on travaille (L'un de nous aura peut-être modifié du code) : Voir la section 5 "Mise à jour des branches locales
- Placez-vous dans la branche à travailler : ``` git checkout <nom de la branche>``` /!\ PAS DANS MASTER

**3- Travail en cours :**

Avant de publier les modifications, avertir les autres sur Teams pour éviter les pushs croisés sur une même branche
À ce stade, les modifications sont possibles en sécurité

**4- Publier les modifications dans la branche :**

- Créer le commit
- Faire le push : ``` git push origin <nom de la branche> ```

Les modifications sont publiées uniquement dans la branche de travail.
 
**5 -Mise à jour des branches locales :**

Pour mettre à jour une branche de votre dépôt local avec la branche accessible depuis le dépôt en ligne, placez-vous dans la branche sur laquelle vous souhaitez travailler.

Exécutez :

```bash
git pull origin <nom de la branche>
```

Les modifications qui auront été pushées sur cette branche se synchroniseront dans votre dépôt local.

- Template : ``` git checkout template ``` pour travailler sur Twig
- Migration : ``` git checkout migration ``` pour créer les entités et migrations
- Data : ``` git checkout data ``` pour la gestion des articles, catégories, requêtes
- Auth : ``` git checkout auth ``` pour gérer l'authentification et la sécurité

Prévenir le groupe avant chaque push sur la branche
