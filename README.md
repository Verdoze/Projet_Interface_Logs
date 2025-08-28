# Projet_Interface_Logs

VM LOG = 172.16.4.71
user1   Admin1..1
root    Admin1..1
cmd ssh user1@172.16.4.71

VM WEB = 172.16.4.65
user1   Admin1..1
root    Admin1..1
cmd     ssh user1@172.16.4.65
BDD Syslog / User: rsyslog / MDP : rsyslogpwd01
userlog:userlogpwd01


Consulter les logs :
mariadb -u rsyslog -p Syslog
SELECT ReceivedAt, FromHost, Facility, Priority, Message, SysLogTag FROM SystemEvents ORDER BY ID DESC LIMIT 20;