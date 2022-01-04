<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class MY_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            return show_error('You must be an admin to view this page.');
        }
    }

}
