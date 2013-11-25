<?php class Model_Cuentas_Pago extends CI_Model {

	const cuenta_pago = 'cuenta_pago';

	function __construct() {
		parent::__construct();
	}

	function cuentas_pago()
	{
		return $this->db->get(self::cuenta_pago)->result();
	}

}