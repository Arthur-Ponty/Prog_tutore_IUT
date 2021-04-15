
	<div class="diviut">

		<h1>Ajouter un utilisateur</h1>
		<hr/>

		<table align="center">

			<form action="page_admin_utilisateur.php" method="post">

				<tr>
					<td><label name="id" id="id">Identifiant</label></td>
					<td><input type="text" name="id"></td>
				</tr>

				<tr>
					<td><label name="nom" id="nom">Nom</label></td>
					<td><input type="text" name="nom"></td>
				</tr>

				<tr>
					<td><label name="prenom" id="prenom">Prénom</label></td>
					<td><input type="text" name="prenom"></td>
				</tr>

				<tr>
					<td><label name="mdp" id="mdp">Mot de passe</label></td>
					<td><input type="password" name="mdp"></td>
				</tr>

				<tr>
					<td><label name="role" id="role">Rôle</label></td>
					<td>
						<input type="checkbox" name="role[]" value="A"> Administrateur
						<input type="checkbox" name="role[]" value="E"> Enseignant
						<input type="checkbox" name="role[]" value="T"> Tuteur
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
		if($_POST['valider'] and isset($_POST['id'], $_POST['nom'], $_POST['prenom'], $_POST['role']) )
		{

			if(!empty($_POST['id']) AND !empty($_POST['nom']) AND !empty($_POST['prenom']) AND !empty($_POST['role']))
			{
				// On récupère les information depuis les <input>
				$id     = htmlspecialchars($_POST['id'    ]);
				$nom    = htmlspecialchars($_POST['nom'   ]);
				$prenom = htmlspecialchars($_POST['prenom']);
				$mdp    = htmlspecialchars($_POST['mdp'   ]);
					
				if(isPasswordStrong($mdp))
				{
					$roles = "";
					foreach ($_POST['role'] as $role)
						$roles .= $role;
					
					
					date_default_timezone_set("Europe/Paris"); // fuseau horaire

					$date = new DateTime();
					$string = $date->format('Y-m-d');

					$insertion = $bdd->prepare("INSERT INTO utilisateur(id_utilisateur, nom, prenom, mdp, role, dte_crea, dte_modif) VALUES (?, ?, ?, ?, ?, '$string', '$string')");
					// on exécute l'insertion du tuple
					$insertion->execute(array($id, $nom, $prenom, password_hash($mdp, PASSWORD_DEFAULT), $roles));
					//header('Refresh: 0.01;URL=page_admin_utilisateur.php');
				}
			}
			else
			{
				$erreur = 'Veuillez remplir tous les champs';
			}
			
        }
    }
?>