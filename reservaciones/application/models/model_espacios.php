<?php class Model_Espacios extends CI_Model {

	const espacio = 'espacio';
	const intervalo = 'actividad_espacio_intervalo';
	const actividad = 'actividad';

	function __construct() {
		parent::__construct();
	}

	function espacios()
	{
		return $this->db->get( self::espacio )->result();
	}

	function intervalos_siguientes() {
		$this->db->select('intervalos.fecha_inicio, intervalos.fecha_fin,
			intervalos.proforma, espacio.descripcion as espacio_descripcion,
			espacio.id_espacio, actividad.descripcion as proforma_descripcion,
			actividad.codigo');
		$this->db->from( self::intervalo . ' as intervalos' );
		$this->db->where( 'actividad.confirmada = 1' );
		$this->db->join( self::espacio, 'intervalos.espacio = espacio.id_espacio', 'inner' );
		$this->db->join( self::actividad, 'intervalos.proforma = actividad.id_proforma', 'inner' );
		return $this->db->get()->result();
	}
	
	function espacios_intervalos() {
		$this->db->select( 'intervalos.fecha_inicio, intervalos.fecha_fin,
			intervalos.espacio, espacio.descripcion, actividad.confirmada as confirmado, 
			espacio.modo_alquiler' );
		$this->db->from( self::intervalo . ' as intervalos' );
		$this->db->join( self::espacio, 'intervalos.espacio = espacio.id_espacio', 'inner' );
		$this->db->join( self::actividad, 'intervalos.proforma = actividad.id_proforma', 'inner' );
		return $this->db->get()->result();
	}

	function intervalos_con_descripcion() {
		$this->db->select( 'intervalos.fecha_inicio, intervalos.fecha_fin,
			intervalos.espacio, espacio.descripcion, espacio.modo_alquiler,
			actividad.confirmada as confirmado, actividad.descripcion as descripcion_actividad,
			actividad.codigo' );
		$this->db->from( self::intervalo . ' as intervalos' );
		$this->db->where( 'intervalos.fecha_inicio >= NOW()' );
		$this->db->join( self::espacio, 'intervalos.espacio = espacio.id_espacio', 'inner' );
		$this->db->join( self::actividad, 'intervalos.proforma = actividad.id_proforma', 'inner' );
		return $this->db->get()->result();
	}

}