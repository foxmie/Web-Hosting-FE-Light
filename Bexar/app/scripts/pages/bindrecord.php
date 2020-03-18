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
	
	// Récupération du domaine
	if (!empty($_POST['domaine'])){
		
		$domaine = htmlspecialchars($_POST['domaine']);
		
		// Switch
		if (!empty($_POST['action'])){
			
			$action = htmlspecialchars($_POST['action']);
			
			// Creation d'un records
			if ($action == 'create'){
				
				// Protection de la connectivité
				if (!empty($_SESSION['webhostingfe'])){
					
					// Recuperation du type
					if (!empty($_POST['type'])){
						
						$type = htmlspecialchars($_POST['type']);
						
						// TYPE A
						if ($type == 'A'){
							
							// Recuperation des informations
							if ( !empty($_POST['cible']) ){
								
								// Récupération des informations
								if (!empty($_POST['sousdomaine'])){ $sousdomaine = htmlspecialchars($_POST['sousdomaine']).' ';  }else{ $sousdomaine = ' '; }
								if (!empty($_POST['ttl'])){ $ttl = htmlspecialchars($_POST['ttl']);  }else{ $ttl = ''; }
								$cible = htmlspecialchars($_POST['cible']);
								
								// Creation du records
								$records = $sousdomaine.$ttl.' IN A '.$cible;
								
								// Insertion dans la base de donnéées de l'enregistrement
								$database->query('INSERT INTO `bindrecord`(`id`, `name`, `domaine`, `record`, `type`, `target`, `readonly`) VALUES (:id, :name, :domaine, :record, :type, :target, :readonly)');
								$database->bind(':id', '');
								$database->bind(':name', $sousdomaine);
								$database->bind(':domaine', $domaine);
								$database->bind(':record', $records);
								$database->bind(':type', $type);
								$database->bind(':target', $cible);
								$database->bind(':readonly', '0');
								$database->execute();
								
								// Envoie de la commande de création du records
								$output = shell_exec("sudo $core/bind/lib/createrecords.sh '$domaine' '$records' ");
								print($output);
								
								// Définition de l'alerte
								$_SESSION['tmp']['zone']['type'] = 'success';
								$_SESSION['tmp']['zone']['msgbox'] = 'Records correctement créer !';
								
								// Redirection
								header('Location:../../?service=zone&domaine='.$domaine);
								
							}else{
								
								// Définition de l'alerte
								$_SESSION['tmp']['zone']['type'] = 'warning';
								$_SESSION['tmp']['zone']['msgbox'] = 'Le champ "Cible" est obligatoire !';
								
								// Redirection
								header('Location:../../?service=zone&domaine='.$domaine);
								
							}
							
						// TYPE AAAA
						}else if ($type == 'AAAA'){
							
							// Recuperation des informations
							if ( !empty($_POST['cible']) ){
								
								// Récupération des informations
								if (!empty($_POST['sousdomaine'])){ $sousdomaine = htmlspecialchars($_POST['sousdomaine']).' ';  }else{ $sousdomaine = ' '; }
								if (!empty($_POST['ttl'])){ $ttl = htmlspecialchars($_POST['ttl']);  }else{ $ttl = ''; }
								$cible = htmlspecialchars($_POST['cible']);
								
								// Creation du records
								$records = $sousdomaine.$ttl.' IN AAAA '.$cible;
								
								// Insertion dans la base de donnéées de l'enregistrement
								$database->query('INSERT INTO `bindrecord`(`id`, `name`, `domaine`, `record`, `type`, `target`, `readonly`) VALUES (:id, :name, :domaine, :record, :type, :target, :readonly)');
								$database->bind(':id', '');
								$database->bind(':name', $sousdomaine);
								$database->bind(':domaine', $domaine);
								$database->bind(':record', $records);
								$database->bind(':type', $type);
								$database->bind(':target', $cible);
								$database->bind(':readonly', '0');
								$database->execute();
								
								// Envoie de la commande de création du records
								$output = shell_exec("sudo $core/bind/lib/createrecords.sh '$domaine' '$records' ");
								print($output);
								
								// Définition de l'alerte
								$_SESSION['tmp']['zone']['type'] = 'success';
								$_SESSION['tmp']['zone']['msgbox'] = 'Records correctement créer !';
								
								// Redirection
								header('Location:../../?service=zone&domaine='.$domaine);
								
							}else{
								
								// Définition de l'alerte
								$_SESSION['tmp']['zone']['type'] = 'warning';
								$_SESSION['tmp']['zone']['msgbox'] = 'Le champ "Cible" est obligatoire !';
								
								// Redirection
								header('Location:../../?service=zone&domaine='.$domaine);
								
							}
							
						// TYPE NS
						}else if ($type == 'NS'){
							
							// Recuperation des informations
							if ( !empty($_POST['cible']) and !empty($_POST['sousdomaine']) ){
								
								// Récupération des informations
								$sousdomaine = htmlspecialchars($_POST['sousdomaine']).' ';
								if (!empty($_POST['ttl'])){ $ttl = htmlspecialchars($_POST['ttl']);  }else{ $ttl = ''; }
								$cible = htmlspecialchars($_POST['cible']);
								
								// Creation du records
								$records = $sousdomaine.$ttl.' IN NS '.$cible;
								
								// Insertion dans la base de donnéées de l'enregistrement
								$database->query('INSERT INTO `bindrecord`(`id`, `name`, `domaine`, `record`, `type`, `target`, `readonly`) VALUES (:id, :name, :domaine, :record, :type, :target, :readonly)');
								$database->bind(':id', '');
								$database->bind(':name', $sousdomaine);
								$database->bind(':domaine', $domaine);
								$database->bind(':record', $records);
								$database->bind(':type', $type);
								$database->bind(':target', $cible);
								$database->bind(':readonly', '0');
								$database->execute();
								
								// Envoie de la commande de création du records
								$output = shell_exec("sudo $core/bind/lib/createrecords.sh '$domaine' '$records' ");
								print($output);
								
								// Définition de l'alerte
								$_SESSION['tmp']['zone']['type'] = 'success';
								$_SESSION['tmp']['zone']['msgbox'] = 'Records correctement créer !';
								
								// Redirection
								header('Location:../../?service=zone&domaine='.$domaine);
								
							}else{
								
								// Définition de l'alerte
								$_SESSION['tmp']['zone']['type'] = 'warning';
								$_SESSION['tmp']['zone']['msgbox'] = 'Les champs "Sous domaine" et "Cible" sont obligatoire !';
								
								// Redirection
								header('Location:../../?service=zone&domaine='.$domaine);
								
							}
							
						// TYPE CNAME
						}else if ($type == 'CNAME'){
							
							// Recuperation des informations
							if ( !empty($_POST['cible']) ){
								
								// Récupération des informations
								if (!empty($_POST['sousdomaine'])){ $sousdomaine = htmlspecialchars($_POST['sousdomaine']).' ';  }else{ $sousdomaine = ' '; }
								if (!empty($_POST['ttl'])){ $ttl = htmlspecialchars($_POST['ttl']);  }else{ $ttl = ''; }
								$cible = htmlspecialchars($_POST['cible']);
								
								// Creation du records
								$records = $sousdomaine.$ttl.' IN CNAME '.$cible;
								
								// Insertion dans la base de donnéées de l'enregistrement
								$database->query('INSERT INTO `bindrecord`(`id`, `name`, `domaine`, `record`, `type`, `target`, `readonly`) VALUES (:id, :name, :domaine, :record, :type, :target, :readonly)');
								$database->bind(':id', '');
								$database->bind(':name', $sousdomaine);
								$database->bind(':domaine', $domaine);
								$database->bind(':record', $records);
								$database->bind(':type', $type);
								$database->bind(':target', $cible);
								$database->bind(':readonly', '0');
								$database->execute();
								
								// Envoie de la commande de création du records
								$output = shell_exec("sudo $core/bind/lib/createrecords.sh '$domaine' '$records' ");
								print($output);
								
								// Définition de l'alerte
								$_SESSION['tmp']['zone']['type'] = 'success';
								$_SESSION['tmp']['zone']['msgbox'] = 'Records correctement créer !';
								
								// Redirection
								header('Location:../../?service=zone&domaine='.$domaine);
								
							}else{
								
								// Définition de l'alerte
								$_SESSION['tmp']['zone']['type'] = 'warning';
								$_SESSION['tmp']['zone']['msgbox'] = 'Le champ "Cible" est obligatoire !';
								
								// Redirection
								header('Location:../../?service=zone&domaine='.$domaine);
								
							}
							
						//  TYPE TXT
						}else if ($type == 'TXT'){
							
							// Recuperation des informations
							if ( !empty($_POST['valeur']) ){
								
								// Récupération des informations
								if (!empty($_POST['sousdomaine'])){ $sousdomaine = htmlspecialchars($_POST['sousdomaine']).' ';  }else{ $sousdomaine = ' '; }
								if (!empty($_POST['ttl'])){ $ttl = htmlspecialchars($_POST['ttl']);  }else{ $ttl = ''; }
								$valeur = htmlspecialchars($_POST['valeur']);
								
								// Creation du records
								$records = $sousdomaine.$ttl.' IN TXT "'.$valeur.'"';
								
								// Insertion dans la base de donnéées de l'enregistrement
								$database->query('INSERT INTO `bindrecord`(`id`, `name`, `domaine`, `record`, `type`, `target`, `readonly`) VALUES (:id, :name, :domaine, :record, :type, :target, :readonly)');
								$database->bind(':id', '');
								$database->bind(':name', $sousdomaine);
								$database->bind(':domaine', $domaine);
								$database->bind(':record', $records);
								$database->bind(':type', $type);
								$database->bind(':target', $valeur);
								$database->bind(':readonly', '0');
								$database->execute();
								
								// Envoie de la commande de création du records
								$output = shell_exec("sudo $core/bind/lib/createrecords.sh '$domaine' '$records' ");
								print($output);
								
								// Définition de l'alerte
								$_SESSION['tmp']['zone']['type'] = 'success';
								$_SESSION['tmp']['zone']['msgbox'] = 'Records correctement créer !';
								
								// Redirection
								header('Location:../../?service=zone&domaine='.$domaine);
								
							}else{
								
								// Définition de l'alerte
								$_SESSION['tmp']['zone']['type'] = 'warning';
								$_SESSION['tmp']['zone']['msgbox'] = 'Le champ "Valeur" est obligatoire !';
								
								// Redirection
								header('Location:../../?service=zone&domaine='.$domaine);
								
							}
							
						// TYPE SRV
						}else if ($type == 'SRV'){
							
							// Recuperation des informations
							if ( !empty($_POST['priorite']) and !empty($_POST['poids']) and !empty($_POST['port']) and !empty($_POST['cible']) ){
								
								// Récupération des informations
								if (!empty($_POST['sousdomaine'])){ $sousdomaine = htmlspecialchars($_POST['sousdomaine']).' ';  }else{ $sousdomaine = ' '; }
								if (!empty($_POST['ttl'])){ $ttl = htmlspecialchars($_POST['ttl']);  }else{ $ttl = ''; }
								$priorite = htmlspecialchars($_POST['priorite']).' ';
								$poids = htmlspecialchars($_POST['poids']).' ';
								$port = htmlspecialchars($_POST['port']).' ';
								$cible = htmlspecialchars($_POST['cible']);
								
								// Creation du records
								$records = $sousdomaine.$ttl.' IN SRV '.$priorite.$poids.$port.$cible;
								
								// Insertion dans la base de donnéées de l'enregistrement
								$database->query('INSERT INTO `bindrecord`(`id`, `name`, `domaine`, `record`, `type`, `target`, `readonly`) VALUES (:id, :name, :domaine, :record, :type, :target, :readonly)');
								$database->bind(':id', '');
								$database->bind(':name', $sousdomaine);
								$database->bind(':domaine', $domaine);
								$database->bind(':record', $records);
								$database->bind(':type', $type);
								$database->bind(':target', $cible);
								$database->bind(':readonly', '0');
								$database->execute();
								
								// Envoie de la commande de création du records
								$output = shell_exec("sudo $core/bind/lib/createrecords.sh '$domaine' '$records' ");
								print($output);
								
								// Définition de l'alerte
								$_SESSION['tmp']['zone']['type'] = 'success';
								$_SESSION['tmp']['zone']['msgbox'] = 'Records correctement créer !';
								
								// Redirection
								header('Location:../../?service=zone&domaine='.$domaine);
								
							}else{
								
								// Définition de l'alerte
								$_SESSION['tmp']['zone']['type'] = 'warning';
								$_SESSION['tmp']['zone']['msgbox'] = 'Les champs "Priorité", "Poids", "Port" et "Cible" sont obligatoire !';
								
								// Redirection
								header('Location:../../?service=zone&domaine='.$domaine);
								
							}
							
						// TYPE MX
						}else if ($type == 'MX'){
							
							// Recuperation des informations
							if ( !empty($_POST['priorite']) and !empty($_POST['cible']) ){
								
								// Récupération des informations
								if (!empty($_POST['sousdomaine'])){ $sousdomaine = htmlspecialchars($_POST['sousdomaine']).' ';  }else{ $sousdomaine = ' '; }
								if (!empty($_POST['ttl'])){ $ttl = htmlspecialchars($_POST['ttl']);  }else{ $ttl = ''; }
								$priorite = htmlspecialchars($_POST['priorite']).' ';
								$cible = htmlspecialchars($_POST['cible']);
								
								// Creation du records
								$records = $sousdomaine.$ttl.' IN MX '.$priorite.$cible;
								
								// Insertion dans la base de donnéées de l'enregistrement
								$database->query('INSERT INTO `bindrecord`(`id`, `name`, `domaine`, `record`, `type`, `target`, `readonly`) VALUES (:id, :name, :domaine, :record, :type, :target, :readonly)');
								$database->bind(':id', '');
								$database->bind(':name', $sousdomaine);
								$database->bind(':domaine', $domaine);
								$database->bind(':record', $records);
								$database->bind(':type', $type);
								$database->bind(':target', $cible);
								$database->bind(':readonly', '0');
								$database->execute();
								
								// Envoie de la commande de création du records
								$output = shell_exec("sudo $core/bind/lib/createrecords.sh '$domaine' '$records' ");
								print($output);
								
								// Définition de l'alerte
								$_SESSION['tmp']['zone']['type'] = 'success';
								$_SESSION['tmp']['zone']['msgbox'] = 'Records correctement créer !';
								
								// Redirection
								header('Location:../../?service=zone&domaine='.$domaine);
								
							}else{
								
								// Définition de l'alerte
								$_SESSION['tmp']['zone']['type'] = 'warning';
								$_SESSION['tmp']['zone']['msgbox'] = 'Les champs "Priorité" et "Cible" sont obligatoire !';
								
								// Redirection
								header('Location:../../?service=zone&domaine='.$domaine);
								
							}
							
						// TYPE SPF
						}else if ($type == 'SPF'){
							
							// Récupération des informations
							if (!empty($_POST['sousdomaine'])){ $sousdomaine = htmlspecialchars($_POST['sousdomaine']).' ';  }else{ $sousdomaine = ' '; }
							if (!empty($_POST['ttl'])){ $ttl = htmlspecialchars($_POST['ttl']);  }else{ $ttl = ''; }
							if (!empty($_POST['radio1'])){ $radio1 = ' '.htmlspecialchars($_POST['radio1']);  }else{ $radio1 = ''; }
							if (!empty($_POST['radio2'])){ $radio2 = ' '.htmlspecialchars($_POST['radio2']);  }else{ $radio2 = ''; }
							if (!empty($_POST['radio3'])){ $radio3 = ' '.htmlspecialchars($_POST['radio3']);  }else{ $radio3 = ''; }
							if (!empty($_POST['include'])){ $include = ' include:'.htmlspecialchars($_POST['include']);  }else{ $include = ''; }
							if (!empty($_POST['radio4'])){ $radio4 = ' '.htmlspecialchars($_POST['radio4']);  }else{ $radio4 = ''; }
							
							// Creation du records
							$records = $sousdomaine.$ttl.' IN TXT "v=spf1'.$radio1.''.$radio2.''.$radio3.''.$include.''.$radio4.'"';
							
							// Insertion dans la base de donnéées de l'enregistrement
							$database->query('INSERT INTO `bindrecord`(`id`, `name`, `domaine`, `record`, `type`, `target`, `readonly`) VALUES (:id, :name, :domaine, :record, :type, :target, :readonly)');
							$database->bind(':id', '');
							$database->bind(':name', $sousdomaine);
							$database->bind(':domaine', $domaine);
							$database->bind(':record', $records);
							$database->bind(':type', $type);
							$database->bind(':target', '"v=spf1'.$radio1.$radio2.$radio3.$include.$radio4.'"');
							$database->bind(':readonly', '0');
							$database->execute();
							
							// Envoie de la commande de création du records
							$output = shell_exec("sudo $core/bind/lib/createrecords.sh '$domaine' '$records' ");
							print($output);
							
							// Définition de l'alerte
							$_SESSION['tmp']['zone']['type'] = 'success';
							$_SESSION['tmp']['zone']['msgbox'] = 'Records correctement créer !';
							
							// Redirection
							header('Location:../../?service=zone&domaine='.$domaine);
							
						// Erreur irécupérable
						}else{
							
							// Définition de l'alerte
							$_SESSION['tmp']['zone']['type'] = 'danger';
							$_SESSION['tmp']['zone']['msgbox'] = 'Erreur irécupérable !';
							
							// Redirection
							header('Location:../../?service=zone&domaine='.$domaine);
							
						}
						
					}else{
						
						// Définition de l'alerte
						$_SESSION['tmp']['zone']['type'] = 'danger';
						$_SESSION['tmp']['zone']['msgbox'] = 'Erreur irécupérable !';
						
						// Redirection
						header('Location:../../?service=zone&domaine='.$domaine);
						
					}
					
				}else{
					
					// Définition de l'alerte
					$_SESSION['tmp']['login']['type'] = 'danger';
					$_SESSION['tmp']['login']['msgbox'] = 'Echec lors de la récupération de l\'identifiant utilisateur';
					
					// Redirection
					header('Location:../logout.php');
					
				}
				
			// Suppression d'un records
			}else if ($action == "delete"){
				
				// Protection de la connectivité
				if (!empty($_SESSION['webhostingfe'])){
					
					// Protection des informations
					if ( !empty($_POST['id']) and !empty($_POST['record']) ){
						
						$id = htmlspecialchars($_POST['id']);
						
						// Verification de la disponibilite du record
						$database->query("SELECT * FROM bindrecord WHERE id = :id");
						$database->bind(':id', $id);
						$row = $database->resultset();
						$countfqdn = $database->rowCount();

						if ($countfqdn > 0){
							
							$record = $_POST['record'];
							
							// Envoie de la commande de suppression du records
							$output = shell_exec("sudo $core/bind/lib/deleterecords.sh '$domaine' '$record' ");
							print($output);
							
							// Suppression du record
							$database->query('DELETE FROM `bindrecord` WHERE id = :id');
							$database->bind(':id', $id);
							$database->execute();
							
							// Définition de l'alerte
							$_SESSION['tmp']['zone']['type'] = 'success';
							$_SESSION['tmp']['zone']['msgbox'] = 'l\'enregistrement a correctement été supprimé !';
							
							// Redirection
							header('Location:../../?service=zone&domaine='.$domaine);
							
						}else{
							
							// Définition de l'alerte
							$_SESSION['tmp']['zone']['type'] = 'warning';
							$_SESSION['tmp']['zone']['msgbox'] = 'L\'enregistrement n\'existe pas !';
							
							// Redirection
							header('Location:../../?service=zone&domaine='.$domaine);
							
						}
						
					}else{
						
						// Définition de l'alerte
						$_SESSION['tmp']['zone']['type'] = 'warning';
						$_SESSION['tmp']['zone']['msgbox'] = 'Echec lors de la récupération de l\'id du record !';
						
						// Redirection
						header('Location:../../?service=zone&domaine='.$domaine);
						
					}
					
				}else{
					
					// Définition de l'alerte
					$_SESSION['tmp']['login']['type'] = 'danger';
					$_SESSION['tmp']['login']['msgbox'] = 'Echec lors de la récupération de l\'identifiant utilisateur';
					
					// Redirection
					header('Location:../logout.php');
					
				}
				
			// Modification d'un records
			}else if ($action == "set"){
				
				// Protection de la connectivité
				if (!empty($_SESSION['webhostingfe'])){
					
					// Protection des informations
					if ('1' == '1'){
						
						// Recuperation des information
						echo "";
						
					}else{
						
						// Définition de l'alerte
						$_SESSION['tmp']['zone']['type'] = 'warning';
						$_SESSION['tmp']['zone']['msgbox'] = 'Les champs "" et "" est obligatoire !';
						
						// Redirection
						header('Location:../../?service=zone&domaine='.$domaine);
						
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
				$_SESSION['tmp']['zone']['type'] = 'danger';
				$_SESSION['tmp']['zone']['msgbox'] = 'Erreur irécupérable !';
				
				// Redirection
				header('Location:../../?service=zone&domaine='.$domaine);
				
			}
			
		}else{
			
			// Définition de l'alerte
			$_SESSION['tmp']['zone']['type'] = 'danger';
			$_SESSION['tmp']['zone']['msgbox'] = 'Erreur irécupérable !';
			
			// Redirection
			header('Location:../../?service=zone&domaine='.$domaine);
			
		}
		
	}else{
		
		// Définition de l'alerte
		$_SESSION['tmp']['bind']['type'] = 'danger';
		$_SESSION['tmp']['bind']['msgbox'] = 'Erreur lors de la récupération du domaine !';
		
		// Redirection
		header('Location:../../?service=bind');
		
	}
	
	
	
?>