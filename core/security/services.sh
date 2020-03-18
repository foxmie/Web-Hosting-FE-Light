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

echo ''
sleep 5

# Recuperation des arguments
services=$1

# Protection des arguments
if [ "$#" -ne 1 ];then
        echo -e "\e[101m La commande est incomplète ! \e[49m \n"
else

	# Service WEB
	if [ $services == 1 ];then

	# Redemarrage des services
	service apache2 start

	# Message de fin
        echo -e "\n \e[32m Opération terminé avec succès ! \e[39m \n"

	# Service MySQL
	elif [ $services == 2 ];then

	# Redemarrage des services
	service mariadb restart

        # Message de fin
        echo -e "\n \e[32m Opération terminé avec succès ! \e[39m \n"

	# Service DNS
	elif [ $services == 3 ];then

	# Redemarrage des services
	service bind9 restart

        # Message de fin
        echo -e "\n \e[32m Opération terminé avec succès ! \e[39m \n"

	# Service de monitoring
	elif [ $services == 4 ];then

	# Redemarrage des services
	service nagios-nrpe-server restart
        # Message de fin
        echo -e "\n \e[32m Opération terminé avec succès ! \e[39m \n"

	# Service FTP
	elif [ $services == 5 ];then

	# Redemarrage des services
	service proftpd restart

        # Message de fin
        echo -e "\n \e[32m Opération terminé avec succès ! \e[39m \n"

	# Service SFTP
	elif [ $services == 6 ];then

	# Redemarrage des services
	service ssh restart && service sshd restart

        # Message de fin
        echo -e "\n \e[32m Opération terminé avec succès ! \e[39m \n"

	# Service Antivirus
	elif [ $services == 7 ];then

	# Redemarrage des services
	service clamav-freshclam restart

        # Message de fin
        echo -e "\n \e[32m Opération terminé avec succès ! \e[39m \n"

	# Service Pare-feu
	elif [ $services == 8 ];then

	# Redemarrage des services
	service netfilter-persistent restart

        # Message de fin
        echo -e "\n \e[32m Opération terminé avec succès ! \e[39m \n"

	# Erreur
	else
		 echo -e "\e[101m Le service n'est pas redemarrable ! \e[49m \n"
	fi
fi

