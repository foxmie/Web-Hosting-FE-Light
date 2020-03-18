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
	require_once('../../config/class.php');
	require_once('../../config/db.connect.php');
	
	// Initialisation de l'objet
	$database = new db();
	
	// Switch
	if (!empty($_POST['action'])){
		
		$action = htmlspecialchars($_POST['action']);
		
		// Creation d'un compte
		if ( $action == "create" ){
			
			// Protection de la connectivité
			if (!empty($_SESSION['webhostingfe'])){
				
				// Protection des informations
				if ( !empty($_POST['name']) and !empty($_POST['password']) and !empty($_POST['repeatpassword']) ){
					
					// Recuperation des donnees
					$name = htmlspecialchars($_POST['name']);
					$password = htmlspecialchars($_POST['password']);
					$repeatpassword = htmlspecialchars($_POST['repeatpassword']);
					
					// Verification des mots de passe
					if ( $password == $repeatpassword ){
						
						// Verification de la disponibilite du compte
						$database->query("SELECT * FROM mysql WHERE databasename = :databasename");
						$database->bind(':databasename', $name);
						$row = $database->resultset();
						$countname = $database->rowCount();

						if ($countname > 0){
							
							// Définition de l'alerte
							$_SESSION['tmp']['mysql']['type'] = 'warning';
							$_SESSION['tmp']['mysql']['msgbox'] = 'Le compte mysql "'.$name.'" existe déjà !';
							
							// Redirection
							header('Location:../../?service=mysql');
							
						}else{
							
							// Verification du nom de la base de donnees
							$ban = array("information_schema", "mysql", "performance_schema", "phpmyadmin", "webhostingfe");
							if (in_array($name, $ban)){
								
								// Définition de l'alerte
								$_SESSION['tmp']['mysql']['type'] = 'danger';
								$_SESSION['tmp']['mysql']['msgbox'] = 'Le compte mysql "'.$name.'" est interdit !';
								
								// Redirection
								header('Location:../../?service=mysql');
								
							}else{
								
								// Creation du compte
								$database->query('INSERT INTO `mysql`(`id`, `databasename`) VALUES (:id, :databasename)');
								$database->bind(':id', '');
								$database->bind(':databasename', $name);
								$database->execute();
								
								// Envoie de la commande de création du compte
								$output = shell_exec("sudo $core/mysql/lib/create.sh '$name' '$password' > /dev/null&");
								print($output);
								
								// Définition de l'alerte
								$_SESSION['tmp']['mysql']['type'] = 'success';
								$_SESSION['tmp']['mysql']['msgbox'] = 'Le compte mysql "'.$name.'" a correctement été créé !';
								
								// Redirection
								header('Location:../../?service=mysql');
								
							}
							
						}
						
					}else{
						
						// Définition de l'alerte
						$_SESSION['tmp']['mysql']['type'] = 'warning';
						$_SESSION['tmp']['mysql']['msgbox'] = 'Les mots de passe ne concordent pas !';
						
						// Redirection
						header('Location:../../?service=mysql');
						
					}
					
				}else{
					
					// Définition de l'alerte
					$_SESSION['tmp']['mysql']['type'] = 'warning';
					$_SESSION['tmp']['mysql']['msgbox'] = 'Les champs "Nom de la base de données", "Mot de passe" et "Confirmation du mot de passe" est obligatoire !';
					
					// Redirection
					header('Location:../../?service=mysql');
					
				}
				
			}else{
				
				// Définition de l'alerte
				$_SESSION['tmp']['login']['type'] = 'danger';
				$_SESSION['tmp']['login']['msgbox'] = 'Echec lors de la récupération de l\'identifiant utilisateur';
				
				// Redirection
				header('Location:../logout.php');
				
			}
			
		// Suppression du compte
		}else if ( $action == "delete" ){
			
			// Protection de la connectivité
			if (!empty($_SESSION['webhostingfe'])){
				
				if (!empty($_POST['id']) and !empty($_POST['name'])){
				
					// Recuperation de l'id
					$id = htmlspecialchars($_POST['id']);
					
					// Verification de la disponibilite du compte
					$database->query("SELECT * FROM mysql WHERE id = :id");
					$database->bind(':id', $id);
					$row = $database->resultset();
					$countfqdn = $database->rowCount();

					if ($countfqdn > 0){
						
						// Suppression du domaine
						$database->query('DELETE FROM `mysql` WHERE id = :id');
						$database->bind(':id', $id);
						$database->execute();
						
						// Envoie de la commande de modification du compte
						$name = $_POST['name'];
						$output = shell_exec("sudo $core/mysql/lib/delete.sh '$name' > /dev/null&");
						print($output);
						
						// Définition de l'alerte
						$_SESSION['tmp']['mysql']['type'] = 'success';
						$_SESSION['tmp']['mysql']['msgbox'] = 'Le compte MySQL a correctement été supprimé !';
						
						// Redirection
						header('Location:../../?service=mysql');
						
					}else{
						
						// Définition de l'alerte
						$_SESSION['tmp']['mysql']['type'] = 'warning';
						$_SESSION['tmp']['mysql']['msgbox'] = 'Le compte mysql "'.$domaine.'" n\'existe pas !';
						
						// Redirection
						header('Location:../../?service=mysql');
						
					}
					
				}else{
					
					// Définition de l'alerte
					$_SESSION['tmp']['mysql']['type'] = 'danger';
					$_SESSION['tmp']['mysql']['msgbox'] = 'Erreur irécupérable !';
					
					// Redirection
					header('Location:../../?service=mysql');
					
				}
				
			}else{
				
				// Définition de l'alerte
				$_SESSION['tmp']['login']['type'] = 'danger';
				$_SESSION['tmp']['login']['msgbox'] = 'Echec lors de la récupération de l\'identifiant utilisateur';
				
				// Redirection
				header('Location:../logout.php');
				
			}
			
		// Modification du mot de passe
		}else if ( $action == "set" ){
			
			// Protection de la connectivité
			if (!empty($_SESSION['webhostingfe'])){
				
				// Protection des informations
				if ( !empty($_POST['id']) and !empty($_POST['name']) and !empty($_POST['password']) and !empty($_POST['repeatpassword']) ){
				
					// Recuperation de l'id
					$id = htmlspecialchars($_POST['id']);
					
					// Verification de la disponibilite du compte
					$database->query("SELECT * FROM mysql WHERE id = :id");
					$database->bind(':id', $id);
					$row = $database->resultset();
					$countfqdn = $database->rowCount();

					if ($countfqdn > 0){
						
						// Verification des mots de passe
						if ( htmlspecialchars($_POST['password']) == htmlspecialchars($_POST['repeatpassword']) ){
							
							// Envoie de la commande de modification du compte
							$name = $_POST['name'];
							$password = $_POST['password'];
							$output = shell_exec("sudo $core/mysql/lib/set.sh '$name' '$password' > /dev/null&");
							print($output);
							
							// Définition de l'alerte
							$_SESSION['tmp']['mysql']['type'] = 'success';
							$_SESSION['tmp']['mysql']['msgbox'] = 'Le mot de passe du compte a été mis à jour !';
							
							// Redirection
							header('Location:../../?service=mysql');
							
						}else{
							
							// Définition de l'alerte
							$_SESSION['tmp']['mysql']['type'] = 'warning';
							$_SESSION['tmp']['mysql']['msgbox'] = 'Les mots de passe ne concordent pas !';
							
							// Redirection
							header('Location:../../?service=mysql');
							
						}
						
					}else{
						
						// Définition de l'alerte
						$_SESSION['tmp']['mysql']['type'] = 'warning';
						$_SESSION['tmp']['mysql']['msgbox'] = 'Le compte mysql "'.$domaine.'" n\'existe pas !';
						
						// Redirection
						header('Location:../../?service=mysql');
						
					}
					
				}else{
					
					// Définition de l'alerte
					$_SESSION['tmp']['mysql']['type'] = 'danger';
					$_SESSION['tmp']['mysql']['msgbox'] = 'Erreur irécupérable !';
					
					// Redirection
					header('Location:../../?service=mysql');
					
				}
				
			}else{
				
				// Définition de l'alerte
				$_SESSION['tmp']['login']['type'] = 'danger';
				$_SESSION['tmp']['login']['msgbox'] = 'Echec lors de la récupération de l\'identifiant utilisateur';
				
				// Redirection
				header('Location:../logout.php');
				
			}
			
		// Erreur irécupérable
		}else{
			
			// Définition de l'alerte
			$_SESSION['tmp']['mysql']['type'] = 'danger';
			$_SESSION['tmp']['mysql']['msgbox'] = 'Erreur irécupérable !';
			
			// Redirection
			header('Location:../../?service=mysql');
			
		}
		
	}else{
		
		// Définition de l'alerte
		$_SESSION['tmp']['mysql']['type'] = 'danger';
		$_SESSION['tmp']['mysql']['msgbox'] = 'Erreur irécupérable !';
		
		// Redirection
		header('Location:../../?service=mysql');
		
	}
	
?>