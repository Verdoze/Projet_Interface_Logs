# Php programmation web

**Tableau des recommandations de l’anssi :** 

| Recommandation ANSSI | Statut projet | Justification |
| --- | --- | --- |
| Utiliser des solutions avec fonction de journalisation native | ✔ Pris en compte | Node.js/Angular génèrent déjà des logs + rsyslog présent |
| Activer la journalisation sur un grand nombre d’équipements | ✖ Non retenu | Projet limité à une seule appli (CNF) |
| Horodater les évènements | ✔ Pris en compte | Horodatage UTC dans rsyslog + DB |
| Homogénéiser les paramètres d’horodatage | ✔ Pris en compte | Format ISO 8601/UTC unique |
| Synchroniser les horloges (NTP) | ✔ Pris en compte | NTP activé sur les 2 VMs |
| Identifier granularité de journalisation | ✖ Non retenu | Pas de tuning fin prévu (NIVEAU INFO/ERROR suffisent) |
| Journaliser empreintes fichiers malveillants | ❌ Hors périmètre | Pas d’antivirus/EDR intégré |
| Estimer espace stockage local | ✔ Simplifié | DB ≤ quelques centaines de Mo, suivi manuel |
| Centraliser les journaux | ✔ Pris en compte | VM LOG → VM APP Collecte |
| Service résilient de collecte | ✖ Non retenu | Pas de redondance (POC simple) |
| Hiérarchiser les serveurs de collecte | ✖ Non retenu | Une seule VM de collecte |
| Contrôler couverture de la collecte | ✖ Non retenu | Trop complexe pour délai |
| Conserver logs dans format natif avant transfert | ✖ Non retenu | Envoi direct JSON via rsyslog |
| Transfert temps réel | ✔ Pris en compte | rsyslog push immédiat |
| Transfert différé | ✖ Non retenu | Non nécessaire |
| Analyse de risque pour mode transfert | ✖ Non retenu | Projet étudiant, faible risque |
| Utiliser protocoles fiables | ✔ Pris en compte | TCP/HTTP |
| Utiliser protocoles sécurisés | ✔ Simplifié | HTTPS + token d’ingestion |
| Maîtriser bande passante | ✖ Non retenu | Volume faible de logs |
| Durcir & MAJ serveurs de collecte | ✔ Simplifié | MAJ paquets + firewall |
| Cloisonner serveurs collecte dans SI admin | ✖ Non retenu | Pas d’archi complexe |
| Partition disque dédiée | ✖ Non retenu | Trop lourd pour POC |
| Superviser espace disque | ✔ Simplifié | Vérif manuelle `df -h` |
| Classer journaux par thématique | ✔ Simplifié | Champs `app`, `level`, `host` en DB |
| Stockage DB indexée | ✔ Pris en compte | MariaDB + index |
| Rotation journaux | ✔ Simplifié | Script purge >90 j |
| Durées de rétention conformes | ✔ Simplifié | Conservation 3 mois |
| Restreindre droits écriture journaux | ✔ Pris en compte | Seul PHP/rsyslog écrit DB |
| Restreindre droits suppression | ✔ Simplifié | Suppression interdite via UI |
| Restreindre droits lecture | ✔ Pris en compte | Accès UI protégé par login |
| Journalisation externalisation | ❌ Hors périmètre | Pas d’externalisation |
| Journaux interconnexions | ❌ Hors périmètre | Pas d’interconnexion externe |
| Recourir à PDIS si externalisation | ❌ Hors périmètre | Projet interne |
| Collecter journaux postes nomades | ❌ Hors périmètre | Pas de postes nomades |

**Contexte :** 

Nous avons une application web qui génère des log (notre application cnf) basé sur angular et nodeJS et nous devons déporter les logs sur une autre machine que celle qui héberge l’application pour des raisons de sécurité (selon les recommandations de l’anssi)

**Expression du besoin :** 

Il faut avoir accès aux logs pour les personnes ayant les privilèges élevés sans besoin d’accéder à l’application pour pouvoir agir rapidement en cas de problème. Il faut donc que les serveurs soient synchroniser pour avoir les informations des côtés en même temps. Il faut gérer les accès des différents utilisateurs (admin, dev, tech)

**Objectif principal :** 
Mettre en place une collecte centralisée et sécurisée des logs de CNF vers une VM dédiée, avec une interface web permettant la recherche, la consultation.

**Fonctions principales :**

- Les logs de l’application CNF sont lus et envoyés instantanément à la VM de l’application de consultation de log. (collecte de log)
- Interface web php permettant de consulter et filtrer les logs sur l’application php.
- La gestion du stockage temporaire des logs entre la VM LOG et la VM APP
- Administration des utilisateurs

**Critères de performance :** 

- Affichage des logs en moins de 20 secondes
- Synchronisation de l’heure en temps quasi réel (ntp)
- Traçabilité des accès
- Filtrage des logs réponse en moins de 15 secondes

**Contraintes techniques :** 

- 2 VM, une de collecte de log et une applicative
- Utilisation de PHP
- Utilisation des recommandation de l’anssi
- Utilisation de Rsyslog
- Obligation d’avoir une application générant des logs

**Liste des livrables :**

- Une documentation du projet explicatif
    - Analyse Anssi (Enzo, Arthur)
    - Procédure installation (Enzo)
    - Doc utilisateurs (Enzo)
    - Cahier de tests (Enzo)
- La documentation technique du projet
    - Répartition de la charge (Yanis)
    - Diagramme UML (Yanis)
    - Synoptique (Yanis)
    - Sitemap et mockup (Yanis)
- La programmation et la configuration système
    - Application PHP (Arthur, Yanis, Enzo)
    - Schéma de BDD (Arthur, Enzo)
    - Configuration Nginx (Yanis)
    - Configuration Rsyslog (Arthur)
- Le diaporama (Yanis)
- Repo Github (Enzo)
    - Contenu de tout le projet + réalisation de l’application