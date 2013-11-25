<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email extends CI_Controller {

	public function index($body='Hello World!', $to=array('bvjd91@gmail.com','lavb91@gmail.com'),$subject='Test Email') {
        if ($this->session->userdata('usuario') === false) {
            redirect('');
        }
        $from = 'Analytics.People.Adm@gmail.com';
        $config = Array(
        'protocol' => 'smtp',
        'smtp_host' => 'ssl://smtp.googlemail.com',
        'smtp_port' => 465,
        'smtp_user' => $from,
        'smtp_pass' => 'Analytics.People.Adm12345',
        'mailtype'  => 'html', 
        'charset' => 'utf-8'
        );

        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $email_setting  = array('mailtype'=>'html');
        $this->email->initialize($email_setting);
        $email_body =$body;
        $this->email->from($from, 'CTEC');
        $this->email->bcc($to);
        $this->email->subject($subject);
        $this->email->message($email_body);

        $this->email->send();
    }
}