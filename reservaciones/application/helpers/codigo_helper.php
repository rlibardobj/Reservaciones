<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
	El formato de los códigos es el siguiente:

	PROFORMAS: CTEC-Prof-YYYYCCCC
	ACTIVIDADES: CTEC-Act-YYYYCCCC

	Con:
	YYYY = Año de la proforma o actividad
	CCCC = Código de la proforma o actividad
 */


function obtener_ultimo_codigo($row) {
	$año_actual = intval(date('Y'));
	if (is_object($row)) {
		$codigo = $row->codigo;
		$codigo = explode('-', $codigo);
		$codigo = $codigo[count($codigo) - 1];
		
		$año 	= intval(substr($codigo, 0, 4));
		$id 	= intval(substr($codigo, 4));

		if ($año_actual > $año) {
			$id = 1;
		} else {
			$id++;
		}
	} else {
		$id = 1;
	}

	$resultado = strval($año_actual) .
		sprintf('%1$04d', $id);
	
	return $resultado;
}
