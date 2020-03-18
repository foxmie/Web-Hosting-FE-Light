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
		
		// Creation du domaine
		if ($action == 'create'){
			
			// Protection de la connectivité
			if (!empty($_SESSION['webhostingfe'])){
				
				// Protection des informations
				if ( !empty($_POST['domaine']) and !empty($_POST['extension']) ){
					
					// Recuperation des information
					$domaine = htmlspecialchars($_POST['domaine']);
					$extension = htmlspecialchars($_POST['extension']);
					$domaine = "$domaine$extension";
					$domaine = str_replace(' ','',$domaine);
					
					// Verification de la disponibilitee du domaine
					$database->query("SELECT * FROM bindzone WHERE domaine = :domaine");
					$database->bind(':domaine', $domaine);
					$row = $database->resultset();
					$countfqdn = $database->rowCount();

					if ($countfqdn > 0){
						
						// Définition de l'alerte
						$_SESSION['tmp']['bind']['type'] = 'warning';
						$_SESSION['tmp']['bind']['msgbox'] = 'Le domaine "'.$domaine.'" existe déjà ! ';
						
						// Redirection
						header('Location:../../?service=bind');
						
					}else{
						
						// Creation du domaine
						$database->query('INSERT INTO `bindzone`(`id`, `domaine`) VALUES (:id, :domaine)');
						$database->bind(':id', '');
						$database->bind(':domaine', $domaine);
						$database->execute();
						
						// Creation de l'enregistrement NS
						$database->query('INSERT INTO `bindrecord`(`id`, `name`, `domaine`, `record`, `type`, `target`, `readonly`) VALUES (:id, :name, :domaine, :record, :type, :target, :readonly)');
						$database->bind(':id', '');
						$database->bind(':name', '');
						$database->bind(':domaine', $domaine);
						$database->bind(':record', ' IN NS ns.'.$domaine.'.');
						$database->bind(':type', 'NS');
						$database->bind(':target', $domaine.'.');
						$database->bind(':readonly', '1');
						$database->execute();
						
						// Creation de l'enregistrement A
						$database->query('INSERT INTO `bindrecord`(`id`, `name`, `domaine`, `record`, `type`, `target`, `readonly`) VALUES (:id, :name, :domaine, :record, :type, :target, :readonly)');
						$database->bind(':id', '');
						$database->bind(':name', '');
						$database->bind(':domaine', $domaine);
						$database->bind(':record', ' IN A '.$_SERVER['SERVER_ADDR']);
						$database->bind(':type', 'A');
						$database->bind(':target', $_SERVER['SERVER_ADDR']);
						$database->bind(':readonly', '1');
						$database->execute();
						
						// Creation de l'enregistrement A ns.ip
						$database->query('INSERT INTO `bindrecord`(`id`, `name`, `domaine`, `record`, `type`, `target`, `readonly`) VALUES (:id, :name, :domaine, :record, :type, :target, :readonly)');
						$database->bind(':id', '');
						$database->bind(':name', 'ns');
						$database->bind(':domaine', $domaine);
						$database->bind(':record', 'ns IN A '.$_SERVER['SERVER_ADDR']);
						$database->bind(':type', 'A');
						$database->bind(':target', $_SERVER['SERVER_ADDR']);
						$database->bind(':readonly', '1');
						$database->execute();
						
						// Envoie de la commande de création du domaine
						$output = shell_exec('sudo '.$core.'/bind/lib/createzone.sh "'.$domaine.'" "'.$_SERVER['SERVER_ADDR'].'" ');
						print($output);
						
						// Définition de l'alerte
						$_SESSION['tmp']['bind']['type'] = 'success';
						$_SESSION['tmp']['bind']['msgbox'] = 'Le domaine "'.$domaine.'" a correctement été créé !';
						
						// Redirection
						header('Location:../../?service=bind');
						
					}
				
				}else{
					
					// Définition de l'alerte
					$_SESSION['tmp']['bind']['type'] = 'warning';
					$_SESSION['tmp']['bind']['msgbox'] = 'Les champs "Domaine" et "Extension" est obligatoire !';
					
					// Redirection
					header('Location:../../?service=bind');
					
				}
				
			}else{
				
				// Définition de l'alerte
				$_SESSION['tmp']['login']['type'] = 'danger';
				$_SESSION['tmp']['login']['msgbox'] = 'Echec lors de la récupération de l\'identifiant utilisateur';
				
				// Redirection
				header('Location:../logout.php');
				
			}
			
		// Suppression du domaine
		}else if ( $action == "delete"){
			
			// Protection de la connectivité
			if (!empty($_SESSION['webhostingfe'])){
				
				if (!empty($_POST['id'])){
				
					// Recuperation de l'id
					$id = htmlspecialchars($_POST['id']);
					
					// Verification de la disponibilite du domaine
					$database->query("SELECT * FROM bindzone WHERE id = :id");
					$database->bind(':id', $id);
					$row = $database->resultset();
					$countfqdn = $database->rowCount();

					if ($countfqdn > 0){
						
						// Suppression des records du domaine
						$database->query('DELETE FROM `bindrecord` WHERE domaine = :domaine');
						$database->bind(':domaine', $row[0]['domaine']);
						$database->execute();
						
						// Suppression du domaine
						$database->query('DELETE FROM `bindzone` WHERE id = :id');
						$database->bind(':id', $id);
						$database->execute();
						
						// Envoie de la commande de suppression du domaine
						$output = shell_exec('sudo '.$core.'/bind/lib/deletezone.sh "'.$row[0]['domaine'].'" ');
						print($output);
						
						// Définition de l'alerte
						$_SESSION['tmp']['bind']['type'] = 'success';
						$_SESSION['tmp']['bind']['msgbox'] = 'Le domaine a correctement été supprimé !';
						
						// Redirection
						header('Location:../../?service=bind');
						
					}else{
						
						// Définition de l'alerte
						$_SESSION['tmp']['bind']['type'] = 'warning';
						$_SESSION['tmp']['bind']['msgbox'] = 'Le domaine "'.$domaine.'" n\'existe pas !';
						
						// Redirection
						header('Location:../../?service=bind');
						
					}
					
				}else{
					
					// Définition de l'alerte
					$_SESSION['tmp']['bind']['type'] = 'danger';
					$_SESSION['tmp']['bind']['msgbox'] = 'Erreur irécupérable !';
					
					// Redirection
					header('Location:../../?service=bind');
					
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
			$_SESSION['tmp']['bind']['type'] = 'danger';
			$_SESSION['tmp']['bind']['msgbox'] = 'Erreur irécupérable !';
			
			// Redirection
			header('Location:../../?service=bind');
			
		}
		
	}else{
		
		// Définition de l'alerte
		$_SESSION['tmp']['bind']['type'] = 'danger';
		$_SESSION['tmp']['bind']['msgbox'] = 'Erreur irécupérable !';
		
		// Redirection
		header('Location:../../?service=bind');
		
	}
	
?>