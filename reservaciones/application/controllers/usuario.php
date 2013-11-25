<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario extends CI_Controller {

	public function index() {

		if ($this->session->userdata('usuario') === false) {
			redirect('');
		}

		$this->load->library('form_validation');

		$this->form_validation->set_rules('contrasenna');

		if ($this->form_validation->run() !== false) {

			// Autentificar nuevamente antes de cambiar datos
			$email_actual = $this->session->userdata('usuario')->email;
			$contrasenna = $this->input->post('contrasenna');
			$this->load->model('model_user');
			$usuario = $this->model_user->validate($email_actual, $contrasenna);

			if ($usuario !== false) {
				$nuevo_nombre = $this->input->post('nombre');
				$nuevo_email = $this->input->post('email');
				$nueva_contrasenna = $this->input->post('nueva_contrasenna');

				$nuevo_usuario = new stdClass();
				if ($nuevo_nombre !== '') {
					$nuevo_usuario->nombre = $nuevo_nombre;
				}
				if ($nuevo_email !== '') {
					$nuevo_usuario->email = $nuevo_email;
				}
				if ($nueva_contrasenna !== '') {
					$nuevo_usuario->contrasenna = sha1($nueva_contrasenna);
				}
				$nuevo_usuario = $this->model_user->usuario_actualizar($usuario->usuario_id, $nuevo_usuario);

				// Actualizar la sesión
				$this->session->set_userdata('usuario', $nuevo_usuario);
				$data['notificacion'] = 'Datos actualizados correctamente';
			} else {
				// No se pudo autentificar correctamente
				$data['notificacion'] = 'No se pudo autentificar correctamente';
			}
		} else {
			$data['notificacion'] = '';
		}
		$data['title'] = 'Usuario';
		$data['js']	= array('lib/jquery', 'lib/jqueryui', 'home');
		$data['css']   = array('main', 'home', 'lib/jqueryui', 'usuario/perfil');

		$data['username'] = $this->session->userdata('usuario')->nombre;
		$data['email'] = $this->session->userdata('usuario')->email;

		$this->load->view('general/head', $data);
		$this->load->view('general/header');
		$this->parser->parse('usuario/perfil', $data);
		$this->load->view('general/footer');
	}

	public function login() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email', 'email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'contraseña', 'required');

		$this->form_validation->set_message('required', 'El campo %s es requerido.');
		$this->form_validation->set_message('valid_email', 'El campo %s debe ser un email válido.');

		if ($this->form_validation->run() !== false ) {
			$this->load->model('model_user');
			$res = $this->model_user->validate(
				$this->input->post('email'),
				$this->input->post('password'));
			if ($res !== false) {
				$this->session->set_userdata('usuario', $res);
				redirect('');
			}
		}

		$data['title'] = 'Reservaciones';
		$data['js']    = array('lib/jquery', 'lib/jqueryui', 'lib/fullcalendar', 'home');
		$data['css']   = array('main', 'home', 'lib/jqueryui', 'lib/fullcalendar', 'usuario/login');

		$this->load->view('general/head', $data);
		$this->load->view('general/header');
		$this->load->view('usuario/login');
	}

	public function salir() {
		$this->session->sess_destroy();
		redirect('');
	}
}