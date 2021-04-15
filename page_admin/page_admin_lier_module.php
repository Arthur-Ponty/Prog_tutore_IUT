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
		
			<h1>Lier module/utilisateur</h1>
			<hr/>

			<form action="page_admin_lier_module.php" method="post">
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
								<td><label name="modu" id="modu">Module</label></td>
								<td>
									<select name="modu[]">
										<?php

											$request = "SELECT code, libelleMod FROM module where nature = 'MO'";
											$reponse = $bdd->query($request);

											while ($code = $reponse->fetch())
											{
												echo "<option value=\"$code[0]\"> $code[1]";
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
					if($_POST['lier'] && $_POST['modu'] != '-1')
					{
						$id = "";
						$code = "";

						if(isset($_POST['role']) && isset($_POST['modu']))
						{
							$id = $_POST['role'];
							$id = $id[0];

							$code = $_POST['modu'];
							$code = $code[0];
						}

						if ($code != '-1')
						{

							$insert = $bdd->prepare("INSERT into assigner(id_utilisateur, code, nature) values (?, ?, 'MO')");
						   

							if(!$insert->execute(array($id, $code)))
								echo '<center><p class="erreur">Erreur le lien ne peut pas être fait il existe peut être deja !! </p></center>';

						}
					}
				}

			?>
		
		</div>
	
		<br >

		<div class="diviut" class="table100">

			<h1>Délier un utilisateur d'un module </h1>
			<hr/>

			<table align="center">

				<form action="page_admin_lier_module.php" method="post">

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
					$code = $_POST["supprimer"];
					$id   = $_POST["id"];
					
					$suprim = $bdd->prepare("DELETE FROM assigner where code=? and id_utilisateur=?");


					if(!$suprim->execute(array($code, $id)))
						echo '<center><p class="erreur">La supresion a echouer </p></center>';

				}

				if(isset($_POST['choisir']) || isset($_POST['supprimer']) )
				{
					if($_POST['choisir'] OR  $_POST['supprimer'] )
					{
						$id = "";

						if(isset($_POST['role']))
						{
							$id = $_POST['role'];
							$id = $id[0];
						}
						if(isset($_POST['id']))
						{
							$id = $_POST['id'];
						}

						$request = "SELECT id_utilisateur, code, libelleMod FROM assigner natural join module WHERE id_utilisateur='".$id."'";
						$reponse = $bdd->query($request);

						echo '<form action="page_admin_lier_module.php" method="post">';
						echo "<center><table class=\"listModule\">";
						echo "<tr>";
						echo "<td> Nom     </td><td> Code</td>";
						echo "</tr>";
						while ($res = $reponse->fetch())
						{
							echo "<tr>";
							echo "<td> $res[2]</td>";
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
</div>