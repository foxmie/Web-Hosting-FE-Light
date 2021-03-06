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
home="/home/$fqdn/www"

# Protection des arguments
if [ "$#" -ne 2 ];then
        echo -e "\e[101m La commande est incomplète ! \e[49m \n"
else

	# Installation du certificat
	service apache2 reload
	certbot run -a webroot -i apache -w $home -d $fqdn --register-unsafely-without-email --redirect --agree-tos --force-renewal

	# Redemarrage des services
	service apache2 reload

	# Message de fin
	echo -e "\n \e[32m Opération terminé avec succès ! \e[39m \n"

fi
