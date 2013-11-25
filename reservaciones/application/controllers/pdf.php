<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PDF extends CI_Controller {

	const date_input_format = 'm/d/Y';
	const datetime_input_format = 'm/d/Y h:i a';
	const datetime_human_format = 'd/m/Y h:i a';
	const date_sql_format = 'Y-m-d';
	const datetime_sql_format = 'Y-m-d G:i';
	const datetime_sql_format_with_seconds = 'Y-m-d G:i:s';

public function generar($id_prof)
{
	if ($this->session->userdata('usuario') === false) {
			redirect('');
	}
	
	$this->load->library('mail');
	$this->load->model('model_proform');
	$proformas = $this->model_proform->proforma_detalle($id_prof);
	$proforma_servicios = $this->model_proform->proforma_servicios($id_prof);
	$proforma_espacios_intervalos = $this->model_proform->proforma_espacio_intervalos($id_prof);
	$proforma_correos = $this->model_proform->proforma_emails($id_prof);
	
	$costo=0;
	$correos=''; //correo predeterminado al que también se enviará la proforma como un respaldo
	foreach($proforma_correos as $correo){ //obtiene la lista de correos de los interesados en la proforma
	$correos = $correo->email . ',' . $correos;
	}
	
	foreach ($proforma_espacios_intervalos as $intervalo) {
		$fecha_inicio_diff = strtotime($intervalo->fecha_inicio);
		$fecha_inicio = DateTime::createFromFormat(self::datetime_sql_format_with_seconds,
			$intervalo->fecha_inicio);
		$intervalo->fecha_inicio = $fecha_inicio->format(self::datetime_human_format);
		
		$fecha_fin_diff = strtotime($intervalo->fecha_fin);
		$fecha_fin = DateTime::createFromFormat(self::datetime_sql_format_with_seconds,
			$intervalo->fecha_fin);
		$intervalo->fecha_fin = $fecha_fin->format(self::datetime_human_format);
		
		$diferencia = abs($fecha_fin_diff - $fecha_inicio_diff);
		$intervalo->cantidad = ($intervalo->alquiler === '2') ? $diferencia / 3600 : $diferencia / 86400;
		$intervalo->cantidad = ceil($intervalo->cantidad);
		$intervalo->costo = $intervalo->cantidad * intval($intervalo->precio_base);
		$costo = $costo + $intervalo->costo;
	}
	
	foreach ($proformas as $proforma) {
		$fecha_inicio = DateTime::createFromFormat(self::datetime_sql_format_with_seconds,
			$proforma->fecha_inicio);
		$proforma->fecha_inicio = $fecha_inicio->format(self::datetime_human_format);
		
		$fecha_fin = DateTime::createFromFormat(self::datetime_sql_format_with_seconds,
			$proforma->fecha_fin);
		$proforma->fecha_fin = $fecha_fin->format(self::datetime_human_format);
	}
	
	foreach($proforma_servicios as $servicio)
	{
		$servicio->costo_total = intval($servicio->cantidad)*intval($servicio->precio);
		$costo = $costo + $servicio->costo_total;
	}
	
	$filename= $proformas[0]->codigo;
	
	$data['costo_final'] = $costo;
	$data['espacios'] = $proforma_espacios_intervalos;
	$data['proforma'] = $proformas;
	$data['servicios'] = $proforma_servicios	;
	$html = $this->parser->parse('pdf/generar', $data, TRUE);
	
	$pdfFilePath = FCPATH."/pdf/$filename.pdf";
	ini_set('memory_limit','32M'); // boost the memory limit if it's low 
    $this->load->library('mypdf');
    $pdf = $this->mypdf->load();
    $pdf->SetFooter($_SERVER['HTTP_HOST'].'|{PAGENO}|'.date(DATE_RFC822)); // Add a footer for good measure
    $pdf->WriteHTML($html); // write the HTML into the PDF
    $pdf->Output($pdfFilePath, 'F'); // save to file because we can
	
	$to = $correos;
	$file = FCPATH.'pdf/'.$filename.'.pdf';
	
	$asunto ='<p>Adjunto archivo de proforma solicitado.</p>';
	$asunto = $asunto.'<p>Que tenga un buen día.</p>';
	$asunto = $asunto.'<br><br><br>';
	$asunto = $asunto.'<p>----</p>';
	$asunto = $asunto.'<p>Saludos,</p>';
	$asunto = $asunto.'<p>MAP. Rogelio González Quirós</p>';
	$asunto = $asunto.'<p>Director CTEC</p>';
	$email = $this->mail->correo($correos, 'CTEC - Proforma' , $asunto,$file);
	redirect('http://'. $_SERVER['HTTP_HOST']  .'/reservaciones/pdf/' . $filename . '.pdf');
}
}
