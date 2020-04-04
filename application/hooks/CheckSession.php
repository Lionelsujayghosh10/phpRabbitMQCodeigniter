<?php



class CheckSession{
    
    public function sessionCheck(){
        $instance = & get_instance();
        $instance->load->library('session');
        if ($instance->uri->segment(1) === 'Login' || empty($instance->uri->segment(1))){

        } else {
            if(is_null($instance->session->userdata('email')) || empty($instance->session->userdata('email')) || trim($instance->session->userdata('email')) === '') {
                redirect('Login', 'refresh');
            }
        }       
    }
}