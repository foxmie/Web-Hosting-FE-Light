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
apt-get -y install sendmail
apt-get -y install mailutils

# Definition des mots de passe
mysqlroot=`date +%s | sha256sum | base64 | head -c 25`
webhostingfepassword=`date +%s | sha256sum | base64 | head -c 25`

# Recuperation de l'adresse IPv4
ipv4=`hostname --all-ip-addresses | sed -e "s/\ //g"`

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

# Redemarrage de syslog
apt install -y --reinstall tzdata
/etc/init.d/rsyslog restart

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
a2dismod php5.6 php7.0 php7.1 php7.2 php7.3
a2enmod php7.4
service apache2 restart
update-alternatives --set php /usr/bin/php7.4
update-alternatives --set phar /usr/bin/phar7.4
update-alternatives --set phar.phar /usr/bin/phar.phar7.4
update-alternatives --set phpize /usr/bin/phpize7.4
update-alternatives --set php-config /usr/bin/php-config7.4

# Installation des extensions de php requis par prestashop, joomla, drupal et wordpress
apt-get -y install php5.6-gd php7.0-gd php7.1-gd php7.2-gd php7.3-gd php7.4-gd
apt-get -y install php5.6-zip php7.0-zip php7.1-zip php7.2-zip php7.3-zip php7.4-zip
apt-get -y install php5.6-intl php7.0-intl php7.1-intl php7.2-intl php7.3-intl php7.4-intl
apt-get -y install php5.6-yaml php7.0-yaml php7.1-yaml php7.2-yaml php7.3-yaml php7.4-yaml

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

# Modification des upload PHP 5.6
sed -i -e "s/post_max_size = 8M/post_max_size = 8192M/g" /etc/php/5.6/apache2/php.ini
sed -i -e "s/upload_max_filesize = 2M/upload_max_filesize = 2048M/g" /etc/php/5.6/apache2/php.ini

# Modification des upload PHP 7.0
sed -i -e "s/post_max_size = 8M/post_max_size = 8192M/g" /etc/php/7.0/apache2/php.ini
sed -i -e "s/upload_max_filesize = 2M/upload_max_filesize = 2048M/g" /etc/php/7.0/apache2/php.ini

# Modification des upload PHP 7.1
sed -i -e "s/post_max_size = 8M/post_max_size = 8192M/g" /etc/php/7.1/apache2/php.ini
sed -i -e "s/upload_max_filesize = 2M/upload_max_filesize = 2048M/g" /etc/php/7.1/apache2/php.ini

# Modification des upload PHP 7.2
sed -i -e "s/post_max_size = 8M/post_max_size = 8192M/g" /etc/php/7.2/apache2/php.ini
sed -i -e "s/upload_max_filesize = 2M/upload_max_filesize = 2048M/g" /etc/php/7.2/apache2/php.ini

# Modification des upload PHP 7.3
sed -i -e "s/post_max_size = 8M/post_max_size = 8192M/g" /etc/php/7.3/apache2/php.ini
sed -i -e "s/upload_max_filesize = 2M/upload_max_filesize = 2048M/g" /etc/php/7.3/apache2/php.ini

# Modification des upload PHP 7.4
sed -i -e "s/post_max_size = 8M/post_max_size = 8192M/g" /etc/php/7.4/apache2/php.ini
sed -i -e "s/upload_max_filesize = 2M/upload_max_filesize = 2048M/g" /etc/php/7.4/apache2/php.ini

# Parametrage de l'envoie de mail
sed -i -e "s/;sendmail_path =/sendmail_path = \/usr\/sbin\/sendmail/g" /etc/php/5.6/apache2/php.ini
sed -i -e "s/;sendmail_path =/sendmail_path = \/usr\/sbin\/sendmail/g" /etc/php/7.0/apache2/php.ini
sed -i -e "s/;sendmail_path =/sendmail_path = \/usr\/sbin\/sendmail/g" /etc/php/7.1/apache2/php.ini
sed -i -e "s/;sendmail_path =/sendmail_path = \/usr\/sbin\/sendmail/g" /etc/php/7.2/apache2/php.ini
sed -i -e "s/;sendmail_path =/sendmail_path = \/usr\/sbin\/sendmail/g" /etc/php/7.3/apache2/php.ini
sed -i -e "s/;sendmail_path =/sendmail_path = \/usr\/sbin\/sendmail/g" /etc/php/7.4/apache2/php.ini

# Redemarrage d'apache2
/etc/init.d/apache2 restart

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

