<div class="diviut">

	<h1>Modifier/Supprimer un évènement</h1>
	<hr/>

	<table align="center">
		<form action="ajouter_evenement.php" method="post">
			<tr>
				<td><label name="even" id="even">Evenement</label></td>
				<td>
					<select name="even[]">
						<?php
							$request = "SELECT id_even, libelleEnv FROM evenement WHERE id_seance='".$_SESSION['id_seance']."'";
							$reponse = $bdd->query($request);
							while ($id = $reponse->fetch())
							{
								echo "<option value=\"$id[0]\"> $id[1]";
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

	    if(isset($_POST['modif']) AND $_POST['modif'] AND $_POST['new_type'][0] != "fait" AND empty($_POST['new_echeance']))
		{                
			echo "<table align=\"center\"\n>";

			echo "<form action=\"ajouter_evenement.php\" method=\"post\" enctype=\"multipart/form-data\"\n>";

			if    (isset($_POST['even' ])) $id = $_POST['even'][0];
			elseif(isset($_POST['valider_modif'])) $id = $_POST['id'];

			$request = "SELECT libelleEnv, type, dte_echeance, duree FROM evenement WHERE id_even='".$id."'";
			$reponse = $bdd->query($request)->fetch();

			echo "<tr\n>";
			echo "<td><label name=\"new_commentaire\" id=\"new_commentaire\">Commentaire</label></td\n>";
			echo "<td><input type=\"text\" name=\"new_commentaire\" value=\"$reponse[0]\"> </td\n>";
			echo "</tr\n>";

			echo "<tr\n>";
			echo "<td><label name=\"new_type\" id=\"new_type\">Type</label></td\n>";
			echo "<td\n>";
			echo "<select name=\"new_type[]\"\n>";

			$request = "SELECT libelleMod FROM module WHERE nature='TE' AND code!='-1'";
			$reponse1 = $bdd->query($request);

			while ($type = $reponse1->fetch())
			{
				echo "<option value=\"$type[0]\" ";

				if ($type[0] == $reponse[1] AND !isset($_POST['new_type']))
				{
					echo "selected=\"selected\"";
				}
				elseif($_POST['new_type'] == $type[0])
				{
					echo "selected=\"selected\"";
				}

				echo ">$type[0]</option>";
			}

			echo "</select\n>";
			echo "</td\n>";
			echo "</tr\n>";

			if((isset($_POST['new_type']) AND $_POST['new_type'] != "fait") OR $reponse[1] != "fait")
			{
				echo "<tr>\n";
				echo "\t<td><label name=\"new_duree\" id=\"new_duree\">Durée</label></td>\n";
				echo "\t<td><input type=\"text\" name=\"new_duree\" value=\"".$reponse[3]."\"> </td>\n";
				echo "</tr>\n";
				echo "\n";

				echo "<tr>\n";
				echo "\t<td><label name=\"new_echeance\" id=\"new_echeance\">Échéance</label></td>\n";
				echo "\t<td><input type=\"date\" name=\"new_echeance\" value=\"".$reponse[2]."\"></td>\n";
				echo "</tr>\n";
				echo "\n";

				echo "<td>Ajouter une durée(facultative)</td><td>et une date d'échéance</td>";
			}
			
			echo "<tr\n>";
			echo "<td></td\n>";
			echo "<td\n>";

			echo "<input type=\"hidden\" name=\"id\" value=\"".$id."\">";
			echo "<input type=\"submit\" name=\"valider_modif\" value=\"Valider\"\n>";
			echo "<input type=\"submit\" name=\"suppr\" value=\"Supprimer\"\n>";
			echo "</td\n>";
			echo "<td\n>";

			echo "</td\n>";
			echo "</tr\n>";

			echo "</form\n>";

			echo "</table\n>";
	    }

		if(isset($_POST['valider_modif']) )
		{
			if($_POST['valider_modif']  )
			{
				if($_POST['new_type'][0] == "fait" OR !empty($_POST['new_echeance']))
	    		{
					if(isset($_POST['new_type'], $_POST['new_commentaire']))
					{
						if(!empty($_POST['new_type']) AND !empty($_POST['new_commentaire']))
						{
							$new_duree = $_POST['new_duree'      ];
							$new_date  = $_POST['new_echeance'   ];
							$new_lbl   = htmlspecialchars($_POST['new_commentaire']);
							$new_type  = htmlspecialchars($_POST['new_type'       ][0]);
							$id        = $_POST['id'];

							if(empty($new_date )) $new_date  = "0000-00-00";
							if(empty($new_duree)) $new_duree = null;

							$update = $bdd->prepare("UPDATE evenement SET libelleEnv=?, type=?, duree=?, dte_echeance=? WHERE id_even=?");
							$update->execute(array($new_lbl, $new_type, $new_duree, $new_date, $id));
						}
					}
				}
			}
		}

		if(isset($_POST['suppr']) )
		{
			if($_POST['suppr']  )
			{
				if(isset($_POST['id']) AND !empty($_POST['id']))
				{
					$id = htmlspecialchars($_POST['id']);
					$suprression = $bdd->prepare("DELETE FROM evenement WHERE id_even=?");
					$suprression->execute(array($id));
				}
			}
		}
	?>
</div>