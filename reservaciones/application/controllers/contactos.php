<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contactos extends CI_Controller {

	function __construct() {
		parent::__construct();
		if ($this->session->userdata('usuario') === false) {
			redirect('');
		}
	}

	public function index() {
		redirect('');
	}

	public function buscar() {
		if ($this->session->userdata('usuario') === false) {
			redirect('');
		}
		
		$this->load->model('model_contactos');

		$texto = $this->input->get('texto');

		$data['resultados_empresa'] = array();
		$data['resultados_sector'] = array();
		$data['empresa_titulo'] = '';
		$data['sector_titulo'] = '';
		
		if ($texto !== false) {
			$data['resultados'] = $this->model_contactos->contactos_por_nombre(
				$this->input->get('texto'));
			if ($this->input->get('empresa') !== false) {
				$data['resultados_empresa'] = $this->model_contactos->contactos_por_empresa($texto);
				$data['empresa_titulo'] = 'Resultados por empresa';
			}
			if ($this->input->get('sector') !== false) {
				$data['resultados_sector'] = $this->model_contactos->contactos_por_sector($texto);
				$data['sector_titulo'] = 'Resultados por sector';
			}
		} else {
			$data['resultados'] = array();
		}
		
		$data['js']    = array('lib/jquery', 'lib/jqueryui');
		$data['css']   = array('main', 'general/menu', 'lib/jqueryui');
		$data['username'] = $this->session->userdata('usuario')->nombre;

		$this->load->view('general/head', $data);
		$this->load->view('general/header', $data);
		$this->load->view('general/menu', $data);
		$this->parser->parse('contacto/buscar', $data);
		$this->load->view('general/footer', $data);
	}
}