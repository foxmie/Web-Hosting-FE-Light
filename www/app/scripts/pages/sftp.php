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
				
				// Protection des infos
				if ( !empty($_POST['domaine']) and !empty($_POST['password']) and !empty($_POST['repeatpassword']) and !empty($_POST['loglevel']) and !empty($_POST['phpvers']) ){
					
					// Recuperation des informations
					$domaine = htmlspecialchars($_POST['domaine']);
					$password = htmlspecialchars($_POST['password']);
					$repeatpassword = htmlspecialchars($_POST['repeatpassword']);
					$loglevel = htmlspecialchars($_POST['loglevel']);
					$phpvers = htmlspecialchars($_POST['phpvers']);
					if (!empty($_POST['sousdomaine'])){ $domaine = htmlspecialchars($_POST['sousdomaine']).'.'.$domaine; }
					
					// Verification des mots de passe
					if ( $password == $repeatpassword ){
						
						// Verification de la disponibilite du compte
						$database->query("SELECT * FROM sftp WHERE fqdn = :fqdn");
						$database->bind(':fqdn', $domaine);
						$row = $database->resultset();
						$countfqdn = $database->rowCount();

						if ($countfqdn > 0){
							
							// Définition de l'alerte
							$_SESSION['tmp']['sftp']['type'] = 'warning';
							$_SESSION['tmp']['sftp']['msgbox'] = 'Le compte sftp "'.$domaine.'" existe déjà !';
							
							// Redirection
							header('Location:../../?service=sftp');
							
						}else{
							
							// Verification du nom d'utilisateur
							$ban = array("debian", "root");
							if (in_array($domaine, $ban)){
								
								// Définition de l'alerte
								$_SESSION['tmp']['sftp']['type'] = 'danger';
								$_SESSION['tmp']['sftp']['msgbox'] = 'Le compte sftp "'.$domaine.'" est interdit !';
								
								// Redirection
								header('Location:../../?service=sftp');
								
							}else{
								
								// Creation du records
								if (!empty($_POST['sousdomaine'])){
									
									$database->query('INSERT INTO `bindrecord`(`id`, `name`, `domaine`, `record`, `type`, `target`, `readonly`) VALUES (:id, :name, :domaine, :record, :type, :target, :readonly)');
									$database->bind(':id', '');
									$database->bind(':name', htmlspecialchars($_POST['sousdomaine']));
									$database->bind(':domaine', htmlspecialchars($_POST['domaine']));
									$database->bind(':record', htmlspecialchars($_POST['sousdomaine']).' IN A '.$_SERVER['SERVER_ADDR']);
									$database->bind(':type', 'A');
									$database->bind(':target', $_SERVER['SERVER_ADDR']);
									$database->bind(':readonly', '1');
									$database->execute();
									
									$database->query('SELECT MAX(`id`) AS `id` FROM `bindrecord`');
									$database->execute();
									$bindrecord = $database->resultset();
									
									// Envoie de la commande de création du records
									$domainerecords = $_POST['domaine'];
									$recordsrecords = htmlspecialchars($_POST['sousdomaine']).' IN A '.$_SERVER['SERVER_ADDR'];
									$output = shell_exec("sudo $core/bind/lib/createrecords.sh '$domainerecords' '$recordsrecords' ");
									print($output);
									
								}
								
								if (!empty($bindrecord)){
									
									// Creation du compte
									$database->query('INSERT INTO `sftp`(`id`, `fqdn`, `loglevel`, `phpvers`, `records`) VALUES (:id, :fqdn, :loglevel, :phpvers, :records)');
									$database->bind(':id', '');
									$database->bind(':fqdn', $domaine);
									$database->bind(':loglevel', $loglevel);
									$database->bind(':phpvers', $phpvers);
									$database->bind(':records', $bindrecord[0]['id']);
									$database->execute();
									
								}else{
									
									// Creation du compte
									$database->query('INSERT INTO `sftp`(`id`, `fqdn`, `loglevel`, `phpvers`, `records`) VALUES (:id, :fqdn, :loglevel, :phpvers, :records)');
									$database->bind(':id', '');
									$database->bind(':fqdn', $domaine);
									$database->bind(':loglevel', $loglevel);
									$database->bind(':phpvers', $phpvers);
									$database->bind(':records', '');
									$database->execute();
									
								}
								
								// Envoie de la commande de création du compte
								$passwordcompte = $_POST['password'];
								$output = shell_exec("sudo $core/sftp/lib/create.sh '$domaine' '$passwordcompte' '$loglevel' '$phpvers' > /dev/null&");
								print($output);
								
								// Définition de l'alerte
								$_SESSION['tmp']['sftp']['type'] = 'success';
								$_SESSION['tmp']['sftp']['msgbox'] = 'Le compte sftp "'.$domaine.'" a correctement été créé !';
								
								// Redirection
								header('Location:../../?service=sftp');
								
							}
							
						}
						
					}else{
						
						// Définition de l'alerte
						$_SESSION['tmp']['sftp']['type'] = 'warning';
						$_SESSION['tmp']['sftp']['msgbox'] = 'Les mots de passe ne concordent pas !';
						
						// Redirection
						header('Location:../../?service=sftp');
						
					}
					
				}else{
					
					// Définition de l'alerte
					$_SESSION['tmp']['sftp']['type'] = 'warning';
					$_SESSION['tmp']['sftp']['msgbox'] = 'Les champs "domaine", "password", "repeatpassword", "loglevel" et "phpvers" est obligatoire !';
					
					// Redirection
					header('Location:../../?service=sftp');
					
				}
				
			}else{
				
				// Définition de l'alerte
				$_SESSION['tmp']['login']['type'] = 'danger';
				$_SESSION['tmp']['login']['msgbox'] = 'Echec lors de la récupération de l\'identifiant utilisateur';
				
				// Redirection
				header('Location:../logout.php');
				
			}
			
		// Suppression d'un compte
		}else if ( $action == "delete" ){
			
			// Protection de la connectivité
			if (!empty($_SESSION['webhostingfe'])){
				
				if (!empty($_POST['id']) and !empty($_POST['fqdn'])){
				
					// Recuperation de l'id
					$id = htmlspecialchars($_POST['id']);
					
					// Verification de la disponibilite du compte
					$database->query("SELECT * FROM sftp WHERE id = :id");
					$database->bind(':id', $id);
					$row = $database->resultset();
					$countfqdn = $database->rowCount();

					if ($countfqdn > 0){
						
						if (!empty($row[0]['records'])){
							
							// Récupération du record
							$database->query("SELECT * FROM bindrecord WHERE id = :id");
							$database->bind(':id', $row[0]['records']);
							$rowRecords = $database->resultset();
							
							// Suppression du records
							$database->query('DELETE FROM `bindrecord` WHERE id = :id');
							$database->bind(':id', $row[0]['records']);
							$database->execute();
							
							// Envoie de la commande de suppression du record
							$domaineRow = $rowRecords[0]['domaine'];
							$recordsRow = $rowRecords[0]['record'];
							$output = shell_exec("sudo $core/bind/lib/deleterecords.sh '$domaineRow' '$recordsRow' > /dev/null&");
							print($output);
							
						}
						
						// Suppression du compte
						$database->query('DELETE FROM `sftp` WHERE id = :id');
						$database->bind(':id', $id);
						$database->execute();
						
						// Envoie de la commande de suppression du compte
						$fqdn = $_POST['fqdn'];
						$output = shell_exec("sudo $core/sftp/lib/delete.sh '$fqdn' > /dev/null&");
						print($output);
						
						// Définition de l'alerte
						$_SESSION['tmp']['sftp']['type'] = 'success';
						$_SESSION['tmp']['sftp']['msgbox'] = 'Le compte SFTP a correctement été supprimé !';
						
						// Redirection
						header('Location:../../?service=sftp');
						
					}else{
						
						// Définition de l'alerte
						$_SESSION['tmp']['sftp']['type'] = 'warning';
						$_SESSION['tmp']['sftp']['msgbox'] = 'Le compte sftp "'.$domaine.'" n\'existe pas !';
						
						// Redirection
						header('Location:../../?service=sftp');
						
					}
					
				}else{
					
					// Définition de l'alerte
					$_SESSION['tmp']['sftp']['type'] = 'danger';
					$_SESSION['tmp']['sftp']['msgbox'] = 'Erreur irécupérable !';
					
					// Redirection
					header('Location:../../?service=sftp');
					
				}
				
			}else{
				
				// Définition de l'alerte
				$_SESSION['tmp']['login']['type'] = 'danger';
				$_SESSION['tmp']['login']['msgbox'] = 'Echec lors de la récupération de l\'identifiant utilisateur';
				
				// Redirection
				header('Location:../logout.php');
				
			}
			
		// Modification du mot de passe du compte
		}else if ( $action == "set" ){
			
			// Protection de la connectivité
			if (!empty($_SESSION['webhostingfe'])){
				
				if ( !empty($_POST['id']) and !empty($_POST['fqdn']) and !empty($_POST['password']) and !empty($_POST['repeatpassword']) ){
				
					// Recuperation de l'id
					$id = htmlspecialchars($_POST['id']);
					
					// Verification de la disponibilite du compte
					$database->query("SELECT * FROM sftp WHERE id = :id");
					$database->bind(':id', $id);
					$row = $database->resultset();
					$countfqdn = $database->rowCount();

					if ($countfqdn > 0){
						
						// Verification des mots de passe
						if ( htmlspecialchars($_POST['password']) == htmlspecialchars($_POST['repeatpassword']) ){
							
							// Envoie de la commande de modification du compte
							$fqdn = $_POST['fqdn'];
							$password = $_POST['password'];
							$output = shell_exec("sudo $core/sftp/lib/set.sh '$fqdn' '$password' ");
							print($output);
							
							// Définition de l'alerte
							$_SESSION['tmp']['sftp']['type'] = 'success';
							$_SESSION['tmp']['sftp']['msgbox'] = 'Le mot de passe du compte a été mis à jour !';
							
							// Redirection
							header('Location:../../?service=sftp');
							
						}else{
							
							// Définition de l'alerte
							$_SESSION['tmp']['sftp']['type'] = 'warning';
							$_SESSION['tmp']['sftp']['msgbox'] = 'Les mots de passe ne concordent pas !';
							
							// Redirection
							header('Location:../../?service=sftp');
							
						}
						
					}else{
						
						// Définition de l'alerte
						$_SESSION['tmp']['sftp']['type'] = 'warning';
						$_SESSION['tmp']['sftp']['msgbox'] = 'Le compte sftp "'.$domaine.'" n\'existe pas !';
						
						// Redirection
						header('Location:../../?service=sftp');
						
					}
					
				}else{
					
					// Définition de l'alerte
					$_SESSION['tmp']['sftp']['type'] = 'danger';
					$_SESSION['tmp']['sftp']['msgbox'] = 'Erreur irécupérable !';
					
					// Redirection
					header('Location:../../?service=sftp');
					
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
			$_SESSION['tmp']['sftp']['type'] = 'danger';
			$_SESSION['tmp']['sftp']['msgbox'] = 'Erreur irécupérable !';
			
			// Redirection
			header('Location:../../?service=sftp');
			
		}
		
	}else{
		
		// Définition de l'alerte
		$_SESSION['tmp']['sftp']['type'] = 'danger';
		$_SESSION['tmp']['sftp']['msgbox'] = 'Erreur irécupérable !';
		
		// Redirection
		header('Location:../../?service=sftp');
		
	}
	
?>