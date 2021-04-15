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


	
	<div class="table100">
		<!--div qui contient la nav qui est déterminée par le type d'utilisateur-->
		<div class="divnav">
			<div class="diviut positionNav">
				<?php require '../autre/nav.php'; ?>
			</div>
		</div>

		<div class="diviut">

			<h1>Mes séances</h1>
			<hr/>

			<table align="center">

				<th>Libelle</th>
				<th>Date de création</th>
				<th>Type</th>
				<th>Nom groupe</th>

				<?php

					$request = "SELECT id_seance, code, dte_crea, type, nom_groupe FROM seance WHERE id_utilisateur='".$_SESSION['id_utilisateur']."'";
					$reponse = $bdd->query($request);
					
					while($seance = $reponse->fetch())
					{
						$tpl = $twig->loadTemplate( "seance.tpl" );
						echo $tpl->render( array("id_seance" => $seance[0], "code" => $seance[1], "dte_crea" => $seance[2], "type" => $seance[3],
												 "nom_groupe" => $seance[4]) );
					}
				?>
			
			</table>

		</div>
	
	</div>

<?php
	pied();

	if(isset($_POST['ajouter']))
	{
		if( isset($_POST['id_seance']) )
		{
			if( !empty($_POST['id_seance']) )
			{
				$_SESSION['id_seance'] = $_POST['id_seance'];
				header("location: ../ajouter/ajouter_evenement.php");
			}
		}
	}

	if(isset($_POST['supprimer']))
	{
		if( isset($_POST['id_seance']) )
		{
			if( !empty($_POST['id_seance']) )
			{
				$delete = $bdd->prepare("DELETE from seance WHERE id_seance=?");
				$delete->execute(array($_POST['id_seance']));
				header("location: mes_seances.php");
			}
		}
	}
?>