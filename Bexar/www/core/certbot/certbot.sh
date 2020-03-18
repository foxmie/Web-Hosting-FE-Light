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

# Formatage du terminal
clear
echo -e "\e[44m APACHE \e[49m \n"

# Recuperation des arguments
action=$1

# Recuperation de l'arborescence
emp=$PWD'/certbot'

# Vérification de l'argument
if [ "$#" -lt 1 ]; then
	cat "$emp/lib/man.txt"
else

	# Creation de l'objet
	if [ $action = "create" ];then
		echo -e "\e[42m Creation d'un objet \e[49m \n"

		bash "$emp/lib/create.sh" "$2"

	# Suppression de l'objet
	elif [ $action = "delete" ];then
		echo -e "\e[42m Suppression d'un objet \e[49m \n"

		bash "$emp/lib/delete.sh" "$2"

	# Modification de l'objet
	elif [ $action = "renew" ];then
		echo -e "\e[42m Modification d'un objet \e[49m \n"

		bash "$emp/lib/renew.sh"

	# Commande inconnue
	else
		echo -e "\e[101m Commande inconnue \e[49m \n"
	fi

fi
