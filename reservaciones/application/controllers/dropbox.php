<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Dropbox extends CI_Controller
{
// Call this method first by visiting http://SITE_URL/example/request_dropbox
public function request_dropbox()
{
$params['key'] = 'a6os5vqo2bmfkqg';
$params['secret'] = 'tiar3fpddwktozs';

$this->load->library('dropbox', $params);
$data = $this->dropbox->get_request_token(site_url("access_dropbox"));
$this->session->set_userdata('token_secret', $data['token_secret']);
redirect($data['redirect']);
}
}

//This method should not be called directly, it will be called after
//the user approves your application and dropbox redirects to it
public function access_dropbox()
{
$params['key'] = 'a6os5vqo2bmfkqg';
$params['secret'] = 'tiar3fpddwktozs';

$this->load->library('dropbox', $params);

$oauth = $this->dropbox->get_access_token($this->session->userdata('token_secret'));

$this->session->set_userdata('oauth_token', $oauth['oauth_token']);
$this->session->set_userdata('oauth_token_secret', $oauth['oauth_token_secret']);
        redirect('test_dropbox');
}


public function test_dropbox()
{
$params['key'] = 'a6os5vqo2bmfkqg';
$params['secret'] = 'tiar3fpddwktozs';
$params['access'] = array('oauth_token'=>urlencode($this->session->userdata('oauth_token')),
'oauth_token_secret'=>urlencode($this->session->userdata('oauth_token_secret')));

$this->load->library('dropbox', $params);

        $dbobj = $this->dropbox->account();

        print_r($dbobj);
}