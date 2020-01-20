#!/bin/bash

#    This file is part of Web Hosting FE Light.
#
#    Web Hosting FE Light is free software: you can redistribute it and/or modify
#    it under the terms of the GNU General Public License as published by
#    the Free Software Foundation, either version 3 of the License, or
#    (at your option) any later version.
#
#    Web Hosting FE Light is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU General Public License for more details.
#
#    You should have received a copy of the GNU General Public License
#    along with Web Hosting FE Light.  If not, see <https://www.gnu.org/licenses/>.

# Verification de la version de debian
if [ -f "/etc/debian_version" ];then
	echo "Le fichier de version est charge !";
	var=`cat /etc/debian_version`
	var=`echo $var | cut -f1 -d.`
	if [ $var = 9 ];then
		echo "La version de debian est compatible !";
	else
		echo "Script pour installation sur Debian 9 uniquement!";
		exit 2
	fi
else
	echo "Script pour installation sur Debian uniquement!";
	exit 1
fi

# Mis à jour du serveur
apt-get update && apt-get -y upgrade && apt-get -y dist-upgrade && apt-get -y full-upgrade

# Installation des utilitaires
apt-get -y install ca-certificates apt-transport-https
apt-get -y install openssh-server
apt-get -y install ntp ntpdate
apt-get -y install python-certbot-apache
apt-get -y install sudo

# Definition des mots de passe
mysqlroot=`date +%s | sha256sum | base64 | head -c 25`
webhostingfepassword=`date +%s | sha256sum | base64 | head -c 25`

# Configuration de l'heure
service ntp stop
rm /etc/timezone
rm /etc/localtime
echo "Europe/Paris" > /etc/timezone
dpkg-reconfigure -f noninteractive tzdata
ntpdate pool.ntp.org
service ntp start

# Configuration de ntp
sed -i -e "s/pool 0.debian.pool.ntp.org iburst/server 0.fr.pool.ntp.org iburst dynamic/g" /etc/ntp.conf
sed -i -e "s/pool 1.debian.pool.ntp.org iburst/server 1.fr.pool.ntp.org iburst dynamic/g" /etc/ntp.conf 
sed -i -e "s/pool 2.debian.pool.ntp.org iburst/server 2.fr.pool.ntp.org iburst dynamic/g" /etc/ntp.conf 
sed -i -e "s/pool 3.debian.pool.ntp.org iburst/server 3.fr.pool.ntp.org iburst dynamic/g" /etc/ntp.conf 
/etc/init.d/ntp restart

# Installation du service WEB
apt-get -y install apache2 libapache2-mod-fcgid

# Parametrage d'apache
sed -i 's/ServerTokens OS/ServerTokens Prod /g' /etc/apache2/conf-enabled/security.conf 

# Activation des modules Apache
a2enmod rewrite
a2enmod actions
a2enmod fcgid
a2enmod alias
a2enmod proxy_fcgi

# Redémarrage d'Apache
systemctl restart apache2

# Préparation du dépot PHP
wget -q https://packages.sury.org/php/apt.gpg -O- | apt-key add -
echo "deb https://packages.sury.org/php/ stretch main" | tee /etc/apt/sources.list.d/php.list

# Mis à jour du serveur
apt-get update && apt-get -y upgrade && apt-get -y dist-upgrade && apt-get -y full-upgrade

# Installation de PHP 5.6
apt-get -y install php5.6
apt-get -y install php5.6-fpm
apt-get -y install php5.6-cli php5.6-common php5.6-curl php5.6-mbstring php5.6-mysql php5.6-xml php5.6-dev

# Installation de PHP 7.0
apt-get -y install php7.0
apt-get -y install php7.0-fpm
apt-get -y install php7.0-cli php7.0-common php7.0-curl php7.0-mbstring php7.0-mysql php7.0-xml php7.0-dev

# Installation de PHP 7.1
apt-get -y install php7.1
apt-get -y install php7.1-fpm
apt-get -y install php7.1-cli php7.1-common php7.1-curl php7.1-mbstring php7.1-mysql php7.1-xml php7.1-dev

# Installation de PHP 7.2
apt-get -y install php7.2
apt-get -y install php7.2-fpm
apt-get -y install php7.2-cli php7.2-common php7.2-curl php7.2-mbstring php7.2-mysql php7.2-xml php7.2-dev

# Installation de PHP 7.3
apt-get -y install php7.3
apt-get -y install php7.3-fpm
apt-get -y install php7.3-cli php7.3-common php7.3-curl php7.3-mbstring php7.3-mysql php7.3-xml php7.3-dev

