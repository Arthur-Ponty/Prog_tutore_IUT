<?php
	include "../inc/DB.inc.php";
	include "../inc/fctAux.inc.php";

	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	require_once( "../Twig/lib/Twig/Autoloader.php" );
	Twig_Autoloader::register();

	$twig = new Twig_Environment( new Twig_Loader_Filesystem("../tpl"));

	$tpl_entete_di           = $twig->loadTemplate( "entete_di.tpl" );
	$tpl_tab_bord_titres     = $twig->loadTemplate( "tab_bord_titres.tpl"    );

	enTete("Département Informatique", "projet");
	$bdd = connexionBdd();
?>

<!DOCTYPE html>

	<?php 
		//Affiche l'entête de l'iut
		echo $tpl_entete_di->render(array());
	?>

	<!--div qui contient la nav + le contenu de la page -->
	<div style="display:table; width :100%">

		<!--div qui contient la nav qui est déterminée par le type d'utilisateur-->
		<div style="display: table-cell; vertical-align: top;">
			<div style="margin-right: 10px; padding-top: 10px;" class="diviut">
				<?php require '../autre/nav.php'; ?>
			</div>
		</div>


		<!--div contenant les formulaires de recherche -->
		<div style="display: table-cell; " class="diviut">

			<center><h1>États des séances</h1></center>

			<table>
				<tr> 
					<td>
						<fieldset>
							<legend>Critères de séance</legend>

							<form action="etats_seance.php" method="post">
								<table>
									<tr>
										<td><label name="module"> Module </label></td>
										<td><input type="text" name="module"></td>
									</tr>
									<tr>
										<td><label name="date" style="width: 200px;">Date de création entre le</label></td>
										<td>
											<table>
												<tr> 
													<td><input type="date" name="date1"></td>
													<td style="width: 40px;">et le</td>
													<td><input type="date" name="date2"></td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td><label name="type"> Type </label></td>
										<td>
											<select name="type[]">
												<?php

													$request = "SELECT libelleMod FROM module WHERE nature='TS' AND code!='-1'";
													$reponse = $bdd->query($request);

													echo "<option value=\"-1\">";
													if(!empty($reponse))
													{
														while ($code = $reponse->fetch())
															echo "<option value=\"$code[0]\"> $code[0]";
													}
													
													   
													$reponse ->closeCursor();

												?>
											</select>
										</td>
									</tr>
									<tr>
										<td><label name="groupe"> Groupe </label></td>
										<td>
											<select name="groupe[]">
												<?php

													$request = "SELECT nom_groupe FROM groupe";
													$reponse = $bdd->query($request);

													echo "<option value=\"-1\">";
													if(!empty($reponse))
													{
														while ($code = $reponse->fetch())
															echo "<option value=\"$code[0]\"> $code[0]";
													}
													
													   
													$reponse ->closeCursor();

												?>
											</select>
										</td>
									</tr>
									<tr>
										<td><label name="proprietaire"> Propriétaire </label></td>
										<td>
											<select name="proprietaire[]">
												<?php

													$request = "SELECT distinct id_utilisateur FROM seance";
													$reponse = $bdd->query($request);

													echo "<option value=\"-1\">";
													if(!empty($reponse))
													{
														while ($code = $reponse->fetch())
														{
															$request  = "SELECT nom, prenom FROM utilisateur WHERE id_utilisateur='".$code[0]."'";
															$reponse1 = $bdd->query($request);

															while($proprietaire = $reponse1->fetch())
																echo "<option value=\"$code[0]\"> $proprietaire[0] $proprietaire[1]";
														}
													}
													   
													$reponse ->closeCursor();

												?>
											</select>
										</td>
									</tr>

									<tr>
										<td colspan="2">
											<input type="submit" name="chercherSeance" value="Chercher">
										</td>
									</tr>
								
								</table>
							</form>

						</fieldset>
					</td>

					<td>
						<fieldset style="width:400px; height:225px">
							<legend>Critères d'évènement</legend>

							<form action="etats_seance.php" method="post">
								<table>
									<tr>
										<td><label name="type"> Type </label></td>
										<td>
											<select name="type[]">
												<?php

													$request = "SELECT libelleMod FROM module WHERE nature='TE' AND code!='-1'";
													$reponse = $bdd->query($request);

													echo "<option value=\"-1\">";
													if(!empty($reponse))
													{
														while ($code = $reponse->fetch())
															echo "<option value=\"$code[0]\"> $code[0]";
													}
													
													   
													$reponse ->closeCursor();

												?>
											</select>
										</td>
									</tr>
									<tr>
										<td><label name="echeance" style="width: 150px;">Échéance entre le</label></td>
										<td>
											<table>
												<tr> 
													<td><input type="date" name="echeance1"></td>
													<td style="width: 40px;">et le</td>
													<td><input type="date" name="echeance2"></td>
												</tr>
											</table>
											
										</td>
									</tr>
								</table>

								<input type="submit" name="chercherEven" value="Chercher">
							</form>

						</fieldset>
					</td>

				</tr>
			</table>

		<br/>

		</div>

	</div>

	<br/>

	<?php
		if(isset($_POST['chercherSeance']))
		{
			if($_POST['chercherSeance'])
			{
				if(isset($_POST['module']) AND isset($_POST['date1']) AND isset($_POST['date2']) AND isset($_POST['type']) AND isset($_POST['groupe']) AND isset($_POST['proprietaire']))
				{
					if(!empty($_POST['module']) OR !empty($_POST['date1']) OR !empty($_POST['date2']) OR !empty($_POST['type']) OR !empty($_POST['groupe']) OR !empty($_POST['proprietaire']))
					{
						echo '<div class="diviut">';
						echo '<table>';

						/* Titres colonnes */
						$tab_colonnes = array("sem", "Module", "Date", "Type", "Groupe", "Professeur", "PJ", "durée hh:mm", "pour le");
						echo $tpl_tab_bord_titres ->render(array("items" => $tab_colonnes));

						$request = "SELECT * FROM seance WHERE ";

						if(!empty($_POST['module'       ])) $request.= "code='".$_POST['module']."' AND "                   ;
						if(!empty($_POST['date1'        ])) $request.= "dte_crea>='".$_POST['date1']."' AND "               ;
						if(!empty($_POST['date2'        ])) $request.= "dte_crea<='".$_POST['date2']."'  AND "              ;
						if($_POST['type'][0]         != -1) $request.= "type='".$_POST['type'][0]."' AND "                  ;
						if($_POST['groupe'][0]       != -1) $request.= "nom_groupe='".$_POST['groupe'][0]."' AND "          ;
						if($_POST['proprietaire'][0] != -1) $request.= "id_utilisateur='".$_POST['proprietaire'][0]."' AND ";
						$request = substr($request, 0, strlen($request)-4);

						recupSeance($request);
					}
				}
			}
		}

		if(isset($_POST['chercherEven']))
		{
			if($_POST['chercherEven'])
			{
				if(isset($_POST['type']) AND isset($_POST['echeance1']) AND isset($_POST['echeance2']))
				{
					if(!empty($_POST['type']) OR !empty($_POST['echeance1']) OR !empty($_POST['echeance2']))
					{
						echo '<div class="diviut">';
						echo '<table>';

						/* Titres colonnes */
						$tab_colonnes = array("sem", "Module", "Date", "Type", "Groupe", "Professeur", "PJ", "durée hh:mm", "pour le");
						echo $tpl_tab_bord_titres ->render(array("items" => $tab_colonnes));

						$request = "SELECT * FROM evenement WHERE ";
						
						if($_POST['type'] == -1       ) $request.= "type='".$_POST['type'][0]."' AND "    ;
						if(!empty($_POST['echeance1'])) $request.= "dte_echeance>='".$_POST['echeance1']."' AND ";
						if(!empty($_POST['echeance2'])) $request.= "dte_echeance<='".$_POST['echeance2']."' AND ";
						$request = substr($request, 0, strlen($request)-4);

						recupEvenement($request);
					}
				}
			}
		}
	?>

		<br/>
<?php
	pied();
?>