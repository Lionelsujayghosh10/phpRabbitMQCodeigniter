<?php


defined('BASEPATH') OR exit('No direct script access allowed');


class ClassSection extends CI_Controller {

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
	 * @purpose: csv upload for class & section
	 */
	public function uploadCsv() {
		try {
			if(!empty($this->input->post()) && $this->input->post('create') === 'Create') {
				if(!empty($_FILES['classsectioncsv'])) {
					if(!empty($_FILES['classsectioncsv']['name'])) {
						if($_FILES['classsectioncsv']['type'] === "text/csv") {
							$file = fopen($_FILES["classsectioncsv"]["tmp_name"],"r");
							$finalArr = array();
							$count = 0;
							while($data = fgetcsv($file)) {
								array_push($finalArr,$data);                   
							}
							foreach($finalArr as $key => $single_value) {
								if(empty($single_value[0]) || is_null($single_value[0]) || trim($single_value[0]) === '') {
									$this->session->set_flashdata('error','Class code can not be empty.Try again!');
									redirect('ClassSection/uploadCsv', 'refresh');
								}
								if(empty($single_value[1]) || is_null($single_value[1]) || trim($single_value[1]) === '') {
									$this->session->set_flashdata('error','Class name can not be empty.Try again!');
									redirect('ClassSection/uploadCsv', 'refresh');
								}
								if(empty($single_value[2]) || is_null($single_value[2]) || trim($single_value[2]) === '') {
									$this->session->set_flashdata('error','Section code can not be empty.Try again!');
									redirect('ClassSection/uploadCsv', 'refresh');
								}
								if(empty($single_value[3]) || is_null($single_value[3]) || trim($single_value[3]) === '') {
									$this->session->set_flashdata('error','Section name can not be empty.Try again!');
									redirect('ClassSection/uploadCsv', 'refresh');
								}
								$classConditionArray = array('class_code' => $single_value[0], 'class_name' => $single_value[1], 'isDelete' => '0');
								$checkClassExistsOrnot = $this->QueryModel->getWhere($classConditionArray, 'classes');
								if(!empty($checkClassExistsOrnot)) {
									$sectionConditionArray = array('section_code' => $single_value[2], 'section_name' => $single_value[3], 'isDelete' => '0', 'class_id' => $checkClassExistsOrnot['classId']);
									$checkSectionExistsOrnot = $this->QueryModel->getWhere($sectionConditionArray, 'sections');
									if(!empty($checkSectionExistsOrnot)) {
										unset($classConditionArray);
										unset($sectionConditionArray);
										continue;
									} else {
										$classArray = array(
											'section_code' 		=> 	$single_value[2], 
											'section_name' 		=> 	$single_value[3], 
											'class_id' 			=> 	$checkClassExistsOrnot['classId'],
											'isDelete'			=> '0'
										);
										$sectionId = $this->QueryModel->insertDataIntoTable($classArray, 'sections');
									}
									unset($classConditionArray);
									unset($sectionConditionArray);
									continue;
								} else {
									$classArray = array(
										'class_code' 		=> 	$single_value[0], 
										'class_name' 		=> 	$single_value[1], 
										'isDelete' 			=> 	'0'
									);
									$classId = $this->QueryModel->insertDataIntoTable($classArray, 'classes');
									$sectionConditionArray = array('section_code' => $single_value[2], 'section_name' => $single_value[3], 'isDelete' => '0', 'class_id' => $classId);
									$checkSectionExistsOrnot = $this->QueryModel->getWhere($sectionConditionArray, 'sections');
									if(!empty($checkSectionExistsOrnot)) {
										unset($classConditionArray);
										unset($sectionConditionArray);
										continue;
									} else {
										$classArray = array(
											'section_code' 		=> 	$single_value[2], 
											'section_name' 		=> 	$single_value[3], 
											'class_id' 			=> 	$classId,
											'isDelete'			=> '0'
										);
										$sectionId = $this->QueryModel->insertDataIntoTable($classArray, 'sections');
									}
									unset($classConditionArray);
									unset($sectionConditionArray);
									continue;
								}
							}
							$this->session->set_flashdata('success','CSV file uploaded successful.');
							redirect('ClassSection/uploadCsv', 'refresh');

						} else {
							$this->session->set_flashdata('error','CSV file is required.Try again!');
							redirect('ClassSection/uploadCsv', 'refresh');
						}
					} else {
						$this->session->set_flashdata('error','CSV file is required.Try again!');
						redirect('ClassSection/uploadCsv', 'refresh');
					}
				} else {
					redirect('ClassSection/uploadCsv', 'refresh');
				}
			} else {
				$this->load->view('classsection.php');
			}
		} catch(Exception $e) {
			echo $e->getMessage(); die;
		}
	}




	/**
	 * @purpose: list class
	 */
	public function listClass($offset = 0) {
		try {
			if($offset > 1) {
				$offset 						= 	$offset-1;
				$offset 						= 	(int)$offset * 8;
			}else {
				$offset 						= 	(int)$offset;
			}
			$config['base_url'] 				= 	base_url('ClassSection/listClass');
			$config['total_rows'] 				= 	$this->QueryModel->getNumberOfRows('classes');
			$config['per_page'] 				= 	8;
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
			$details['data'] 					= 	$this->QueryModel->fetchDataWithLimitOffset('classes', $config['per_page'], $offset, array('isDelete' => '0'));
			$this->load->view('listclass.php', $details);
		} catch(Exception $e) {
			echo $e->getMessage(); die;
		}
		
	}



