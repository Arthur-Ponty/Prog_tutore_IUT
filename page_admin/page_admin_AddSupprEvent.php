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
		<div class="diviut" >
			<h1>Ajouter un type d'évènement</h1>
			<hr />

			<table align="center">
				<form action="page_admin_AddSupprEvent.php" method="post">
					<tr>
						<td><label name="nom_event" id="nom_event">Nom du type de l'évènement</label></td>
						<td><input type="text" name="nom_event"></td>
					</tr>
					<tr>
						<td colspan=2>
							<input type="reset"  name="annuler" value="Annuler">
							<input type="submit" name="valider" value="Valider">
						</td>
					</tr>
				</form>
			</table>
		</div>

		<br>
		<div class="diviut" >

			<h1>Modifier un type d'évènement</h1>
			<hr />

			<table align="center">
				<form action="page_admin_AddSupprEvent.php" method="post">
					<tr>
						<td><label name="lbl_nom_event" id="lbl_nom_event"> Modifier l'évènement :</label></td>
						<td>
							<select name="modif[]">
									<?php

										$request = "SELECT code, libelleMod FROM Module WHERE nature = 'TE' AND code !=-1";
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
						<td colspan=2>
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
						$request = "SELECT code, libelleMod FROM module WHERE nature='TE' and code = $code ";
						$reponse = $bdd->query($request);
						$tuple = $reponse->fetch();
						echo "<form action=\"page_admin_AddSupprEvent.php\" method=\"post\">";
						echo "<td><label name=\"name_event\" id=\"name_event\">Nouveau nom :</label></td>";
						echo "<td><input type=\"text\" name=\"nom_event\" value='".$tuple[1]."'/></td></tr>";
						echo "<td colspan=2><input type=\"submit\" name=\"ok\" value=\"Valider\"></td>";
						echo "<input type=\"hidden\" name=\"code_ref\" value='".$tuple[0]."'/>";
					}
					if(isset($_POST['ok']) and $_POST['ok'] )
					{	
						$new_nom_event = htmlspecialchars($_POST['nom_event']);
						$code_ref = htmlspecialchars($_POST['code_ref']);
						$update = $bdd->prepare("UPDATE Module SET libelleMod=? WHERE code=? ");
						$update->execute(array($new_nom_event, $code_ref));
						header('Refresh: 0.01;URL=page_admin_AddSupprEvent.php');
					}

				?>
				
			</table>

		</div>
		<br>
		<div class="diviut">

			<h1>Supprimer un type d'évènement</h1>
			<hr/>

			<table align="center">

				<form action="page_admin_AddSupprEvent.php" method="post">

					<tr>
						<td><label name="delete_nom_event" id="delete_nom_event">Nom du type de l'évènement</label></td>
						<td>
							<select name="delete[]">
									<?php

										$request = "SELECT code, libelleMod FROM Module WHERE nature = 'TE' AND code !=-1";
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
						<td colspan=2>
							<input type="submit" name="supprimer" value="Supprimer">
						</td>
					</tr>
				</form>
			</table>
		</div>
	</div>

<?php
pied();
if(isset($_POST['valider']) and $_POST['valider'] )
{	
	if(isset($_POST['nom_event']))
	{
		if(!empty($_POST['nom_event']))
		{
			$new_nom_event = htmlspecialchars($_POST['nom_event']);

			$count = "SELECT code FROM Module WHERE nature = 'TE' AND code != -1";
			$nb =$bdd->query($count);
			
			while($id = $nb->fetch())
				if($id[0] > $idMax) $idMax = $id[0];

			$insertion = $bdd->prepare("INSERT INTO module(code, nature, libelleMod, couleur, droit) VALUES (?, 'TE', ?, DEFAULT, NULL)");
			

			if(!$insertion->execute(array($idMax+1, $new_nom_event)))
				echo '<center><p class="erreur">Erreur le nom de cet evenement ne peut pas être créer !! </p></center>';
			else
				header('Refresh: 0.01;URL=page_admin_AddSupprEvent.php');
		}
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

				$suprression = $bdd->prepare("DELETE  FROM module WHERE code=? and nature='TE'");
				$suprression->execute(array($id));
				header('Refresh: 0.01;URL=page_admin_AddSupprEvent.php');
			}
        }
    }
?>