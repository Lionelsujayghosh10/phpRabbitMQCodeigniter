<?php


defined('BASEPATH') OR exit('No direct script access allowed');


class Logout extends CI_Controller {


    /**
     * @purpose: Logout from reportcard 
    */
    public function index(){
        $this->session->unset_userdata('email');
        redirect('Login');
    }
}