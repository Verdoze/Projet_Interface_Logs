# Test de validation
### Rappel des cas d'usage

![UseCase Diagram](Diagramme/diagram%20usecase.png)

Il est donc attendu de pouvoir : 
```
- Consulter les logs
- Filtrer les logs
- Exporter les logs
```
## Connexion
La connexion est réfusée si l'identifiant ou le mot de passe n'est pas bon.

![Refus de connexion](img/connexionrefusee.png "Refus de connexion")

Si les identifiants sont bons, l'utilisateur peut accéder à la page de visualisation des logs :

![Accès page log](img/accespagelog.png "Accès à la page de logs")

## Différents filtres
Il est possible d'appliquer différents filtres :
- En fonction de la date:
![Filtre Date](img/filtredate.png "")
- En fonction de l'application qui génère le log:
![Filtre Application](img/filtreapp.png "")
- En fonction de la vm d'origine du log
![Filtre Hote](img/filtrevm.png "")
- En fonction du niveau de criticité:
![Filtre Niveau](img/filtreniveau.png "")
- En effectuant une recherche textuelle:
![Filtre Texte](img/filtretext.png "")

## Export de la liste des logs
Il doit être possible de télécharger un csv de la liste des logs : 
![Download CSV](img/downloadcsv.png "")

Il est ensuite possible d'exporter ces données dans un tableur.
![Export CSV](img/exportcsv.png "")

Et de visualiser les données.
![Classeur](img/classeur.png "")