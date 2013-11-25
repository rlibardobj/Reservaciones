<style>

	body {
		margin-top: 20px;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
	}

	#calendar {
		width: 65%;
		margin: 0 auto;
	}

	#espacios-list {
		display: inline;
	}

	.confirmada, .pendiente {
		border-radius: 2px;
		color: #fff;
		background-color: #eee;
		padding: 0.16em;
	}

</style>
<div class="main">
	<h3>Calendario</h3>
	<p><span class="confirmada">Actividades confirmadas</span> <span class="pendiente">Actividades aún por confirmar</span></p>
	<label for="espacios-list">Puede realizar un filtro de eventos por espacio seleccionando uno en esta lista: </label>
	<select id="espacios-list">
		<option value="-1">Mostrar todos</option>
		{espacios}
		<option value="{id_espacio}">{descripcion}</option>
		{/espacios}
	</select>
	<input type="hidden" value='{lista}' id="data_calendar">
	<div id='calendar' style='font-size:14px'></div>
</div>