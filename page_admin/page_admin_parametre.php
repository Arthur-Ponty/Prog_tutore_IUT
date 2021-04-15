<?php	
	include "../inc/fctAux.inc.php";
	include "../inc/DB.inc.php";

	enTete("Paramètre administrateur", "projet");

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
			<div class="diviut" >

				<h1>Paramètre</h1>
				<hr/>

				<form action="page_admin_parametre.php" method="post">

					<table align="center">
						<tr>
							<td><label name="lblEvent" id="evenement">Nombre limite d'évènement</label></td>
							<td><input type="number" min="0" name="libelleModuleEV"></td>
						</tr>
						<tr>
							<td><label name="lblEventPJ" id="pieceJointe">Nombre limite de pièce jointe</label></td>
							<td><input type="number" min="0" name="libelleModulePJ"></td>
						</tr>
						<tr>
							<td><label name="lblEventSE" id="nbSeanceMax">Nombre limite de séance</label></td>
							<td><input type="number" min="0" name="libelleModuleSE"></td>
						</tr>
					</table>
					<table align="center">
						<tr>
							<td>
								<input type="submit" name="modif" value="Valider">
							</td>
						</tr>
					</table>

				</form>
			</div>
		<br>
		
	</div>
	<?php
		pied();
		

		if( isset($_POST['libelleModuleEV']) )
		{
			if( !empty($_POST['libelleModuleEV']))
			{
				$new_libelleMod = htmlspecialchars($_POST['libelleModuleEV']);

				$update = $bdd->prepare("UPDATE module SET libelleMod=? WHERE nature='NBE'");
					
				if(!$update->execute(array($new_libelleMod)))
					echo '<center><p class="erreur">Erreur le nombre d evenement ne peut pas etre modifier peut pas étre modifier !! </p></center>';
			}
			else{echo'<center><p class="erreur">ERREUR</p></center>';}
		}

		if( isset($_POST['libelleModulePJ']) )
		{
			if( !empty($_POST['libelleModulePJ']) )
			{
				$new_libelleModPJ = htmlspecialchars($_POST['libelleModulePJ']);

				$update = $bdd->prepare("UPDATE module SET libelleMod=? WHERE nature='NBPJ'");
					
				if(!$update->execute(array($new_libelleModPJ)))
					echo '<center><p class="erreur">Erreur le nombre de piece jointe ne peut pas étre modifier !! </p></center>';
			}
		}

		if( isset($_POST['libelleModuleSE']) )
		{
			if( !empty($_POST['libelleModuleSE']) )
			{
				$new_libelleModSE = htmlspecialchars($_POST['libelleModuleSE']);

				$update = $bdd->prepare("UPDATE module SET libelleMod=? WHERE nature='NBSE'");
					
				if(!$update->execute(array($new_libelleModSE)))
					echo '<center><p class="erreur">Erreur le nombre de seance ne peut pas étre modifier !! </p></center>';
			}
		}

		
	?>