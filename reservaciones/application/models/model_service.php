<?php class Model_Service extends CI_Model {

	const servicio = 'servicios';

	function __construct() {
		parent::__construct();
	}

	function services()
	{
		return $this->db->get( self::servicio )->result();
	}

}