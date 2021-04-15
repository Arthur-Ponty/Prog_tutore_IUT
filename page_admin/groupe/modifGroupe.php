<div class="diviut"">

		<h1>Modifier/Supprimer un groupe</h1>
		<hr/>

		<table align="center">

			<form action="page_admin_groupe.php" method="post">

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

				<tr>
					<td></td>
					<td>
						<input type="submit" name="modif" value="Choisir">
					</td>
				</tr>

			</form>

		</table>

		<?php

			if(isset($_POST['modif']) )
			{
				if($_POST['modif'] )
				{
					$groupe = "";

					if(isset($_POST['groupe']))
					{
						$groupe = $_POST['groupe'];
						$groupe = $groupe[0];
					}

					$request = "SELECT nom_groupe, groupe_pere FROM groupe WHERE nom_groupe='".$groupe."' ";
					$reponse = $bdd->query($request);
                    $tuple   = $reponse->fetch();
                    $daron   = str_replace(" " , "" ,$tuple[1]);

                    echo "<table align=center>
                            <form action=\"page_admin_groupe.php\" method=\"post\">
                            <tr>
                                <td><label name=\"nom_groupe\" id=\"nom_groupe\">Nom goupe</label></td>
                                <td><input type=\"text\" name=\"nom_groupe\" value=\"".$tuple[0]."\"></td>
                            </tr>
                
                        <tr>
                        <td><label name=\"papa\" id=\"papa\">Groupe père</label></td>
					    <td>
                            <select name=\"papa[]\">
                            <option value=\"null\"> </option>";
                        
                        $request = "SELECT nom_groupe FROM groupe";
                        $reponse = $bdd->query($request);
        
                         while ($nom = $reponse->fetch())
                         {
                             if($nom[0] === $daron)
                             {
                                echo "<option value=\"$nom[0]\" selected> $nom[0] </option>";
                             }
                             else
                                echo "<option value=\"$nom[0]\"> $nom[0] </option>";
                         }
                                           
                        $reponse ->closeCursor();
                    
                        echo "</select>
                            </td>
                            </tr>
                                <tr>
                                   <td></td>
                                   <td>
                                       <input type=\"submit\"  name=\"suppr\" value=\"Supprimer\">
                                       <input type=\"hidden\" name=\"id_cache\" value=\"".$tuple[0]."\">
                                       <input type=\"submit\" name=\"valider_modif\" value=\"Valider\">
                                   </td>
                               </tr>
                           </form>
                       </table>";

					header('Refresh: 0.01;URL=page_admin_groupe.php');
				}
			}

			if(isset($_POST['valider_modif']) )
			{
				if($_POST['valider_modif']  )
				{
					if(isset($_POST['nom_groupe'], $_POST['papa']))
					{
					    if(!empty($_POST['nom_groupe']))
					    {

					    	$papa = "";

                            if(isset($_POST['papa']))
                            {
                                $papa = $_POST['papa'];
                                $papa = $papa[0];
                            }

                            $nom     = htmlspecialchars($_POST['nom_groupe'    ]);
                            $id_ref  = htmlspecialchars($_POST['id_cache'    ]);


							$update = $bdd->prepare("UPDATE groupe SET nom_groupe=?, groupe_pere=? where nom_groupe =? ");
							
							if(!$update->execute(array($nom, $papa, $id_ref)))
								echo '<center><p class="erreur">Erreur le peut pas étre modifier !! </p></center>';
									
					    }
					}
					header('Refresh: 0.01;URL=page_admin_groupe.php');
				}
			}

			if(isset($_POST['suppr']) )
			{
				if($_POST['suppr']  )
				{

					if(isset($_POST['id_cache']))
					{
						$id = htmlspecialchars($_POST['id_cache']);

						$suprression = $bdd->prepare("DELETE FROM groupe WHERE nom_groupe=?");
						$suprression->execute(array($id));
					}
					header('Refresh: 0.01;URL=page_admin_groupe.php');
				}
			}


		?>

	</div>
