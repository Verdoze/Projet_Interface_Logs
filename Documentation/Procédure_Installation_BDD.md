# Procédure de configuration
# 1. Configuration de la VM BDD 
```bash
root> apt update
apt install sudo
apt install mariadb-server
apt install rsyslog-mysql
```

### Configuration de la BDD
```bash
sudo dpkg-reconfigure rsyslog-mysql
```
*Répondre "Oui" aux questions pour que dbconfig-common configure automatiquement la base de données.*

## Configuration réception TCP avec rsyslog
#### Éditer le fichier `/etc/rsyslog.conf` pour activer la réception TCP :
module(load="imtcp")
input(type="imtcp" port="514")

### Configuration du module MySQL dans rsyslog
Vérifier que le module est chargé dans `/etc/rsyslog.d/mysql.conf`:
```
module(load="ommysql")
. action(type="ommysql" server="localhost" db="Syslog" uid="rsyslog" pwd="motdepasse")
```
*Remplacez `motdepasse` par le mot de passe défini pour l’utilisateur MariaDB `rsyslog`.*

### Redémarrer le service rsyslog
```bash
sudo systemctl restart rsyslog
```
# 2. Configuration de la VM de Log
### Configuration de l’envoi des logs vers le serveur rsyslog (VM BDD)

Créer ou éditer `/etc/rsyslog.d/remote.conf`, y ajouter la destination pour l'envoi des logs via TCP (port 514) :
```
*.* @@ip_du_serveur_bdd:514
```
*Remplacer `ip_du_serveur_bdd` par l’adresse IP ou le nom de la VM BDD.*

### Redémarrer le service rsyslog
```bash
sudo systemctl restart rsyslog
```
#creation fichier /etc/rsyslog.d/remote.conf
*.* @@ip_du_serveur:514

## 3. Vérifications
### Sur la VM générant les logs (client)

Surveiller les logs locaux pour vérifier la génération des messages :
```bash
tail -f /var/log/syslog
```

### Sur la VM BDD (serveur rsyslog + MariaDB)

Installer net-tools si nécessaire pour vérifier le port d’écoute de rsyslog :
```bash
sudo apt install net-tools
```
Vérifier que rsyslog écoute sur le port TCP 514 :
```bahs
sudo netstat -tnlp | grep rsyslog
```
### Exemple de sortie attendue :
```
tcp 0 0 0.0.0.0:514 0.0.0.0:* LISTEN 9065/rsyslogd\
tcp6 0 0 :::514 :::* LISTEN 9065/rsyslogd
```
## Vérfifier le remplissage de la table de logs en BDD
```bash
mariadb -u rsyslog -p Syslog
```
```SQL
SELECT ReceivedAt, FromHost, Facility, Priority, Message, SysLogTag FROM SystemEvents ORDER BY ID DESC LIMIT 20;
```