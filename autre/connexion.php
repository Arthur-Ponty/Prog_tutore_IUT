<?php
	session_start();
	$_SESSION['id_utilisateur'];
	
	include "../inc/DB.inc.php";
	include "../inc/fctAux.inc.php";
	enTete("- Departement Informatique", "projet");

	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	require_once( "../Twig/lib/Twig/Autoloader.php" );
	Twig_Autoloader::register();

	$twig = new Twig_Environment( new Twig_Loader_Filesystem("../tpl"));

	$tpl_entete_di = $twig->loadTemplate( "entete_di.tpl");

	$bdd = connexionBdd();
?>

<!DOCTYPE HTML>

		<?php
			//Affiche l'entête de l'iut
			echo $tpl_entete_di->render(array());
		?>

	<div class="diviut">
		<center><h1>Page de connexion</h1></center>
		<hr size="5" style="background-color: black;" />
		<br /><br />

		<form action="connexion.php" method="post">
			<table>
				<tr>
					<td><label name="login" id="login" class="gros">Login</label></td>
					<td><input type="text" name="login" class="gros"></td>
				</tr>

				<tr>
					<td><label name="mdp" class="gros">Mot de passe</label></td>
					<td><input type="password" name="mdp" class="gros"></td>
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

	if(isset($_POST['deconnexion']))
		if($_POST['deconnexion'])
			session_destroy();

	if( !empty($_POST['login']) AND !empty($_POST['mdp']) )
	{
			
		if(isset($_POST['login'], $_POST['mdp']))
		{	
			$login = $_POST['login'];
			$mdp   = $_POST['mdp'  ];

			if(isMotDePasseOK($login, $mdp) == 1)
			{
				
				$_SESSION['id_utilisateur'  ] = $login;
				
				$request = "SELECT role FROM utilisateur WHERE id_utilisateur='".$_SESSION['id_utilisateur']."'";
				$reponse = $bdd->query($request);
				
				$_SESSION['role_utilisateur'] = $reponse->fetch()[0];

				$_SESSION['nom_utilisateur'   ] = recupNomUtilisateur($login);
				$_SESSION['prenom_utilisateur'] = recupPrenomUtilisateur($login);

				date_default_timezone_set("Europe/Paris"); // fuseau horaire

                $date = date('Y-m-d'); // Date du jour

                $_SESSION['mois' ] = (int) strftime("%m", strtotime($date));
				$_SESSION['annee'] = strftime("%Y", strtotime($date));
				
				
				$request = "SELECT mdp_valide FROM utilisateur WHERE id_utilisateur='".$_SESSION['id_utilisateur']."'";
				$reponse = $bdd->query($request);
				$valide = $reponse->fetch()[0];
				
				if(!$valide)
					header ("location: nouveau_mdp.php");
				else
					header ("location: ../affichage/tableau_de_bord.php");
			}
			else
				echo '<center><p class="erreur">Identifiant et/ou mot de passe incorrect</p></center>';
		}
	}	

	pied();

?>