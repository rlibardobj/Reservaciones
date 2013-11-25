<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mail {

	private $CI;

	function __construct() {
	
	$config = array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.googlemail.com',
			'smtp_port' => 465,
			'smtp_user' => 'Analytics.People.Adm@gmail.com',
			'smtp_pass' => 'Analytics.People.Adm12345',
			'mailtype'  => 'html', 
			'charset' => 'utf-8'
		);
		$this->CI = get_instance();
		$this->CI->load->library('email',$config);
	}

	function correo($to='', $subject='', $body='', $file=NULL) {
		$this->CI->email->set_newline("\r\n");
		$email_setting  = array('mailtype'=>'html');
		$this->CI->email->initialize($email_setting);
		$this->CI->email->from('Analytics.People.Adm@gmail.com', 'CTEC');
		$this->CI->email->bcc($to);
		$this->CI->email->subject($subject);
		$this->CI->email->message($body);
		$this->CI->email->attach($file);
		$this->CI->email->send();
	}
}