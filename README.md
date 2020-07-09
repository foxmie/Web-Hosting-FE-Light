# Web-Hosting-FE-Light
Panel de gestion WEB (Light)

### Prérequis : 
 - Debian 9
 - Accès root
 
###  Clonage du repositories 
``apt-get update && apt-get -y install git``

 ``git clone https://github.com/foxmie/Web-Hosting-FE-Light.git``
 
###  Installation du panel
 ``cd Web-Hosting-FE-Light``
 
 ``chmod +x setup.sh``
 
 ``./setup.sh``
 
###  Accès au panel de gestion :
 ``http://ipv4:8000``
 
###  Le script d'installation installera et configurera automatiquement les services suivants : 
 - Apache2
 - PHP 5.6, 7.0, 7.1, 7.2, 7.3, 7.4
 - phpMyAdmin
 - Sudo
 - SendMail
 - MariaDB
 - Bind9
 - Nagios NRPE Client
 - ProFTPD
 - OpenSSH
 - ClamAV
 - ipTables

## Licences
- Ce projet est sous licence ``GNU GENERAL PUBLIC LICENSE V3``
- Bootstrap du panel : [Light Bootstrap Dashboard Pro](https://demos.creative-tim.com/light-bootstrap-dashboard-pro/examples/dashboard.html) - ``MIT Developer License ``
