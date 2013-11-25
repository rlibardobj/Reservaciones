var FECHA_LARGA = 'dddd D, MMMM, h:mm a',
	FECHA_HORA	= 'h:mm a',
	FECHA_SQL	= 'YYYY-MM-DD HH:mm:ss',
	FECHA_INPUT = 'MM/DD/YYYY hh:mm a';

var fechaInicio = moment(),
	fechaFin 	= moment();

var insertarEspacioEnTabla = function (espacio) {
		espacio.inicio.lang('es');
		fila = $('<tr>', {
			class: 'intervalo-fila'
		});
		fila.append($('<td>', {
			'text': espacio.nombre
		}));
		fila.append($('<td>', {
			'text': espacio.inicio.format(FECHA_LARGA)
		}));
		fila.append($('<td>', {
			'text': espacio.fin.format(FECHA_HORA)
		}));
		fila.append($('<td>', {
			'text': espacio.precio
		}));
		fila.append(
			$('<td>').append($('<a>', {
				"class": "button intervalo-eliminar",
				"type": "button",
				"value": "Eliminar"
			}).append($('<span>', {
				"class": "ui-icon ui-icon-trash"
			}))));
		$('#intervalo-tabla').append(fila);
		$('.button').button();
	}

var modoPrestamo = false;

