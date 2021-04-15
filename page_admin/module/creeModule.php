<div class="diviut">
	<h1>Ajouter un module</h1>
	<hr/>

	<table align="center">

		<form action="page_admin_module.php" method="post">

			<tr>
				<td><label name="code" id="code">Code</label></td>
				<td><input type="text" name="code"></td>
			</tr>

			<tr>
				<td><label name="libelleMod" id="libelleMod">Nom du module</label></td>
				<td><input type="text" name="libelleMod"></td>
			</tr>

			<tr>
				<td><label name="droit" id="droit">Droit</label></td>
				<td>
					<input type="radio" name="droit[]" value="E"> Enseignant
					<input type="radio" name="droit[]" value="T"> Tuteur
				</td>
			</tr>
			<tr>
				<td><label name="couleur" id="couleur">Couleur</label></td>
				<td>
					<input class="color" type="color" name="color" value="#DDDDDD"/>
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
			if(isset($_POST['code'], $_POST['libelleMod'], $_POST['droit'], $_POST['color']))
			{
			    if(!empty($_POST['code']) AND !empty($_POST['libelleMod']) AND !empty($_POST['droit']))
			    {
					$code = "";

					// On récupère les information depuis les <input>
					$code          = htmlspecialchars($_POST['code'    ]);
					$libelleMod    = htmlspecialchars($_POST['libelleMod'   ]);
					$color = $_POST['color'];
					$droit = $_POST['droit'];
						

			     $insertion = $bdd->prepare("INSERT INTO Module(code, nature, libelleMod, couleur, droit) VALUES (?, 'MO', ?, ?, ?)");
			     // on exécute l'insertion du tuple
				 
				 	if(!$insertion->execute(array($code, $libelleMod, $color, $droit[0])))
                   		 echo '<center><p class="erreur">Erreur le module ne peut pas étre crée !! </p></center>';
               		else 
                		header('Refresh: 0.01;URL=page_admin_module.php');
			    }
			    else
			    {
			      $erreur = 'Veuillez remplir tous les champs';
			    }
			}
		}
	}
?>