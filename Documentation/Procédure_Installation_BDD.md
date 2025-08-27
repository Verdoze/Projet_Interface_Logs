root> apt install sudo
apt update
apt install mariadb-server
apt install rsyslog-mysql

sudo mariadb

CREATE DATABASE Syslog;
CREATE USER 'rsyslog'@'localhost' IDENTIFIED BY 'rsyslogpwd01';
GRANT ALL ON Syslog.* TO 'rsyslog'@'localhost';
FLUSH PRIVILEGES;

#Modif dans /etc/rsyslog.conf
module(load="imtcp")
input(type="imtcp" port="514")

#Chargement du module dans /etc/rsyslog.d/mysql.conf
module(load="ommysql")
*.* action(type="ommysql" server="localhost" db="Syslog" uid="rsyslog" pwd="motdepasse")

#creation fichier /etc/rsyslog.d/remote.conf
*.* @@ip_du_serveur:514

#Vérification
#Sur le serveur qui génère les logs
tail -f /var/log/syslog => lecture des logs
logger "test rsyslog depuis Vm de Log" => génération d'un message de log

#sur le serveur qui recoit les logs
apt install net-tools
root@VM-WEB:~# sudo netstat -tnlp | grep rsyslog
tcp        0      0 0.0.0.0:514             0.0.0.0:*               LISTEN      9065/rsyslogd
tcp6       0      0 :::514                  :::*                    LISTEN      9065/rsyslogd