# Installation du service FTP
apt-get -y install proftpd-mod-mysql

# Configuration du service FTP
sed -i 's/"Debian"/"Foxmie MUTU"/g' /etc/proftpd/proftpd.conf
sed -i '37iDefaultRoot                   /home/%u' /etc/proftpd/proftpd.conf
sed -i 's/# RequireValidShell/RequireValidShell/g' /etc/proftpd/proftpd.conf

# Redemarrage du service FTP
service proftpd restart

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
echo "<VirtualHost $ipv4:80>" >> /etc/apache2/sites-available/000-default.conf
echo "" >> /etc/apache2/sites-available/000-default.conf
echo "        DocumentRoot /var/www/html" >> /etc/apache2/sites-available/000-default.conf
echo "" >> /etc/apache2/sites-available/000-default.conf
echo "        ErrorLog /var/www/error.log" >> /etc/apache2/sites-available/000-default.conf
echo "        CustomLog /var/www/access.log combined" >> /etc/apache2/sites-available/000-default.conf
echo "" >> /etc/apache2/sites-available/000-default.conf
echo "</VirtualHost>" >> /etc/apache2/sites-available/000-default.conf
echo "" >> /etc/apache2/sites-available/000-default.conf
echo "<VirtualHost $ipv4:8000>" >> /etc/apache2/sites-available/000-default.conf
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

# Installation du plugin nagios
apt-get -y install nagios-nrpe-server
apt-get -y install nagios-plugins

# Ajout du serveur de monitoring
# sed -i -e "s/allowed_hosts=127.0.0.1/allowed_hosts=$ipduserveurdemonitoring/g" /etc/nagios/nrpe.cfg

# Automatisation du démarrage de nagios
update-rc.d nagios-nrpe-server defaults
service nagios-nrpe-server restart

# Definition des commandes
echo '# Commandes Foxmie' >> /etc/nagios/nrpe.cfg
echo 'command[check_disk_/]=/usr/lib/nagios/plugins/check_disk -w 20 -c 10 -p /' >> /etc/nagios/nrpe.cfg
echo 'command[check_load]=/usr/lib/nagios/plugins/check_load -w 50 -c 80' >> /etc/nagios/nrpe.cfg
echo 'command[check_http]=/usr/lib/nagios/plugins/check_tcp -H 127.0.0.1 -p 80 -w 5 -c 8' >> /etc/nagios/nrpe.cfg
echo 'command[check_https]=/usr/lib/nagios/plugins/check_tcp -H 127.0.0.1 -p 443 -w 5 -c 8' >> /etc/nagios/nrpe.cfg
echo 'command[check_panel]=/usr/lib/nagios/plugins/check_tcp -H 127.0.0.1 -p 8000 -w 5 -c 8' >> /etc/nagios/nrpe.cfg
echo 'command[check_mysql]=/usr/lib/nagios/plugins/check_tcp -H 127.0.0.1 -p 3306 -w 5 -c 8' >> /etc/nagios/nrpe.cfg
echo 'command[check_ssh]=/usr/lib/nagios/plugins/check_ssh -p 22 127.0.0.1' >> /etc/nagios/nrpe.cfg
echo 'command[check_smtp]=/usr/lib/nagios/plugins/check_tcp -H 127.0.0.1 -p 25 -w 5 -c 8' >> /etc/nagios/nrpe.cfg
echo 'command[check_dns]=/usr/lib/nagios/plugins/check_tcp -H 127.0.0.1 -p 53 -w 5 -c 8' >> /etc/nagios/nrpe.cfg

# Redémarrage du service nagios
service nagios-nrpe-server restart

# Installation de l'antivirus
apt-get -y install clamav clamav-freshclam clamav-daemon

# Mise a jour de la base anti-virus
service clamav-freshclam stop && freshclam && service clamav-freshclam start

# Analyse
cat <<EOF > /core/security/clamav.sh
#!/bin/bash

emp='/home/'
cd \$emp

for home in *
do
        if [ \$home == 'debian' ];then
                clamscan -i -r \$emp\$home > \$emp\$home/scanresults.txt
                chown \$home:www-data \$emp\$home/scanresults.txt
        elif [ -d \$home ];then
                clamscan -i -r \$emp\$home > \$emp\$home/logs/scanresults.txt
                chown \$home:www-data \$emp\$home/logs/scanresults.txt
        fi
done
EOF
chmod +x /core/security/clamav.sh

# Planification
echo "00 5,23 * * * root /core/security/clamav.sh" >> /etc/crontab
service cron reload

