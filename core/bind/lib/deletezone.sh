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

# Protection des arguments
if [ "$#" -ne 1 ];then
        echo -e "\e[101m La commande est incomplète ! \e[49m \n"
else

	# Suppression du domaine a la configuration de bind
	sed -i '/include "\/etc\/bind\/zones\/'$domaine'.conf";/ d' /etc/bind/named.conf

	# Suppression du fichier de configuration du domaine
	rm /etc/bind/zones/$domaine.conf

	# Suppression du fichier de donnees du domaine
	rm /etc/bind/zones/db.$domaine

	# Redemarrage des services
	service bind9 restart

	# Message de fin
        echo -e "\n \e[32m Opération terminé avec succès ! \e[39m \n"

fi
