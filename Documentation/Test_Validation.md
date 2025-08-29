# Test de validation
### Rappel des cas d'usage

![UseCase Diagram](Diagramme/diagramme%20usecase%20last%20version.png)

Il est donc attendu de pouvoir : 
```
- Consulter les logs
- Filtrer les logs
- Exporter les logs
```
## Consulter les logs
### Action : l'utilisateur doit se connecter pour accéder à la page de logs:

![Refus de connexion](img/connexion.png "Refus de connexion")

Si les identifiants sont bons, l'utilisateur accède à la page de visualisation des logs :

![Accès page log](img/accespagelog.png "Accès à la page de logs")

## Filtrer les logs
### Action : Appliquer un filtre de date
![Filtre Date](img/filtredate.png "")
### Action : Appliquer un filtre d'application
![Filtre Application](img/filtreapp.png "")
### Action : Appliquer un filtre de VM
![Filtre Hote](img/filtrevm.png "")
### Action : Appliquer un filtre de niveau de criticité
![Filtre Niveau](img/filtreniveau.png "")
### Action : Appliquer une recherche textuelle
![Filtre Texte](img/filtretext.png "")

## Exporter la liste des logs
### Action : cliquer sur l'export des logs
![Bouton Export](img/boutonexport.png "")

### Action : récupérer le fichier csv
![Download CSV](img/downloadcsv.png "")

### Action : exporter le fichier dans une application tableur
![Export CSV](img/exportcsv.png "")

### Action : visualiser les données
![Classeur](img/classeur.png "")