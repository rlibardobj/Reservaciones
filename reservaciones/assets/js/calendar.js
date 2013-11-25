$(document).ready(function() {	
	var $calendario = $('#calendar'),
		$espacios	= $('#espacios-list');
	var date = new Date();
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();

	var datos = eval('(' + $('#data_calendar').val() + ')');
	var eventos = [];

	var colorConfirmado = '#6cc655',
		colorPendiente  = '#aaaaaa';

	$('.confirmada').css('background-color', colorConfirmado);
	$('.pendiente').css('background-color', colorPendiente);

	// El menú toma el espacio vertical total
	$('.menu-proform').height($(window).height() + 200);

	var actualizarCalendario = function(datosMostrados) {
		var eventosMostrados = [];
		for (var i = datosMostrados.length - 1; i >= 0; i--) {
			evento = datosMostrados[i];
			eventosMostrados.push({
				title: evento.descripcion,
				start: new Date(evento.fecha_inicio),
				end: new Date(evento.fecha_fin),
				// Mostrar todo el día para espacios con modo alquiler = 2
				allDay: (evento.modo_alquiler == 2),
				color: (evento.confirmado == '0') ? colorPendiente : colorConfirmado
			});
		}

		$calendario.empty();
		$calendario.fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			editable: false,
			events: eventosMostrados
		});
	}

	$espacios.on('change', function() {
		var datosMostrados;
		var filtro = $(this).find(':selected').val();
		if (filtro > -1) {
			datosMostrados = [];
			for (var i = datos.length - 1; i >= 0; i--) {
				if (datos[i].espacio == filtro) {
					datosMostrados.push(datos[i]);
				}
			};
		} else {
			datosMostrados = datos;
		}
		actualizarCalendario(datosMostrados);
	});

	$espacios.trigger('change');
	
});