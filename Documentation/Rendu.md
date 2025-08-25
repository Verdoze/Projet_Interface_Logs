# Rendu Projet Journalisation
## Recommandations de l'ANSSI
1. Choix de mise en oeuvre:
   - Journalisation native Linux (rsyslog)
   - Synchronisation temporelle des systèmes (NTP)
   - Horodatage des journaux (Format Homogène)
   - Fiabiliser le transfert de données (Protocole TCP)
   - Rotation des logs : Contrôle et rétention (Logrotate)
   - Droits d'accès (restreindre lecture/écriture sur les comptes)
  
2. Trop complexe ou long pour mise en oeuvre
   - Architecture hiérarchique avec serveurs de collecte intermédiaires et centraux, meilleure répartition de  la charge,facilite la gestion multi-sites.
   - Recour à un prestataire de détection d’incidents qualifié (PDIS) pour l’externalisation du stockage ou la corrélation des logs.
   - Adapter et faire évoluer les politiques de journalisation/détection en continu
  
3. Ne souhaite pas mettre en oeuvre
    - Collecter et superviser les logs des postes nomades, même en situation de mobilité via VPN.
    - Cloisonner physiquement les serveurs de collecte dans une zone dédiée du SI d’administration
    - Recours à des outils et pratiques avancées : L’utilisation d’outils professionnels de détection avancée ou la simulation d’attaques complexes (DetectionLab, Atomic Red Team).