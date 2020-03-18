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
	
	// Recuperation du domaine
	$database->query("SELECT * FROM `bindzone` WHERE `domaine` = :domaine");
	$database->bind(':domaine', htmlspecialchars($_GET['domaine']));
	$rowZone = $database->resultset();
	
?>
					<div class="container-fluid">
						<div class="row">
							<div class="col-md-12">
								<div class="card">
									<div class="row">
										<div class="col-md-12">
											<div class="card-header">
												<h4 class="card-title"><center><?= $rowZone[0]['domaine'] ?></center></h4><hr>
												<div class="row">
													<div class="col-md-12">
														<div class="row">
															<div class="col-md-6">
																<center><label>Type : Master</label></center>
															</div>
															<div class="col-md-6">
																<center><label>Host : <?= $_SERVER['SERVER_ADDR'] ?></label></center>
															</div>
														</div>
													</div>
												</div><hr>
												<?php
																		
													if ( !empty($_SESSION['tmp']['zone']['type']) and !empty($_SESSION['tmp']['zone']['msgbox']) ){
													
												?>			
												<div class="alert alert-<?= $_SESSION['tmp']['zone']['type'] ?>">
													<button type="button" aria-hidden="true" class="close" data-dismiss="alert">
														<i class="nc-icon nc-simple-remove"></i>
													</button>
													<span>
														<center> <?= $_SESSION['tmp']['zone']['msgbox'] ?> </center>
													</span>
												</div>				
												<?php
														
														// Suppression des sessions temporaires
														unset($_SESSION['tmp']['zone']['type']);
														unset($_SESSION['tmp']['zone']['msgbox']);
													}
												?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="container-fluid">
						<div class="row">			
							<div class="col-md-8">	
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
																	<th><center>Cible</center></th>
																	<th class="disabled-sorting text-right"><center>Actions</center></th>
																</tr>
															</thead>
															<tbody>
																<?php
																	
																	$database->query('SELECT * FROM bindrecord');
																	$rowBindrecord = $database->resultset();
																	
																	$maxBindrecord = $database->rowCount();
																	for ($i=0; $i<$maxBindrecord; $i++) {
																		
																?>
																<tr>
																	<td><center><?php if (!empty($rowBindrecord[$i]['name'])){ echo str_replace(' ','',$rowBindrecord[$i]['name'].'.'); } ?><?= str_replace(' ','',$rowBindrecord[$i]['domaine']) ?>.</center></td>
																	<td><center><?= $rowBindrecord[$i]['type'] ?></center></td>
																	<td><center><?= $rowBindrecord[$i]['target'] ?></center></td>
																	<td class="text-right">
																		<center>
																			<?php
																				if ($rowBindrecord[$i]['readonly'] != 1 ){
																			?>
																			<a class="btn btn-link btn-danger" data-toggle="modal" data-target="#delete<?= $rowBindrecord[$i]['id'] ?>"><i class="fa fa-times"></i> Supprimer l'enregistrement</a>
																			<?php																	
																				}
																			?>
																		</center>
																	</td>
																</tr>
																
																<!-- Supprimer l'enregistrement -->
																<div class="modal" id="delete<?= $rowBindrecord[$i]['id'] ?>" tabindex="-1" role="dialog">
																	<div class="modal-dialog" role="document">
																		<div class="modal-content">
																			<form action="scripts/pages/bindrecord.php" method="POST">
																				<div class="modal-header">
																					<h5 class="modal-title">Supprimer l'enregistrement</h5>
																					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																						<span aria-hidden="true">&times;</span>
																					</button>
																				</div>
																				<div class="modal-body">
																					<p>Voulez vous vraiment supprimer l'enregistrement ?</p>
																				</div>
																				<div class="modal-footer">
																					<input type="hidden" name="action" value="delete" readonly />
																					<input type="hidden" name="id" value="<?= $rowBindrecord[$i]['id'] ?>" readonly />
																					<input type="hidden" name="record" value="<?= $rowBindrecord[$i]['record'] ?>" readonly />
																					<input type="hidden" name="domaine" value="<?= $rowZone[0]['domaine'] ?>" readonly />
																					<button type="submit" class="btn btn-danger">Supprimer</button>
																					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
							<div class="col-md-4">
								<div class="container-fluid">
									<div class="row">
										<div class="row">
											<div class="col-md-12">
												<p class="title">Champs de pointage</p>
												<div class="row">
													<div class="col-md-3"><button class="btn btn-block btn-primary" data-toggle="modal" data-target="#createa">A</button></div>
													<div class="col-md-3"><button class="btn btn-block btn-primary" data-toggle="modal" data-target="#createaaaa">AAAA</button></div>
													<div class="col-md-3"><button class="btn btn-block btn-primary" data-toggle="modal" data-target="#createns">NS</button></div>
													<div class="col-md-3"><button class="btn btn-block btn-primary" data-toggle="modal" data-target="#createcname">CNAME</button></div>
												</div>
											</div>
											<hr>
											<div class="col-md-12">	
												</br><p class="title">Champs étendus</p>
												<div class="row">
													<div class="col-md-3"><button class="btn btn-block btn-primary" data-toggle="modal" data-target="#createcaa" disabled>CAA</button></div>
													<div class="col-md-3"><button class="btn btn-block btn-primary" data-toggle="modal" data-target="#createtxt">TXT</button></div>
													<div class="col-md-3"><button class="btn btn-block btn-primary" data-toggle="modal" data-target="#createnaptr" disabled>NAPTR</button></div>
													<div class="col-md-3"><button class="btn btn-block btn-primary" data-toggle="modal" data-target="#createsrv">SRV</button></div>
													<div class="col-md-3"><button class="btn btn-block btn-primary" data-toggle="modal" data-target="#createloc" disabled>LOC</button></div>
													<div class="col-md-3"><button class="btn btn-block btn-primary" data-toggle="modal" data-target="#createsshpf" disabled>SSHPF</button></div>
													<div class="col-md-3"><button class="btn btn-block btn-primary" data-toggle="modal" data-target="#createtlsa" disabled>TLSA</button></div>
												</div>
											</div>
											<hr>
											<div class="col-md-12">	
												</br><p class="title">Champs mails</p>
												<div class="row">
													<div class="col-md-3"><button class="btn btn-block btn-primary" data-toggle="modal" data-target="#createmx">MX</button></div>
													<div class="col-md-3"><button class="btn btn-block btn-primary" data-toggle="modal" data-target="#createspf">SPF</button></div>
													<div class="col-md-3"><button class="btn btn-block btn-primary" data-toggle="modal" data-target="#createdkim" disabled>DKIM</button></div>
													<div class="col-md-3"><button class="btn btn-block btn-primary" data-toggle="modal" data-target="#createdmarc" disabled>DMARC</button></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<script>
						// Enregistrement type A
						function sousdomainea() {
							var x = document.getElementById("getsousdomainea").value;
							document.getElementById("sousdomainea").innerHTML = x;
						}
						function ttla() {
							var x = document.getElementById("getttla").value;
							document.getElementById("ttla").innerHTML = x;
						}
						function ciblea() {
							var x = document.getElementById("getciblea").value;
							document.getElementById("ciblea").innerHTML = x;
						}
						
					</script>
					
					<!-- Enregistrement A -->
					<div class="modal" id="createa" tabindex="-1" role="dialog">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form action="scripts/pages/bindrecord.php" method="POST">
									<div class="modal-header">
										<h5 class="modal-title">Ajouter une entrée à la zone DNS</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										
										<table class="table table-hover">
											<tbody>
												<tr>
													<td> Sous-domaine </td>
													<td><input type="text" class="form-control" name="sousdomaine" id="getsousdomainea" onkeyup="sousdomainea()"></td>
													<td> .<?= $rowZone[0]['domaine'] ?></td>
												</tr>
												<tr>
													<td> TTL </td>
													<td><input type="number" class="form-control" name="ttl" value="60" min="60" max="604800" id="getttla" onkeyup="ttla()"></input></td>
													<td> S</td>
												</tr>
												<tr>
													<td> Cible <font color="red">*</font></td>
													<td colspan="2"><input type="text" class="form-control" name="cible" id="getciblea" onkeyup="ciblea()" required></input></td>
												</tr>
											</tbody>
										</table>
										
										<p>Le champ A actuellement généré est le suivant :</p>
										<div class="card">
											<div class="card-body">
												<code><span id="sousdomainea"></span> <span id="ttla"></span> IN A <span id="ciblea"></span></code>
											</div>
										</div>
										
									</div>
									<div class="modal-footer">
										<input type="hidden" name="action" value="create" readonly />
										<input type="hidden" name="type" value="A" readonly />
										<input type="hidden" name="domaine" value="<?= $rowZone[0]['domaine'] ?>" readonly />
										<button type="submit" class="btn btn-primary">Valider</button>
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					
					<script>
						// Enregistrement type AAAAA
						function sousdomaineaaaa() {
							var x = document.getElementById("getsousdomaineaaaa").value;
							document.getElementById("sousdomaineaaaa").innerHTML = x;
						}
						function ttlaaaa() {
							var x = document.getElementById("getttlaaaa").value;
							document.getElementById("ttlaaaa").innerHTML = x;
						}
						function cibleaaaa() {
							var x = document.getElementById("getcibleaaaa").value;
							document.getElementById("cibleaaaa").innerHTML = x;
						}
					</script>
					
					<!-- Enregistrement AAAA -->
					<div class="modal" id="createaaaa" tabindex="-1" role="dialog">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form action="scripts/pages/bindrecord.php" method="POST">
									<div class="modal-header">
										<h5 class="modal-title">Ajouter une entrée à la zone DNS</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										
										<table class="table table-hover">
											<tbody>
												<tr>
													<td> Sous domaine </td>
													<td><input type="text" class="form-control" name="sousdomaine" id="getsousdomaineaaaa" onkeyup="sousdomaineaaaa()"></td>
													<td> .<?= $rowZone[0]['domaine'] ?></td>
												</tr>
												<tr>
													<td> TTL </td>
													<td><input type="number" class="form-control" name="ttl" value="60" min="60" max="604800" id="getttlaaaa" onkeyup="ttlaaaa()"></input></td>
													<td> S</td>
												</tr>
												<tr>
													<td> Cible <font color="red">*</font></td>
													<td colspan="2"><input type="text" class="form-control" name="cible" id="getcibleaaaa" onkeyup="cibleaaaa()" required></input></td>
												</tr>
											</tbody>
										</table>
										
										<p>Le champ AAAA actuellement généré est le suivant :</p>
										<div class="card">
											<div class="card-body">
												<code><span id="sousdomaineaaaa"></span> <span id="ttlaaaa"></span> IN AAAA <span id="cibleaaaa"></span></code>
											</div>
										</div>
										
									</div>
									<div class="modal-footer">
										<input type="hidden" name="action" value="create" readonly />
										<input type="hidden" name="type" value="AAAA" readonly />
										<input type="hidden" name="domaine" value="<?= $rowZone[0]['domaine'] ?>" readonly />
										<button type="submit" class="btn btn-primary">Valider</button>
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					
					<script>
						// Enregistrement type NS
						function sousdomainens() {
							var x = document.getElementById("getsousdomainens").value;
							document.getElementById("sousdomainens").innerHTML = x;
						}
						function ttlns() {
							var x = document.getElementById("getttlns").value;
							document.getElementById("ttlns").innerHTML = x;
						}
						function ciblens() {
							var x = document.getElementById("getciblens").value;
							document.getElementById("ciblens").innerHTML = x;
							
							// Récupération du dernier caractère de la cible
							var lastChar = x.substr(x.length - 1); 
							
							// Si la cible est vide, on affiche rien
							if(x == ''){
								document.getElementById("ciblereellens").innerHTML = '';
								
							// Si la cible se termine pas par un point, on affiche le message
							}else if (lastChar != '.'){
								document.getElementById("ciblereellens").innerHTML = 'Cible réelle "' + x + '.<?php echo $rowZone[0]['domaine']; ?>"';
								
							// Si la cible se termine par un point on n'affiche pas le message
							}else{
								document.getElementById("ciblereellens").innerHTML = '';
							}
								
						}
					</script>
					
					<!-- Enregistrement NS -->
					<div class="modal" id="createns" tabindex="-1" role="dialog">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form action="scripts/pages/bindrecord.php" method="POST">
									<div class="modal-header">
										<h5 class="modal-title">Ajouter une entrée à la zone DNS</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										
										<table class="table table-hover">
											<tbody>
												<tr>
													<td> Sous domaine <font color="red">*</font></td>
													<td><input type="text" class="form-control" name="sousdomaine" id="getsousdomainens" onkeyup="sousdomainens()" required></td>
													<td> .<?= $rowZone[0]['domaine'] ?></td>
												</tr>
												<tr>
													<td> TTL </td>
													<td><input type="number" class="form-control" name="ttl" value="60" min="60" max="604800" id="getttlns" onkeyup="ttlns()"></input></td>
													<td> S</td>
												</tr>
												<tr>
													<td> Cible <font color="red">*</font></td>
													<td colspan="2"><input type="text" class="form-control" name="cible" id="getciblens" onkeyup="ciblens()" required></input></td>
												</tr>
												<tr>
													<td colspan="3"><span id="ciblereellens"></span></td>
												</tr>
											</tbody>
										</table>
										
										<p>Le champ NS actuellement généré est le suivant :</p>
										<div class="card">
											<div class="card-body">
												<code><span id="sousdomainens"></span> <span id="ttlns"></span> IN NS <span id="ciblens"></span></code>
											</div>
										</div>
										
									</div>
									<div class="modal-footer">
										<input type="hidden" name="action" value="create" readonly />
										<input type="hidden" name="type" value="NS" readonly />
										<input type="hidden" name="domaine" value="<?= $rowZone[0]['domaine'] ?>" readonly />
										<button type="submit" class="btn btn-primary">Valider</button>
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					
					<script>
						// Enregistrement type CNAME
						function sousdomainecname() {
							var x = document.getElementById("getsousdomainecname").value;
							document.getElementById("sousdomainecname").innerHTML = x;
						}
						function ttlcname() {
							var x = document.getElementById("getttlcname").value;
							document.getElementById("ttlcname").innerHTML = x;
						}
						function ciblecname() {
							var x = document.getElementById("getciblecname").value;
							document.getElementById("ciblecname").innerHTML = x;
							
							// Récupération du dernier caractère de la cible
							var lastChar = x.substr(x.length - 1); 
							
							// Si la cible est vide, on affiche rien
							if(x == ''){
								document.getElementById("ciblereellecname").innerHTML = '';
								
							// Si la cible se termine pas par un point, on affiche le message
							}else if (lastChar != '.'){
								document.getElementById("ciblereellecname").innerHTML = 'Cible réelle "' + x + '.<?php echo $rowZone[0]['domaine']; ?>"';
								
							// Si la cible se termine par un point on n'affiche pas le message
							}else{
								document.getElementById("ciblereellecname").innerHTML = '';
							}
							
						}
					</script>
					
					<!-- Enregistrement CNAME -->
					<div class="modal" id="createcname" tabindex="-1" role="dialog">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form action="scripts/pages/bindrecord.php" method="POST">
									<div class="modal-header">
										<h5 class="modal-title">Ajouter une entrée à la zone DNS</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										
										<table class="table table-hover table-striped">
											<tbody>
												<tr>
													<td> Sous domaine </td>
													<td><input type="text" class="form-control" name="sousdomaine" id="getsousdomainecname" onkeyup="sousdomainecname()"></td>
													<td> .<?= $rowZone[0]['domaine'] ?></td>
												</tr>
												<tr>
													<td> TTL </td>
													<td><input type="number" class="form-control" name="ttl" value="60" min="60" max="604800" id="getttlcname" onkeyup="ttlcname()"></input></td>
													<td> S</td>
												</tr>
												<tr>
													<td> Cible <font color="red">*</font></td>
													<td colspan="2"><input type="text" name="cible" class="form-control" id="getciblecname" onkeyup="ciblecname()" required></input></td>
												</tr>
												<tr>
													<td colspan="3"><span id="ciblereellecname"></span></td>
												</tr>
											</tbody>
										</table>
										
										<p>Le champ CNAME actuellement généré est le suivant :</p>
										<div class="card">
											<div class="card-body">
												<code><span id="sousdomainecname"></span> <span id="ttlcname"></span> IN CNAME <span id="ciblecname"></span></code>
											</div>
										</div>
										
									</div>
									<div class="modal-footer">
										<input type="hidden" name="action" value="create" readonly />
										<input type="hidden" name="type" value="CNAME" readonly />
										<input type="hidden" name="domaine" value="<?= $rowZone[0]['domaine'] ?>" readonly />
										<button type="submit" class="btn btn-primary">Valider</button>
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					
					<!-- Enregistrement CAA -->
					<div class="modal" id="createcaa" tabindex="-1" role="dialog">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form action="scripts/pages/bindrecord.php" method="POST">
									<div class="modal-header">
										<h5 class="modal-title">Ajouter une entrée à la zone DNS</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										
									</div>
									<div class="modal-footer">
										<input type="hidden" name="action" value="create" readonly />
										<input type="hidden" name="type" value="CAA" readonly />
										<input type="hidden" name="domaine" value="<?= $rowZone[0]['domaine'] ?>" readonly />
										<button type="submit" class="btn btn-primary">Valider</button>
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					
					<script>
						// Enregistrement type TXT
						function sousdomainetxt() {
							var x = document.getElementById("getsousdomainetxt").value;
							document.getElementById("sousdomainetxt").innerHTML = x;
						}
						function ttltxt() {
							var x = document.getElementById("getttltxt").value;
							document.getElementById("ttltxt").innerHTML = x;
						}
						function cibletxt() {
							var x = document.getElementById("getcibletxt").value;
							document.getElementById("cibletxt").innerHTML = x;
						}
					</script>
					
					<!-- Enregistrement TXT -->
					<div class="modal" id="createtxt" tabindex="-1" role="dialog">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form action="scripts/pages/bindrecord.php" method="POST">
									<div class="modal-header">
										<h5 class="modal-title">Ajouter une entrée à la zone DNS</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										
										<table class="table table-hover">
											<tbody>
												<tr>
													<td> Sous domaine </td>
													<td><input type="text" class="form-control" name="sousdomaine" id="getsousdomainetxt" onkeyup="sousdomainetxt()"></td>
													<td> .<?= $rowZone[0]['domaine'] ?></td>
												</tr>
												<tr>
													<td> TTL </td>
													<td><input type="number" class="form-control" name="ttl" value="60" min="60" max="604800" id="getttltxt" onkeyup="ttltxt()"></input></td>
													<td> S.</td>
												</tr>
												<tr>
													<td> Valeur <font color="red">*</font></td>
													<td colspan="2"><input type="text" name="valeur" class="form-control" id="getcibletxt" onkeyup="cibletxt()" required></input></td>
												</tr>
											</tbody>
										</table>
										
										<p>Le champ TXT actuellement généré est le suivant :</p>
										<div class="card">
											<div class="card-body">
												<code><span id="sousdomainetxt"></span> <span id="ttltxt"></span> IN TXT "<span id="cibletxt"></span>"</code>
											</div>
										</div>
										
									</div>
									<div class="modal-footer">
										<input type="hidden" name="action" value="create" readonly />
										<input type="hidden" name="type" value="TXT" readonly />
										<input type="hidden" name="domaine" value="<?= $rowZone[0]['domaine'] ?>" readonly />
										<button type="submit" class="btn btn-primary">Valider</button>
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					
					<!-- Enregistrement NAPTR -->
					<div class="modal" id="createnaptr" tabindex="-1" role="dialog">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form action="scripts/pages/bindrecord.php" method="POST">
									<div class="modal-header">
										<h5 class="modal-title">Ajouter une entrée à la zone DNS</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										
									</div>
									<div class="modal-footer">
										<input type="hidden" name="action" value="create" readonly />
										<input type="hidden" name="type" value="NAPTR" readonly />
										<input type="hidden" name="domaine" value="<?= $rowZone[0]['domaine'] ?>" readonly />
										<button type="submit" class="btn btn-primary">Valider</button>
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					
					<script>
						// Enregistrement type SRV
						function sousdomainesrv() {
							var x = document.getElementById("getsousdomainesrv").value;
							document.getElementById("sousdomainesrv").innerHTML = x;
						}
						function ttlsrv() {
							var x = document.getElementById("getttlsrv").value;
							document.getElementById("ttlsrv").innerHTML = x;
						}
						function prioritesrv() {
							var x = document.getElementById("getprioritesrv").value;
							document.getElementById("prioritesrv").innerHTML = x;
						}
						function poidssrv() {
							var x = document.getElementById("getpoidssrv").value;
							document.getElementById("poidssrv").innerHTML = x;
						}
						function portsrv() {
							var x = document.getElementById("getportsrv").value;
							document.getElementById("portsrv").innerHTML = x;
						}
						function ciblesrv() {
							var x = document.getElementById("getciblesrv").value;
							document.getElementById("ciblesrv").innerHTML = x;
							
							// Récupération du dernier caractère de la cible
							var lastChar = x.substr(x.length - 1); 
							
							// Si la cible est vide, on affiche rien
							if(x == ''){
								document.getElementById("ciblereellesrv").innerHTML = '';
								
							// Si la cible se termine pas par un point, on affiche le message
							}else if (lastChar != '.'){
								document.getElementById("ciblereellesrv").innerHTML = 'Cible réelle "' + x + '.<?php echo $rowZone[0]['domaine']; ?>"';
								
							// Si la cible se termine par un point on n'affiche pas le message
							}else{
								document.getElementById("ciblereellesrv").innerHTML = '';
							}
							
						}
					</script>
					
					<!-- Enregistrement SRV -->
					<div class="modal" id="createsrv" tabindex="-1" role="dialog">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form action="scripts/pages/bindrecord.php" method="POST">
									<div class="modal-header">
										<h5 class="modal-title">Ajouter une entrée à la zone DNS</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										
										<table class="table table-hover">
											<tbody>
												<tr>
													<td> Sous domaine </td>
													<td><input type="text" class="form-control" name="sousdomaine" id="getsousdomainesrv" onkeyup="sousdomainesrv()"></td>
													<td> .<?= $rowZone[0]['domaine'] ?></td>
												</tr>
												<tr>
													<td> TTL </td>
													<td><input type="number" class="form-control" name="ttl" value="60" min="60" max="604800" id="getttlsrv" onkeyup="ttlsrv()"></input></td>
													<td> S</td>
												</tr>
												<tr>
													<td> Priorité <font color="red">*</font></td>
													<td><input type="number" class="form-control" name="priorite" id="getprioritesrv" onkeyup="prioritesrv()" required></input></td>
													<td> S</td>
												</tr>
												<tr>
													<td> Poids <font color="red">*</font></td>
													<td><input type="number" class="form-control" name="poids" id="getpoidssrv" onkeyup="poidssrv()" required></input></td>
													<td> S</td>
												</tr>
												<tr>
													<td> Port <font color="red">*</font></td>
													<td><input type="number" class="form-control" name="port" id="getportsrv" onkeyup="portsrv()" required></input></td>
													<td> S</td>
												</tr>
												<tr>
													<td> Cible <font color="red">*</font></td>
													<td colspan="2"><input type="text" class="form-control" name="cible" id="getciblesrv" onkeyup="ciblesrv()" required></input></td>
												</tr>
												<tr>
													<td colspan="3"><span id="ciblereellesrv"></span></td>
												</tr>
											</tbody>
										</table>
										
										<p>Le champ SRV actuellement généré est le suivant :</p>
										<div class="card">
											<div class="card-body">
												<code><span id="sousdomainesrv"></span> <span id="ttlsrv"></span> IN SRV <span id="prioritesrv"></span> <span id="poidssrv"></span> <span id="portsrv"></span> <span id="ciblesrv"></span></code>
											</div>
										</div>
										
									</div>
									<div class="modal-footer">
										<input type="hidden" name="action" value="create" readonly />
										<input type="hidden" name="type" value="SRV" readonly />
										<input type="hidden" name="domaine" value="<?= $rowZone[0]['domaine'] ?>" readonly />
										<button type="submit" class="btn btn-primary">Valider</button>
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					
					<!-- Enregistrement LOC -->
					<div class="modal" id="createloc" tabindex="-1" role="dialog">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form action="scripts/pages/bindrecord.php" method="POST">
									<div class="modal-header">
										<h5 class="modal-title">Ajouter une entrée à la zone DNS</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										
									</div>
									<div class="modal-footer">
										<input type="hidden" name="action" value="create" readonly />
										<input type="hidden" name="type" value="LOC" readonly />
										<input type="hidden" name="domaine" value="<?= $rowZone[0]['domaine'] ?>" readonly />
										<button type="submit" class="btn btn-primary">Valider</button>
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					
					<!-- Enregistrement SSHPF -->
					<div class="modal" id="createsshpf" tabindex="-1" role="dialog">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form action="scripts/pages/bindrecord.php" method="POST">
									<div class="modal-header">
										<h5 class="modal-title">Ajouter une entrée à la zone DNS</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										
									</div>
									<div class="modal-footer">
										<input type="hidden" name="action" value="create" readonly />
										<input type="hidden" name="type" value="SSHPF" readonly />
										<input type="hidden" name="domaine" value="<?= $rowZone[0]['domaine'] ?>" readonly />
										<button type="submit" class="btn btn-primary">Valider</button>
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					
					<!-- Enregistrement TLSA -->
					<div class="modal" id="createtlsa" tabindex="-1" role="dialog">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form action="scripts/pages/bindrecord.php" method="POST">
									<div class="modal-header">
										<h5 class="modal-title">Ajouter une entrée à la zone DNS</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										
									</div>
									<div class="modal-footer">
										<input type="hidden" name="action" value="create" readonly />
										<input type="hidden" name="type" value="TLSA" readonly />
										<input type="hidden" name="domaine" value="<?= $rowZone[0]['domaine'] ?>" readonly />
										<button type="submit" class="btn btn-primary">Valider</button>
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					
					<script>
						// Enregistrement type MX
						function sousdomainemx() {
							var x = document.getElementById("getsousdomainemx").value;
							document.getElementById("sousdomainemx").innerHTML = x;
						}
						function ttlmx() {
							var x = document.getElementById("getttlmx").value;
							document.getElementById("ttlmx").innerHTML = x;
						}
						function prioritemx() {
							var x = document.getElementById("getprioritemx").value;
							document.getElementById("prioritemx").innerHTML = x;
						}
						function ciblemx() {
							var x = document.getElementById("getciblemx").value;
							document.getElementById("ciblemx").innerHTML = x;
							
							// Récupération du dernier caractère de la cible
							var lastChar = x.substr(x.length - 1); 
							
							// Si la cible est vide, on affiche rien
							if(x == ''){
								document.getElementById("ciblereellemx").innerHTML = '';
								
							// Si la cible se termine pas par un point, on affiche le message
							}else if (lastChar != '.'){
								document.getElementById("ciblereellemx").innerHTML = 'Cible réelle "' + x + '.<?php echo $rowZone[0]['domaine']; ?>"';
								
							// Si la cible se termine par un point on n'affiche pas le message
							}else{
								document.getElementById("ciblereellemx").innerHTML = '';
							}
							
						}
					</script>
					
					<!-- Enregistrement MX -->
					<div class="modal" id="createmx" tabindex="-1" role="dialog">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form action="scripts/pages/bindrecord.php" method="POST">
									<div class="modal-header">
										<h5 class="modal-title">Ajouter une entrée à la zone DNS</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										
										<table class="table table-hover">
											<tbody>
												<tr>
													<td> Sous domaine </td>
													<td><input type="text" class="form-control" name="sousdomaine" id="getsousdomainemx" onkeyup="sousdomainemx()"></td>
													<td> .<?= $rowZone[0]['domaine'] ?></td>
												</tr>
												<tr>
													<td> TTL </td>
													<td><input type="number" class="form-control" name="ttl" value="60" min="60" max="604800" id="getttlmx" onkeyup="ttlmx()"></input></td>
													<td> S.</td>
												</tr>
												<tr>
													<td> Priorité <font color="red">*</font></td>
													<td><input type="number" class="form-control" name="priorite" id="getprioritemx" onkeyup="prioritemx()" required></input></td>
													<td> S</td>
												</tr>
												<tr>
													<td> Cible <font color="red">*</font></td>
													<td colspan="2"><input type="text" class="form-control" name="cible" id="getciblemx" onkeyup="ciblemx()" required></input></td>
												</tr>
												<tr>
													<td colspan="3"><span id="ciblereellemx"></span></td>
												</tr>
											</tbody>
										</table>
										
										<p>Le champ MX actuellement généré est le suivant :</p>
										<div class="card">
											<div class="card-body">
												<code><span id="sousdomainemx"></span> <span id="ttlmx"></span> IN MX <span id="prioritemx"></span> <span id="ciblemx"></span></code>
											</div>
										</div>
										
									</div>
									<div class="modal-footer">
										<input type="hidden" name="action" value="create" readonly />
										<input type="hidden" name="type" value="MX" readonly />
										<input type="hidden" name="domaine" value="<?= $rowZone[0]['domaine'] ?>" readonly />
										<button type="submit" class="btn btn-primary">Valider</button>
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					
					<script>
						// Enregistrement type SPF
						function sousdomainespf() {
							var x = document.getElementById("getsousdomainespf").value;
							document.getElementById("sousdomainespf").innerHTML = x;
						}
						function ttlspf() {
							var x = document.getElementById("getttlspf").value;
							document.getElementById("ttlspf").innerHTML = x;
						}
						function includespf() {
							var x = document.getElementById("getincludespf").value;
							document.getElementById("includespf").innerHTML = "include:"+x;
						}
						
						function confirmspf() {
							if($('#getconfirmspf1').is(':checked')){
								document.getElementById("confirmspf").innerHTML = "-all";
							}else if($('#getconfirmspf2').is(':checked')){
								document.getElementById("confirmspf").innerHTML = "~all";
							}else if($('#getconfirmspf3').is(':checked')){
								document.getElementById("confirmspf").innerHTML = "?all";
							}
							
						}
						
						function unspf(){
							var x = document.getElementById("getunspf").value;
							document.getElementById("unspf").innerHTML = "a";
						}
						function unspf1(){
							var x = document.getElementById("getunspf1").value;
							document.getElementById("unspf").innerHTML = "";
						}
						
						function deuxspf(){
							var x = document.getElementById("getdeuxspf").value;
							document.getElementById("deuxspf").innerHTML = "mx";
						}
						function deuxspf1(){
							var x = document.getElementById("getdeuxspf1").value;
							document.getElementById("deuxspf").innerHTML = "";
						}
						
						function troisspf(){
							var x = document.getElementById("gettroisspf").value;
							document.getElementById("troisspf").innerHTML = "ptr";
						}
						function troisspf1(){
							var x = document.getElementById("gettroisspf1").value;
							document.getElementById("troisspf").innerHTML = "";
						}
						
					</script>
					
					<!-- Enregistrement SPF -->
					<div class="modal" id="createspf" tabindex="-1" role="dialog">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form action="scripts/pages/bindrecord.php" method="POST">
									<div class="modal-header">
										<h5 class="modal-title">Ajouter une entrée à la zone DNS</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										
										<table class="table table-hover">
											<tbody>
												<tr>
													<td> Sous domaine : </td>
													<td><input type="text" class="form-control" name="sousdomaine" id="getsousdomainespf" onkeyup="sousdomainespf()"></td>
													<td> .<?= $rowZone[0]['domaine'] ?></td>
												</tr>
												<tr>
													<td> TTL : </td>
													<td><input type="number" class="form-control" name="ttl" value="60" min="60" max="604800" id="getttlspf" onkeyup="ttlspf()"></input></td>
													<td> S.</td>
												</tr>
											</tbody>
										</table>
										<table class="table table-hover">
											<tbody>
												<tr>
													<td>Autoriser l'IP de <?= $rowZone[0]['domaine'] ?> à envoyer des emails ?</td>
													<td colspan="2"><input type="radio" name="radio1" id="getunspf" OnChange="unspf()" value="a"> OUI <input type="radio" name="radio1" id="getunspf1" OnChange="unspf1()" value=""> NON</td>
												</tr>
												<tr>
													<td>Autoriser les serveurs MX de <?= $rowZone[0]['domaine'] ?> à envoyer des emails ?</td>
													<td colspan="2"><input type="radio" name="radio2" id="getdeuxspf" OnChange="deuxspf()" value="mx"> OUI <input type="radio" name="radio2" id="getdeuxspf1" OnChange="deuxspf1()" value=""> NON</td>
												</tr>
												<tr>
													<td>Autoriser tous les serveurs dont le nom se termine par <?= $rowZone[0]['domaine'] ?> à envoyer des emails ? (Cette option n'est pas recommandée)</td>
													<td colspan="2"><input type="radio" name="radio3" id="gettroisspf" OnChange="troisspf()" value="ptr"> OUI <input type="radio" name="radio3" id="gettroisspf1" OnChange="troisspf1()" value=""> NON</td>
												</tr>
												<tr>
													<td>Est-ce que le courrier de <?= $rowZone[0]['domaine'] ?> provient originellement d'autres serveurs appartenant à d'autres domaines (ex.: votre FAI) ?</td>
													<td colspan="2"><input type="text" name="include" class="form-control" id="getincludespf" onkeyup="includespf()"></input></td>
												</tr>
												<tr>
													<td colspan="3">Est-ce que les informations que vous avez indiquées décrivent tous les hôtes qui envoient du courrier de <?= $rowZone[0]['domaine'] ?> ?</td>
												</tr>
												<tr>
													<td colspan="3">
														<input type="radio" name="radio4" id="getconfirmspf1" OnChange="confirmspf()" value="-all"> Oui, je suis sûr
													</td>
												</tr>
												<tr>
													<td colspan="3">
														<input type="radio" name="radio4" id="getconfirmspf2" OnChange="confirmspf()" value="~all"> Oui, mais utiliser le safe mode
													</td>
												</tr>
												<tr>
													<td colspan="3">
														<input type="radio" name="radio4" id="getconfirmspf3" OnChange="confirmspf()" value="?all"> Non
													</td>
												</tr>
												
											</tbody>
										</table>
										
										<p>Le champ SPF actuellement généré est le suivant :</p>
										<div class="card">
											<div class="card-body">
												<code><span id="sousdomainespf"></span> <span id="ttlspf"></span> IN TXT "v=spf1 <span id="unspf"></span> <span id="deuxspf"></span> <span id="troisspf"></span> <span id="includespf"></span> <span id="confirmspf"></span>"</code>
											</div>
										</div>
										
									</div>
									<div class="modal-footer">
										<input type="hidden" name="action" value="create" readonly />
										<input type="hidden" name="type" value="SPF" readonly />
										<input type="hidden" name="domaine" value="<?= $rowZone[0]['domaine'] ?>" readonly />
										<button type="submit" class="btn btn-primary">Valider</button>
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					
					<!-- Enregistrement DKIM -->
					<div class="modal" id="createdkim" tabindex="-1" role="dialog">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form action="scripts/pages/bindrecord.php" method="POST">
									<div class="modal-header">
										<h5 class="modal-title">Ajouter une entrée à la zone DNS</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										
									</div>
									<div class="modal-footer">
										<input type="hidden" name="action" value="create" readonly />
										<input type="hidden" name="type" value="DKIM" readonly />
										<input type="hidden" name="domaine" value="<?= $rowZone[0]['domaine'] ?>" readonly />
										<button type="submit" class="btn btn-primary">Valider</button>
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					
					<!-- Enregistrement DMARC -->
					<div class="modal" id="createdmarc" tabindex="-1" role="dialog">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form action="scripts/pages/bindrecord.php" method="POST">
									<div class="modal-header">
										<h5 class="modal-title">Ajouter une entrée à la zone DNS</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										
									</div>
									<div class="modal-footer">
										<input type="hidden" name="action" value="create" readonly />
										<input type="hidden" name="type" value="DMARC" readonly />
										<input type="hidden" name="domaine" value="<?= $rowZone[0]['domaine'] ?>" readonly />
										<button type="submit" class="btn btn-primary">Valider</button>
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					