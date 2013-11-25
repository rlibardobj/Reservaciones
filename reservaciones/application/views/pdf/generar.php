<body>
	<style>
		td {
			text-align: center;
		}
	</style>
	<div style="text-align:center">
	<p>Centro de Transferencia Tecnológica y Educación Continúa</p>
	<p>Instituto Tecnológico de Costa Rica</p>
	<p>Cédula Jurídica: 4000-042145-07</p>			
	<h3>Proforma</h3>
	</div>
{proforma}
	<div style="text-align:right">
		<p>Fecha de Contacto: 
		{fecha_contacto}</p>
		</div>
			
<p>Proforma: {codigo}</p>
<p>Cliente: {cliente}</p>
<p>Atención: {atencion}</p>
<br>
Descripción: {descripcion}
<br>
<p>Su actividad se llevará a cabo entre el {fecha_inicio} y el {fecha_fin}</p>

<p>El pago se puede realizar por transferencia o cheque a nombre de ITCR a la cuenta {cuenta_codigo} del {cuenta_nombre}.</p>
{/proforma}
<p>*No se incluye publicidad, propaganda o materiales.</p>

<p>A continuación se detallan los servicios solicitados:</p>

		<table border="0" id="tabla-servicios">
			<tr>
				<th>Detalle</th>
				<th>Cantidad</th>
				<th>Costo</th>
			</tr>
			{servicios}
			<tr>
				<td>{descripcion}</td>
				<td>{cantidad}</td>
				<td>{costo_total}</td>
			</tr>
			{/servicios}
		<tr>
		</table>

<p>A continuación se detallan los espacios físicos solicitados:</p>

		<table border="0" id="tabla-servicios" visibility: hidden;>
			<tr>
				<th>Lugar</th>
				<th>Fecha Inicio</th>
				<th>Fecha Finalización</th>
				<th>Costo</th>
			</tr>
			{espacios}
			<tr>
				<td>{nombre}</td>
				<td>{fecha_inicio}</td>
				<td>{fecha_fin}</td>
				<td>{costo}</td>
			</tr>
			{/espacios}
		<tr>
		</table>
		
<div style="text-align:right">
<p>Costo total: {costo_final}</p>
</div>
<br>
<div style="text-align:center">
<p> __________________________________________</p>	
<p>MAP. Rogelio González Quirós</p>
<p>Director CTEC</p>
</div>
<div style="text-align:right">
<p>Teléfono: 2401-3002</p>
<p>FAX: 2475-5395</p>
</div>
</body>