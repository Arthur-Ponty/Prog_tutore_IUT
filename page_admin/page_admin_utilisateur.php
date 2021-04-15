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

		<div class="table100">
			<?php require './utilisateur/creeUtilisateur.php'; ?>
			<br >
			<?php require './utilisateur/modifUtilisateur.php'; ?>
		</div>
			
	</div>

<?php
	pied();
?>