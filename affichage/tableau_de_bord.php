<?php
	include "../inc/DB.inc.php";
	include "../inc/fctAux.inc.php";

	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	require_once( "../Twig/lib/Twig/Autoloader.php" );
	Twig_Autoloader::register();

	$twig = new Twig_Environment( new Twig_Loader_Filesystem("../tpl"));

	$tpl_entete_di           = $twig->loadTemplate( "entete_di.tpl"          );
	$tpl_tab_bord_titres     = $twig->loadTemplate( "tab_bord_titres.tpl"    );
	$tpl_tab_bord_seance     = $twig->loadTemplate( "tab_bord_seance.tpl"    );
	$tpl_tab_bord_evenement  = $twig->loadTemplate( "tab_bord_evenement.tpl" );

	enTete("- Departement Informatique", "projet");
?>

<!DOCTYPE html>
		<?php
			//Affiche l'entête de l'iut
			echo $tpl_entete_di->render(array());
		?>

		<!--div qui contient la nav + le contenu de la page -->
		<div class="table100">

			<!--div qui contient la nav qui est déterminée par le type d'utilisateur-->
			<div class="divnav">
				<div class="diviut positionNav">
					<?php require '../autre/nav.php'; ?>
				</div>
			</div>


			<!--div qui contient les séances et les activités auxquelles l'utilisateur peut accéder-->
			<div class="diviut">

				<center><h1>Tableau de bord</h1></center>

				<table>
					<tr>
						<form action="tableau_de_bord.php" method="post">
							<td><input type="submit" name="avant" value="" id="avant"></td>
							<td><center><h3>Journal de bord: <?php echo recupMois() ?></h3></center></td>
							<td><input type="submit" name="apres" value="" id="apres"></td>
						</form>
					</tr>
				</table>

				<table>
					<?php
						if(isset($_POST['avant']) OR isset($_POST['apres']))
							header('Refresh: 0.01;URL=tableau_de_bord.php');
						
						$bdd = connexionBdd();

						/* Titres colonnes */
						$tab_colonnes = array("sem", "Module", "Date", "Type", "Groupe", "Professeur", "PJ", "durée hh:mm", "pour le");
						echo $tpl_tab_bord_titres ->render(array("items" => $tab_colonnes));

						/* Séance */
						$request = "SELECT * FROM seance";
						$reponse = $bdd->query($request);

						while ($seance = $reponse->fetch())
						{
							if( moisCourantOk($seance[2]) AND anneeCouranteOk($seance[2]))
							{
								$request = "SELECT * FROM evenement WHERE id_seance='".$seance[0]."'";
								$reponse1 = $bdd->query($request);

								$utilisateur = recupNomUtilisateur($seance[5])."  ".recupPrenomUtilisateur($seance[5]);
								$couleur     = recupCouleurModule($seance[1]);
								$libelleMod  = recupLibelleMod($seance[1]);

								$tab_parametres = array(recupSemaine($seance[2]), $libelleMod, changerDate($seance[2]), $seance[3], $seance[4], $utilisateur, "", "", "");
								echo $tpl_tab_bord_seance ->render(array("items" => $tab_parametres, "couleur_module" => $couleur, "vu" => "checked"));

							    while($evenement = $reponse1->fetch())
							    {
							    	$request = "SELECT * FROM lienPieceJointe WHERE id_even='".$evenement[0]."'";
									$reponse2 = $bdd->query($request);

									$tab_fichier = array();
									while($piece_jointe = $reponse2->fetch())
									{
										$tab_fichier[] = $piece_jointe[2];
									}

							    	/* Evènement */
							    	if($evenement[2] == "fait") { $date = "";  }
							    	else { $date = changerDate($evenement[4]); }

									$tab_parametres = array($evenement[2], $evenement[1], $evenement[3], $date);
									echo $tpl_tab_bord_evenement ->render(array("items" => $tab_parametres, "fichier" => $tab_fichier));
							    }
							}
						}

					?>
				</table>

				<br/>
			</div>


	<!-- Fin -->
<?php
	pied();

	if(isset($_POST['avant']))
	{	
		$_SESSION['mois'] -= 1;

		if($_SESSION['mois'] <= 0)
		{
			$_SESSION['mois' ]  = 12;
			$_SESSION['annee'] -= 1;
		}
	}

	if(isset($_POST['apres']))
	{
		$_SESSION['mois'] += 1;

		if($_SESSION['mois'] >= 13)
		{
			$_SESSION['mois' ]  = 1;
			$_SESSION['annee'] += 1;
		}
	}
?> 