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

# Recuperation des arguments
fqdn=$1
passwd=$2
loglevel=$3
phpvers=$4
ipv4=`hostname --all-ip-addresses`

# Protection des arguments
if [ "$#" -ne 4 ];then
	echo -e "\e[101m La commande est incomplète ! \e[49m \n"
else
	# Utilisateurs interdit
	if [ $fqdn = "debian" ]
	then
		echo -e "\e[31m Ce nom est interdit ! \e[39m"
	else

		# Creation de l'utilisateur
		useradd $fqdn --home /home/$fqdn/ --create-home --groups www-data --gid www-data

		# Activation du compte
                echo -e "$passwd\n$passwd" | (passwd $fqdn)

		# Creation des repertoires
		mkdir -p /home/$fqdn/{www,logs,conf}

		# Reglage des permisions
		chown root:www-data /home/$fqdn
		chown -R $fqdn:www-data /home/$fqdn/www
		chown -R $fqdn:www-data /home/$fqdn/logs
		chown -R $fqdn:www-data /home/$fqdn/conf
		chmod -R 755 /home/$fqdn/www
		chmod -R 750 /home/$fqdn/logs
		chmod -R 750 /home/$fqdn/conf

		# Creation du Vhost
		echo "<VirtualHost $ipv4:80>" > /home/$fqdn/conf/apache.conf
		echo "    ServerName $fqdn" >> /home/$fqdn/conf/apache.conf
		echo "    DocumentRoot /home/$fqdn/www" >> /home/$fqdn/conf/apache.conf
		echo "    <Directory /home/$fqdn/www>" >> /home/$fqdn/conf/apache.conf
		echo "        Options -Indexes +FollowSymLinks +MultiViews" >> /home/$fqdn/conf/apache.conf
		echo "        AllowOverride All" >> /home/$fqdn/conf/apache.conf
		echo "        Require all granted" >> /home/$fqdn/conf/apache.conf
		echo "    </Directory>" >> /home/$fqdn/conf/apache.conf
		echo "    ErrorLog /home/$fqdn/logs/error.log" >> /home/$fqdn/conf/apache.conf
		echo "    LogLevel $loglevel" >> /home/$fqdn/conf/apache.conf
		echo "    CustomLog /home/$fqdn/logs/access.log combined" >> /home/$fqdn/conf/apache.conf
		echo '    <FilesMatch \.php$>' >> /home/$fqdn/conf/apache.conf
		echo '        SetHandler "proxy:unix:/var/run/php/php'$phpvers'-fpm.sock|fcgi://localhost/"' >> /home/$fqdn/conf/apache.conf
		echo "    </FilesMatch>" >> /home/$fqdn/conf/apache.conf
		echo "</VirtualHost>" >> /home/$fqdn/conf/apache.conf

		# Activation du Vhost
		chown $fqdn:www-data /home/$fqdn/conf/apache.conf
		chmod 664 /home/$fqdn/conf/apache.conf
		ln -s /home/$fqdn/conf/apache.conf /etc/apache2/sites-available/$fqdn.conf
		a2ensite $fqdn

		# Redemarrage des services
                #systemctl reload apache2
		service apache2 restart

		# Parametrage des droits sur les logs
		chown $fqdn:www-data /home/$fqdn/logs/error.log
		chown $fqdn:www-data /home/$fqdn/logs/access.log
		chmod 744 /home/$fqdn/logs/error.log
		chmod 744 /home/$fqdn/logs/access.log

		# Activation du compte
		#echo -e "$passwd\n$passwd" | (passwd $fqdn)

		# Message de fin
		echo -e "\n \e[32m Opération terminé avec succès ! \e[39m \n"

	fi

fi
