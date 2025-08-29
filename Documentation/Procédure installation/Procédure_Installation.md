# Procédure d'installation

## Installation VM de Log

```bash
apt install sudo apt install rsyslog -y
```

- crée un fichier pour le client example client.conf
  se rendre dans les fichier /etc/rsyslog.d/client.conf autorisé les flux sur le port 514 (port de base pour rsyslog) vers la machine contenant la BDD.

![alt text](../Img/conf%20rsyslog%20VM%20WEB.png)

- coté serveur :

```bash
apt install sudo apt install rsyslog -y
```

- crée un fichier server.conf, autorisé les flux tcp sur le port 514.

![alt text](../Img/conf%20rsyslog%20VM%20LOGS.png)

- redemarrer le service.

```bash
systemctl restart rsyslog
```

## Installation chrony pour la syncronisation ntp :

```bash
sudo apt install chrony -y
```

- crée un fichier dans /etc/chrony/chrony.conf dans la vm ou il y a la bdd

```bash
pool 0.pool.ntp.org iburst
allow 172.16.4.0/24
```

![alt text](../Img/conf%20chrony%20VmWeb.png)

- dans la vm log faire pareil crée un fichier dans /etc/chrony/chrony.conf

```bash
server 172.16.4.65 iburst
```

![alt text](../Img/conf%20chrony%20VmLogs.png)

ne pas oublier de redemarrer chrony
systemctl restart chrony

---

## Procédure d’installation NGINX PHP et PHP-FPM

```bash
apt install nginx php php-fpm

cd /etc/nginx/sites-enabled
nano log.conf

#création du fichier nginx
server {
  listen 80 default_server;
  server_name _;

  root /var/www/log/public;
  index index.php;

  client_max_body_size 2m;

  location / {
    try_files $uri /$uri /index.php?$query_string;
  }

  location ~ \.php$ {
    include snippets/fastcgi-php.conf;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    fastcgi_pass unix:/run/php/php8.4-fpm.sock;  # adapte selon ta version >  }
}

#Création des dossiers et fichiers php

cd /var/www/log
mkdir -p config inc public
touch config/.env \
      inc/db.php inc/auth.php \
      public/index.php public/login.php public/logout.php public/logs.php public/export.php

#Créer un lien symbolique pour activer le site
 sudo ln -s /etc/nginx/sites-available/log.conf  /etc/nginx/sites-enabled/

#Mettre les droits utilisateurs php/nginx
chown -R www-data:www-data /var/www/log/

#Tester la conf & Reload nginx
nginx -t
systemctl reload nginx

#Vérifier le fonctionnement
http://172.16.4.65
```
