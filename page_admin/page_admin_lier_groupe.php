<?php	
	include "../inc/fctAux.inc.php";
	include "../inc/DB.inc.php";

	enTete("- Departement Informatique", "../css/projet");

	$bdd = connexionBdd();
	
	require_once( "../Twig/lib/Twig/Autoloader.php" );

	Twig_Autoloader::register();
	$twig = new Twig_Environment( new Twig_Loader_Filesystem("../tpl") );
	$tpl_entete_di           = $twig->loadTemplate( "entete_di.tpl" );

?>

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
		<div class="diviut" class="table100">
		<h1>Lier groupe/utilisateur</h1>
        <hr/>

        <form action="page_admin_lier_groupe.php" method="post">
			<table align="center" >
				<td>
					<table align="center">

						<tr>
							<td><label name="role" id="role">Utilisateur</label></td>
							<td>
								<select name="role[]">
									<?php

										$request = "SELECT id_utilisateur, nom, prenom FROM utilisateur";
										$reponse = $bdd->query($request);

										while ($id = $reponse->fetch())
										{
											echo "<option value=\"$id[0]\"> $id[1] $id[2]";
										}
										   
										$reponse ->closeCursor();

									?>
								</select>
							</td>
						</tr>

					</table>
				</td>
				<td>
					<table align="center">
						<tr>
							<td><label name="groupe" id="groupe">Groupe</label></td>
							<td>
								<select name="groupe[]">
									<?php

										$request = "SELECT nom_groupe FROM groupe";
										$reponse = $bdd->query($request);

										while ($nom = $reponse->fetch())
										{
											echo "<option value=\"$nom[0]\"> $nom[0]";
										}
				 
										$reponse ->closeCursor();

									?>
								</select>
							</td>
						</tr>
					</table>
				</td>
			</table>
			<center><input type="submit" name="lier" value="Lier"></center>
        </form>
	<?php

		if(isset($_POST['lier']) )
		{
			if($_POST['lier'] )
			{
				$groupe = "";
				$id = "";

				if(isset($_POST['role']) && isset($_POST['groupe']))
				{
					$id = $_POST['role'];
					$id = $id[0];

					$groupe = $_POST['groupe'];
					$groupe = $groupe[0];
				}

				  $insert = $bdd->prepare("INSERT into gere(id_utilisateur, nom_groupe) values (?, ?)");
				   

				if(!$insert->execute(array($id, $groupe)))
				{
					echo '<center><p class="erreur">Erreur le lien ne peut pas être fait il existe peut être deja !! </p></center>';

				}

				
			}
		}

	?>

</div>

<br >

<div class="diviut" class="table100">

		<h1>Délier un utilisateur d'un groupe </h1>
		<hr/>

		<table align="center">

			<form action="page_admin_lier_groupe.php" method="post">

				<tr>
					<td><label name="groupe" id="groupe">Utilisateur</label></td>
					<td>
						<select name="groupe[]">
							<?php

								$request = "SELECT id_utilisateur, nom, prenom FROM utilisateur";
								$reponse = $bdd->query($request);

								while ($id = $reponse->fetch())
								{
								    echo "<option value=\"$id[0]\"> $id[1] $id[2]";
								}
								   
								$reponse ->closeCursor();

							?>
						</select>
					</td>
				</tr>

				<tr>
					<td></td>
					<td>
						<input type="submit" name="choisir" value="Choisir">
					</td>
				</tr>

			</form>

		</table>

		<?php

			
			
			if(isset($_POST["supprimer"]) )
			{
				$groupe = $_POST["supprimer"];
				$id   = $_POST["id"];
				
				$suprim = $bdd->prepare("DELETE FROM gere where nom_groupe=? and id_utilisateur=?");


                if(!$suprim->execute(array($groupe, $id)))
                    echo '<center><p class="erreur">La supresion a echouer </p></center>';

			}

			if(isset($_POST['choisir']) || isset($_POST['supprimer']) )
			{
				if($_POST['choisir'] OR  $_POST['supprimer'] )
				{
					$id = "";

					if(isset($_POST['groupe']))
					{
						$id = $_POST['groupe'];
						$id = $id[0];
					}
					if(isset($_POST['id']))
					{
						$id = $_POST['id'];
					}

					$request = "SELECT id_utilisateur, nom_groupe FROM gere WHERE id_utilisateur='".$id."'";
					$reponse = $bdd->query($request);

					echo '<form action="page_admin_lier_groupe.php" method="post">';
					echo "<center><table class=\"listModule\">";
					echo "<tr>";
					echo "<td> Nom     </td>";
					echo "</tr>";
					while ($res = $reponse->fetch())
					{
						echo "<tr>";
						echo "<td> $res[1]</td>";
						echo "<input type=\"hidden\" name=\"id\" value=\"$res[0]\">";
						echo "<td> <button name=\"supprimer\" value=\"$res[1]\">supprimer </button></td>";
						echo "</tr>";
						
					}
					echo "</table></center>";
					echo "</form>";
				}
			}
		?>

</div> 
</div> 