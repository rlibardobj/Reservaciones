<?php class Model_Proform extends CI_Model {

	const actividad 					= 'actividad';
	const actividad_espacio 			= 'actividad_espacio';
	const actividad_espacio_intervalo 	= 'actividad_espacio_intervalo';
	const actividad_servicio 			= 'actividad_servicio';
	const actividad_email 				= 'actividad_email';
	const espacio 						= 'espacio';
	const value_one						= 1;

	function __construct() {
		parent::__construct();
	}

	function proforma($id_proforma) {
		$q = $this->db
				->where('id_proforma', $id_proforma)
				->limit(1)
				->get(self::actividad);
		return $q->result();
	}
	
	function proforma_detalle($id_proforma) {
		$q = $this->db
				->select('actividad.*, cuenta_pago.nombre as cuenta_nombre, cuenta_pago.codigo as cuenta_codigo')
				->from(self::actividad)
				->where('id_proforma', $id_proforma)
				->join('cuenta_pago','cuenta_pago.id_cuenta_pago = actividad.cuenta_pago','inner')
				->limit(1)
				->get();
		return $q->result();
	}

	function proformas($date = 'todas') {
		$this->db->select('*');
		$this->db->from(self::actividad);
		switch ($date) {
			case 'siguientes':
				$this->db->where('fecha_inicio >= NOW()');
				break;
			case 'pasadas':
				$this->db->where('fecha_fin <= NOW()');
				break;
			default:
				break;
		}
		return $this->db->get()->result();
	}

	function proformas_pendientes() {
		$this->db->select('*');
		$this->db->from(self::actividad);
		$this->db->where('confirmada = 0');
		$this->db->order_by( 'fecha_inicio', 'desc' );
		return $this->db->get()->result();
	}

	function proformas_confirmadas() {
		$this->db->select('*');
		$this->db->from(self::actividad);
		$this->db->where('confirmada = 1');
		$this->db->order_by( 'fecha_inicio', 'desc' );
		return $this->db->get()->result();
	}

	function actividades($date = 'todas') {
		$this->db->select('id_proforma, codigo, descripcion, fecha_inicio, fecha_fin');
		$this->db->from(self::actividad);
		$this->db->where('confirmada = 1');
		switch ($date) {
			case 'siguientes':
				$this->db->where('fecha_inicio >= NOW()');
				break;
			case 'pasadas':
				$this->db->where('fecha_fin <= NOW()');
				break;
			default:
				break;
		}
		return $this->db->get()->result();
	}

	function proforma_emails($id_proforma) {
		$q = $this->db
				->where('proforma', $id_proforma)
				->get(self::actividad_email);
		return $q->result();
	}

	function proforma_servicios($id_proforma) {
		$this->db->select('*');
		$this->db->from(self::actividad_servicio);
		$this->db->where('proforma', $id_proforma);
		$this->db->join('servicios', 'servicios.id_servicio = actividad_servicio.servicio', 'inner');
		return $this->db->get()->result();
	}

	function proforma_espacios($id_proforma) {
		$q = $this->db
				->where('proforma', $id_proforma)
				->join(self::espacio, 'actividad_espacio.espacio = espacio.id_espacio', 'inner')
				->get(self::actividad_espacio);
		return $q->result();
	}

	function proforma_espacio_intervalos($id_proforma)
	{
		$q = $this->db
				->select('fecha_inicio, fecha_fin, intervalos.espacio,
					precio as precio_base, espacio.descripcion as nombre, espacio.modo_alquiler as alquiler')
				->from(self::actividad_espacio_intervalo . ' as intervalos')
				->where('actividad_espacio.proforma', $id_proforma)
				->join(self::actividad_espacio, 'intervalos.proforma_espacio =
					actividad_espacio.id_proforma_espacio', 'inner')
				->join(self::espacio, 'intervalos.espacio =
					espacio.id_espacio', 'inner')
				->get();
		return $q->result();
	}

	function proforma_espacio_intervalos_por_proforma_espacio($id_proforma_espacio)
	{
		$q = $this->db
				->select('fecha_inicio, fecha_fin, intervalos.espacio,
					precio as precio_base, espacio.descripcion as nombre')
				->from(self::actividad_espacio_intervalo . ' as intervalos')
				->where('actividad_espacio.id_proforma_espacio', $id_proforma_espacio)
				->join(self::actividad_espacio, 'intervalos.proforma_espacio =
					actividad_espacio.id_proforma_espacio', 'inner')
				->join(self::espacio, 'intervalos.espacio =
					espacio.id_espacio', 'inner')
				->get();
		return $q->result();
	}

	function proforma_insertar($proforma) {
		$this->db->insert( self::actividad, $proforma );
		return $this->db->insert_id();
	}

	function proforma_espacios_insertar($proforma_espacio) {
		$this->db->insert( self::actividad_espacio, $proforma_espacio );
		return $this->db->insert_id();
	}

	function proforma_espacios_intervalos_insertar($proforma_espacio_intervalo) {
		$this->db->insert( self::actividad_espacio_intervalo, $proforma_espacio_intervalo );
		return $this->db->insert_id();
	}

	function proforma_servicios_insertar($proforma_servicio) {
		$this->db->insert( self::actividad_servicio, $proforma_servicio );
		return $this->db->insert_id();
	}

	function proforma_emails_insertar($proforma_email) {
		$this->db->insert( self::actividad_email, $proforma_email );
		return $this->db->insert_id();
	}

	function proforma_calendario() {
		$this->db->select('codigo,descripcion,fecha_inicio,fecha_fin');
		$this->db->from(self::actividad);
		$this->db->order_by('fecha_inicio', 'desc');
		$this->db->where('confirmada', self::value_one ); 
		return $this->db->get()->result();
	}

	function ultimo_codigo($tipo) {
		$this->db->select( 'codigo' );
		$this->db->from( self::actividad );
		$this->db->where( "prestamo = $tipo" );
		$this->db->order_by( 'codigo', 'desc' );
		$this->db->limit( 1 );
		return $this->db->get()->row();
	}

	function codigo_ultima_proforma() {
		return self::ultimo_codigo(0);
	}

	function codigo_ultimo_prestamo() {
		return self::ultimo_codigo(1);
	}

	function actividad_editar($id_actividad, $actividad) {
		$this->db->where('id_proforma', $id_actividad);
		$this->db->update(self::actividad, $actividad);
	}

	function actividad_eliminar($id_actividad) {
		$this->db->where( 'id_proforma', $id_actividad );
		$this->db->delete( self::actividad );
	}
}