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

?>
					<div class="container-fluid">
						<div class="row">
							<div class="col-md-12">
								<div class="card">
									<div class="row">
										<div class="col-md-12">
											<div class="card-header">
												<h4 class="card-title"><center>Gestion SFTP</center></h4><hr>
												<?php
																		
													if ( !empty($_SESSION['tmp']['sftp']['type']) and !empty($_SESSION['tmp']['sftp']['msgbox']) ){
													
												?>			
												<div class="alert alert-<?= $_SESSION['tmp']['sftp']['type'] ?>">
													<button type="button" aria-hidden="true" class="close" data-dismiss="alert">
														<i class="nc-icon nc-simple-remove"></i>
													</button>
													<span>
														<center> <?= $_SESSION['tmp']['sftp']['msgbox'] ?> </center>
													</span>
												</div>				
												<?php
														
														// Suppression des sessions temporaires
														unset($_SESSION['tmp']['sftp']['type']);
														unset($_SESSION['tmp']['sftp']['msgbox']);
													}
												?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					
					
					
					<div class="col-md-12">
						<div class="card">
							<div class="card-body content-full-width">
								<ul role="tablist" class="nav nav-tabs">
									<li role="presentation" class="nav-item show active">
										<a class="nav-link" id="info-tab" href="#icon-info" data-toggle="tab"><i class="fa fa-info"></i> Liste des comptes</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="createsftp-tab" href="#icon-createsftp" data-toggle="tab"><i class="fa fa-plus"></i> Ajouter un compte</a>
									</li>
								</ul>
								<div class="tab-content">
									<div id="icon-info" class="tab-pane fade show active" role="tabpanel" aria-labelledby="info-tab">
										<div class="container-fluid">
											<div class="row">
												<div class="col-md-12">
													<div class="card data-tables">
														<div class="card-body table-striped table-no-bordered table-hover dataTable dtr-inline table-full-width">
															<div class="toolbar"></div>
															<div class="fresh-datatables">
																<table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
																	<thead>
																		<tr>
																			<th width="25%"><center>Compte SFTP</center></th>
																			<th width="20%"><center>Configuration du Vhost </br>(Lors de la creation du compte)</center></th>
																			<th width="55%" class="disabled-sorting"><center>Actions</center></th>
																		</tr>
																	</thead>
																	<tbody>
																		<?php
																			
																			$database->query('SELECT * FROM sftp');
																			$rowSftp = $database->resultset();
																			
																			$maxSftp = $database->rowCount();
																			for ($i=0; $i<$maxSftp; $i++) {
																			
																		?>
																		<tr>
																			<td><center><?= $rowSftp[$i]['fqdn'] ?></center></td>
																			<td><center>Log level : <?= $rowSftp[$i]['loglevel'] ?> | PHP version : <?= $rowSftp[$i]['phpvers'] ?></center></td>
																			<td class="text-right">
																				<?php
																					if ( $rowSftp[$i]['letsencrypt'] == '0' ){
																				?>
																				<a class="btn btn-link btn-success" data-toggle="modal" data-target="#letsencrypt<?= $rowSftp[$i]['id'] ?>"><i class="fa fa-lock"></i> Activer let's encrypt</a>
																				<?php
																					}else if ( $rowSftp[$i]['letsencrypt'] == '1' ){
																				?>
																				<a class="btn btn-link btn-warning" data-toggle="modal" data-target="#letsencrypt<?= $rowSftp[$i]['id'] ?>"><i class="fa fa-unlock"></i> Désactiver let's encrypt</a>
																				<?php
																					}
																				?>
																				<a class="btn btn-link btn-warning" data-toggle="modal" data-target="#set<?= $rowSftp[$i]['id'] ?>"><i class="fa fa-edit"></i> Modifier le mot de passe</a>
																				<a class="btn btn-link btn-info" data-toggle="modal" data-target="#reset<?= $rowSftp[$i]['id'] ?>"><i class="fa fa-undo"></i> Réinitialiser les droits</a>
																				<a class="btn btn-link btn-danger" data-toggle="modal" data-target="#delete<?= $rowSftp[$i]['id'] ?>"><i class="fa fa-times"></i> Supprimer le compte</a>
																			</td>
																		</tr>
																		
																		<!-- Let's Encrypt -->
																		<div class="modal" id="letsencrypt<?= $rowSftp[$i]['id'] ?>" tabindex="-1" role="dialog">
																			<div class="modal-dialog" role="document">
																				<div class="modal-content">
																					<form action="scripts/pages/sftp.php" method="POST">
																						<div class="modal-header">
																							<h5 class="modal-title">
																								<?php
																									if ( $rowSftp[$i]['letsencrypt'] == '0' ){
																										echo 'Activation du certificat ssl let\'s encrypt';
																									}else if ( $rowSftp[$i]['letsencrypt'] == '1' ){
																										echo 'Désactivation du certificat ssl let\'s encrypt';
																									}
																								?>
																							</h5>
																							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																								<span aria-hidden="true">&times;</span>
																							</button>
																						</div>
																						<div class="modal-body">
																							<?php
																								if ( $rowSftp[$i]['letsencrypt'] == '0' ){
																									echo 'Vous êtes sur le point d\'activer un certificat ssl let\'s encrypt pour le compte : '.$rowSftp[$i]['fqdn'];
																								}else if ( $rowSftp[$i]['letsencrypt'] == '1' ){
																									echo 'Vous êtes sur le point de supprimer le certificat ssl let\'s encrypt pour le compte : '.$rowSftp[$i]['fqdn'];
																								}
																							?>
																						</div>
																						<div class="modal-footer">
																							<input type="hidden" name="action" value="letsencrypt" readonly />
																							<input type="hidden" name="id" value="<?= $rowSftp[$i]['id'] ?>" readonly />
																							<input type="hidden" name="fqdn" value="<?= $rowSftp[$i]['fqdn'] ?>" readonly />
																							<input type="hidden" name="state" value="<?= $rowSftp[$i]['letsencrypt'] ?>" readonly />
																							<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
																							<button type="submit" class="btn btn-primary">
																								<?php
																									if ( $rowSftp[$i]['letsencrypt'] == '0' ){
																										echo 'Activer let\'s encrypt';
																									}else if ( $rowSftp[$i]['letsencrypt'] == '1' ){
																										echo 'Désactiver let\'s encrypt';
																									}
																								?>
																							</button>
																						</div>
																					</form>
																				</div>
																			</div>
																		</div>
																		
																		<!-- Modifier le mot de passe -->
																		<div class="modal" id="set<?= $rowSftp[$i]['id'] ?>" tabindex="-1" role="dialog">
																			<div class="modal-dialog" role="document">
																				<div class="modal-content">
																					<form action="scripts/pages/sftp.php" method="POST">
																						<div class="modal-header">
																							<h5 class="modal-title">Modification du mot de passe</h5>
																							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																								<span aria-hidden="true">&times;</span>
																							</button>
																						</div>
																						<div class="modal-body">
																							<div class="form-group">
																								<label>Nouveau mot de passe</label>
																								<input type="password" class="form-control" name="password" required />
																							</div>
																							<div class="form-group">
																								<label>Confirmation du mot de passe</label>
																								<input type="password" class="form-control" name="repeatpassword" required />
																							</div>
																						</div>
																						<div class="modal-footer">
																							<input type="hidden" name="action" value="set" readonly />
																							<input type="hidden" name="id" value="<?= $rowSftp[$i]['id'] ?>" readonly />
																							<input type="hidden" name="fqdn" value="<?= $rowSftp[$i]['fqdn'] ?>" readonly />
																							<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
																							<button type="submit" class="btn btn-primary">Modifier le mot de passe</button>
																						</div>
																					</form>
																				</div>
																			</div>
																		</div>
																		
																		<!-- Réinitialiser les droits -->
																		<div class="modal" id="reset<?= $rowSftp[$i]['id'] ?>" tabindex="-1" role="dialog">
																			<div class="modal-dialog" role="document">
																				<div class="modal-content">
																					<form action="scripts/pages/sftp.php" method="POST">
																						<div class="modal-header">
																							<h5 class="modal-title">Réinitialisation des droits</h5>
																							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																								<span aria-hidden="true">&times;</span>
																							</button>
																						</div>
																						<div class="modal-body">
																							<p><center> Voici les actions qui vont être effectuées sur l'hébergement "<?= $rowSftp[$i]['fqdn'] ?>" : </center></p>
																							<ul>
																								<li>Propriétaire : <?= $rowSftp[$i]['fqdn'] ?> </li>
																								<li>Groupe   : www-data </li>
																								<li>Dossiers : chmod 775</li>
																								<li>Fichiers : chmod 664</li>
																							</ul>
																						</div>
																						<div class="modal-footer">
																							<input type="hidden" name="action" value="reset" readonly />
																							<input type="hidden" name="id" value="<?= $rowSftp[$i]['id'] ?>" readonly />
																							<input type="hidden" name="fqdn" value="<?= $rowSftp[$i]['fqdn'] ?>" readonly />
																							<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
																							<button type="submit" class="btn btn-primary">Réinitialiser les droits</button>
																						</div>
																					</form>
																				</div>
																			</div>
																		</div>

																		<!-- Supprimer le comptes -->
																		<div class="modal" id="delete<?= $rowSftp[$i]['id'] ?>" tabindex="-1" role="dialog">
																			<div class="modal-dialog" role="document">
																				<div class="modal-content">
																					<form action="scripts/pages/sftp.php" method="POST">
																						<div class="modal-header">
																							<h5 class="modal-title">Suppression du comptes</h5>
																							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																								<span aria-hidden="true">&times;</span>
																							</button>
																						</div>
																						<div class="modal-body">
																							<p>Voulez vous vraiment supprimer le comptes "<?= $rowSftp[$i]['fqdn'] ?>" ?</p>
																						</div>
																						<div class="modal-footer">
																							<input type="hidden" name="action" value="delete" readonly />
																							<input type="hidden" name="id" value="<?= $rowSftp[$i]['id'] ?>" readonly />
																							<input type="hidden" name="fqdn" value="<?= $rowSftp[$i]['fqdn'] ?>" readonly />
																							<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
																							<button type="submit" class="btn btn-primary">Supprimer</button>
																						</div>
																					</form>
																				</div>
																			</div>
																		</div>
																		
																		<?php
																		
																			}
																		?>
																	</tbody>
																</table>
															</div>
														</div>
													</div>
													<form action="scripts/pages/sftp.php" method="POST">
														<input type="hidden" name="action" value="renew" readonly />
														<button class="btn btn-primary pull-right">Regénérer les certificats SSL Let's encrypt</button>
													</form>
												</div>
											</div>
										</div>
									</div>
									
									<div id="icon-createsftp" class="tab-pane fade" role="tabpanel" aria-labelledby="createsftp-tab">
									
										<div class="container-fluid">
											<div class="row">
												<div class="col-md-12">
													<div class="card">
														<div class="row">
															<div class="col-md-12">
																<div class="card-body">
																	<h4 class="card-title"><center>Création d'un compte SFTP</center></h4><hr>
																	<form action="scripts/pages/sftp.php" method="POST">
																		<div class="col-md-12">
																			<div class="row">
																				<div class="col-md-8">
																					<div class="form-group">
																						<label>Sous domaine</label>
																						<input class="form-control" type="text" name="sousdomaine" />
																					</div>
																				</div>
																				<div class="col-md-4">
																					<div class="form-group">
																						<label>Domaine</label>
																						<select class="form-control" name="domaine" required>
																							<?php
																								
																								$database->query("SELECT * FROM bindzone"); 
																								$rowDomaine = $database->resultset();
																	
																								$maxDomaine = $database->rowCount();
																								for ($i=0; $i<$maxDomaine; $i++){
																								
																							?>
																							<option value="<?= $rowDomaine[$i]['domaine'] ?>"><?= $rowDomaine[$i]['domaine'] ?></option>
																							<?php
																								}
																							?>
																						</select>
																					</div>
																				</div>
																				<div class="col-md-6">
																					<div class="form-group">
																						<label>Mot de passe</label>
																						<input class="form-control" type="password" name="password" required />
																					</div>
																				</div>
																				<div class="col-md-6">
																					<div class="form-group">
																						<label>Confirmation du mot de passe</label>
																						<input class="form-control" type="password" name="repeatpassword" required />
																					</div>
																				</div>
																				<div class="col-md-6">
																					<div class="form-group">
																						<label>Niveau de logs</label>
																						<select class="form-control" name="loglevel" required>
																							<option value="notice">notice</option>
																							<option value="warn">warn</option>
																							<option value="error">error</option>
																							<option value="crit">crit</option>
																							<option value="alert">alert</option>
																							<option value="emerg">emerg</option>
																						</select>
																					</div>
																				</div>
																				<div class="col-md-6">
																					<div class="form-group">
																						<label>Version de PHP</label>
																						<select class="form-control" name="phpvers" required>
																							<option value="5.6">5.6</option>
																							<option value="7.0">7.0</option>
																							<option value="7.1">7.1</option>
																							<option value="7.2">7.2</option>
																							<option value="7.3">7.3</option>
																							<option value="7.4">7.4</option>
																						</select>
																					</div>
																				</div>
																			</div>
																		</div>
																		<input type="hidden" name="action" value="create" readonly />
																		<?php
																			if ( $maxDomaine != '0' ){
																		?>
																		<button type="submit" class="btn btn-primary btn-block">Créer le compte</button>
																		<?php
																			}else{
																		?>
																		<div class="alert alert-warning">
																			<button type="button" aria-hidden="true" class="close" data-dismiss="alert">
																				<i class="nc-icon nc-simple-remove"></i>
																			</button>
																			<span>
																				<center> Veuillez ajouter un domaine avant de créer un compte SFTP </center>
																			</span>
																		</div>	
																		<?php
																			}
																		?>
																		
																	</form>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									
									</div>
								</div>
							</div>
						</div>
					</div>
