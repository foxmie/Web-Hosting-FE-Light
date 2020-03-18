<?php
	
	/*
	
	This file is part of Web Hosting FE Light.

    Web Hosting FE Light is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Web Hosting FE Light is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Web Hosting FE Light.  If not, see <https://www.gnu.org/licenses/>.

	*/
	
	// Démarrage des sessions
	session_start();
	
	// EMP CORE
	$core = '/core';
	
	// Récupération de la configuration
	require_once('../config/class.php');
	require_once('../config/db.connect.php');
	
	// Initialisation de l'objet
	$database = new db();
	
	// Protection
	if (!empty($_GET['service'])){
		
		// Protection de la connectivité
		if (!empty($_SESSION['webhostingfe'])){
			
			// Service WEB
			if ( htmlspecialchars($_GET['service']) == '1' ){
				
				// Redémarrage du service
				$output = shell_exec("sudo $core/security/services.sh '1' > /dev/null&");
				print($output);
							
				// Définition de l'alerte
				$_SESSION['tmp']['dashboard']['type'] = 'success';
				$_SESSION['tmp']['dashboard']['msgbox'] = 'Le service "WEB" redémarre ! ';
				
				// Redirection
				header('Location:../?service=dashboard');
							
			// Service MySQL
			}else if ( htmlspecialchars($_GET['service']) == '2' ){
				
				// Redémarrage du service
				$output = shell_exec("sudo $core/security/services.sh '2' > /dev/null&");
				print($output);
							
				// Définition de l'alerte
				$_SESSION['tmp']['dashboard']['type'] = 'success';
				$_SESSION['tmp']['dashboard']['msgbox'] = 'Le service "MySQL" redémarre ! ';
				
				// Redirection
				header('Location:../?service=dashboard');
				
			// Service DNS
			}else if ( htmlspecialchars($_GET['service']) == '3' ){
				
				// Redémarrage du service
				$output = shell_exec("sudo $core/security/services.sh '3' > /dev/null&");
				print($output);
							
				// Définition de l'alerte
				$_SESSION['tmp']['dashboard']['type'] = 'success';
				$_SESSION['tmp']['dashboard']['msgbox'] = 'Le service "DNS" redémarre ! ';
				
				// Redirection
				header('Location:../?service=dashboard');
				
			// Service de Monitoring
			}else if ( htmlspecialchars($_GET['service']) == '4' ){
				
				// Redémarrage du service
				$output = shell_exec("sudo $core/security/services.sh '4' > /dev/null&");
				print($output);
							
				// Définition de l'alerte
				$_SESSION['tmp']['dashboard']['type'] = 'success';
				$_SESSION['tmp']['dashboard']['msgbox'] = 'Le service "de Monitoring" redémarre ! ';
				
				// Redirection
				header('Location:../?service=dashboard');
				
			// Service FTP
			}else if ( htmlspecialchars($_GET['service']) == '5' ){
				
				// Redémarrage du service
				$output = shell_exec("sudo $core/security/services.sh '5' > /dev/null&");
				print($output);
							
				// Définition de l'alerte
				$_SESSION['tmp']['dashboard']['type'] = 'success';
				$_SESSION['tmp']['dashboard']['msgbox'] = 'Le service "FTP" redémarre ! ';
				
				// Redirection
				header('Location:../?service=dashboard');
				
			// Service SFTP
			}else if ( htmlspecialchars($_GET['service']) == '6' ){
				
				// Redémarrage du service
				$output = shell_exec("sudo $core/security/services.sh '6' > /dev/null&");
				print($output);
							
				// Définition de l'alerte
				$_SESSION['tmp']['dashboard']['type'] = 'success';
				$_SESSION['tmp']['dashboard']['msgbox'] = 'Le service "SFTP" redémarre ! ';
				
				// Redirection
				header('Location:../?service=dashboard');
				
			// Service Antivirus
			}else if ( htmlspecialchars($_GET['service']) == '7' ){
				
				// Redémarrage du service
				$output = shell_exec("sudo $core/security/services.sh '7' > /dev/null&");
				print($output);
							
				// Définition de l'alerte
				$_SESSION['tmp']['dashboard']['type'] = 'success';
				$_SESSION['tmp']['dashboard']['msgbox'] = 'Le service "Antivirus" redémarre ! ';
				
				// Redirection
				header('Location:../?service=dashboard');
				
			// Service Pare-feu
			}else if ( htmlspecialchars($_GET['service']) == '8' ){
				
				// Redémarrage du service
				$output = shell_exec("sudo $core/security/services.sh '8' > /dev/null&");
				print($output);
							
				// Définition de l'alerte
				$_SESSION['tmp']['dashboard']['type'] = 'success';
				$_SESSION['tmp']['dashboard']['msgbox'] = 'Le service "Pare-feu" redémarre ! ';
				
				// Redirection
				header('Location:../?service=dashboard');
				
			// Erreur interne
			}else{
				
				// Définition de l'alerte
				$_SESSION['tmp']['dashboard']['type'] = 'danger';
				$_SESSION['tmp']['dashboard']['msgbox'] = 'Erreur irécupérable !';
				
				// Redirection
				header('Location:../?service=dashboard');
				
			}
			
		}else{
			
			// Définition de l'alerte
			$_SESSION['tmp']['login']['type'] = 'danger';
			$_SESSION['tmp']['login']['msgbox'] = 'Echec lors de la récupération de l\'identifiant utilisateur';
			
			// Redirection
			header('Location:logout.php');
			
		}
		
	}else{
		
		// Définition de l'alerte
		$_SESSION['tmp']['dashboard']['type'] = 'danger';
		$_SESSION['tmp']['dashboard']['msgbox'] = 'Erreur irécupérable !';
		
		// Redirection
		header('Location:../?service=dashboard');
		
	}
?>