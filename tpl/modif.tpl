<table align=center>
	<form action="page_admin_utilisateur.php" method="post">
		<tr>
			<td><label name="id" id="id">Identifiant</label></td>
			<td><input type="text" name="id" value="{{id}}"></td>
		</tr>
		
		<tr>
			<td><label name="nom" id="nom">Nom</label></td>
			<td><input type="text" name="nom" value="{{nom}}"></td>
		</tr>

		<tr>
			<td><label name="prenom" id="prenom">Prénom</label></td>
			<td><input type="text" name="prenom" value="{{prenom}}"></td>
		</tr>

		<tr>
			<td><label name="mdp" id="mdp">Mot de passe</label></td>
			<td><input type="password" name="mdp" value="{{mdp}}"></td>
		</tr>

		<tr>
			<td><label name="role" id="role">Rôle</label></td>
			<td>
				<input type="checkbox" name="role[]" value="A" {{admin}}> Administrateur

				<input type="checkbox" name="role[]" value="E" {{enseignant}}> Enseignant

				<input type="checkbox" name="role[]" value="T" {{tuteur}}> Tuteur

			</td>
		</tr>

		<tr>
			<td></td>
			<td>
				<input type="submit" name="valider_modif" value="Valider">
				<input type="submit"  name="suppr" value="Supprimer">
				<input type="hidden" name="id_cache" value="{{id}}">
				<input type="hidden" name="mdp_cache" value="{{mdp}}">
			</td>
		</tr>
	</form>
</table>