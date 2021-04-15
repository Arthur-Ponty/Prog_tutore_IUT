<?php
	session_start();

	include "../inc/DB.inc.php";
	include "../inc/fctAux.inc.php";
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

	<div class="diviut">
		<center><h1>Modifier votre mot de passe</h1></center>
		<hr/>
		<br /><br />

		<form action="nouveau_mdp.php" method="post">
			<table>
				<tr>
                    <td><label name="mdp" class="gros">Mot de passe</label></td>
					<td><input type="password" name="mdp" class="gros"></td>
				</tr>

				<tr>
					<td><label name="mdpConfirm" class="gros">Confirmer le mot de passe</label></td>
					<td><input type="password" name="mdpConfirm" class="gros"></td>
				</tr>

				<tr>
					<td>
						<input type="reset" name="effacer" value="Effacer" class="gros">
					</td>

					<td>
						<input type="submit" name="valider" value="Valider" class="gros">
					</td>
				</tr>
			</table>
		</form>
	</div>

<?php

	if( !empty($_POST['mdpConfirm']) AND !empty($_POST['mdp']) )
	{
		//Traitement du mdp
		if(isset($_POST['mdpConfirm'], $_POST['mdp']))
		{
			$mdpConfirm = $_POST['mdpConfirm'];
			$mdp   = $_POST['mdp'  ];
			
			if (!isPasswordStrong($mdp))
			{}
			else
			{
				if($mdp === $mdpConfirm)
				{

					$update = $bdd->prepare("UPDATE utilisateur SET mdp=?, mdp_valide=TRUE where id_utilisateur='".$_SESSION['id_utilisateur']."'");
						
						if(!$update->execute(array(password_hash($mdp, PASSWORD_DEFAULT))))
							echo '<center><p class="erreur">Echec de la mise à jour du mot de passe </p></center>';
						else
							header ("location: ../affichage/tableau_de_bord.php");
				}
				else
				{
					echo '<center><p class="erreur">Les deux mots de passe ne correspondent pas.</p></center>';
				}
			}
		}
	}	

	pied();

?>