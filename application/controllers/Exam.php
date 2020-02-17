<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Exam extends CI_Controller {


    public function __construct() {
        parent:: __construct();
        if(empty($this->session->userdata('email'))) {
            redirect('Login', 'refresh');
        }
        
        $this->load->library('form_validation');
        $this->load->model('QueryModel');
    }
    
    /**
     * @purpose: create exam
     */

    public function createExam() {
        try {
            if(!empty($this->input->post()) && $this->input->post('create') === 'Create') {
                $this->form_validation->set_rules('exam_name', 'exam_name', 'required|min_length[5]');
                $this->form_validation->set_rules('exam_code', 'exam_code', 'required|is_unique[exams.exam_code]');
                if ($this->form_validation->run() === FALSE) {
                    $this->session->set_flashdata('error',validation_errors());
                    redirect('Exam/createExam', 'refresh');
                } else {
                    $examConditionArray = array(
                        'exam_name' => trim(strip_tags($this->input->post('exam_name'))),
                        'exam_code' => trim(strip_tags($this->input->post('exam_code'))),
                        'isDelete' => '0'
                    );
                    $examExistsOrNot = $this->QueryModel->getWhere($examConditionArray, 'exams');
                    if(empty($examExistsOrNot)) {
                        $insertArray = array(
                            'exam_name' => trim(strip_tags($this->input->post('exam_name'))),
                            'exam_code' => trim(strip_tags($this->input->post('exam_code'))),
                            'isDelete' => '0'
                        );
                        $examId = $this->QueryModel->insertDataIntoTable($examConditionArray, 'exams');
                        if(!empty($examId)) {
                            $this->session->set_flashdata('success',  'Exam created successfully done.');
                            redirect('Exam/createExam', 'refresh');
                        } else {
                            $this->session->set_flashdata('error',  'Exam Creation failed.');
                            redirect('Exam/createExam', 'refresh');
                        }
                    } else {
                        $this->session->set_flashdata('error',  'Exam already exists for given class & section');
                        redirect('Exam/createExam', 'refresh');
                    }
                }
            } else {
                $classCoditionArray = array(
                    'isDelete' => '0'
                );
                $data['classList'] = $this->QueryModel->getMultipleRow($classCoditionArray, 'classes');
                $this->load->view('exam/createexam.php', $data);
            }
        } catch(Exception $e) {
            echo $e->getMessage(); die;
        }
    }

    /**
     * @purpose: list class
     */

    public function listSection() {
        try {
            if(!empty($this->input->post('classId'))) {
                $conditionArray = array(
                    'class_id' => $this->input->post('classId'),  
                    'isDelete' => '0'              
                );
                $sectionList = $this->QueryModel->getMultipleRow($conditionArray, 'sections');
                if(!empty($sectionList)) {
                    echo json_encode($sectionList); die;
                } else {
                    echo "error"; die;
                }
            }
        } catch(Exception $e) {
            echo $e->getMessage(); die;
        }
    }


    /**
     * @purpose: list subject aganist sectionId
     */
    public function listSubject() {
        try {
            if(!empty($this->input->post('sectionId'))) {
                $conditionArray = array(
                    'section_id' => $this->input->post('sectionId'),  
                    'isDelete' => '0'              
                );
                $subjectList = $this->QueryModel->getMultipleRow($conditionArray, 'assign_subject');
                if(!empty($subjectList)) {
                    foreach($subjectList as $single_subject){ //echo "<pre>";print_r($single_subject);die;
                        $subjectConditionArray = array(
                            'subjectId' => $single_subject['subject_id'],
                            'isDelete' => '0'
                        );
                        $subject      =   $this->QueryModel->getWhere($subjectConditionArray, 'subjects');
                        if(!empty($subject)){ 
                            $single_subject['subjectName']      =   $subject['subject_name'];
                        }else{
                            $single_subject['subjectName']  =  "N/A";
                        }
                        $value[]             =  $single_subject;
                    }
                    echo json_encode($value); die;
                } else {
                    echo "error"; die;
                }
            }
        } catch(Exception $e) {
            echo $e->getMessage(); die;
        }


    }
    /**
     * @purpose: list exam
     */
    public function listExam($offset = 0) {
        try {
			if($offset > 1) {
				$offset 						= 	$offset-1;
				$offset 						= 	(int)$offset * 10;
			}else {
				$offset 						= 	(int)$offset;
			}
			$config['base_url'] 				= 	base_url('Exam/listExam');
			$config['total_rows'] 				= 	$this->QueryModel->getNumberOfRows('exam');
			$config['per_page'] 				= 	10;
			$config['num_links'] 				= 	5;
			$config['use_page_numbers'] 		= 	TRUE;
			$config['full_tag_open'] 			= 	'<ul class="pagination">';
			$config['full_tag_close'] 			= 	'</ul>';
			$config['prev_link'] 				= 	'&laquo;';
			$config['prev_tag_open'] 			= 	'<li>';
			$config['prev_tag_close'] 			= 	'</li>';
			$config['next_tag_open'] 			= 	'<li>';
			$config['next_tag_close'] 			= 	'</li>';
			$config['cur_tag_open'] 			= 	'<li class="active"><a href="#">';
			$config['cur_tag_close'] 			= 	'</a></li>';
			$config['num_tag_open'] 			= 	'<li>';
			$config['num_tag_close'] 			= 	'</li>';
			$this->pagination->initialize($config);
            $data['value']					    = 	$this->QueryModel->fetchDataWithLimitOffset('exams', $config['per_page'], $offset, array('isDelete' => '0'));
            //echo "<pre>"; print_r($data['value']); die;
			$this->load->view('exam/listexam.php', $data);
		} catch(Exception $e) {
			echo $e->getMessage(); die;
		}
    }



}