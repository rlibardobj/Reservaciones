<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Actividades extends CI_Controller {

	const date_input_format = 'm/d/Y';
	const datetime_input_format = 'm/d/Y h:i a';
	const datetime_human_format = 'd/m/Y h:i a';
	const date_sql_format = 'Y-m-d';
	const datetime_sql_format = 'Y-m-d G:i';
	const datetime_sql_format_with_seconds = 'Y-m-d G:i:s';

	public function index() { 
		if ($this->session->userdata('usuario') === false) {
			redirect('');
		}
		$data['username'] = $this->session->userdata('usuario')->nombre;

		$data['title'] = 'Reservaciones';
		$data['js']    = array('lib/jquery', 'lib/jqueryui', 'home');
		$data['css']   = array('main', 'general/menu', 'lib/jqueryui');

		$this->load->view('general/head', $data);
		$this->load->view('general/header');
		$this->load->view('general/menu', $data);
	}

	public function nueva() {
		if ($this->session->userdata('usuario') === false) {
			redirect('');
		}

		$this->load->library('form_validation');

		// Agregar todo lo que sea requerido
		$this->form_validation->set_rules('codigo', 'codigo', 'required');
		$this->form_validation->set_rules('cliente', 'cliente', 'required');
		$this->form_validation->set_rules('descripcion', 'descripcion', 'required');

		if ($this->form_validation->run() !== false) {
			$recibido = $this->input->post();
			$proforma = new stdClass();
			$proforma->codigo 			= $recibido['codigo'];
			$proforma->cliente 			= $recibido['cliente'];
			
			$fecha_contacto = DateTime::createFromFormat(self::date_input_format,
				$recibido['fecha_contacto']);
			$proforma->fecha_contacto 	= $fecha_contacto->format(self::date_sql_format);

			$fecha_inicio = DateTime::createFromFormat(self::datetime_input_format,
				$recibido['fecha_inicio']);
			$proforma->fecha_inicio 	= $fecha_inicio->format(self::datetime_sql_format);
			
			$fecha_fin = DateTime::createFromFormat(self::datetime_input_format,
				$recibido['fecha_fin']);
			$proforma->fecha_fin 		= $fecha_fin->format(self::datetime_sql_format);

			$proforma->atencion 		= $recibido['atencion'];
			$proforma->descripcion 		= $recibido['descripcion'];
			$proforma->cuenta_pago		= $recibido['cuenta'];
			$proforma->prestamo			= isset($recibido['prestamo']) ? 1 : 0;
			$proforma->cupo 			= $recibido['cupo'];
			
			$this->load->model('model_proform');
			$id_proforma = $this->model_proform->proforma_insertar($proforma);
			
			// Procesar emails
			if ($recibido['emails'] !== '') {
				$emails = explode(',', $recibido['emails']);
				foreach ($emails as $email) {
					$direccion = $email;
					$email = new stdClass();
					$email->email = $direccion;
					$email->proforma = $id_proforma;
					$this->model_proform->proforma_emails_insertar($email);
				}
			}

			// Procesar espacios
			$espacios = json_decode($recibido['espacios']);
			if (!is_array($espacios)) {
				$espacios = array();
			}
			// El arreglo contendrá cada espacio como llave
			// y el ID insertardo como valor
			$espacios_agregados = array();
			
			foreach ($espacios as $espacio_intervalo) {
				$id_espacio = $espacio_intervalo->espacio;
				// El espacio debe ser ingresado en la tabla de
				// proformas - espacios también
				if (!array_key_exists($id_espacio, $espacios_agregados)) {
					$proforma_espacio = new stdClass();
					$proforma_espacio->proforma = $id_proforma;
					$proforma_espacio->espacio = $id_espacio;
					$proforma_espacio->precio = $espacio_intervalo->precio_base;
					
					$inserted_id = $this->model_proform->proforma_espacios_insertar($proforma_espacio);
					$espacios_agregados[$id_espacio] = $inserted_id;
				}
				
				// Insertar espacio intervalo
				$proforma_espacio_intervalo = new stdClass();
				$proforma_espacio_intervalo->proforma_espacio = $espacios_agregados[$id_espacio];
				$proforma_espacio_intervalo->fecha_inicio = $espacio_intervalo->fecha_inicio;
				$proforma_espacio_intervalo->fecha_fin = $espacio_intervalo->fecha_fin;
				$proforma_espacio_intervalo->proforma = $id_proforma;
				$proforma_espacio_intervalo->espacio = $id_espacio;
				$this->model_proform->proforma_espacios_intervalos_insertar($proforma_espacio_intervalo);
			}
			
			// Procesar servicios
			$servicios = json_decode($recibido['servicios']);
			if (!is_array($servicios)) {
				$servicios = array();
			}
			foreach ($servicios as $servicio) {
				$servicio->proforma = $id_proforma;
				$this->model_proform->proforma_servicios_insertar($servicio);
			}

			// Redirigir a lista de pendientes
			redirect('actividades/pendientes');

		} else {
			$data['username'] = $this->session->userdata('usuario')->nombre;

			$this->load->model('model_service');
			$data['servicios'] = $this->model_service->services();
			$this->load->model('model_cuentas_pago');
			$data['cuentas_pago'] = $this->model_cuentas_pago->cuentas_pago();
			$this->load->model('model_espacios');
			$data['espacios'] = $this->model_espacios->espacios();
			$data['espacios_url'] = base_url('actividades/intervalos');

			$this->load->model('model_proform');
			$ultima_proforma = $this->model_proform->codigo_ultima_proforma();
			$ultimo_prestamo = $this->model_proform->codigo_ultimo_prestamo();
			$this->load->helper('codigo');
			$ultima_proforma = obtener_ultimo_codigo($ultima_proforma);
			$ultimo_prestamo = obtener_ultimo_codigo($ultimo_prestamo);
			
			$data['codigo_proforma'] = 'CTEC-Prof-'.$ultima_proforma;
			$data['codigo_prestamo'] = 'CTEC-Act-'.$ultimo_prestamo;

			$data['title'] = 'Reservaciones';
			$data['js']    = array('lib/jquery', 'lib/jqueryui', 'lib/timepicker', 'lib/moment', 'lib/moment.es', 'proform/add');
			$data['css']   = array('main', 'general/menu', 'lib/jqueryui', 'lib/timepicker', 'proform/add');

			$this->load->view('general/head', $data);
			$this->load->view('general/header', $data);
			$this->load->view('general/menu', $data);
			$this->parser->parse('actividad/nueva', $data);
		}
	}

	public function pendientes() {
		if ($this->session->userdata('usuario') === false) {
			redirect('');
		}

		$data['username'] = $this->session->userdata('usuario')->nombre;

		$data['title'] = 'Reservaciones';
		$data['js']    = array('lib/jquery', 'lib/jqueryui');
		$data['css']   = array('main', 'general/menu', 'lib/jqueryui', 'proform/table');

		$this->load->model('model_proform');
		$data['pendientes'] = $this->model_proform->proformas_pendientes();
		foreach ($data['pendientes'] as $pendiente) {
			$fecha_inicio = DateTime::createFromFormat(self::datetime_sql_format_with_seconds,
				$pendiente->fecha_inicio);
			$pendiente->fecha_inicio = $fecha_inicio->format(self::datetime_input_format);
			
			$fecha_fin = DateTime::createFromFormat(self::datetime_sql_format_with_seconds,
				$pendiente->fecha_fin);
			$pendiente->fecha_fin = $fecha_fin->format(self::datetime_input_format);
		}

		$data['view_url'] = base_url('actividades/ver');
		$data['eliminar_url'] = base_url('actividades/eliminar');

		$this->load->view('general/head', $data);
		$this->load->view('general/header');
		$this->load->view('general/menu', $data);
		$this->parser->parse('actividad/pendientes', $data);
		$this->load->view('general/footer', $data);
	}

	public function confirmadas() {
		if ($this->session->userdata('usuario') === false) {
			redirect('');
		}

		$data['username'] = $this->session->userdata('usuario')->nombre;

		$data['title'] = 'Reservaciones';
		$data['js']    = array('lib/jquery', 'lib/jqueryui');
		$data['css']   = array('main', 'general/menu', 'lib/jqueryui', 'proform/table');

		$this->load->model('model_proform');
		$data['confirmadas'] = $this->model_proform->proformas_confirmadas();
		foreach ($data['confirmadas'] as $pendiente) {
			$fecha_inicio = DateTime::createFromFormat(self::datetime_sql_format_with_seconds,
				$pendiente->fecha_inicio);
			$pendiente->fecha_inicio = $fecha_inicio->format(self::datetime_input_format);
			
			$fecha_fin = DateTime::createFromFormat(self::datetime_sql_format_with_seconds,
				$pendiente->fecha_fin);
			$pendiente->fecha_fin = $fecha_fin->format(self::datetime_input_format);
		}

		$data['view_url'] = base_url('actividades/ver');

		$this->load->view('general/head', $data);
		$this->load->view('general/header');
		$this->load->view('general/menu', $data);
		$this->parser->parse('actividad/confirmadas', $data);
		$this->load->view('general/footer', $data);
	}

	public function ver($id_actividad) {
		if ($this->session->userdata('usuario') === false) {
			redirect('');
		}
	
		$data['css']   = array('main', 'general/menu', 'lib/jqueryui', 'proform/table');
	
		$this->load->library('form_validation');
		$this->load->model('model_proform');
		$this->form_validation->set_rules('confirmada', 'confirmada', 'required');

		if ($this->form_validation->run() !== false) {
			$actividad = new stdClass();
			$actividad->confirmada = 1;
			$this->model_proform->actividad_editar($id_actividad, $actividad);
			redirect('actividades/confirmadas');
		}

		$proforma = $this->model_proform->proforma($id_actividad);
		if (count($proforma) < 1) {
			redirect('actividades/pendientes');
		}

		$data['username'] = $this->session->userdata('usuario')->nombre;

		$data['title'] = 'Reservaciones';
		$data['js']    = array('lib/jquery', 'lib/jqueryui', 'lib/timepicker', 'lib/moment',
			'lib/moment.es', 'proform/add', 'proform/view');
		$data['css']   = array('main', 'general/menu', 'lib/jqueryui', 'lib/timepicker',
			'proform/add', 'proform/vista');
		$data['url_pdf'] = base_url('pdf/generar');

		$this->load->model('model_service');
		$this->load->model('model_cuentas_pago');
		$this->load->model('model_espacios');

		// Procesar proforma
		$proforma = $proforma[0];
		$proforma->fecha_inicio =
			DateTime::createFromFormat(self::datetime_sql_format_with_seconds,
				$proforma->fecha_inicio);
		$proforma->fecha_inicio =
			$proforma->fecha_inicio->format(self::datetime_input_format);
		
		$proforma->fecha_fin =
			DateTime::createFromFormat(self::datetime_sql_format_with_seconds,
				$proforma->fecha_fin);
		$proforma->fecha_fin = $proforma->fecha_fin->format(self::datetime_input_format);
		
		$data['proforma'] = array($proforma);
		$data['codigo_proforma']=$proforma->id_proforma;

		// Procesar servicios
		$proforma_servicios = $this->model_proform->proforma_servicios($id_actividad);
		$proforma_servicios = json_encode($proforma_servicios);

		$data['proforma_servicios'] = $proforma_servicios;

		// Procesar intervalos
		$proforma_intervalos =
			$this->model_proform->proforma_espacio_intervalos($id_actividad);
		$data['proforma_intervalos'] = json_encode($proforma_intervalos);


		// Procesar emails
		$emails_array = $this->model_proform->proforma_emails($id_actividad);
		$emails = '';
		foreach ($emails_array as $email) {
			$emails .= $email->email . ',';
		}
		$data['emails'] = $emails;

		$data['servicios'] = $this->model_service->services();
		$data['cuentas_pago'] = $this->model_cuentas_pago->cuentas_pago();
		$data['espacios'] = $this->model_espacios->espacios();
		$data['espacios_url'] = base_url('actividades/intervalos');

		$this->load->view('general/head', $data);
		$this->load->view('general/header');
		$this->load->view('general/menu', $data);
		$this->parser->parse('actividad/ver', $data);
		$this->load->view('general/footer', $data);
	}

	public function intervalos() {
		header("Content-Type: text/plain charset=UTF-8");
		$this->load->model('model_espacios');
		$espacios = $this->model_espacios->intervalos_siguientes();
		echo json_encode($espacios);
	}

	public function reporte() {

		$this->load->model('model_espacios');
		$actividades = $this->model_espacios->intervalos_con_descripcion();
		foreach ($actividades as $actividad) {
			$fecha_inicio = DateTime::createFromFormat(self::datetime_sql_format_with_seconds,
				$actividad->fecha_inicio);
			$actividad->fecha_inicio = $fecha_inicio->format(self::datetime_human_format);

			$fecha_fin = DateTime::createFromFormat(self::datetime_sql_format_with_seconds,
				$actividad->fecha_fin);
			$actividad->fecha_fin = $fecha_fin->format(self::datetime_human_format);
		}
		$data['actividades'] = $actividades;
		$data['css'] = array('main', 'proform/reporte');
		$this->load->view('general/head', $data);
		$this->parser->parse('actividad/reporte', $data);
	}

	public function eliminar($id_actividad) {
		if ($this->session->userdata('usuario') === false) {
			redirect('');
		}

		$this->load->model('model_proform');
		$this->model_proform->actividad_eliminar($id_actividad);
		redirect('actividades/pendientes');
	}
}