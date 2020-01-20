# Web-Hosting-FE-Light
Panel de gestion WEB (Light)

### Prérequis : 
 - Debian 9
 - Accès root
 
###  Clonage du repositories 
``apt-get update && apt-get -y install git``

 ``git clone https://github.com/alexis77370/Web-Hosting-FE-Light.git``
 
###  Installation du panel
 ``cd Web-Hosting-FE-Light``
 
 ``chmod +x setup.sh``
 
 ``./setup.sh``
 
###  Accès au panel de gestion :
 ``http://ipv4:8000``
 
###  Le script d'installation installera et configurera automatiquement les services suivants :
 - Apache2
 - Bind9
 - MariaDB
 - OpenSSH
 - Sudo
 - PHP5.6, 7.0, 7.1, 7.2, 7.3, 7.4
 - phpMyAdmin

## Licences
- Ce projet est sous licence ``GNU GENERAL PUBLIC LICENSE V3``
- Bootstrap du panel : [Light Bootstrap Dashboard Pro](https://demos.creative-tim.com/light-bootstrap-dashboard-pro/examples/dashboard.html) - ``MIT Developer License ``
