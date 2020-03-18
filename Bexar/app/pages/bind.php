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
												<h4 class="card-title"><center>Gestion DNS (bind)</center></h4><hr>
												<?php
																		
													if ( !empty($_SESSION['tmp']['bind']['type']) and !empty($_SESSION['tmp']['bind']['msgbox']) ){
													
												?>			
												<div class="alert alert-<?= $_SESSION['tmp']['bind']['type'] ?>">
													<button type="button" aria-hidden="true" class="close" data-dismiss="alert">
														<i class="nc-icon nc-simple-remove"></i>
													</button>
													<span>
														<center> <?= $_SESSION['tmp']['bind']['msgbox'] ?> </center>
													</span>
												</div>				
												<?php
														
														// Suppression des sessions temporaires
														unset($_SESSION['tmp']['bind']['type']);
														unset($_SESSION['tmp']['bind']['msgbox']);
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
										<a class="nav-link" id="info-tab" href="#icon-info" data-toggle="tab"><i class="fa fa-info"></i> Liste des domaines</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="createzone-tab" href="#icon-createzone" data-toggle="tab"><i class="fa fa-plus"></i> Ajouter un domaine</a>
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
																			<th><center>Domaine</center></th>
																			<th><center>Type</center></th>
																			<th><center>Host</center></th>
																			<th class="disabled-sorting text-right"><center>Actions</center></th>
																		</tr>
																	</thead>
																	<tbody>
																		<?php
																			
																			$database->query('SELECT * FROM bindzone');
																			$rowDomaine = $database->resultset();
																			
																			$maxDomaine = $database->rowCount();
																			for ($i=0; $i<$maxDomaine; $i++) {
																				
																		?>
																		<tr>
																			<td><center><?= $rowDomaine[$i]['domaine'] ?></center></td>
																			<td><center>Master</center></td>
																			<td><center><?= $_SERVER['SERVER_ADDR'] ?></center></td>
																			<td class="text-right">
																				<center>
																					<a href="?service=zone&domaine=<?= $rowDomaine[$i]['domaine'] ?>" class="btn btn-link btn-info" ><i class="fa fa-edit"></i> Gestion du domaine</a>
																					<a class="btn btn-link btn-danger" data-toggle="modal" data-target="#delete<?= $rowDomaine[$i]['id'] ?>"><i class="fa fa-times"></i> Supprimer le domaine</a>
																				</center>
																			</td>
																		</tr>
																		
																		<!-- Supprimer le domaine -->
																		<div class="modal" id="delete<?= $rowDomaine[$i]['id'] ?>" tabindex="-1" role="dialog">
																			<div class="modal-dialog" role="document">
																				<div class="modal-content">
																					<form action="scripts/pages/bindzone.php" method="POST">
																						<div class="modal-header">
																							<h5 class="modal-title">Suppression du domaine</h5>
																							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																								<span aria-hidden="true">&times;</span>
																							</button>
																						</div>
																						<div class="modal-body">
																							<p>Voulez vous vraiment supprimer le domaine "<?= $rowDomaine[$i]['domaine'] ?>" ?</p>
																						</div>
																						<div class="modal-footer">
																							<input type="hidden" name="action" value="delete" readonly />
																							<input type="hidden" name="id" value="<?= $rowDomaine[$i]['id'] ?>" readonly />
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
												</div>
											</div>
										</div>
									</div>
									
									<div id="icon-createzone" class="tab-pane fade" role="tabpanel" aria-labelledby="createzone-tab">
									
										<div class="container-fluid">
											<div class="row">
												<div class="col-md-12">
													<div class="card">
														<div class="row">
															<div class="col-md-12">
																<div class="card-body">
																	<h4 class="card-title"><center>Création d'un domaine</center></h4><hr>
																	<form action="scripts/pages/bindzone.php" method="POST">
																		<div class="col-md-12">
																			<div class="row">
																				<div class="col-md-8">
																					<div class="form-group">
																						<label>Domaine</label>
																						<input class="form-control" type="text" name="domaine" required />
																					</div>
																				</div>
																				<div class="col-md-4">
																					<div class="form-group">
																						<label>Extension</label>
																						<select class="form-control" name="extension" required>
																							<?php
																								
																								$database->query("SELECT * FROM bindextension"); 
																								$rowBindExtension = $database->resultset();
																	
																								$maxBindExtension = $database->rowCount();
																								for ($i=0; $i<$maxBindExtension; $i++){
																								
																							?>
																							<option value="<?= $rowBindExtension[$i]['extension'] ?>"><?= $rowBindExtension[$i]['extension'] ?></option>
																							<?php
																								}
																							?>
																						</select>
																					</div>
																				</div>
																			</div>
																		</div>
																		<input type="hidden" name="action" value="create" readonly />
																		<button type="submit" class="btn btn-primary btn-block">Créer le domaine</button>
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