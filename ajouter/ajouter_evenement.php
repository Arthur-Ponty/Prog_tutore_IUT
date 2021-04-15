<?php
	include "../inc/fctAux.inc.php";
	include "../inc/DB.inc.php";

	enTete("- Departement Informatique", "projet");
	
	$bdd = connexionBdd();
	
	require_once( "../Twig/lib/Twig/Autoloader.php" );

	Twig_Autoloader::register();
	$twig = new Twig_Environment( new Twig_Loader_Filesystem("../tpl") );
	$tpl_entete_di           = $twig->loadTemplate( "entete_di.tpl" );
?>

<!DOCTYPE HTML>

	<?php 
		//Affiche l'entête de l'iut
		echo $tpl_entete_di->render(array());
	?>
	
	<!--div qui contient la nav + le contenu de la page -->
	<div style="display:table; width:100%">

	
		<!--div qui contient la nav qui est déterminée par le type d'utilisateur-->
		<div class="divnav">
			<div style="margin-right: 10px; padding-top: 10px;" class="diviut">
				<?php require '../autre/nav.php'; ?>
			</div>
		</div>

		<div class="table100">
			<div class="diviut" style="display:table;width:100%">

				<h1>Ajouter un évènement</h1>
				<hr size="5" style="background-color: black;" />

				<table align="center">

					<form action="ajouter_evenement.php" method="post">

						<tr>
							<td><label name="commentaire" id="commentaire">Commentaire</label></td>
							<td>
								<?php
									echo "<input type=\"text\" name=\"commentaire\" maxlength=\"90\" ";

									if($_POST['type'][0] != "fait")
										echo "value=\"".$_POST['commentaire']."\"";
									
									echo ">";
								?>
							</td>
						</tr>

						<tr>
							<td><label name="type" id="type">Type</label></td>
							<td>
								<select name="type[]">
									<?php

										$request = "SELECT libelleMod FROM module WHERE nature='TE' AND code!='-1'";
										$reponse = $bdd->query($request);

										while ($type = $reponse->fetch())
										{
											echo "<option value=\"$type[0]\"";

											if($_POST['type'][0] != "fait" AND $_POST['type'][0] == $type[0])
												echo "selected=\"selected\"";

											echo "> $type[0]";
										}
										   
										$reponse ->closeCursor();

									?>
								</select>
							</td>
						</tr>

						<?php
							if(isset($_POST['type']))
							{
								if($_POST['type'][0] != "fait")
								{
									echo "<tr>\n";
									echo "\t<td><label name=\"duree\" id=\"duree\">Durée</label></td>\n";
									echo "\t<td><input type=\"text\" name=\"duree\"> </td>\n";
									echo "</tr>\n";
									echo "\n";

									echo "<tr>\n";
									echo "\t<td><label name=\"echeance\" id=\"echeance\">Échéance</label></td>\n";
									echo "\t<td><input type=\"date\" name=\"echeance\"></td>\n";
									echo "</tr>\n";
									echo "\n";

									echo "<td>Ajouter une durée(facultative)</td><td>et une date d'échéance</td>";
								}
							}

						?>

						<tr>
							<td></td>
							<td>
								<input type="reset"  name="annuler" value="Annuler">
								<input type="submit" name="valider" value="Valider">
							</td>
						</tr>

					</form>

				</table>

			</div>
		
		<br />

		<div class="diviut" style="display:table;width:100%;">

			<h1>Ajouter une pièce jointe</h1>
			<hr size="5" style="background-color: black;" />

			<table align="center">

				<form action="upload.php" method="post" enctype="multipart/form-data">

					<tr>
						<td><label name="evenement" id="evenement">Type</label></td>
						<td>
							<select name="evenement[]">
								<?php

									$request = "SELECT * FROM evenement WHERE id_seance='".$_SESSION['id_seance']."'";
									$reponse = $bdd->query($request);

									while ($evenement = $reponse->fetch())
									{
										echo "<option value=\"$evenement[0]\"> $evenement[1]";
									}
									   
									$reponse ->closeCursor();

								?>
							</select>
						</td>
					</tr>

					<tr>
						<td><label name="fichier" id="fichier">Pièce jointe</label></td>
						<td><input type="file" name="fichier"> </td>
					</tr>

					<tr>
						<td></td>
						<td>
							<input type="reset"  name="annuler" value="Annuler">
							<input type="submit" name="valider" value="Valider">
						</td>
						<td>
							<?php
								if(!empty($_GET['erreur'])) echo $erreur;
							?>
						</td>
					</tr>

				</form>

			</table>

		</div>
		<br />
		<?php require 'modifEvenement.php'; ?>
	</div>
</div>
<?php
	pied();

	if(isset($_POST['commentaire'], $_POST['type']))
	{
	    if(!empty($_POST['commentaire']) AND !empty($_POST['type']))
	    {
	    	if($_POST['type'][0] == "fait" OR !empty($_POST['echeance']))
	    	{
				// On récupère les information depuis les <input>
				$commentaire = $_POST['commentaire'];
				$type        = $_POST['type'       ];
				$duree       = $_POST['duree'      ];
				$date        = $_POST['echeance'   ];

				$id_seance   = $_SESSION['id_seance'];

				if(empty($date )) $date  = "0000-00-00";
				if(empty($duree)) $duree = null;

				$request = "SELECT libelleMod FROM module WHERE nature='NBE'";
				$reponse = $bdd->query($request)->fetch();

				$request  = "SELECT COUNT(*) FROM evenement WHERE id_seance='".$id_seance."'";
				$reponse1 = $bdd->query($request)->fetch();
				
				if($reponse[0] > $reponse1[0])
				{
					$insertion = $bdd->prepare("INSERT INTO evenement(libelleEnv, type, duree, dte_echeance, id_seance) VALUES (?, ?, ?, ?, ?)");
					// on exécute l'insertion du tuple

					$insertion->execute(array($commentaire, $type[0], $duree, $date, $id_seance));

					header('Refresh: 0.01;URL=ajouter_evenement.php');
				}
				else
				{
					echo "Il y a trop d'évènement pour la séance";
				}
			}
	    }
	}
    
?>