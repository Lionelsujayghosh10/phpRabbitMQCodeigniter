<?php


defined('BASEPATH') OR exit('No direct script access allowed');


class Dashboard extends CI_Controller {

    public function __construct() {
        parent:: __construct();
        // if(empty($this->session->userdata('email'))) {
        //     redirect('Login', 'refresh');
        // }
        
        $this->load->library('form_validation');
        $this->load->model('QueryModel');
        //$this->load->library('password');
    }


    /**
     * @purpose: view dashboard 
     */
    public function index() {
        try {
            $count['classCount']       =   $this->QueryModel->countIds('classes');
            $count['studentCount']     =   $this->QueryModel->countIds('students');
            $count['subjectCount']     =   $this->QueryModel->countIds('subjects'); 
            $this->load->view('dashboard.php', $count);
        } catch(Exception $e) {
            echo $e->getMessage(); die;
        }
    }





}