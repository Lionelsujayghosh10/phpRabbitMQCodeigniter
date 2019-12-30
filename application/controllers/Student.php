<?php


defined('BASEPATH') OR exit('No direct script access allowed');


class Student extends CI_Controller {


    public function __construct() {
        parent:: __construct();
        if(empty($this->session->userdata('email'))) {
            redirect('Login', 'refresh');
        }
        
        $this->load->library('form_validation');
        $this->load->model('QueryModel');
    }


    /**
     * @purpose: student csv upload
     */
    public function uploadCsv() {
        try {
            if(!empty($this->input->post()) && $this->input->post('create') === 'Upload') {
                if(!empty($_FILES['studntcsv'])) {
					if(!empty($_FILES['studntcsv']['name'])) {
                        if($_FILES['studntcsv']['type'] === "text/csv") {
                            $file = fopen($_FILES["studntcsv"]["tmp_name"],"r");
							$finalArr = array();
							$count = 0;
							while($data = fgetcsv($file)) {
								array_push($finalArr,$data);                   
                            }
                            foreach($finalArr as $single_student){
                                if(empty($single_student[0]) || is_null($single_student[0]) || trim($single_student[0]) === '') {

                                }
                                if(empty($single_student[1]) || is_null($single_student[1]) || trim($single_student[1]) === '') {

                                }
                                if(empty($single_student[2]) || is_null($single_student[2]) || trim($single_student[2]) === '') {

                                }
                                if(empty($single_student[5]) || is_null($single_student[5]) || trim($single_student[5]) === '') {

                                }
                                if(empty($single_student[4]) || is_null($single_student[4]) || trim($single_student[4]) === '') {

                                }
                                if(empty($single_student[6]) || is_null($single_student[6]) || trim($single_student[6]) === '') {

                                }
                                $classConditionArray = array('class_name' => trim(strip_tags($single_student[5])), 'isDelete' => '0');
                                $checkClassAlreayExists = $this->QueryModel->getWhere($classConditionArray, 'classes');
                                if(!empty($checkClassAlreayExists)) {
                                    $sectionConditionArray = array('section_name' => trim(strip_tags($single_student[6])), 'isDelete' => '0', 'class_id' => $checkClassAlreayExists['classId']);
                                    $checkSectionAlreayExists = $this->QueryModel->getWhere($sectionConditionArray, 'sections');
                                    if(!empty($checkSectionAlreayExists)) {
                                        $studentConditionArray = array('student_code' => trim(strip_tags($single_student[1])), 'isDelete' => '0');
                                        $checkStudentExistsOrNot = $this->QueryModel->getWhere($studentConditionArray, 'students');
                                        if(empty($checkStudentExistsOrNot)) {
                                            $studentInsertArray = array(
                                                'student_id'                =>  (!empty(trim(strip_tags($single_student[0]))) ? trim(strip_tags($single_student[0])) : "N/A"),
                                                'student_code'              =>  (!empty(trim(strip_tags($single_student[1]))) ? trim(strip_tags($single_student[1])) : "N/A"),
                                                'student_name'              =>  (!empty(trim(strip_tags($single_student[2]))) ? trim(strip_tags($single_student[2])) : "N/A")." ".(!empty(trim(strip_tags($single_student[3]))) ? trim(strip_tags($single_student[3])) : ""),
                                                'class_id'                  =>  $checkClassAlreayExists['classId'],
                                                'section_id'                =>  $checkSectionAlreayExists['sectionId'],
                                                'student_rollNumber'        =>  (!empty(trim(strip_tags($single_student[4]))) ? trim(strip_tags($single_student[4])) : "N/A"),
                                            );
                                            $studentId = $this->QueryModel->insertDataIntoTable($studentInsertArray, 'students');
                                            if(!empty($studentId)) {
                                                unset($studentInsertArray);
                                                unset($classConditionArray);
                                                unset($sectionConditionArray);
                                                continue;
                                            } else {
                                                continue;
                                            }
                                        } else {
                                            unset($classConditionArray);
                                            unset($sectionConditionArray);
                                            continue;
                                        }
                                    } else {
                                        $this->session->set_flashdata('error','Section not exists.');
							            redirect('Student/uploadCsv', 'refresh');
                                    }
                                } else {
                                    $this->session->set_flashdata('error','Class not exists.');
							        redirect('Student/uploadCsv', 'refresh');
                                }
                            }
                            $this->session->set_flashdata('success','CSV file uploaded successful.');
							redirect('Student/uploadCsv', 'refresh');
                        } else {
                            $this->session->set_flashdata('error','CSV file is required.Try again!');
							redirect('Student/uploadCsv', 'refresh');
                        }
                    } else {
                        $this->session->set_flashdata('error','CSV file is required.Try again!');
						redirect('Student/uploadCsv', 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('error','CSV file is required.Try again!');
					redirect('Student/uploadCsv', 'refresh');
                }
            } else {
                $this->load->view('student/studentcsv.php');
            }
        } catch(Exception $e){
            echo $e->getMessage(); die;
        }
    }


    /**
     * @purpose: list student
     */
    public function listStudent($offset = 0){
        try {
            if($offset > 1) {
				$offset 						= 	$offset - 1;
				$offset 						= 	(int)$offset * 10;
			}else {
				$offset 						= 	(int)$offset;
            }
            $config['base_url'] 				= 	base_url('Student/listStudent');
            $config['total_rows'] 				= 	$this->QueryModel->getNumberOfRows('students');
            //echo $config['total_rows']; die;
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
            $studentList 					    = 	$this->QueryModel->fetchDataWithLimitOffset('students', $config['per_page'], $offset, array('isDelete' => '0'));
            if(!empty($studentList)) {
                foreach($studentList as $key => $single_student){
                    $value['student_id']            =   (!empty($single_student['student_id']) ? $single_student['student_id'] : "N/A");
                    $value['studentId']             =   (!empty($single_student['studentId']) ? $single_student['studentId'] : "N/A");
                    $value['student_code']          =   (!empty($single_student['student_code']) ? $single_student['student_code'] : "N/A");
                    $value['student_name']          =   (!empty($single_student['student_name']) ? $single_student['student_name'] : "N/A");
                    $value['student_rollNumber']    =   (!empty($single_student['student_id']) ? $single_student['student_rollNumber'] : "N/A");
                    $value['class_name']            =   (!empty($this->QueryModel->getWhere(array('classId' => $single_student['class_id'], 'isDelete' => '0'), 'classes')['class_name']) ? $this->QueryModel->getWhere(array('classId' => $single_student['class_id'], 'isDelete' => '0'), 'classes')['class_name'] : "N/A");
                    $value['section_name']          =   (!empty($this->QueryModel->getWhere(array('sectionId' => $single_student['section_id'], 'isDelete' => '0'), 'sections')['section_name']) ? $this->QueryModel->getWhere(array('sectionId' => $single_student['section_id'], 'isDelete' => '0'), 'sections')['section_name'] : "N/A");
                    $listArray[]                    =   $value;
                }
            } else {
                $listArray                          =   array();
            }
            $details['students']                    =   $listArray;
		    $this->load->view('student/liststudent.php', $details);
        } catch(Exception $e) {
            echo $e->getMessage(); die;
        }
    }


    /**
     * @purpose: get student list by class and section
     */
    public function getStudent(){
        try {
            if(!empty($this->input->post('classId')) && !empty($this->input->post('sectionId')) ){
                $studentConditionArray = array(
                    'class_id'      =>  $this->input->post('classId'),
                    'section_id'    =>  $this->input->post('sectionId')
                );
                $data = $this->QueryModel->getMultipleRow($studentConditionArray, 'students');
                echo json_encode($data); die;
            } else {
                echo "error"; die;
            }
        } catch(Exception $e) {
            echo "error"; die;
        }
    }



    /**
     * @purpose: search student
     */
    public function search($offset = 0) {
        try {
            if(!empty($_GET['table_search']) && $_GET['search'] === 'search') {
                if($offset > 1) {
                    $offset 						= 	$offset - 1;
                    $offset 						= 	(int)$offset * 10;
                }else {
                    $offset 						= 	(int)$offset;
                }
                $config['base_url'] 				= 	base_url('Student/search');
                $config['total_rows'] 				= 	$this->QueryModel->getNumberOfRowsForSearch($_GET['table_search'], 'students');
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
                $config['reuse_query_string']       =   true;
               // $config['suffix']                   =   '?' . http_build_query($_GET, '', "&");
                $this->pagination->initialize($config);
                $studentList = $this->QueryModel->getSearchResult($_GET['table_search'], 'students', array('isDelete' => '0'), $config['per_page'], $offset); 
                if(!empty($studentList)) {
                    foreach($studentList as $key => $single_student){
                        $single_student['class_name']               =   $this->QueryModel->getWhere(array('classId' => $single_student['class_id'], 'isDelete' => '0'), 'classes')['class_name'];
                        $single_student['section_name']             =   $this->QueryModel->getWhere(array('sectionId' => $single_student['section_id'], 'isDelete' => '0'), 'sections')['section_name'];
                        unset($single_student['class_id']);
                        unset($single_student['section_id']);
                        $listArray[]                                =   $single_student;
                    }
                } else {
                    $listArray                                      =   array();
                }
                $details['students']                                =   $listArray;
		        $this->load->view('student/liststudent.php', $details);
            } else {
                redirect('Student/listStudent', 'refresh');
            }
        } catch(Thorwable $e) {
            echo $e->getMessage(); die;
        }
    }




}