<tr><td></td></tr>
<tr><td></td></tr>

<tr class="seance">
	<td>{{items[0]}}</td>
	<td class="vide"></td>
	<td class="vide"></td>
	<td class="vide"></td>
	<td class="module" style="background-color: {{couleur_module}}">{{items[1]}}</td>
{% for i in range(low=2, high=items|length-1, step=1) %}
	<td>{{items[i]}}</td>
{% endfor %}
	<td class="vide"><input type="checkbox" name="vu" {{vu}} ></td>
</tr>