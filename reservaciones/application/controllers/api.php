<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class API extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('model_proform');
		$this->load->model('model_user');
	}

	public function index()
	{
		header("Content-Type: text/html charset=UTF-8");
		$this->load->view('api');
	}

	public function proforma($id = 0, $apikey = '0')
	{
		header("Content-Type: text/plain charset=UTF-8");

		$autorizado = $this->model_user->check_key($apikey);
		if (!$autorizado) {
			echo json_encode(array('Result' => 'No autenticado'));
			return;
		}
		
		$result = $this->model_proform->proforma($id);

		if ($result !== false) {
			$espacios = $this->model_proform->proforma_espacios($id);
			foreach ($espacios as $espacio) {
				$espacio->intervalos = $this->model_proform->proforma_espacio_intervalos($espacio->id_proforma_espacio);
			}
			$result->espacios = $espacios;

			$result->servicios = $this->model_proform->proforma_servicios($id);

			echo json_encode($result);
		} else {
			echo json_encode(array('Result' => 'Null'));
		}
	}

	public function proformas($fecha = 'siguientes', $apikey = '0')
	{
		header("Content-Type: text/plain charset=UTF-8");

		$autorizado = $this->model_user->check_key($apikey);
		if (!$autorizado) {
			echo json_encode(array('Result' => 'No autenticado'));
			return;
		}
		
		$proformas = $this->model_proform->proformas($fecha);

		foreach ($proformas as $proforma) {
			$espacios = $this->model_proform->proforma_espacios($proforma->id_proforma);
			foreach ($espacios as $espacio) {
				$espacio->intervalos = $this->model_proform->proforma_espacio_intervalos($espacio->id_proforma_espacio);
			}
			$proforma->espacios = $espacios;

			$proforma->servicios = $this->model_proform->proforma_servicios($proforma->id_proforma);
		}

		echo json_encode($proformas);
	}

	public function actividades($fecha = 'siguientes')
	{
		header("Content-Type: text/plain charset=UTF-8");
		
		$actividades = $this->model_proform->actividades($fecha);

		foreach ($actividades as $actividad) {
			$espacios = $this->model_proform->proforma_espacios($actividad->id_proforma);
			foreach ($espacios as $espacio) {
				$espacio->intervalos = 
					$this->model_proform->proforma_espacio_intervalos_por_proforma_espacio(
						$espacio->id_proforma_espacio);
				foreach ($espacio->intervalos as $intervalo) {
					unset($intervalo->id_intervalo);
					unset($intervalo->proforma_espacio);
				}
				unset($espacio->id_proforma_espacio);
				unset($espacio->proforma);
				unset($espacio->espacio);
				unset($espacio->precio);
				unset($espacio->id_espacio);
				unset($espacio->capacidad_personas);
				unset($espacio->precio_base);
				unset($espacio->modo_alquiler);
				unset($espacio->subespacio);
			}
			$actividad->espacios = $espacios;
			unset($actividad->id_proforma);
		}
		echo json_encode($actividades);
	}

	public function test() {
		header("Content-Type: text/plain charset=UTF-8");
		$intervalos = $this->model_proform->proforma_espacio_intervalos(28);
		echo json_encode($intervalos);
	}

	public function espacios()
	{
		header("Content-Type: text/plain charset=UTF-8");
		$this->load->model('model_espacios');
		$espacios = $this->model_espacios->espacios();
		foreach ($espacios as $espacio) {
			$espacio->imagen = base_url('assets/images/espacios/').'/'.$espacio->imagen;
		}
		echo json_encode($espacios);
	}

	public function calendario() {
		header("Content-Type: text/plain charset=UTF-8");
		$this->load->model('model_proform');
		$result = $this->model_proform->proforma_calendario();
		if ( count($result) > 0 )
			echo json_encode($result);
		else
			echo 'No hay actividades registradas';
	}
}