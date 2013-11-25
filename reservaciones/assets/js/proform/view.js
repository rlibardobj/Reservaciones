$(function() {
	$espacios 	= $('#espacios-list');
	$servicios 	= $('#servicios-list');

	// Procesar servicios
	var servicios = eval( '(' + $servicios.val() + ')' );

	for (var i = 0; i < servicios.length; i++) {
		var servicio = servicios[i];
		$fila = $('.servicio-fila[data-servicio-id=' + servicio.servicio + ']');
		$check = $fila.find('.servicio-check');
		$check.prop('checked', true);
		$precio = $fila.find('.servicio-precio');
		$precio.val(servicio.precio);
		$cantidad = $fila.find('.servicio-cantidad');
		$cantidad.val(servicio.cantidad);
	};

	$('.servicio-precio').trigger('change');
	
	// Procesar espacios
	var espacios = eval( '(' + $espacios.val() + ')' );

	for (var i = 0; i < espacios.length; i++) {
		var espacio = espacios[i];
		var espacio = {
			nombre: espacio.nombre,
			inicio: moment(espacio.fecha_inicio),
			fin: moment(espacio.fecha_fin),
			precio: espacio.precio_base
		}
		insertarEspacioEnTabla(espacio);
	};
});