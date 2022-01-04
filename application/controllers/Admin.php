<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->lang->load('auth');
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('auth/login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            // redirect them to the home page because they must be an administrator to view this
            show_error('You must be an administrator to view this page.');
        }

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        /** user */
        $user = $this->ion_auth->user()->row();
        $this->user_name = $user->first_name;

        $this->load->model('backend_model', 'backend');
    }

    /** admin dashboard  */
    public function index() {
        $this->data['content'] = 'admin/home';
        $this->load->view('admin/common', $this->data);
    }
}