	/**
	 * @purpose: list  section
	 */
	public function listSection($offset = 0) {
		try {
			if($offset > 1) {
				$offset 						= 	$offset-1;
				$offset 						= 	(int)$offset * 10;
			}else {
				$offset 						= 	(int)$offset;
			}
			$config['base_url'] 				= 	base_url('ClassSection/listSection');
			$config['total_rows'] 				= 	$this->QueryModel->getNumberOfRows('sections');
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
			$data 								= 	$this->QueryModel->fetchDataWithLimitOffset('sections', $config['per_page'], $offset, array('isDelete' => '0'));
			if(!empty($data)) {
				foreach($data as $single_section) {
					$conditionArray = array('classId' => $single_section['class_id'], 'isDelete' => '0');
					$classValue = $this->QueryModel->getWhere($conditionArray, 'classes');
					$classSectionvalue['sectionId'] 			= 	(!empty($single_section['sectionId']) ? $single_section['sectionId'] : "N/A");
					$classSectionvalue['section_name'] 			= 	(!empty($single_section['section_name']) ? $single_section['section_name'] : "N/A");
					$classSectionvalue['section_code'] 			= 	(!empty($single_section['section_code']) ? $single_section['section_code'] : "N/A");
					$classSectionvalue['class_code'] 			= 	(!empty($classValue['class_code']) ? $classValue['class_code'] : "N/A");
					$classSectionvalue['class_name'] 			= 	(!empty($classValue['class_name']) ? $classValue['class_name'] : "N/A");
					$classSectionvalue['sectionId'] 			= 	(!empty($classValue['classId']) ? $classValue['classId'] : "N/A");
					$classSectionList[]							=	$classSectionvalue;
					unset($classSectionvalue);
					unset($classValue);
				}
			} else {
				$classSectionList 								= 	[];
			}
			$sendData['classSectionList'] 						= 	$classSectionList;
			$this->load->view('listsection.php', $sendData);
		} catch(Exception $e) {
			echo $e->getMessage(); die;
		}
	}


	/**
	 * @purpose: fetch class details agianst classId
	 */
	public function fetchClass($class_id) {
		try {
			if(!empty($class_id) || !is_null($class_id) || trim($class_id) !== '') {
				$class_id 					= 	base64_decode($class_id);
				if(is_numeric($class_id) === true) {
					$classConditionArray 	= 	array('classId' => $class_id, 'isDelete' => '0');
					$data['class'] 			= 	$this->QueryModel->getWhere($classConditionArray, 'classes');
					if(!empty($data['class'])) {
						$this->load->view('classdetails.php', $data);
					} else {
						redirect('ClassSection/listClass', 'refresh');
					}
				} else {
					redirect('ClassSection/listClass', 'refresh');
				}
			} else {
				redirect('ClassSection/listClass', 'refresh');
			}
		} catch(Exception $e) {
			echo $e->getMessage(); die;
		}
	}


	/**
	 * @purpose: edit class detail
	 */
	public function editClass(){
		try{
			if(!empty($this->input->post()) && $this->input->post('update') === 'Update' && !empty($this->input->post('classId'))) {
				$this->form_validation->set_rules('class_name', 'class_name', 'required');
				$this->form_validation->set_rules('class_code', 'class_code', 'required');
				if ($this->form_validation->run() === FALSE) {
                    $this->session->set_flashdata('error', validation_errors());
					redirect('ClassSection/fetchClass/'.base64_encode($this->input->post('classId')), $this->input->post('classId'), 'refresh');
				} else {
					$classConditionArray 		= 	array('class_code' => trim(strip_tags($this->input->post('class_code'))), 'isDelete' => '0');
					$classCodeExists 			= 	$this->QueryModel->getWhere($classConditionArray, 'classes');
					if(!empty($classCodeExists)) {
						$this->session->set_flashdata('error', 'Class code already exists.');
						redirect('ClassSection/fetchClass/'.base64_encode($this->input->post('classId')), 'refresh');
					} else {
						$array 					= 	array('classId' => $this->input->post('classId'));
						$updateArray = array(
							'class_code' 		=> 	trim(strip_tags($this->input->post('class_code'))),
							'class_name' 		=> 	trim(strip_tags($this->input->post('class_name')))
						);
						$updateStatus = $this->QueryModel->updateData($updateArray, $array, 'classes');
						if($updateStatus === true) {
							$this->session->set_flashdata('success', 'Class updated successfully.');
							redirect('ClassSection/listClass', 'refresh');
						} else {
							$this->session->set_flashdata('error', 'Try again.');
							redirect('ClassSection/listClass', 'refresh');
						}
					}
				}
			} else {
				redirect('ClassSection/listClass', 'refresh');
			}
		} catch(Exception $e) {
			echo $e->getMessage(); die;
		}
	}


	
}