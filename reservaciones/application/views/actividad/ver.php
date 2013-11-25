{proforma}
<div class="ver">
<form method="post" id="FProform" name="FProform">

	<h3 class="estiloA">Proforma/Actividad</h3>
	<p class="estiloB">Centro de Transferencia Tecnológica y Educación Continua</p>
	<p class="estiloB">Instituto Tecnológico de Costa Rica</p>
	<p class="estiloB">Cédula Jurídica: 4000-042145-07</p>
	<br>
	<input type="hidden" value="{espacios_url}" id="espacios-url">
	<hr>
	<label for="proforma">Código de Proforma</label>
	<input type="text" id="proforma" name="codigo" size="30" value="{codigo}" readonly="readonly">
	<br>
	<label for="usuario">Nombre del Cliente</label>
	<input type="text" id="usuario" name="cliente" size="80" value="{cliente}">
	<br>
	<label for="fecha_contacto">Fecha de Contacto</label>
	<input type="text" id="fecha_contacto" name="fecha_contacto" class="datepicker" value="{fecha_contacto}">
	<br>
	<label for="atencion">Atención</label>
	<input type="text" id="atencion" name="atencion" size="80" value="{atencion}">
	<br>
	<label for="descripcion">Descripción del Servicio</label>
	<textarea rows="5" cols="100" id="descripcion" name="descripcion">{descripcion}</textarea>
	<br>	
	<label for="cupo">Cupo</label>
	<input type="number" id="cupo" name="cupo" value="{cupo}">
	<br>	
	<label for="fecha_inicio">Fecha de inicio de la actividad</label>
	<input type="text" id="fecha_inicio" name="fecha_inicio" class="datetimepicker" value="{fecha_inicio}">
	<br>	
	<label for="fecha_fin">Fecha de final de la actividad</label>
	<input type="text" id="fecha_fin" name="fecha_fin" class="datetimepicker" value="{fecha_fin}">
	<br>
	<label for="emails">Correo electrónicos de interés (separados con coma)</label>
	<input type="text" name="emails" id="email" size="80" value="{emails}">
{/proforma}
	<br>
	<hr>
	<h3>Espacios</h3>
	<div>
		<div id="dialog-espacios" title="Agregar un espacio">
			<p>Seleccione el espacio que quiere agregar, así como la hora de inicio y de fin</p>
			<select id="dialogo-espacio-list">
				{espacios}
				<option value="{id_espacio}" data-categoria="{modo_alquiler}" data-precio="{precio_base}">{descripcion}</option>
				{/espacios}
			</select>
			<label for="intervalo_inicio">Hora de inicio</label>
			<input type="text" id="intervalo_inicio" class="datetimepicker">
			<label for="intervalo_fin">Hora de fin</label>
			<input type="text" id="intervalo_fin" class="datetimepicker">
			<label for="intervalo_precio">Precio establecido (en colones)</label>
			<input type="number" id="intervalo_precio" size="7">
			<div class="ui-widget">
			<div id="intervalo-notificacion" style="padding: 0 .7em;">
				<p id="intervalo-notificacion-texto"></p>
			</div>
			</div>
		</div>
		<input type="button" id="espacios-agregar-button" value="Agregar">
		<table class="servicios">
			<tbody id="intervalo-tabla">
				<tr>
					<th class="intervalo-tabla-espacio">Espacio</th>
					<th class="intervalo-tabla-inicio">Hora de inicio</th>
					<th class="intervalo-tabla-fin">Hora de fin</th>
					<th class="intervalo-tabla-precio">Precio</th>
					<th class="intervalo-tabla-opciones"></th>
				</tr>
			</tbody>
		</table>
		<input type="hidden" id="espacios-list" name="espacios" value='{proforma_intervalos}'>
	</div>
	<br>
	<br>
	<br>
	<hr>
	<h3>Servicios</h3>
	<div>
		<table border="1" id="tabla-servicios" class="servicios">
			<tr>
				<th>Detalle</th>
				<th>Costo Unitario</th>
				<th>Cantidad</th>
				<th>Costo Total</th>
				<th>Requerido</th>
			</tr>
			{servicios}
			<tr class="servicio-fila" data-servicio-id="{id_servicio}">
				<td class="servicio-detalle">{descripcion}</td>
				<td><input type="text" value="{precio_base}" class="servicio-precio"></td>
				<td><input type="number" value="0" class="servicio-cantidad"></td>
				<td><span class="servicio-total"></span></td>
				<td><input type="checkbox" value="1" class="servicio-check"/></td>
			</tr>
			{/servicios}
		<tr>
		</table>
		<input type="hidden" id="servicios-list" name="servicios" value='{proforma_servicios}'>
	</div>
	<br>
	<br>
	<hr>
	<br>
	<label>Cuentas de Pago:</label>
	<select name="cuenta">
		{cuentas_pago}
		<option value="{id_cuenta_pago}">{nombre} ({codigo})</option>
		{/cuentas_pago}
	</select>
	<br>
	<br>
	<input type="checkbox" id="confirmada" name="confirmada" style="display: inline; margin-bottom: 1em">
	<label for="confirmada">Confirmar esta actividad</label>
	<br>
	<br>
	<input type="submit" value="Guardar cambios" style="display: block;">
	<br>
	<br>
</form>

<form method="post" id="BProform" name="BProform" Action="{url_pdf}/{codigo_proforma}">
	<input type="submit" value="Enviar PDF" style="display: block;">
</form>

</div>