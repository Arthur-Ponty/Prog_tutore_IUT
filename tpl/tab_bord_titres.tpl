<tr style="background-color: #5e8a9c; margin:5px;">
	<th>{{items[0]}}</th>
	<td class="vide"></td>
	<td class="vide"></td>
	<td class="vide"></td>
{% for i in range(low=1, high=items|length-1, step=1) %}
	<th>{{items[i]}}</th>
{% endfor %}
</tr>

<tr><td></td></tr>