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

# Formatgage du terminal
clear
echo -e "\e[44m WEB Hosting FE Light \e[49m \n"

# Recuperation des arguments
lib=$1

# Vérification de l'argument
if [ "$#" -lt 1 ];then

	echo -e "\e[104m Sftp \e[49m"
        cat "$PWD/sftp/lib/man.txt"
	echo -e "\e[104m MySQL \e[49m"
	cat "$PWD/mysql/lib/man.txt"
	echo -e "\e[104m Bind \e[49m"
	cat "$PWD/bind/lib/man.txt"
	echo -e "\e[104m CertBot \e[49m"
	cat "$PWD/certbot/lib/man.txt"

else

        # SFTP
        if [ $lib = "sftp" ];then
                echo -e "\e[42m Creation d'un objet \e[49m \n"
		echo $PWD
                bash "$PWD/sftp/sftp.sh" "$2" "$3" "$4" "$5" "$6"

        # MYSQL
        elif [ $lib = "mysql" ];then
                echo -e "\e[42m Creation d'un objet \e[49m \n"

                bash "$PWD/mysql/mysql.sh" "$2" "$3" "$4"

	# BIND
        elif [ $lib = "bind" ];then
                echo -e "\e[42m Creation d'un objet \e[49m \n"

                bash "$PWD/bind/bind.sh" "$2" "$3" "$4"

	# CERTBOT
        elif [ $lib = "certbot" ];then
                echo -e "\e[42m Creation d'un objet \e[49m \n"

                bash "$PWD/certbot/certbot.sh" "$2" "$3"

        # Commande inconnue
        else
                echo -e "\e[101m Commande inconnue \e[49m \n"
        fi

fi

