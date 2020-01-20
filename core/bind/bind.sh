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
echo -e "\e[44m BIND \e[49m \n"

# Recuperation des arguments
action=$1

# Recuperation de l'arborescence
emp=$PWD'/bind'

# Vérification de l'argument
if [ "$#" -lt 1 ]; then
	cat "$emp/lib/man.txt"
else

	# Creation de la zone
	if [ $action = "createzone" ];then
		echo -e "\e[42m Creation d'un objet \e[49m \n"

		bash "$emp/lib/createzone.sh" "$2" "$3"

	# Suppression de la zone
	elif [ $action = "deletezone" ];then
		echo -e "\e[42m Suppression d'un objet \e[49m \n"

		bash "$emp/lib/deletezone.sh" "$2"

	# Creation de l'enregistrement
	elif [ $action = "createrecords" ];then
		echo -e "\e[42m Creation d'un objet \e[49m \n"

		bash "$emp/lib/createrecords.sh" "$2" "$3"

	# Suppression de l'enregistrement
        elif [ $action = "deleterecords" ];then
                echo -e "\e[42m Suppression d'un objet \e[49m \n"

                bash "$emp/lib/deleterecords.sh" "$2" "$3"

	# Commande inconnue
	else
		echo -e "\e[101m Commande inconnue \e[49m \n"
	fi

fi
