<?php


defined('BASEPATH') OR exit('No direct script access allowed');


class Subject extends CI_Controller {


    public function __construct() {
        parent:: __construct();
        if(empty($this->session->userdata('email'))) {
            redirect('Login', 'refresh');
        }

        $this->load->library('form_validation');
        $this->load->model('QueryModel');
    }


  /**
   * @purpose: csv upload for subjec and assigned subjects.
   */
  public function uploadCsv() {
    try {
        if(!empty($this->input->post()) && $this->input->post('create') === 'Upload') {
            if(!empty($_FILES['subjectassigncsv'])) {
      if(!empty($_FILES['subjectassigncsv']['name'])) {
                    if($_FILES['subjectassigncsv']['type'] === "text/csv") {
                        $file = fopen($_FILES["subjectassigncsv"]["tmp_name"],"r");
          $finalArr = array();
          $count = 0;
          while($data = fgetcsv($file)) {
            array_push($finalArr,$data);
                        }
                        foreach($finalArr as $single_value) {
                            if(empty($single_value[0]) || is_null($single_value[0]) || trim($single_value[0]) === '') {
              $this->session->set_flashdata('error','Subject name can not be empty.Try again!');
              redirect('ClassSection/uploadCsv', 'refresh');
            }
            if(empty($single_value[1]) || is_null($single_value[1]) || trim($single_value[1]) === '') {
              $this->session->set_flashdata('error','Subject code can not be empty.Try again!');
              redirect('ClassSection/uploadCsv', 'refresh');
            }
            if(empty($single_value[2]) || is_null($single_value[2]) || trim($single_value[2]) === '') {
              $this->session->set_flashdata('error','section Name can not be empty.Try again!');
              redirect('ClassSection/uploadCsv', 'refresh');
            }
            if(empty($single_value[3]) || is_null($single_value[3]) || trim($single_value[3]) === '') {
              $this->session->set_flashdata('error','class name can not be empty.Try again!');
              redirect('ClassSection/uploadCsv', 'refresh');
                            }
                            $classConditionArray = array('class_name' => trim(strip_tags($single_value[3])), 'isDelete' => '0');
                            $checkClassExistsOrnot = $this->QueryModel->getWhere($classConditionArray, 'classes');
                            if(!empty($checkClassExistsOrnot)) {
                                $sectionConditionArray = array('section_name' => trim(strip_tags($single_value[2])), 'isDelete' => '0', 'class_id' => $checkClassExistsOrnot['classId']);
                                $checkSectionExistsOrnot = $this->QueryModel->getWhere($sectionConditionArray, 'sections');
                                if(!empty($checkSectionExistsOrnot)) {
                                    $subjectConditionArray = array('subject_code' => trim(strip_tags($single_value[1])), 'subject_name' => trim(strip_tags($single_value[0])), 'isDelete' => '0');
                                    $checkSubjectExistsOrNot = $this->QueryModel->getWhere($subjectConditionArray, 'subjects');
                                    if(!empty($checkSubjectExistsOrNot)) {
                                        $assignSubjectCondition = array('class_id' => $checkClassExistsOrnot['classId'], 'section_id' => $checkSectionExistsOrnot['sectionId'], 'subject_id' => $checkSubjectExistsOrNot['subjectId'], 'isDelete' => '0');
                                        $checkAssignSubjectExistsOrNot = $this->QueryModel->getWhere($assignSubjectCondition, 'assign_subject');
                                        if(!empty($checkAssignSubjectExistsOrNot)){
                                            unset($assignSubjectCondition);
                                            unset($checkSectionExistsOrnot);
                                            unset($checkAssignSubjectExistsOrNot);
                                        } else {
                                            $assignSubjectArray = array(
                                                'subject_id'    =>  $checkSubjectExistsOrNot['subjectId'],
                                                'class_id'      =>  $checkClassExistsOrnot['classId'],
                                                'section_id'    =>  $checkSectionExistsOrnot['sectionId'],
                                                'isDelete'      =>  '0'
                                            );
                                            $assignSubjectId = $this->QueryModel->insertDataIntoTable($assignSubjectArray, 'assign_subject');
                                            unset($assignSubjectCondition);
                                            unset($checkSectionExistsOrnot);
                                            unset($checkAssignSubjectExistsOrNot);
                                        }
                                    } else {
                                        $subejctArray = array(
                                            'subject_code'  =>  trim(strip_tags($single_value[1])),
                                            'subject_name'  =>  trim(strip_tags($single_value[0])),
                                            'isDelete'      =>  '0'
                                        );
                                        $subjectId = $this->QueryModel->insertDataIntoTable($subejctArray, 'subjects');
                                        $assignSubjectCondition = array('class_id' => $checkClassExistsOrnot['classId'], 'section_id' => $checkSectionExistsOrnot['sectionId'], 'subject_id' => $subjectId, 'isDelete' => '0');
                                        $checkAssignSubjectExistsOrNot = $this->QueryModel->getWhere($assignSubjectCondition, 'assign_subject');
                                        if(!empty($checkAssignSubjectExistsOrNot)){
                                            unset($assignSubjectCondition);
                                            unset($checkSectionExistsOrnot);
                                            unset($assignSubjectCondition);
                                            continue;
                                        } else {
                                            $assignSubjectArray = array(
                                                'subject_id'    =>  $subjectId,
                                                'class_id'      =>  $checkClassExistsOrnot['classId'],
                                                'section_id'    =>  $checkSectionExistsOrnot['sectionId'],
                                                'isDelete'      =>  '0'
                                            );
                                            $assignSubjectId = $this->QueryModel->insertDataIntoTable($assignSubjectArray, 'assign_subject');
                                            unset($assignSubjectCondition);
                                            unset($checkSectionExistsOrnot);
                                            unset($checkAssignSubjectExistsOrNot);
                                            continue;
                                        }
                                    }
                                } else {
                                    continue;
                                }
                            } else {
                                continue;
                            }
                        }
                        $this->session->set_flashdata('success','CSV file uploaded successful.');
                        redirect('Subject/uploadCsv', 'refresh');
                    } else {
                        $this->session->set_flashdata('error','CSV file is required.Try again!');
                        redirect('Subject/uploadCsv', 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('error','CSV file is required.Try again!');
                    redirect('Subject/uploadCsv', 'refresh');
                }
            } else {
                $this->session->set_flashdata('error','CSV file is required.Try again!');
                redirect('Subject/uploadCsv', 'refresh');
            }

        } else {
            $this->load->view('subjects/assignsubjectcsv.php');
        }
    } catch(Exception $e){
        echo $e->getMessage(); die;
    }
  }


  /**
   * @purpose: subject list
   */
  public function listSubject($offset = 0) {
      try {
          if($offset > 1) {
          $offset 						= 	$offset - 1;
          $offset 						= 	(int)$offset * 10;
        } else {
      $offset 						= 	(int)$offset;
          }
          $config['base_url'] 				= 	base_url('Subject/listSubject');
          $config['total_rows'] 				= 	$this->QueryModel->getNumberOfRows('subjects');
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
          $details['data'] 					= 	$this->QueryModel->fetchDataWithLimitOffset('subjects', $config['per_page'], $offset, array('isDelete' => '0'));
      $this->load->view('subjects/listsubject.php', $details);
      } catch(Exception $e) {
          echo $e->getMessage(); die;
      }
  }



  /**
   * @purpose: assigned subject list
   */
  public function assignSubjectList($offset = 0){
      try {
            if($offset > 1) {
              $offset 						= 	$offset - 1;
              $offset 						= 	(int)$offset * 10;
            } else {
                  $offset 						= 	(int)$offset;
            }
          $config['base_url'] 				= 	base_url('Subject/assignSubjectList');
          $config['total_rows'] 				= 	$this->QueryModel->getNumberOfRows('assign_subject');
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
          $data 					            = 	$this->QueryModel->fetchDataWithLimitOffset('assign_subject', $config['per_page'], $offset, array('isDelete' => '0'));
          if(!empty($data)) {
              foreach($data as $single_assignSubject) {
                $subjectContiditionArray = array(
                  'subjectId' => $single_assignSubject['subject_id'],
                  'isDelete' => '0'
                );
                $subjectArray = $this->QueryModel->getWhere($subjectContiditionArray, 'subjects');
                $classConditionArray = array(
                  'classId' => $single_assignSubject['class_id'],
                  'isDelete' => '0'
                );
                $classArray = $this->QueryModel->getWhere($classConditionArray, 'classes');
                $sectionConditionArray = array(
                  'sectionId' => $single_assignSubject['section_id'],
                  'isDelete' => '0'
                );
                $sectionArray = $this->QueryModel->getWhere($sectionConditionArray, 'sections');
                $single_assignSubject['subject_name'] = (!empty($subjectArray['subject_name']) ? $subjectArray['subject_name'] : "N/A");
                $single_assignSubject['class_name'] = (!empty($classArray['class_name']) ? $classArray['class_name'] : "N/A");
                $single_assignSubject['section_name'] = (!empty($sectionArray['section_name']) ? $sectionArray['section_name'] : "N/A");
                $value['assignSubjects'][] = $single_assignSubject;
              }
          } else {
            $value['assignSubjects'] = array();
          }
          //echo "<pre>"; print_r($value['assignSubjects']); die;
          $this->load->view('subjects/listassignsubjectlist.php', $value);
      } catch(Exception $e) {
          echo $e->getMessage(); die;
      }
  }


  /**
   * @purpose: fetch subject details for edit
   */
  public function editSubject($subject_id) {
    try {
      if(!empty($subject_id)) {
        if(!empty($this->input->post()) && $this->input->post('update') === 'Update' && !empty($this->input->post('subjectId'))) {
          $this->form_validation->set_rules('subject_code', 'subject_code', 'required');
          $this->form_validation->set_rules('subject_name', 'subject_name', 'required');
          if ($this->form_validation->run() === FALSE) {
              $this->session->set_flashdata('error',validation_errors());
              redirect('Subject/listSubject', 'refresh');
          } else {
            $subejctExistsOrNot = $this->QueryModel->getWhere(array('subjectId !=' => $this->input->post('subjectId'), 'subject_code' => trim(strip_tags($this->input->post('subject_code'))), 'isDelete' => '0' ), 'subjects');
            if(!empty($subejctExistsOrNot)) {
              $this->session->set_flashdata('error', 'Subject code already exists.');
              redirect('Subject/editSubject/'.base64_encode($this->input->post('subjectId')), 'refresh');
            } else {
              $updateStatus = $this->QueryModel->updateData(array('subject_code' => trim(strip_tags($this->input->post('subject_code'))), 'subject_name' => trim(strip_tags($this->input->post('subject_name')))), array('subjectId' => $this->input->post('subjectId')), 'subjects');
              if($updateStatus === true) {
                $this->session->set_flashdata('success', 'Subject updated successfully.');
                redirect('Subject/listSubject', 'refresh');
              } else {
                $this->session->set_flashdata('error', 'Try again.');
                redirect('Subject/listSubject', 'refresh');
              }
            }
          }
        } else {
          $data['subjectData'] = $this->QueryModel->getWhere(array('subjectId' => base64_decode($subject_id), 'isDelete' => '0'), 'subjects');
          $this->load->view('subjects/viewsubjects.php', $data);
        }
      } else {
        redirect('Subject/listSubject', 'refresh');
      }
    } catch(Exception $e) {
      echo $e->getMessage(); die;
    }
  }

}
