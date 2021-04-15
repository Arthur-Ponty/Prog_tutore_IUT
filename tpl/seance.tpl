<form action="mes_seances.php" method="post">
	<tr>
		<td>{{code       }}</td>
		<td>{{dte_crea   }}</td>
		<td>{{type       }}</td>
		<td>{{nom_groupe }}</td>
		<td><input type="submit" name="ajouter"   value="" id="ajouter"  ></td>
		<td><input type="submit" name="supprimer" value="" id="supprimer"></td>
		<input type="hidden" name="id_seance" value="{{id_seance}}">
	</tr>
</form>