$(function () {
	var servicios 			= [],
		espacios 			= [],
		intervalos			= [],
		nuevosIntervalos 	= [],
		intervaloInicio,
		intervaloFin,
		intervaloEspacio,
		intervaloEspacioNombre,
		intervaloEspacioModo,
		intervaloPrecioBase,
		intervaloValido = false;

	// Objetos del DOM cacheados
	$fechaContacto		= $('#fecha_contacto');
	$espaciosDialogo	= $('#dialog-espacios');
	$agregarButton 		= $('#espacios-agregar-button');
	$espaciosLista		= $('#espacios-list');
	$codigo				= $('#codigo');
	$serviciosCampos 	= $('.servicio-precio, .servicio-cantidad');
	$fechaInicio 		= $('#fecha_inicio');
	$fechaFin 			= $('#fecha_fin');
	$intervaloInicio 	= $('#intervalo_inicio');
	$intervaloFin 		= $('#intervalo_fin');

	// El menú toma el espacio vertical total
	$('.menu-proform').height($('form').height());
	
	// Darle a los botones y selectores la apariencia de JQuery UI
	$('input[type=button], input[type=submit]').button();
	$('.datepicker').datepicker();
	$('.datetimepicker').datetimepicker({
		timeFormat: "hh:mm tt",
		stepMinute: 10
	});

	// Alternar entre modo de préstamo y modo proforma
	$prestamoCheck = $('#prestamo');

	// Al hacer click en el checkbox, alternar el código de la actividad
	$prestamoCheck.on('change', function() {
		codigos = $('#codigos-proforma').val().split(',', 2);
		if ($(this).is(':checked')) {
			$codigo.val(codigos[1]);
			modoPrestamo = true;
		} else {
			$codigo.val(codigos[0]);
			modoPrestamo = false;
		}
		$(document).trigger('prestamo/cambioModo');
	});

	$(document).on('prestamo/cambioModo', function() {
		$.each($('.servicio-fila'), function(index, value) {
			var precio;
			if (modoPrestamo) {
				precio = 0;
			} else {
				precio = $(this).find('.servicio-precio').data('precio-base');
			}
			$(this).find('.servicio-precio').val(precio);
		});
	});

	// Inicializar la ventana diálogo
	$espaciosDialogo.dialog({
		autoOpen: false,
		modal: true,
		buttons: [
			{
				id: 'dialogo-agregar-boton',
				text: 'Agregar',
				click: function() {
					// Comprobar si el intervalo es válido
					if (intervaloValido) {
						
						// Insertar nuevo intervalo en lista en memoria
						nuevosIntervalos.push({
							"fecha_inicio": intervaloInicio.format(FECHA_SQL),
							"fecha_fin": intervaloFin.format(FECHA_SQL),
							"espacio": parseInt(intervaloEspacio, 10),
							"precio_base": intervaloPrecioBase
						});
					
						// Pasar la lista en memoria al input hidden
						$espaciosLista.val(JSON.stringify(nuevosIntervalos));
						
						// Recalcular inicio y fin de actividad
						if ($fechaInicio.val() === '') {
							fechaInicio = intervaloInicio;
							$fechaInicio.val(fechaInicio.format(FECHA_INPUT));
						} else {
							fechaInicio = moment($fechaInicio.val());
							if (intervaloInicio.isBefore(fechaInicio)) {
								fechaInicio = intervaloInicio;
								$fechaInicio.val(fechaInicio.format(FECHA_INPUT));
							}
						}
						if ($fechaFin.val() === '') {
							fechaFin = intervaloFin;
							$fechaFin.val(fechaFin.format(FECHA_INPUT));
						} else {
							fechaFin = moment($fechaFin.val());
							if (intervaloFin.isAfter(fechaFin)) {
								fechaFin = intervaloFin;
								$fechaFin.val(fechaFin.format(FECHA_INPUT));
							}
						}

						// Insertar en la tabla
						var espacio = {
							nombre: intervaloEspacioNombre,
							inicio: intervaloInicio,
							fin: intervaloFin,
							precio: intervaloPrecioBase
						}
						insertarEspacioEnTabla(espacio);

						// Limpiar campos en el diálogo
						$(this).find('input').val('');
						
						$espaciosDialogo.dialog("close");
					}
				}
			}
		]
	});

	// Utilizar la fecha de inicio como base para la fecha final
	// en las fechas del evento
	$fechaInicio.on('change', function () {
		$fechaFin.val($(this).val());
	});

	// Mostrar diálogo de espacios al hacer clic
	$agregarButton.on('click', function() {
		$espaciosDialogo.dialog('open');
		// Cargar mediante AJAX los intervalos existentes
		espaciosUrl = $('#espacios-url').val();
		$.ajax({
			url: espaciosUrl
		}).done(function(data) {
			intervalos = eval('('+data+')');
			$('#dialogo-espacio-list').trigger('change');
		});
	});

	// Utilizar la fecha de inicio como base para la fecha final
	$intervaloInicio.on('change', function() {
		$intervaloFin.val($(this).val());
	});

	// Al realizar un cambio en los controles del diálogo, comprobar la disponibilidad
	// del horario seleccionado para la inserción de un nuevo intervalo
	$('#intervalo_inicio, #intervalo_fin, #dialogo-espacio-list').on('change', function() {
		inicioText	= $('#intervalo_inicio').val(),
		finText	= $('#intervalo_fin').val();

		// Ignorar si hay controles vacíos
		if (inicioText === '' || finText === '') {
			return;
		}

		$intervaloNotificacionTexto = $('#intervalo-notificacion-texto');
		$intervaloNotificacion 		= $('#intervalo-notificacion');
		$intervaloEspacioSelect		= $('#dialogo-espacio-list');
		$BotonAgregar = $('#dialogo-agregar-boton');

		intervalo_inicio		= moment(inicioText),
		intervalo_fin			= moment(finText),
		intervalo_espacio		= $intervaloEspacioSelect.val();

		resultado = comprobar(intervalo_inicio, intervalo_fin, intervalo_espacio);

		if (resultado.disponible) {
			intervaloInicio 	= intervalo_inicio;
			intervaloFin 		= intervalo_fin;
			intervaloEspacio 	= parseInt(intervalo_espacio, 10);
			intervaloEspacioNombre = $intervaloEspacioSelect.find('option:selected').text();
			intervaloValido		= true;
			$intervaloNotificacion.removeClass('ui-state-error');
			$intervaloNotificacion.addClass('ui-state-highlight');
			$intervaloNotificacionTexto.text('Espacio disponible en fecha dada.');

			$BotonAgregar.button('enable');
		} else {
			colisionInicio 	= moment(resultado.colision.fecha_inicio),
			colisionFin		= moment(resultado.colision.fecha_fin);
			intervaloValido	= false;

			$intervaloNotificacion.addClass('ui-state-error');
			$intervaloNotificacion.removeClass('ui-state-highlight');
			if (typeof resultado.colision.codigo === "undefined") {
				// Colisión es local
				$intervaloNotificacionTexto.text(
					'Este intervalo se superpone a otro intervalo en esta proforma la cual inicia el '
					+ colisionInicio.format(FECHA_LARGA)
					+ ' y finaliza ' + colisionFin.format(FECHA_HORA));
			} else {
				// Colisión con actividad existente
				$intervaloNotificacionTexto.text('Este intervalo se superpone a la actividad con código '
					+ resultado.colision.codigo + ', la cual inicia el ' + colisionInicio.format(FECHA_LARGA)
					+ ' y finaliza ' + colisionFin.format(FECHA_HORA));
			}
			$BotonAgregar.button('disable');
		}
	});

	// Modificar precios al seleccionar un espacio distinto
	$('#dialogo-espacio-list').on('change', function() {
		intervaloPrecioBase = $(this).find(':selected').data('precio');
		if (!modoPrestamo) {
			precioBase = $('#intervalo_precio');
			precioBase.val(intervaloPrecioBase);
		} else {
			precioBase.val(0);
		}
	});
	$('#dialogo-espacio-list').trigger('change');

	$(document).on('click', '.intervalo-eliminar', function() {
		$fila = $(this).closest('.intervalo-fila');
		$fila.remove();
	});

	$serviciosCampos.on('change', function() {
		$servicio 	= $(this).closest('.servicio-fila');
		$precio 	= $servicio.find('.servicio-precio');
		$cantitad 	= $servicio.find('.servicio-cantidad');
		$total 		= $servicio.find('.servicio-total');

		total = parseInt($precio.val(), 10) * parseInt($cantitad.val(), 10);
		$total.text(total);
	});

	$serviciosCampos.trigger('change');

	$('.servicio-check').on('change', function () {
		$servicio 	= $(this).closest('.servicio-fila');
		id_servicio = $servicio.data('servicio-id');
		
		if ($(this).is(':checked')) {
			// Add to object
			$servicio 	= $(this).closest('.servicio-fila');
			$precio 	= $servicio.find('.servicio-precio');
			$cantidad 	= $servicio.find('.servicio-cantidad');
			precio 		= parseInt($precio.val(), 10);
			cantidad 	= parseInt($cantidad.val(), 10);

			servicios.push({
				"servicio": id_servicio,
				"precio": precio,
				"cantidad": cantidad
			});
		} else {
			// Remove from object
			for (var i = 0; i < servicios.length; i++) {
				if (servicios[i].id_servicio == id_servicio) {
					servicios.splice(i, 1);
				}
			};
		}
		$('#servicios-list').val(JSON.stringify(servicios));
	})

	function comprobar(inicio, fin, espacio) {
		resultado = {
			"disponible": true
		};
		if (moment.isMoment(inicio) && moment.isMoment(fin)) {
			// if (inicio.isBefore(fin))
			for (i = 0; i < intervalos.length; i++) {

				intervalo = intervalos[i];
				if (intervalo.id_espacio == espacio) {
					
					var intervalo_inicio 	= moment(intervalo.fecha_inicio),
						intervalo_fin 		= moment(intervalo.fecha_fin);

					if ((intervalo_inicio.isBefore(inicio) ||
							intervalo_inicio.isSame(inicio))
						&& intervalo_fin.isAfter(inicio)) {
						// |------||------|    ||
						// Actividad se superpone al inicio
						resultado.disponible 	= false;
						resultado.colision 		= intervalo;
						break;
					} else if (intervalo_inicio.isAfter(inicio) &&
						intervalo_inicio.isBefore(fin)) {
						// ||      |------||----|
						// Actividad se superpone al final
						resultado.disponible 	= false;
						resultado.colision 	= intervalo;
						break;
					}
				}
			}

			for (var i = 0; i < nuevosIntervalos.length; i++) {
				var intervalo = nuevosIntervalos[i];
				if (intervalo.espacio == espacio) {
					var intervalo_inicio	= moment(intervalo.fecha_inicio),
						intervalo_fin 		= moment(intervalo.fecha_fin);
					if (intervalo_inicio.isBefore(inicio) &&
						intervalo_fin.isAfter(inicio)) {
						// |------||------|    ||
						// Actividad se superpone al inicio
						resultado.disponible 	= false;
						resultado.colision 		= intervalo;
						break;
					} else if (intervalo_inicio.isAfter(inicio) &&
						intervalo_inicio.isBefore(fin)) {
						// ||      |------||----|
						// Actividad se superpone al final
						resultado.disponible 	= false;
						resultado.colision 	= intervalo;
						break;
					}
				}
			};
		} else {
			resultado = {
				"disponible": false
			};
		}
		return resultado;
	}
});