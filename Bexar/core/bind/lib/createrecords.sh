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
records=$2

# Protection des arguments
if [ "$#" -ne 2 ];then
        echo -e "\e[101m La commande est incomplète ! \e[49m \n"
else

	# Ajout de l'enregistrement
	echo "$records" >> /etc/bind/zones/db.$domaine

	# Recuperation du serial
	serial=`head -n 3 /etc/bind/zones/db.$domaine | tail -n 1`
	serial=${serial:1:8}
	old=${serial:0:8}

	# Recuperation de la date
	date=`date '+%d%m%y'`

	# Creation du nouveau serial
	if [ $date = ${serial:0:6} ];then

		# Recuperation de l'identifiant
		inc=${serial:6:7}

		if [ $inc -gt 99 ];then
			inc='01'
		else
			inc=$((10#$inc+1))

			if [ $inc -le 9 ];then
				inc="0$inc"
			fi

		fi

		serial="$date$inc"

	else

		serial=$date'01'

	fi

	# Modification du serial
	sed -i -e 's/'$old'/'$serial'/g' /etc/bind/zones/db.$domaine

	# Redemarrage des services
	service bind9 restart

	# Message de fin
        echo -e "\n \e[32m Opération terminé avec succès ! \e[39m \n"

fi
