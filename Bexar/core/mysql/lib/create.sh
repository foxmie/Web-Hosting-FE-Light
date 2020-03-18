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
database=$1
passwd=$2

# Protection des arguments
if [ "$#" -ne 2 ];then
        echo -e "\e[101m La commande est incomplète ! \e[49m \n"
else
        # Utilisateurs interdit
        if [ $database = "information_schema" ] || [ $database = "mysql" ] || [ $database = "performance_schema" ] || [ $database = "phpmyadmin" ] || [ $database = "webhostingfe" ];then
                echo -e "\e[31m Ce nom d'utilisateur et de base de donnees est interdit ! \e[39m"
	else

		# Creation de la base de donnees
		mysql -e 'CREATE DATABASE `'$database'`;'
		mysql -e 'GRANT ALL PRIVILEGES ON `'$database'`.* TO `'$database'`@`localhost` IDENTIFIED BY "'$passwd'";'
		mysql -e "flush privileges"

		# Message de fin
                echo -e "\n \e[32m Opération terminé avec succès ! \e[39m \n"

	fi

fi
