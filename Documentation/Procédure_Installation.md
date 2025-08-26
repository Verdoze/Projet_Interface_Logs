# Procédure d'installation
## Installation VM de Log

```bash
apt install sudo apt install rsyslog -y
```
* crée un fichier pour le client example client.conf 
  se rendre dans les fichier /etc/rsyslog.d/client.conf autorisé les flux sur le port 514 (port de base pour rsyslog) vers la machine contenant la BDD.

![alt text](image-1.png)

apt-get install lvm2