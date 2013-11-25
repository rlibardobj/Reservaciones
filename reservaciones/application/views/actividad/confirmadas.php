<p>La siguiente es una lista de actividades confirmadas. Puede seleccionar cualquiera de ellas para ver su información.</p>
<table class="proforma">
	<tbody>
		<tr>
			<th>Código</th>
			<th>Cliente</th>
			<th>Atención</th>
			<th>Descripción</th>
			<th>Fecha inicio</th>
			<th>Fecha fin</th>
		</tr>
		{confirmadas}
		<tr>
			<td><a href="{view_url}/{id_proforma}">{codigo}</a></td>
			<td>{cliente}</td>
			<td>{atencion}</td>
			<td>{descripcion}</td>
			<td>{fecha_inicio}</td>
			<td>{fecha_fin}</td>
		</tr>
		{/confirmadas}
	</tbody>
</table>