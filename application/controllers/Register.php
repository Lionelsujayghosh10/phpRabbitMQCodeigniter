<?php


defined('BASEPATH') OR exit('No direct script access allowed');


class Register extends CI_Controller {


    public function __construct() {
        parent:: __construct();
        if(!empty($this->session->userdata('email'))) {
            redirect('Dashboard', 'refresh');
        }
        
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
            if(!empty($this->input->post()) && $this->input->post('register') === 'Register') {
                $this->form_validation->set_rules('fname', 'fname', 'required');
                $this->form_validation->set_rules('email', 'email', 'required|valid_email|is_unique[users.email]');
                $this->form_validation->set_rules('password', 'password', 'required|min_length[5]|max_length[12]');
                $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]');
                if ($this->form_validation->run() === FALSE) {
                    $this->session->set_flashdata('error',validation_errors());
                    redirect('Register', 'refresh');
                } else {    
                    $insertArray = array(
                        'fname'     =>  strip_tags(trim($this->input->post('fname'))),
                        'email'     =>  strip_tags(trim($this->input->post('email'))),
                        'password'  =>  $this->hash_password(strip_tags(trim($this->input->post('password')))),
                        'isDelete'  => '0'
                    );
                    $insertedId = $this->QueryModel->insertDataIntoTable($insertArray, 'users');
                    if(!empty($insertedId)) {
                        $this->session->set_flashdata('success','Registration Done. Log In now.');
                        redirect('Login', 'refresh');
                    } else {
                        $this->session->set_flashdata('error','Invalid Credentials.');
                        redirect('Register', 'refresh');
                    }
                }
            } else {
                $this->load->view('register.php'); 
            }   
        } catch(Exception $e) {
            echo $e->getMessage(); die;
        }
    }
	
}