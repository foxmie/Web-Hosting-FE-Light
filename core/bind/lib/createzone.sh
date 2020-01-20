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
domaine=$1
ip=$2
serial=`date '+%d%m%y'`
serial=$serial'01'

# Protection des arguments
if [ "$#" -ne 2 ];then
        echo -e "\e[101m La commande est incomplète ! \e[49m \n"
else

	# Creation du fichier de configuration du domaine
	echo 'zone "'$domaine'" IN {' >> /etc/bind/zones/$domaine.conf
	echo '        type master;' >> /etc/bind/zones/$domaine.conf
	echo '        allow-transfer { "none";};' >> /etc/bind/zones/$domaine.conf
	echo '        file "/etc/bind/zones/db.'$domaine'";' >> /etc/bind/zones/$domaine.conf
	echo '        notify yes;' >> /etc/bind/zones/$domaine.conf
	echo '};' >> /etc/bind/zones/$domaine.conf

	# Creation du fichier de donnees du domaine
	echo '$TTL 3600' >> /etc/bind/zones/db.$domaine
	echo "@ IN SOA ns.$domaine. tech.$domaine. (" >> /etc/bind/zones/db.$domaine
	echo " $serial  ; Serial" >> /etc/bind/zones/db.$domaine
	echo ' 86400       ; Refresh' >> /etc/bind/zones/db.$domaine
	echo ' 3600        ; Retry' >> /etc/bind/zones/db.$domaine
	echo ' 3600000     ; Expire' >> /etc/bind/zones/db.$domaine
	echo ' 300 )       ; Minimum' >> /etc/bind/zones/db.$domaine
	echo '' >> /etc/bind/zones/db.$domaine
	echo '; Serveurs DNS' >> /etc/bind/zones/db.$domaine
	echo " IN NS ns.$domaine." >> /etc/bind/zones/db.$domaine
	echo '' >> /etc/bind/zones/db.$domaine
	echo '; Enregistrements' >> /etc/bind/zones/db.$domaine
	echo " IN A $ip" >> /etc/bind/zones/db.$domaine
	echo "ns IN A $ip" >> /etc/bind/zones/db.$domaine

	# Ajout du domaine au fichier de configuration bind
	echo 'include "/etc/bind/zones/'$domaine'.conf";' >> /etc/bind/named.conf

	# Redemarrage des services
	service bind9 reload

	# Message de fin
        echo -e "\n \e[32m Opération terminé avec succès ! \e[39m \n"

fi
