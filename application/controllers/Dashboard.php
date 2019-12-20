<?php


defined('BASEPATH') OR exit('No direct script access allowed');


class Dashboard extends CI_Controller {

    public function __construct() {
        parent:: __construct();
        if(empty($this->session->userdata('email'))) {
            redirect('Login', 'refresh');
        }
        
        $this->load->library('form_validation');
        $this->load->model('QueryModel');
        //$this->load->library('password');
    }


    /**
     * @purpose: view dashboard 
     */
    public function index() {
        try {
            $this->load->view('dashboard.php');
        } catch(Exception $e) {
            echo $e->getMessage(); die;
        }
    }





}