
# Analyse des recommandations ANSSI

Dans le cadre du projet, nous avons étudié les recommandations de l’ANSSI relatives à la journalisation.  
Toutes n’ont pas été retenues : certaines ont été mises en œuvre car simples et pertinentes, d’autres jugées trop complexes pour la durée du projet (3 jours), et enfin certaines hors périmètre.

---

## Recommandations mises en œuvre

Nous avons choisi d’appliquer en priorité les recommandations les plus essentielles et simples à intégrer :

- **Journalisation native** : l’application CNF génère déjà des logs, et nous utilisons **rsyslog** pour assurer leur collecte et leur transfert.  
- **Horodatage homogène** : les logs sont normalisés en **UTC/ISO 8601**, ce qui permet de corréler les événements sur plusieurs systèmes.  
- **Synchronisation temporelle (NTP)** : les deux VMs utilisent **NTP** pour garantir la cohérence des horodatages.  
- **Centralisation** : les journaux sont collectés et stockés sur une VM dédiée, distincte de l’application.  
- **Transfert fiable et sécurisé** : nous avons retenu **TCP/HTTP(S)** pour éviter la perte de messages et protéger le flux avec un **token d’ingestion**.  
- **Stockage indexé** : la base **MariaDB** conserve les événements avec des index sur les champs principaux (date, niveau, application).  
- **Rotation et rétention** : un mécanisme de purge simple supprime les logs au-delà de **90 jours** pour maîtriser l’espace disque.  
- **Gestion des accès** : seuls **rsyslog** et l’application PHP peuvent écrire en base, et la consultation des journaux est protégée par **authentification**.  

Ces points couvrent la majorité des besoins fonctionnels et garantissent un minimum de sécurité et de conformité, tout en restant réalistes dans le délai imparti.

---

## Recommandations non retenues (trop complexes pour le projet)

Certaines recommandations de l’ANSSI nécessitent une architecture plus lourde ou des outils spécialisés :

- **Architecture hiérarchisée de collecte** (plusieurs niveaux de collecteurs) : pertinente en production multi-sites, mais trop lourde pour un projet en 2 VMs.  
- **Analyse de risques approfondie** sur le mode de transfert : hors périmètre du projet étudiant.  
- **Durcissement avancé** (SI cloisonné, partitions disque dédiées) : difficile à mettre en place dans un contexte limité en temps.  
- **Surveillance automatique de l’espace disque** : nous avons prévu seulement une vérification manuelle.  

---

## Recommandations hors périmètre

Enfin, certaines recommandations ne concernent pas notre projet :

- **Journalisation des postes nomades** (VPN, mobilité).  
- **Recours à un PDIS** (prestataire qualifié ANSSI) pour externalisation ou corrélation avancée.  
- **Interconnexions avec d’autres SI** ou collectes multi-domaines.  
- **Détection avancée et simulation d’attaques** (EDR, Red Team).  


---
## Contexte : 

Nous avons une application web qui génère des log (notre application cnf) basé sur angular et nodeJS et nous devons déporter les logs sur une autre machine que celle qui héberge l’application pour des raisons de sécurité (selon les recommandations de l’anssi)

---

## Expression du besoin :

Il faut avoir accès aux logs pour les personnes ayant les privilèges élevés sans besoin d’accéder à l’application pour pouvoir agir rapidement en cas de problème. Il faut donc que les serveurs soient synchronisés pour avoir les informations des côtés en même temps. Il faut gérer les accès des différents utilisateurs (admin, dev, tech)

---

## Objectif principal : 
Mettre en place une collecte centralisée et sécurisée des logs de CNF vers une VM dédiée, avec une interface web permettant la recherche, la consultation.

---

## Fonctions principales :

- Les logs de l’application CNF sont lus et envoyés instantanément à la VM de l’application de consultation de log. (collecte de log)
- Interface web php permettant de consulter et filtrer les logs sur l’application php.
- La gestion du stockage temporaire des logs entre la VM LOG et la VM APP
- Administration des utilisateurs

---

## Critères de performance :

- Affichage des logs en moins de 20 secondes
- Synchronisation de l’heure en temps quasi réel (ntp)
- Traçabilité des accès
- Filtrage des logs réponse en moins de 15 secondes

---

## Contraintes techniques : 

- 2 VM, une de collecte de log et une applicative
- Utilisation de PHP
- Utilisation des recommandation de l’anssi
- Utilisation de Rsyslog
- Obligation d’avoir une application générant des logs

---

## Liste des livrables :

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

---

## Liste de matériels :

- 2 VM debian

## Liste logiciel :

- Nginx
- PHP
- MariaDB
- Rsyslog
- Application générant log

---

## UML Bloc

![image.png](/Documentation/diagramme%20bloc%20uml.png)

---

## UML Usecase

![image.png](/Documentation/Diagramme%20uml%20usecase.png)

---

## Synoptique :

![image.png](/Documentation/synoptique%20fonctionnement.png)


## Sitemap : 

![image.png](/Documentation/sitemap.png)
