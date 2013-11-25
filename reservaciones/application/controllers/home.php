<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index()
	{
		if ($this->session->userdata('usuario') === false) {
			$data['title'] = 'Reservaciones';
			$data['js']    = array('lib/jquery', 'lib/jqueryui', 'lib/fullcalendar', 'home');
			$data['css']   = array('main', 'home', 'lib/jqueryui', 'lib/fullcalendar', 'usuario/login');

			$this->load->view('general/head', $data);
			$this->load->view('general/header');
			$this->load->view('usuario/login');
		} else {
			$data['title'] = 'Reservaciones';
			$data['js']    = array('lib/jquery', 'lib/jqueryui', 'lib/fullcalendar', 'home');
			$data['css']   = array('main', 'home', 'lib/jqueryui', 'lib/fullcalendar', 'general/menu');

			$data['username'] = $this->session->userdata('usuario')->nombre;
			
			$this->load->view('general/head', $data);
			$this->load->view('general/header', $data);
			$this->load->view('general/menu', $data);
			$this->load->view('general/home', $data);
		}
		$this->load->view('general/footer');
	}
}
