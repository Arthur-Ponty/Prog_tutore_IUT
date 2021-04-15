<div class="diviut">

	<h1>Modifier/Supprimer un utilisateur</h1>
	<hr/>

	<table align="center">
		<form action="page_admin_utilisateur.php" method="post">
			<tr>
				<td><label name="id" id="id">Utilisateur</label></td>
				<td>
					<select name="id[]">
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
					<input type="submit" name="modif" value="Choisir">
				</td>
			</tr>
		</form>
	</table>

<?php

    if(isset($_POST['modif']) and $_POST['modif'] )
	{

        $id         = "";
        $admin      = "";
        $enseignant = "";
        $tuteur     = "";


		if(isset($_POST['id']))
		{
			$id = $_POST['id'];
			$id = $id[0];
        }
        
		$request = "SELECT nom, prenom, mdp, role FROM utilisateur WHERE id_utilisateur='".$id."'";
		$reponse = $bdd->query($request);
        $tuple = $reponse->fetch();
            
			if($tuple[3] == "A" OR $tuple[3] == "AT" OR $tuple[3] == "AE")
				$admin = "checked=\"checked\"";

			if($tuple[3] == "AE" OR $tuple[3] == "E")
				$enseignant = "checked=\"checked\"";

			if($tuple[3] == "AT" OR $tuple[3] == "T")
				$tuteur = "checked=\"checked\"";

                
			$tpl = $twig->loadTemplate( "modif.tpl" );
			echo $tpl->render( array("id" => $id, "nom" => $tuple[0], "prenom" => $tuple[1],
									 "mdp" => $tuple[2], "admin" => $admin,
									 "enseignant" => $enseignant, "tuteur" => $tuteur) );
				
    }
            
			if(isset($_POST['valider_modif']) AND $_POST['valider_modif'])
			{
				if(isset($_POST['id'], $_POST['nom'], $_POST['prenom'], $_POST['mdp'], $_POST['id']))
				{
					if(!empty($_POST['id']) AND !empty($_POST['nom']) AND !empty($_POST['prenom']) AND !empty($_POST['mdp']) AND !empty($_POST['id']))
					{
						$new_id     = htmlspecialchars($_POST['id'      ]);
						$new_nom    = htmlspecialchars($_POST['nom'     ]);
						$new_prenom = htmlspecialchars($_POST['prenom'  ]);
						$new_mdp    = htmlspecialchars($_POST['mdp'     ]);
						$id_ref     = htmlspecialchars($_POST['id'      ]);
						$mdp_ref    = htmlspecialchars($_POST['mdp_cache']);
						$new_roles = "";
						$mdp_valide = 1;

						if(isPasswordStrong($new_mdp))
						{

							foreach ($_POST['role'] as $new_role)
								$new_roles .= $new_role;
								

							if($new_mdp != $mdp_ref)
							{
								$new_mdp = password_hash($new_mdp, PASSWORD_DEFAULT);
								$mdp_valide = 0;
							}

							date_default_timezone_set("Europe/Paris"); // fuseau horaire
							$date  = new DateTime();
							$string = $date->format('Y-m-d');

							$update = $bdd->prepare("UPDATE utilisateur SET id_utilisateur=?, nom=?, prenom=?,
													 mdp=?, role=?, dte_modif=? ,mdp_valide=? WHERE id_utilisateur=?");

							if(!$update->execute(array($new_id, $new_nom, $new_prenom, $new_mdp, $new_roles, $string, $mdp_valide, $id_ref)))
								echo '<center><p class="erreur">Erreur l\'utilisateur ne peut pas étre modifier !! </p></center>';
							else
								header('Refresh: 0.01;URL=page_admin_utilisateur.php');
						
						}
					}
				}
			}
			
			if(isset($_POST['suppr']) AND $_POST['suppr'])
			{
				if(isset($_POST['id']) AND !empty($_POST['id']))
				{
					$id = htmlspecialchars($_POST['id']);
					$suprression = $bdd->prepare("DELETE  FROM utilisateur WHERE id_utilisateur=?");
					$suprression->execute(array($id));
					header('Refresh: 0.01;URL=page_admin_utilisateur.php');
				}
			}
				?>
</div>