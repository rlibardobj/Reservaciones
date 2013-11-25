<?php class Model_Contactos extends CI_Model {

	const contacto = 'contactos';

	function contactos_por_nombre($nombre) {
		$this->db->select('*');
		$this->db->from(self::contacto);
		$this->db->like('Contacto', $nombre);
		return $this->db->get()->result();
	}

	function contactos_por_empresa($empresa) {
		$this->db->select('*');
		$this->db->from(self::contacto);
		$this->db->like('Empresa', $empresa);
		return $this->db->get()->result();
	}

	function contactos_por_sector($sector) {
		$this->db->select('*');
		$this->db->from(self::contacto);
		$this->db->like('Sector', $sector);
		return $this->db->get()->result();
	}

}