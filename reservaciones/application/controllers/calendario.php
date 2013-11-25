<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Calendario extends CI_Controller {

	function __construct() {
		parent::__construct();
		if ($this->session->userdata('usuario') === false) {
			redirect('');
		}
		$this->load->model('model_proform');
		$this->load->model('model_user');
		$this->load->model('model_espacios');
	}
	
	public function index()
	{
		$data['username'] = $this->session->userdata('usuario')->nombre;

		$data['title'] = 'Reservaciones';
		$data['js']    = array('lib/jquery', 'lib/jqueryui', 'home','lib/fullcalendar.min', 'calendar');
		$data['css']   = array('main', 'general/menu', 'lib/jqueryui','lib/fullcalendar');

		$actividades = $this->model_espacios->espacios_intervalos();
		$data['lista'] = json_encode($actividades);

		$data['espacios'] = $this->model_espacios->espacios();

		$this->load->view('general/head', $data);
		$this->load->view('general/header', $data);
		$this->load->view('general/menu', $data);
		$this->parser->parse('calendar',$data);
	}
}