<p>La siguiente es una lista de actividades que aún no están confirmadas. Puede seleccionar una para editarla y marcar como confirmada.</p>
<table class="proforma">
	<tbody>
		<tr>
			<th>Código</th>
			<th>Cliente</th>
			<th>Atención</th>
			<th>Descripción</th>
			<th>Fecha inicio</th>
			<th>Fecha fin</th>
			<th></th>
		</tr>
		{pendientes}
		<tr>
			<td><a href="{view_url}/{id_proforma}">{codigo}</a></td>
			<td>{cliente}</td>
			<td>{atencion}</td>
			<td>{descripcion}</td>
			<td>{fecha_inicio}</td>
			<td>{fecha_fin}</td>
			<td><a href="{eliminar_url}/{id_proforma}">Eliminar</a></td>
		</tr>
		{/pendientes}
	</tbody>
</table>