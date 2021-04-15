<div class="diviut"">

		<h1>Ajouter un groupe</h1>
		<hr/>

		<table align="center">

			<form action="page_admin_groupe.php" method="post">

				<tr>
					<td><label name="nom_groupe" id="nom_groupe">Nom du groupe</label></td>
					<td><input type="text" name="nom_groupe"></td>
				</tr>

				<tr>
					<td><label name="papa" id="papa">Groupe père</label></td>
					<td>
						<select name="papa[]">
                        <option value="null"> 
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

				<tr>
					<td></td>
					<td>
						<input type="reset"  name="annuler" value="Annuler">
						<input type="submit" name="valider" value="Valider">
					</td>
				</tr>

			</form>

		</table>

	</div>

    <?php	
	if(isset($_POST['valider']) )
	{
		if($_POST['valider']  )
		{
			if(isset($_POST['nom_groupe'], $_POST['papa']))
			{
			    if(!empty($_POST['nom_groupe']) )
			    {   
                    $papa = "";

                    if(isset($_POST['papa']))
					{
						$papa = $_POST['papa'];
						$papa = $papa[0];
					}

					// On récupère les information depuis les <input>
					$nom     = htmlspecialchars($_POST['nom_groupe'    ]);
						
                    if($papa == 'null')
                    {
                         $insertion = $bdd->prepare("INSERT INTO groupe(nom_groupe, groupe_pere) VALUES (?, NULL)");
                         if(!$insertion->execute(array($nom)))
                            echo '<center><p class="erreur">Erreur le module ne peut pas étre crée !! </p></center>';
                    }
                    else 
                    {
                        $insertion = $bdd->prepare("INSERT INTO groupe(nom_groupe, groupe_pere) VALUES (?, ?)");
                        if(!$insertion->execute(array($nom, $papa)))
                         echo '<center><p class="erreur">Erreur le module ne peut pas étre crée !! </p></center>';
                    }

			     // on exécute l'insertion du tuple
				 
				 
			    }
			    else
			    {
			      $erreur = 'Veuillez remplir tous les champs';
			    }
			}
			header('Refresh: 0.01;URL=page_admin_groupe.php');
		}
	}
?>