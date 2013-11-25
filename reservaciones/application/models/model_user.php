<?php class Model_User extends CI_Model {

	const usuario = 'usuarios';

	function __construct() {
		parent::__construct();
	}

	function validate($email, $contrasenna) {
		$q = $this->db
				->where('email', $email)
				->where('contrasenna', sha1($contrasenna))
				->limit(1)
				->get('usuarios');

		if ($q->num_rows > 0 )
			return $q->row();
		return false;
	}

	function check_key($key)
	{
		$q = $this->db
				->where('contrasenna', $key)
				->limit(1)
				->get('usuarios');
		$user = $q->row();
		if (is_null($user->api_calls)) {
			$user->api_calls = 1;
		} else {
			$user->api_calls += 1;
		}
		
		$this->db->where('usuario_id', $user->usuario_id);
		$this->db->update('usuarios', $user);

		return $q->num_rows > 0;
	}

	function usuario($email) {
		$q = $this->db
				->where('email', $email)
				->limit(1)
				->get('usuarios');
		if ($q->num_rows > 0 )
			return $q->row();
		return false;
	}

	function usuario_actualizar($usuario_id, $usuario) {
		$this->db->where('usuario_id', $usuario_id);
		$this->db->update(self::usuario, $usuario);

		$this->db->where('usuario_id', $usuario_id);
		return $this->db->get(self::usuario)->row();
	}
	
}