# Installation de PHP 7.4
apt-get -y install php7.4
apt-get -y install php7.4-fpm
apt-get -y install php7.4-cli php7.4-common php7.4-curl php7.4-mbstring php7.4-mysql php7.4-xml php7.4-dev

# Activation de php7.3
a2dismod php5.6 php7.3 php7.1 php7.2 php7.4
a2enmod php7.0
service apache2 restart
update-alternatives --set php /usr/bin/php7.0
update-alternatives --set phar /usr/bin/phar7.0
update-alternatives --set phar.phar /usr/bin/phar.phar7.0
update-alternatives --set phpize /usr/bin/phpize7.0
update-alternatives --set php-config /usr/bin/php-config7.0

# Installation de MariaDB
apt-get -y install mariadb-server

# Securisation de la base de données
mysql -e "UPDATE mysql.user SET Password=PASSWORD('$mysqlroot') WHERE User='root';"
mysql -e "DELETE FROM mysql.user WHERE User='';"
mysql -e "DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');"
mysql -e "DROP DATABASE IF EXISTS test;"
mysql -e "DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%'"
mysql -e "FLUSH PRIVILEGES;"

# Installation de PHPmyAdmin
echo "phpmyadmin phpmyadmin/dbconfig-install boolean true" | debconf-set-selections				# Configuration de phpmyadmin avec dbconfig
echo "phpmyadmin phpmyadmin/mysql/admin-pass password passwordroot" | debconf-set-selections			# Mot de passe root de la base de donnees
echo "phpmyadmin phpmyadmin/mysql/app-pass password phpmyadmindbpass" | debconf-set-selections			# Mot de passe de l'utilisateur mysql phpmyadmin
echo "phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2" | debconf-set-selections			# Configuration de phpmyadmin pour apache2
apt-get -y install phpmyadmin

# Configuration de phpmyadmin
sed -i "s\Alias /phpmyadmin /usr/share/phpmyadmin\#Alias /phpmyadmin /usr/share/phpmyadmin\g" /etc/apache2/conf-available/phpmyadmin.conf
sed -i "2cAlias /phpmyadmin /usr/share/phpmyadmin" /etc/apache2/sites-available/000-default.conf

# Redemarrage des services
service apache2 restart

# Activation des modules php
phpenmod -v 5.6 mbstring
phpenmod -v 7.0 mbstring
phpenmod -v 7.1 mbstring
phpenmod -v 7.2 mbstring
phpenmod -v 7.3 mbstring
phpenmod -v 7.4 mbstring

# Redémarrage des services
systemctl restart apache2
systemctl restart mariadb

# Installation d'un compte admin phpmyadmin (Uniquement pour les tests)
#mysql -e "CREATE USER 'phproot'@'localhost' IDENTIFIED BY 'phproot';"
#mysql -e "GRANT ALL PRIVILEGES ON *.* TO 'phproot'@'localhost' WITH GRANT OPTION;"
#mysql -e "FLUSH PRIVILEGES;"

# Configuration des chroot SFTP
sed -i "116c#Subsystem       sftp    /usr/lib/openssh/sftp-server" /etc/ssh/sshd_config
sed -i "117iSubsystem sftp internal-sftp" /etc/ssh/sshd_config
echo "" >> /etc/ssh/sshd_config
echo "# Configuration du chroot" >> /etc/ssh/sshd_config
echo "Match Group www-data" >> /etc/ssh/sshd_config
echo "X11Forwarding no" >> /etc/ssh/sshd_config
echo "AllowTcpForwarding no" >> /etc/ssh/sshd_config
echo "ChrootDirectory /home/%u" >> /etc/ssh/sshd_config
echo "ForceCommand internal-sftp" >> /etc/ssh/sshd_config

# Redémarrage des services
service ssh restart
service sshd restart

# Configuration du dossier /home
chown root:root /home

# Installation du service DNS
apt-get -y install bind9 dnsutils

# Configuration du service DNS
mv /etc/bind/named.conf.options /etc/bind/named.conf.options.save
echo 'options {' > /etc/bind/named.conf.options
echo '        directory "/var/cache/bind";' >> /etc/bind/named.conf.options
echo '' >> /etc/bind/named.conf.options
echo '        dnssec-validation auto;' >> /etc/bind/named.conf.options
echo '' >> /etc/bind/named.conf.options
echo '        auth-nxdomain no;    # conform to RFC1035' >> /etc/bind/named.conf.options
echo '        listen-on-v6 { false; };' >> /etc/bind/named.conf.options
echo '};' >> /etc/bind/named.conf.options

# Creation du dossier de zone
mkdir /etc/bind/zones
chmod o+r /etc/bind/zones

# Redemarrage des services
systemctl restart bind9

