<tr>
	<td></td>
	<td class="vide"></td>
	<td class="vide"></td>
	<td class="vide"></td>
	<td></td>
	<td>{{items[0]}}</td>
	<td colspan="3" style="text-align: left">{{items[1]}}</td>
	<td>
	{% if fichier|length > 0 %}
		{% for i in range(low=0, high=fichier|length-1, step=1) %}
			<a href="../upload/{{fichier[i]}}" target="_blank"><img src="../images/piece_jointe.png" /></a>
		{% endfor %}
	{% endif %}
	</td>
	<td>{{items[2]}}</td>
	<td>{{items[3]}}</td>
</tr>