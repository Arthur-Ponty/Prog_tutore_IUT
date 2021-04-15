<?php
	include "../inc/fctAux.inc.php";
	include "../inc/DB.inc.php";

	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	require_once( "../Twig/lib/Twig/Autoloader.php" );
	Twig_Autoloader::register();

	$twig = new Twig_Environment( new Twig_Loader_Filesystem("../tpl"));

	$tpl_entete_di           = $twig->loadTemplate( "entete_di.tpl" );

	enTete("- Departement Informatique", "projet");
	$bdd = connexionBdd();
?>

<!DOCTYPE HTML>
	
	<?php 
		//Affiche l'entête de l'iut
		echo $tpl_entete_di->render(array());
	?>
	
	<div class="table100">
		<!--div qui contient la nav qui est déterminée par le type d'utilisateur-->
		<div class="divnav">
			<div class="diviut positionNav">
				<?php require '../autre/nav.php'; ?>
			</div>
		</div>

		<div class="table100">
			<div class="diviut">
			
				<h1>Ajouter une séance</h1>
				<hr/>

				<table align="center">

					<form action="ajouter_seance.php" method="post">

						<tr>
							<td><label name="code" id="code" class="tailleLib">Module</label></td>
							<td>
								<select name="code[]" class="tailleLib">
									<?php

										$request = "SELECT code, libelleMod FROM assigner NATURAL JOIN module WHERE id_utilisateur='".$_SESSION['id_utilisateur']."' AND nature='MO' AND code!='-1'";
										$reponse = $bdd->query($request);

										if(!empty($reponse))
										{
											while ($code = $reponse->fetch())
												echo "<option value=\"$code[0]\"> $code[1]";
										}
										
										   
										$reponse ->closeCursor();

									?>
							</td>
						</tr>

						<tr>
							<td><label name="type" id="type" class="tailleLib">Type</label></td>
							<td>
								<select name="type[]" class="tailleLib">
									<?php

										$request = "SELECT libelleMod FROM module WHERE nature='TS' AND code!='-1'";
										$reponse = $bdd->query($request);

										while ($type = $reponse->fetch())
										{
											echo "<option value=\"$type[0]\"> $type[0]";
										}
										   
										$reponse ->closeCursor();

									?>
								</select>
							</td>
						</tr>

						<tr>
							<td><label name="groupe" id="groupe" class="tailleLib">Groupe</label></td>
							<td>
								<select name="groupe[]" class="tailleLib">
									<?php

										$request = "SELECT nom_groupe FROM gere NATURAL JOIN groupe WHERE id_utilisateur='".$_SESSION['id_utilisateur']."'";
										$reponse = $bdd->query($request);

										while ($groupe = $reponse->fetch())
										{
											echo "<option value=\"$groupe[0]\"> $groupe[0]";
										}
										   
										$reponse ->closeCursor();

									?>
								</select>
							</td>
						</tr>

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
		</div>
	</div>

<?php
	pied();

	if(isset($_POST['code'], $_POST['type'], $_POST['groupe']))
	{
	    if(!empty($_POST['code']) AND !empty($_POST['type']) AND !empty($_POST['groupe']))
	    {

			// On récupère les information depuis les <input>
			$code   = $_POST['code'  ];
			$type   = $_POST['type'  ];
			$groupe = $_POST['groupe'];
			
			
			date_default_timezone_set("Europe/Paris"); // fuseau horaire

			$date = new DateTime();
			$string = $date->format('Y-m-d');
			$id_utilisateur = $_SESSION['id_utilisateur'];

			$sema = null;

		    $insertion = $bdd->prepare("INSERT INTO seance(code, dte_crea, type, nom_groupe, id_utilisateur, semaphore) VALUES (?, '$string', ?, ?, ?, '$sema')");
		    // on exécute l'insertion du tuple
		    $insertion->execute(array($code[0], $type[0], $groupe[0], $id_utilisateur));
	    }
	    else
	    {
	      $erreur = 'Veuillez remplir tous les champs';
	    }
	}
?>