# Symfony-ticket-mgmt
Portail de gestion de tickets.

# Docker desktop
Lancer VsCode et se connecter à WSL.
Ouvrir le dossier du projet:  
    \\\wsl.localhost\Ubuntu\root\Symfony-ticket-mgmt  
Ouvrir un terminal bash


## Construction des images
```
docker compose build --no-cache
```
## Lancement des containers
```
docker compose up --wait
```
## Suppression des containers
```
docker compose down --remove-orphans
```

# Chargement des données de test:
Terminal dans le container:
```
php bin/console doctrine:fixtures:load
```

# Accés à la base de données PostGre SQL
Lancer PGAdmin
Connexion:
| Paramètre | Valeur |
|:--:|:--:|
| Host | localhost |
| Port | Voir valeur dans Docker desktop |
| DB name | app |
| User | app |
| Password | !ChangeMe! |

__Attention:__ cette configuration doit être modifié avant tout déploiement.