# Projet Symfony

## Utilisation de Git

**Définitions :**

``` git fetch ``` : Récupérer des branches et/ou des étiquettes (collectivement, "réfs") depuis un ou plusieurs autres dépôts, ainsi que les objets nécessaires pour compléter leur historique. Les branches de suivi à distance sont mises à jour

``` git pull ``` : Intègre les modifications d’un dépôt distant dans la branche actuelle. Dans son mode par défaut, git pull est l’abréviation de git fetch suivi de git merge FETCH_HEAD.

**Branches :**

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
