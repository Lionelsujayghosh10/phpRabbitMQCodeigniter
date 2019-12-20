<?php


defined('BASEPATH') OR exit('No direct script access allowed');


class Login extends CI_Controller {

    public function __construct() {
        parent:: __construct();
        if(!is_null($this->session->userdata('email'))) {
            redirect('Dashboard', 'refresh');
        }
        //echo $this->session->userdata('email'); die;
        
        $this->load->library('form_validation');
        $this->load->model('QueryModel');
        //$this->load->library('password');
    }



    /**
     * @purpose: password hash
     */
    private function hash_password($password){
        return password_hash($password, PASSWORD_BCRYPT);
    }

	/**
     * @purpose: Login section to reportcard admin panel
     */
    public function index() {
        try {
            if(!empty($this->input->post()) && $this->input->post('Login') === 'Sign In') {
                $this->form_validation->set_rules('email', 'email', 'required|valid_email');
                $this->form_validation->set_rules('password', 'password', 'required');
                if ($this->form_validation->run() === FALSE) {
                    $this->session->set_flashdata('error',validation_errors());
                    redirect('Login', 'refresh');
                } else {
                    $conditionArray =   array('email' => $this->input->post('email'), 'isDelete' => '0');
                    $data           =   $this->QueryModel->getWhere($conditionArray, 'users');
                    if(!empty($data)) {
                        if(password_verify($this->input->post('password'), $data['password'])) {
                            $this->session->set_userdata(array('email' => $data['email']));
                            redirect('Dashboard', 'refresh');
                        } else {
                            $this->session->set_flashdata('error','Invalid password.');
                            redirect('Login', 'refresh');
                            
                        }
                    } else {
                        $this->session->set_flashdata('error','Invalid Email address.');
                        redirect('Login', 'refresh');
                    }
                }
            } else {
                $this->load->view('login.php'); 
            }   
        } catch(Exception $e) {
            echo $e->getMessage(); die;
        }
    }
	
}