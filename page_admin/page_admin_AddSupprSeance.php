<?php	
	include "../inc/fctAux.inc.php";
	include "../inc/DB.inc.php";

	enTete("Type de séance", "projet");

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
			<h1>Ajouter un type de séance</h1>
			<hr/>

			<table align="center">
				<form action="page_admin_AddSupprSeance.php" method="post">
					<tr>
						<td><label name="nom_seance" id="nom_seance">Nom du type de séance</label></td>
						<td><input type="text" name="nom_seance"></td>
					</tr>
					<tr>
						<td>
							<input type="reset"  name="annuler" value="Annuler">
							<input type="submit" name="valider" value="Valider">
						</td>
					</tr>
				</form>
			</table>
		</div>
	<br>
	<div class="diviut">

		<h1>Modifier un type de séance</h1>
		<hr/>

		<table align="center">
			<form action="page_admin_AddSupprSeance.php" method="post">
				<tr>
					<td><label name="nom_seance_update" id="nom_seance">Type de séance :</label></td>
					<td>
						<select name="modif[]">
								<?php

									$request = "SELECT code, libelleMod FROM Module WHERE nature = 'TS' AND code !=-1";
									$reponse = $bdd->query($request);

									while ($modif = $reponse->fetch())
									{
										echo "<option value=\"$modif[0]\"> $modif[1]";
									}
										
									$reponse ->closeCursor();

								?>
						</select>
					
					
					
					</td>
				</tr>
				<tr>
					<td>
						<input type="submit" name="modifier" value="Modifier">
					</td>
				</tr>
			</form>
		</table>
		<table>	
			<tr>
			<?php
				if(isset($_POST['modifier']) and $_POST['modifier'] )
				{		
					$code = "";

						if(isset($_POST['modif']))
						{
							$code = $_POST['modif'];
							$code = $code[0];
						}
					$request = "SELECT code, libelleMod FROM module WHERE nature='TS' and code = $code ";
					$reponse = $bdd->query($request);
					$tuple = $reponse->fetch();
					echo "<form action=\"page_admin_AddSupprSeance.php\" method=\"post\">";
					echo "<td><label name=\"name_seance\" id=\"name_seance\">Nouveau nom :</label></td>";
					echo "<td><input type=\"text\" name=\"lbl_nom_seance\" value='".$tuple[1]."'/></td></tr>";
					echo "<td><input type=\"submit\" name=\"ok\" value=\"Valider\"></td>";
					echo "<input type=\"hidden\" name=\"code_ref\" value='".$tuple[0]."'/>";
				}
				if(isset($_POST['ok']) and $_POST['ok'] )
				{	
					$new_nom_seance = htmlspecialchars($_POST['lbl_nom_seance']);
					$code_ref = htmlspecialchars($_POST['code_ref']);
					$update = $bdd->prepare("UPDATE Module SET libelleMod=? WHERE code=? ");
					$update->execute(array($new_nom_seance, $code_ref));
					header('Refresh: 0.01;URL=page_admin_AddSupprSeance.php');
				}

			?>
			
		</table>



	</div>

	<br>
	<div class="diviut" >

		<h1>Supprimer un type de séance</h1>
		<hr/>

		<table align="center">

			<form action="page_admin_AddSupprSeance.php" method="post">

				<tr>
					<td><label name="delete_nom_seance" id="delete_nom_seance">Nom du type de séance</label></td>
					<td>
						<select name="delete[]">
							<?php

								$request = "SELECT code, libelleMod FROM Module WHERE nature = 'TS' AND code !=-1";
								$reponse = $bdd->query($request);

								while ($delete = $reponse->fetch())
								{
									echo "<option value=\"$delete[0]\"> $delete[1]";
								}
									
								$reponse ->closeCursor();

							?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<input type="submit" name="supprimer" value="Supprimer">
					</td>
				</tr>
			</form>
		</table>
	</div>


<?php
pied();
	
if(isset($_POST['nom_seance']))
{
	if(!empty($_POST['nom_seance']))
	{
		$new_nom_seance = htmlspecialchars($_POST['nom_seance']);

        $count = "SELECT count(*) FROM Module WHERE nature = 'TS' AND code != -1";
		$nb =$bdd->query($count);
		$nb = $nb->fetch();
		$insertion = $bdd->prepare("INSERT INTO module(code, nature, libelleMod, couleur, droit) VALUES (?, 'TS', ?, DEFAULT, NULL)");
		

		if(!$insertion->execute(array($nb[0], $new_nom_seance)))
			echo '<center><p class="erreur">Erreur le nom cette evenement ne peut pas étre crée !! </p></center>';
		header('Refresh: 0.01;URL=page_admin_AddSupprSeance.php');
	}
}

if(isset($_POST['supprimer']) )
    {
        if($_POST['supprimer']  )
        {
			if(isset($_POST['delete']))
			{
				$id = "";

					if(isset($_POST['delete']))
					{
						$id = $_POST['delete'];
						$id = $id[0];
					}

				$suprression = $bdd->prepare("DELETE  FROM module WHERE code=? and nature='TS'");
				$suprression->execute(array($id));
				header('Refresh: 0.01;URL=page_admin_AddSupprSeance.php');
			}
        }
    }
?>