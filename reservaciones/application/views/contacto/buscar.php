<style type="text/css">
	table td, table th {
		width: 10em;
	}
	input[type=checkbox] {
		display: inline;
	}
</style>
<h3>Buscar contactos</h3>
<form method="get">
	<label for="texto">Texto por buscar</label>
	<input id="texto" type="text" name="texto" size="40" style="display: inline">

	<input type="submit" value="Buscar" style="display: inline">

	<input type="checkbox" name="empresa" id="empresa">
	<label for="empresa">Buscar en la empresa</label>

	<input type="checkbox" name="sector" id="sector">
	<label for="sector">Buscar en el sector</label>
	
	<table>
		<tbody>
			<tr>
				<th>Nombre</th>
				<th>Empresa</th>
				<th>Teléfono</th>
				<th>Email</th>
				<th>Sector</th>
			</tr>
		</tbody>
	</table>
	<h4>Resultados por nombre</h4>
	<table>
		<tbody>
	{resultados}
			<tr>
				<td>{Contacto}</td>
				<td>{Empresa}</td>
				<td>{Telefono}</td>
				<td>{Email}</td>
				<td>{Sector}</td>
			</tr>
	{/resultados}
		</tbody>
	</table>
	<h4>{empresa_titulo}</h4>
	<table>
		<tbody>
	{resultados_empresa}
			<tr>
				<td>{Contacto}</td>
				<td>{Empresa}</td>
				<td>{Telefono}</td>
				<td>{Email}</td>
				<td>{Sector}</td>
			</tr>
	{/resultados_empresa}
		</tbody>
	</table>
	<h4>{sector_titulo}</h4>
	<table>
		<tbody>
	{resultados_sector}
			<tr>
				<td>{Contacto}</td>
				<td>{Empresa}</td>
				<td>{Telefono}</td>
				<td>{Email}</td>
				<td>{Sector}</td>
			</tr>
	{/resultados_sector}
		</tbody>
	</table>
</form>
<script type="text/javascript">
	var windowHeight = $(window).height();
	var formHeight = $('form').height();
	$('.menu-proform').height(windowHeight > formHeight ? windowHeight : formHeight);
</script>