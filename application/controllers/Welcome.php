<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->data['content'] = 'pages/home';
        $this->load->view('common', $this->data);
    }

    public function contactUs() {
        $this->data['content'] = 'pages/contact';
        $this->load->view('common', $this->data);
    }

}
