<table align=center>
	<form action="page_admin_module.php" method="post">
		<tr>
	    	<td><label name="code" id="code">Code</label></td>
			<td><input type="text" name="code" value="{{code}}"></td>
		</tr>

		<tr>			
    		<td><label name="libelleMod" id="libelleMod">LibelleMod</label></td>
			
            	<td><input type="text" name="libelleMod" value="{{libelleMod}}" ></td>
			</tr>
			<tr>
				<td><label name="droit" id="droit">droit</label></td>
				<td>
					<input type="radio" name="droit[]" value="E" {{enseignant}}> Enseignant
					<input type="radio" name="droit[]" value="T" {{tuteur}}> Tuteur
				</td>
			</tr>
			<tr>
				<td><label name="couleur" id="couleur">Couleur</label></td>
				<td>
					<input class="color" type="color" name="color" value="{{couleur}}"/>
			    </td>
			</tr>
        </tr>
		<tr>
			<td></td>
			<td>
				<input type="submit"  name="suppr" value="Supprimer">
				<input type="hidden" name="id_cache" value="{{code}}">
				<input type="submit" name="valider_modif" value="Valider">
			</td>
		</tr>
	</form>
</table>