# Parametrage du resolver dns
rm /etc/resolv.conf
echo 'nameserver 127.0.0.1' > /etc/resolv.conf
chattr +i /etc/resolv.conf

# Mise en place du fichier sudoers
mv /etc/sudoers /etc/sudoers.bak
mv sudoers /etc/sudoers
chown root:root /etc/sudoers

# Mise en place du core
mv core /core
chmod -R +x /core

# Mise en place du panel
mv app /app
chown -R  debian:www-data /app
mkdir /applogs

# Creation de la base de donnees du panel
mysql -e 'CREATE DATABASE `'webhostingfe'`;'
mysql -e 'GRANT ALL PRIVILEGES ON `'webhostingfe'`.* TO `'webhostingfe'`@`localhost` IDENTIFIED BY "'$webhostingfepassword'";'
mysql -e "flush privileges"

# Population de la base de donnees
mysql -u webhostingfe -p$webhostingfepassword webhostingfe < webhostingfe.sql
rm webhostingfe.sql

# Mise en place du site host
mv /var/www/html/index.html /var/www/html/index.html.bak
mv www/* /var/www/html/ 
rm -r www

# Mise en place du mot de passe de la base de donnees
sed -i "s/webhostingfepass/$webhostingfepassword/g" /app/config/db.connect.php

# Suppression des vhost
rm /etc/apache2/sites-enabled/*
rm /etc/apache2/sites-available/*

# Creation du VHOST
echo "Listen 8000" > /etc/apache2/sites-available/000-default.conf
echo "" >> /etc/apache2/sites-available/000-default.conf
echo "<VirtualHost *:80>" >> /etc/apache2/sites-available/000-default.conf
echo "" >> /etc/apache2/sites-available/000-default.conf
echo "        DocumentRoot /var/www/html" >> /etc/apache2/sites-available/000-default.conf
echo "" >> /etc/apache2/sites-available/000-default.conf
echo "        ErrorLog /var/www/error.log" >> /etc/apache2/sites-available/000-default.conf
echo "        CustomLog /var/www/access.log combined" >> /etc/apache2/sites-available/000-default.conf
echo "" >> /etc/apache2/sites-available/000-default.conf
echo "</VirtualHost>" >> /etc/apache2/sites-available/000-default.conf
echo "" >> /etc/apache2/sites-available/000-default.conf
echo "<VirtualHost *:8000>" >> /etc/apache2/sites-available/000-default.conf
echo "	" >> /etc/apache2/sites-available/000-default.conf
echo "    Alias /phpmyadmin /usr/share/phpmyadmin" >> /etc/apache2/sites-available/000-default.conf
echo "    DocumentRoot /app" >> /etc/apache2/sites-available/000-default.conf
echo "" >> /etc/apache2/sites-available/000-default.conf
echo "    LogLevel warn" >> /etc/apache2/sites-available/000-default.conf
echo "    ErrorLog /applogs/error.log" >> /etc/apache2/sites-available/000-default.conf
echo "    CustomLog /applogs/access.log combined" >> /etc/apache2/sites-available/000-default.conf
echo "" >> /etc/apache2/sites-available/000-default.conf
echo "    <Directory /app>" >> /etc/apache2/sites-available/000-default.conf
echo "        Options -Indexes +FollowSymLinks +MultiViews" >> /etc/apache2/sites-available/000-default.conf
echo "        AllowOverride All" >> /etc/apache2/sites-available/000-default.conf
echo "        Require all granted" >> /etc/apache2/sites-available/000-default.conf
echo "    </Directory>" >> /etc/apache2/sites-available/000-default.conf
echo "" >> /etc/apache2/sites-available/000-default.conf
echo "</VirtualHost>" >> /etc/apache2/sites-available/000-default.conf

# Activation des Vhost
a2ensite 000-default

# Redemarrage des services
service apache2 restart

# Suppression des fichiers
rm setup.sh

# Sources : 
# http://guillaume-cortes.fr/serveur-web-apache-debian-9/
# https://tecadmin.net/install-multiple-php-version-with-apache-on-debian/
# https://tecadmin.net/install-php-debian-9-stretch/
# https://tecadmin.net/switch-between-multiple-php-version-on-debian/
# https://gist.github.com/Mins/4602864
# https://www.digitalocean.com/community/tutorials/how-to-install-and-secure-phpmyadmin-on-debian-9
# https://stackoverflow.com/questions/30741573/debconf-selections-for-phpmyadmin-unattended-installation-with-no-webserver-inst

# Infos :
# Nom de base de donnees interdites { 'information_schema', 'mysql', 'performance_schema', 'phpmyadmin' }
# Vérifier les ports ouverts : netstat -paunt