# Installation d'iptables-persistent
echo iptables-persistent iptables-persistent/autosave_v4 boolean true | sudo debconf-set-selections
echo iptables-persistent iptables-persistent/autosave_v6 boolean true | sudo debconf-set-selections
debconf-get-selections | grep iptables
apt-get -y install iptables-persistent

# Configuration des regles IPv6
if [ -f /etc/iptables/rules.v6 ]; then
   echo "*filter" > /etc/iptables/rules.v6
   echo ":INPUT DROP [0:0]" >> /etc/iptables/rules.v6
   echo ":FORWARD DROP [0:0]" >> /etc/iptables/rules.v6
   echo ":OUTPUT DROP [0:0]" >> /etc/iptables/rules.v6
   echo "COMMIT" >> /etc/iptables/rules.v6
else
   echo "Le kernel n'est pas compatible"
fi

# Configuration des règles IPv4
cat >/etc/iptables/rules.v4 <<EOF
# Generated by iptables-save v1.6.0 on Thu Aug 16 17:41:17 2018
*filter
:INPUT ACCEPT [1543:124410]
:FORWARD ACCEPT [0:0]
:OUTPUT ACCEPT [1451:141500]

# Allow internal traffic on the loopback device
-A INPUT -i lo -j ACCEPT

# Continue connections that are already established or related to an established connection
-A INPUT -m conntrack --ctstate RELATED,ESTABLISHED -j ACCEPT

# Drop non-conforming packets, such as malformed headers, etc.
-A INPUT -m conntrack --ctstate INVALID -j DROP

# SSH
-A INPUT -p tcp -m tcp --dport 22 -j ACCEPT

# DHCP used by OVH
-A INPUT -p udp --dport 67:68 --sport 67:68 -j ACCEPT

# DNS (bind)
-A OUTPUT -p tcp --dport 53 -j ACCEPT
-A OUTPUT -p udp --dport 53 -j ACCEPT

# HTTP + HTTPS
-A INPUT -p tcp -m multiport --dports 80,443 -j ACCEPT

# Email (postfix + devecot)
# 25 = smtp, 587 = submission and 993 = IMAPS
#-A INPUT -p tcp -m multiport --dports 25,587,993 -j ACCEPT

# SMTP
-A INPUT -p tcp --dport 25 -j ACCEPT

# NTP
-A INPUT -p udp --dport 123 -j ACCEPT

# Chain for preventing ping flooding - up to 6 pings per second from a single
# source, again with log limiting. Also prevents us from ICMP REPLY flooding
# some victim when replying to ICMP ECHO from a spoofed source.
-N ICMPFLOOD
-A ICMPFLOOD -m recent --name ICMP --set --rsource
-A ICMPFLOOD -m recent --name ICMP --update --seconds 1 --hitcount 6 --rsource --rttl -m limit --limit 1/sec --limit-burst 1 -j LOG --log-prefix "iptables[ICMP-flood]: "
-A ICMPFLOOD -m recent --name ICMP --update --seconds 1 --hitcount 6 --rsource --rttl -j DROP
-A ICMPFLOOD -j ACCEPT

# Permit useful IMCP packet types.
# Note: RFC 792 states that all hosts MUST respond to ICMP ECHO requests.
# Blocking these can make diagnosing of even simple faults much more tricky.
# Real security lies in locking down and hardening all services, not by hiding.
-A INPUT -p icmp --icmp-type 0  -m conntrack --ctstate NEW -j ACCEPT
-A INPUT -p icmp --icmp-type 3  -m conntrack --ctstate NEW -j ACCEPT
-A INPUT -p icmp --icmp-type 8  -m conntrack --ctstate NEW -j ICMPFLOOD
-A INPUT -p icmp --icmp-type 11 -m conntrack --ctstate NEW -j ACCEPT

# Drop all incoming malformed NULL packets
-A INPUT -p tcp --tcp-flags ALL NONE -j DROP

# Drop syn-flood attack packets
-A INPUT -p tcp ! --syn -m conntrack --ctstate NEW -j DROP

# Drop incoming malformed XMAS packets
-A INPUT -p tcp --tcp-flags ALL ALL -j DROP

# Nagios NRPE
-A INPUT -p tcp -m tcp --dport 5666 -j ACCEPT

COMMIT
# Completed on Thu Aug 16 17:41:17 2018
EOF

# Redemarrage iptables-persistent
service netfilter-persistent restart

# Suppression des fichiers
rm setup.sh
rm LICENSE
rm README.md
