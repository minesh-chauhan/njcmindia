<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Auth extends CI_Controller {

    public $data = [];

    public function __construct() {
        parent::__construct();

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
    }

    /** login page */
    public function login() {
        $this->data['title'] = 'Login';
        $this->load->view('auth' . DIRECTORY_SEPARATOR . 'login', $this->data);
    }

    /** check login */
    public function do_login() {
        // validate form input
        $this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
        $this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');

        header('Content-Type: application/json');
        if ($this->form_validation->run() === TRUE) {
            $remember = (bool) $this->input->post('remember');

            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
                echo json_encode(['status' => true, 'message' => strip_tags($this->ion_auth->messages()), 'redirect' => site_url('admin/index')]);
            } else {
                echo json_encode(['status' => false, 'message' => strip_tags($this->ion_auth->errors())]);
            }
        } else {
            echo json_encode(['status' => false, 'message' => 'The Username/Password field is required']);
        }
    }

    /** logout */
    public function logout() {
        $this->data['title'] = "Logout";

        // log the user out
        $this->ion_auth->logout();

        // redirect them to the login page
        redirect('auth/login', 'refresh');
    }

    /** @return array A CSRF key-value pair */
    public function _get_csrf_nonce() {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return [$key => $value];
    }

    /** @return bool Whether the posted CSRF token matches */
    public function _valid_csrf_nonce() {
        $csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
        if ($csrfkey && $csrfkey === $this->session->flashdata('csrfvalue')) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * @param string     $view
     * @param array|null $data
     * @param bool       $returnhtml
     *
     * @return mixed
     */
    public function _render_page($view, $data = NULL, $returnhtml = FALSE) {//I think this makes more sense
        $viewdata = (empty($data)) ? $this->data : $data;

        $view_html = $this->load->view($view, $viewdata, $returnhtml);

        // This will return html on 3rd argument being true
        if ($returnhtml) {
            return $view_html;
        }
    }

}
