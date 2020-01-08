<?php

//require_once '/var/www/html/ReportCard/vendor/autoload.php';
require_once './vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Dompdf\Dompdf as Dompdf;

class Marks extends CI_Controller{

  public function __construct() {
    parent:: __construct();
    if(empty($this->session->userdata('email'))) {
      redirect('Login', 'refresh');
    }
    $this->load->library('form_validation');
    $this->load->model('QueryModel');
    $this->config->load('rabbitmq');
  }



  /**
   * @purpose: Marks enter for all student of specific class section agianst subject
  */
  public function studentMark(){
    try {
      if(!empty($this->input->post()) && $this->input->post('create') === 'Create') {
        $studentMarksConditionArray = array(
          'class_id' => trim(strip_tags($this->input->post('class_id'))),
          'section_id' => trim(strip_tags($this->input->post('section_id'))),
          'exam_id' => trim(strip_tags($this->input->post('exam_id')))
        );
        $checkMarksAlreadyGiven = $this->QueryModel->getWhere($studentMarksConditionArray, 'student_marks');
        if(empty($checkMarksAlreadyGiven)) {
          for($i = 0; $i < count($this->input->post('studentId')); $i++) {
            $insertArray = array(
              'exam_id' => (!empty(trim(strip_tags($this->input->post('exam_id')))) ? trim(strip_tags($this->input->post('exam_id'))) : "N/A") ,
              'section_id' => (!empty(trim(strip_tags($this->input->post('section_id')))) ? trim(strip_tags($this->input->post('section_id')))  :"N/A") ,
              'class_id' => (!empty(trim(strip_tags($this->input->post('class_id')))) ? trim(strip_tags($this->input->post('class_id'))) : "N/A"),
              'subject_id' => (!empty(trim(strip_tags($this->input->post('subject_id')))) ? trim(strip_tags($this->input->post('subject_id')))  :"N/A"),
              'total_marks' => (!empty(trim(strip_tags($this->input->post('total_marks')))) ? trim(strip_tags($this->input->post('total_marks'))) : "N/A"),
              'student_id' => (!empty(trim(strip_tags($this->input->post('studentId')[$i]))) ? trim(strip_tags($this->input->post('studentId')[$i])) : "0"),
              'otained_marks' => (!empty(trim(strip_tags($this->input->post('marks')[$i]))) ? trim(strip_tags($this->input->post('marks')[$i])) : "0")
            );
            $this->QueryModel->insertDataIntoTable($insertArray, 'student_marks');
          }
          $this->session->set_flashdata('success', 'Marks given successfully done.');
          redirect('Marks/studentMark', 'refresh');
        } else {
          $this->session->set_flashdata('error', 'Marks already given for exam.');
          redirect('Marks/studentMark', 'refresh');
        }
      } else {
        $data['exams']              =   $this->QueryModel->getMultipleRow(array('isDelete' => '0'), 'exams');
        $data['classes'] 			      = 	$this->QueryModel->getMultipleRow(array('isDelete' => '0'), 'classes');
        $this->load->view('marks/createmarks.php', $data);
      }
    } catch(Exception $e) {
      echo $e->getMessage(); die;
    }
  }



  /**
  * @purpose: get student marks list
  */
  public function listStudentMarks($offset = 0){
    try {
      if($offset > 1) {
        $offset 						        = 	$offset - 1;
        $offset 						        = 	(int)$offset * 10;
      } else {
            $offset 				        = 	(int)$offset;
      }
      $config['base_url'] 				  = 	base_url('Marks/listStudentMarks');
      $config['total_rows'] 				= 	$this->QueryModel->getNumberOfRows('student_marks');
      $config['first_link']         =   false;
      $config['last_link']          =   false;
      $config['per_page'] 				  = 	10;
      $config['num_links'] 				  = 	5;
      $config['use_page_numbers'] 	= 	TRUE;
      $config['full_tag_open'] 			= 	'<ul class="pagination">';
      $config['full_tag_close'] 		= 	'</ul>';
      $config['prev_link'] 				  = 	'&laquo;';
      $config['prev_tag_open'] 			= 	'<li>';
      $config['prev_tag_close'] 		= 	'</li>';
      $config['next_tag_open'] 			= 	'<li>';
      $config['next_tag_close'] 		= 	'</li>';
      $config['cur_tag_open'] 			= 	'<li class="active"><a href="#">';
      $config['cur_tag_close'] 			= 	'</a></li>';
      $config['num_tag_open'] 			= 	'<li>';
      $config['num_tag_close'] 			= 	'</li>';
      $this->pagination->initialize($config);
      $studentMarksArray = $this->QueryModel->fetchDataWithLimitOffset('student_marks', $config['per_page'], $offset, array('isDelete' => '0'));
      if(!empty($studentMarksArray)) {
        foreach($studentMarksArray as $single_studentMarks) {
          $examDetails = $this->QueryModel->getWhere(array('examId' => $single_studentMarks['exam_id'], 'isDelete' => '0'), 'exams');
          $classDetails = $this->QueryModel->getWhere(array('classId' => $single_studentMarks['class_id'], 'isDelete' => '0'), 'classes');
          $sectionDetails = $this->QueryModel->getWhere(array('sectionId' => $single_studentMarks['section_id'], 'isDelete' => '0'), 'sections');
          $subjectDetals = $this->QueryModel->getWhere(array('subjectId' => $single_studentMarks['subject_id'], 'isDelete' => '0'), 'subjects');
          $studentDetails = $this->QueryModel->getWhere(array('studentId' => $single_studentMarks['student_id'], 'isDelete' => '0'), 'students');
          $single_studentMarks['exam_name'] = $examDetails['exam_name'];
          $single_studentMarks['class_name'] = $classDetails['class_name'];
          $single_studentMarks['section_name'] = $sectionDetails['section_name'];
          $single_studentMarks['subject_name'] = $subjectDetals['subject_name'];
          $single_studentMarks['student_name'] = (!empty($studentDetails['student_name']) ? $studentDetails['student_name'] : "N/A");
          unset($single_studentMarks['subject_id']);
          unset($single_studentMarks['student_id']);
          unset($single_studentMarks['section_id']);
          unset($single_studentMarks['class_id']);
          unset($single_studentMarks['exam_id']);
          $value['studentmarks'][] = $single_studentMarks;
        }
      } else {
        $value['studentmarks'] = array();
      }
      $this->load->view('marks/liststudentmarks', $value);
    } catch(Exception $e) {
      echo $e->getMessage(); die;
    }
  }




  /*
  * @purpose: get student marks by subject and exam
  */
  public function subjectsMarks() {
    try {
      if(!empty($this->input->post()) && $this->input->post('button') === 'View') {
        
      } else {
        $data['exams'] = $this->QueryModel->getWhere(array('isDelete' => '0'), 'exams');
        $data['classes'] = $this->QueryModel->getWhere(array('isDelete' => '0', 'classes'));
        echo "<pre>"; print_r($data); die;
      }
    } catch(Exception $e) {
      echo $e->getMessage(); die;
    }
  }

  /**
   * @purpose: generate class result
   */
  public function tabulationSheet(){
    try {
      if(!empty($this->input->post()) && $this->input->post('button') === 'Generate') {
        $this->form_validation->set_rules('exam_id', 'Exam', 'required');
        $this->form_validation->set_rules('class_id', 'Class', 'required');
        $this->form_validation->set_rules('section_id', 'Section', 'required');
        if ($this->form_validation->run() === FALSE) {
          $this->session->set_flashdata('error',validation_errors());
          redirect('Marks/tabulationSheet', 'refresh');
        } else {
          $sheetAlreadyGenerateOrNot = $this->QueryModel->getWhere(array('exam_id' => trim(strip_tags($this->input->post('exam_id'))), 'class_id' => trim(strip_tags($this->input->post('class_id'))), 'section_id' => trim(strip_tags($this->input->post('section_id')))), 'tabulation_sheet_track');
          if(!empty($sheetAlreadyGenerateOrNot)) {
            //die;
            if($sheetAlreadyGenerateOrNot['isComplete'] === '0') {
              $this->session->set_flashdata('error', 'Tabulation sheet of given section on the way. Please wait....');
              redirect('Marks/tabulationSheet', 'refresh');
            } else {
              $this->session->set_flashdata('success', 'Tabulation Sheet generated succesfully. Download from list or delete it from list to regenerate.');
              redirect('Marks/tabulationSheet', 'refresh');
            }
          } else {
            $checkStudentmarksExistsOrNot = $this->QueryModel->fetchDataWithLimitOffset('student_marks', 1, 0, array('class_id' => trim(strip_tags($this->input->post('class_id'))), 'section_id' => trim(strip_tags($this->input->post('section_id'))), 'exam_id' => trim(strip_tags($this->input->post('exam_id'))), 'isDelete' => '0'));
            if(empty($checkStudentmarksExistsOrNot)) {
              $this->session->set_flashdata('error', 'Please submit marks for tabulation sheet');
              redirect('Marks/tabulationSheet', 'refresh'); 
            } else {
              $insertArray = array(
                'exam_id'       =>   trim(strip_tags($this->input->post('exam_id'))),
                'class_id'      =>   trim(strip_tags($this->input->post('class_id'))),
                'section_id'    =>   trim(strip_tags($this->input->post('section_id'))),
                'isComplete'    =>  '0'
              );
              $sheetId          =   $this->QueryModel->insertDataIntoTable($insertArray, 'tabulation_sheet_track');
              $connection = new AMQPStreamConnection($this->config->item('rabbitmq')['host'], 5672, $this->config->item('rabbitmq')['user'], $this->config->item('rabbitmq')['pass']);
              $channel = $connection->channel();
              $channel->queue_declare('hello', false, false, false, false);
              $msg = new AMQPMessage(json_encode(array('exam_id' => trim(strip_tags($this->input->post('exam_id'))), 'class_id' => trim(strip_tags($this->input->post('class_id'))), 'section_id' => trim(strip_tags($this->input->post('section_id'))), 'sheet_id' => $sheetId)));
              $channel->basic_publish($msg, '', 'hello');
              $channel->close();
              $connection->close();
              $this->session->set_flashdata('success', 'Tabulation Sheet will be avaliable after some times.');
              redirect('Marks/tabulationSheet', 'refresh'); 
            }
          }
        }
        
      } else {
        $data['exams']              =   $this->QueryModel->getMultipleRow(array('isDelete' => '0'), 'exams');
        $data['classes'] 			      = 	$this->QueryModel->getMultipleRow(array('isDelete' => '0'), 'classes');
        $this->load->view('marks/createsectionresult.php', $data);
      }
    } catch(Exception $e){
      echo $e->getMessage(); die;
    }
  }

  /**
   * @purpose: List Generation
   */
  public function tabulationSheetList($offset = 0){
    try {
      if($offset > 1) {
        $offset 						= 	$offset - 1;
        $offset 						= 	(int)$offset * 10;
      } else {
        $offset 						= 	(int)$offset;
      }
      $config['base_url'] 				  = 	base_url('Subject/tabulationSheet');
      $config['total_rows'] 				= 	$this->QueryModel->getNumberOfRows('tabulation_sheet_track');
      $config['per_page'] 				  = 	10;
      $config['num_links'] 				  = 	5;
      $config['use_page_numbers'] 	= 	TRUE;
      $config['full_tag_open'] 			= 	'<ul class="pagination">';
      $config['full_tag_close'] 		= 	'</ul>';
      $config['prev_link'] 				  = 	'&laquo;';
      $config['prev_tag_open'] 			= 	'<li>';
      $config['prev_tag_close'] 		= 	'</li>';
      $config['next_tag_open'] 			= 	'<li>';
      $config['next_tag_close'] 		= 	'</li>';
      $config['cur_tag_open'] 			= 	'<li class="active"><a href="#">';
      $config['cur_tag_close'] 			= 	'</a></li>';
      $config['num_tag_open'] 			= 	'<li>';
      $config['num_tag_close'] 			= 	'</li>';
      $this->pagination->initialize($config);
      $data 					              = 	$this->QueryModel->fetchDataWithLimitOffset('tabulation_sheet_track', $config['per_page'], $offset, array('isDelete' => '0'));
      if(!empty($data)) {
        foreach($data as $single_sheet) {
          $classArray = $this->QueryModel->getWhere(array('classId' => $single_sheet['class_id'], 'isDelete' => '0'), 'classes');
          $sectionArray = $this->QueryModel->getWhere(array('sectionId' => $single_sheet['section_id'], 'isDelete' => '0'), 'sections');
          $examArray = $this->QueryModel->getWhere(array('examId' => $single_sheet['exam_id'], 'isDelete' => '0'), 'exams');
          $single_sheet['class_name'] = (!empty($classArray['class_name']) ? $classArray['class_name'] : "N/A");
          $single_sheet['section_name'] = (!empty($sectionArray['section_name']) ? $sectionArray['section_name'] : "N/A");
          $single_sheet['exam_name'] = (!empty($examArray['exam_name']) ? $examArray['exam_name'] : "N/A");
          unset($single_sheet['class_id']);
          unset($single_sheet['section_id']);
          unset($single_sheet['exam_id']);
          $value['tabulatonSheets'][] = $single_sheet;
        }
      } else {
        $value['tabulatonSheets']   = array(); 
      }
      $this->load->view('marks/listtabulationsheet.php', $value);
    } catch(Exception $e) {

    }
  }


  /**
   * @purpose: Download csv 
   */
  public function download($csv_name) {
    try {
      if(!empty($csv_name)) {
        $file_name = TABAULATION_SHEET_URL.urldecode($csv_name);
        if(file_exists($file_name)) {
          $this->load->helper('download');
          force_download($file_name, NULL);
          redirect('Marks/tabulationSheet', 'refresh');
        } else {
          redirect('Marks/tabulationSheet', 'refresh');
        }
      } else {
        redirect('Marks/tabulationSheet', 'refresh');
      }
    } catch(Exception $e){
      echo $e->getMessage(); die;
    }
  }


  /**
   * @purpose: delete sheet for regenerate
   */
  public function deleteSheet() {
    try {
      if(!empty($this->input->post('sheetId'))) {
        $getSheetDetails = $this->QueryModel->getWhere(array('sheetId' => $this->input->post('sheetId')), 'tabulation_sheet_track');
        if(!empty($getSheetDetails)) {
          $deleteStatus = $this->QueryModel->deleteDataFromDataBase(array('sheetId' => $this->input->post('sheetId')), 'tabulation_sheet_track');
          if($deleteStatus === true) {
            unlink(TABAULATION_SHEET_URL.'/'.$getSheetDetails['csv_name']);
            echo "1"; die;
          } else {
            echo "error"; die;
          }
        } else {
          echo "error"; die;
        }
      } else {
        echo "error"; die;
      }
    } catch(Throwable $e) {
      echo "error"; die;
    }
  }


  /**
   * @purpose: delete student marks
   */
  public function delete(){
    try {
      if(!empty($this->input->post('raw_id'))) {
        $deleteStatus = $this->QueryModel->updateData(array('isDelete' => '1'), array('studentMarksId' => $this->input->post('raw_id'), 'isDelete' => '0'), 'student_marks');
        if($deleteStatus === true) {
          echo "success"; die;
        } else {
          echo "error"; die;
        }
      } else {
        echo "error"; die;
      }
    } catch(Throwable $e) {
      echo "error"; die;
    }
  }


  /**
   * @purpose: Download section wise student
   */
  public function sectionWiseResult() {
    try {
      if(!empty($this->input->post()) && $this->input->post('button') === 'Generate') {
        $this->form_validation->set_rules('class_id', 'Class', 'required');
        $this->form_validation->set_rules('section_id', 'Section', 'required');
        if ($this->form_validation->run() === FALSE) {
          $this->session->set_flashdata('error',validation_errors());
          redirect('Marks/sectionWiseResult', 'refresh');
        } else {
          $insert_status = $this->QueryModel->insertDataIntoTable(array('class_id' => trim(strip_tags($this->input->post('class_id'))), 'section_id' => trim(strip_tags($this->input->post('section_id')))), 'pdf_track');
          if(!empty($insert_status)) {
            $connection = new AMQPStreamConnection($this->config->item('rabbitmq')['host'], 5672, $this->config->item('rabbitmq')['user'], $this->config->item('rabbitmq')['pass']);
            $channel = $connection->channel();
            $channel->queue_declare('pdfResult', false, true, false, false);
            $data = json_encode(array('class_id' => trim(strip_tags($this->input->post('class_id'))), 'section_id' => trim(strip_tags($this->input->post('section_id'))), 'pdf_track_id' => $insert_status));
            $msg = new AMQPMessage(
              $data,
              array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)
            );
            $channel->basic_publish($msg, '', 'pdfResult');
            $channel->close();
            $connection->close();
            $this->session->set_flashdata('success', 'Result as an PDF will be avaliable after some times.');
            redirect('Marks/sectionWiseResult', 'refresh'); 
          } else {
            $this->session->set_flashdata('error', 'Try again.');
            redirect('Marks/sectionWiseResult', 'refresh');
          }
        }
      } else {
        $data['classes'] 			      = 	$this->QueryModel->getMultipleRow(array('isDelete' => '0'), 'classes');
        $this->load->view('marks/sectionwiseresult', $data);
      }
    } catch(Thorwable $e) {
      echo $e->getMessage(); die;
    }
  }


  /**
   * @purpose: csv upload for 
   */
  public function csvUpload() {
    try {
      if(!empty($this->input->post()) && $this->input->post('create') === 'Upload') {
        if(!empty($_FILES['markscsv']['name'])) {
          if($_FILES['markscsv']['type'] === "text/csv") {
            $file = fopen($_FILES["markscsv"]["tmp_name"],"r");
            $finalArr = array();
            $count = 0;
            while($data = fgetcsv($file)) {
              array_push($finalArr,$data);                   
            }
            foreach($finalArr as $single_student_marks) {
              if(empty($single_student_marks[0]) || is_null($single_student_marks[0]) || trim($single_student_marks[0]) === '') {
                
              }
              $examExistsOrNot = $this->QueryModel->getWhere(array('exam_name' => trim(strip_tags($single_student_marks[0])), 'isDelete' => '0'), 'exams');
              if(!empty($examExistsOrNot)) {
                $classExistsOrNot = $this->QueryModel->getWhere(array('class_name' => trim(strip_tags($single_student_marks[1])), 'isDelete' => '0'), 'classes');
                if(!empty($classExistsOrNot)) {
                  $sectionExistsOrNot = $this->QueryModel->getWhere(array('section_name' => trim(strip_tags($single_student_marks[2])), 'class_id' => $classExistsOrNot['classId'], 'isDelete' => '0'), 'sections');
                  if(!empty($sectionExistsOrNot)) {
                    $subjectExistsOrNot = $this->QueryModel->getWhere(array('subject_name' => trim(strip_tags($single_student_marks[3])), 'isDelete' => '0'), 'subjects');
                    if(!empty($subjectExistsOrNot)) {
                      $student_name_array = explode(' ', $single_student_marks[4]);
                      if(sizeof($student_name_array) === 3) {
                        $student_name = $student_name_array[2]." ".$student_name_array[0]." ".$student_name_array[1];
                      } 
                      if(sizeof($student_name_array) === 2) {
                        $student_name = $student_name_array[1]." ".$student_name_array[0];
                      }
                      if(sizeof($student_name_array) === 1) {
                        $student_name = $student_name_array[0];
                      }
                      $checkStudentExistsOrNot = $this->QueryModel->getWhere(array('student_name' => $student_name, 'isDelete' => '0'), 'students');
                      if($checkStudentExistsOrNot) {
                        $checkNumberAlreadyGivenOrNot = $this->QueryModel->getWhere(array('exam_id' => $examExistsOrNot['examId'], 'class_id' => $classExistsOrNot['classId'], 'section_id' => $sectionExistsOrNot['sectionId'], 'subject_id' => $subjectExistsOrNot['subjectId'], 'student_id' => $checkStudentExistsOrNot['studentId']), 'student_marks');
                        if(empty($checkNumberAlreadyGivenOrNot)){
                          $insertArray = array('exam_id' => $examExistsOrNot['examId'], 'class_id' => $classExistsOrNot['classId'], 'section_id' => $sectionExistsOrNot['sectionId'], 'subject_id' => $subjectExistsOrNot['subjectId'], 'student_id' => $checkStudentExistsOrNot['studentId'], 'total_marks' => trim(strip_tags($single_student_marks[6])), 'otained_marks' => trim(strip_tags($single_student_marks[5])));
                          $this->QueryModel->insertDataIntoTable($insertArray, 'student_marks');
                          unset($insertArray);
                          continue;
                        } else {
                          continue;
                        }
                      } else {
                        continue;
                      }
                    } else {
                      continue;
                    }
                  } else {
                    continue;
                  }
                } else {
                  continue;
                }
              } else {
                continue;
              }
            }
            unlink($_FILES['markscsv']['tmp_name']);
            $this->session->set_flashdata('success','Marks entry successfully done.');
						redirect('Marks/csvUpload', 'refresh');
          } else {
            $this->session->set_flashdata('error','CSV file is required.Try again!');
						redirect('Marks/csvUpload', 'refresh');
          }
        } else {
          $this->session->set_flashdata('error','CSV file is required.Try again!');
					redirect('Marks/csvUpload', 'refresh');
        }
      } else {
        $this->load->view('marks/csvupload');
      }
    } catch(Thorwable $e) {
      echo $e->getMessage(); die;
    }
  }


  /**
   * @purpose: serach among student list marks
   */

  public function search(){
    try {
      echo "<pre>"; print_r($_GET); die;
    } catch(Thorwable $e) {
      echo $e->getMessage(); die;
    }
  }


  /**
   * @purpose: edit marks 
   */
  public function fetch($encrypt_id){
    try {
      if(!empty($encrypt_id)) {
        $raw_id = base64_decode($encrypt_id);
        if(!empty($raw_id)) {
          $getData = $this->QueryModel->getWhere(array('studentMarksId' => $raw_id, 'isDelete' => '0'), 'student_marks');
          if(!empty($getData)) {
            $examDetails = $this->QueryModel->getWhere(array('examId' => $getData['exam_id'], 'isDelete' => '0'), 'exams');
            $classDetails = $this->QueryModel->getWhere(array('classId' => $getData['class_id'], 'isDelete' => '0'), 'classes');
            $sectionDetails = $this->QueryModel->getWhere(array('sectionId' => $getData['section_id'], 'isDelete' => '0'), 'sections');
            $subjectDetals = $this->QueryModel->getWhere(array('subjectId' => $getData['subject_id'], 'isDelete' => '0'), 'subjects');
            $studentDetails = $this->QueryModel->getWhere(array('studentId' => $getData['student_id'], 'isDelete' => '0'), 'students');
            $getData['exam_name'] = (!empty($examDetails['exam_name']) ? $examDetails['exam_name'] : "N/A");
            $getData['class_name'] = (!empty($classDetails['class_name']) ? $classDetails['class_name'] : "N/A");
            $getData['section_name'] = (!empty($sectionDetails['section_name']) ? $sectionDetails['section_name'] : "N/A");
            $getData['subject_name'] = (!empty($subjectDetals['subject_name']) ? $subjectDetals['subject_name'] : "N/A");
            $getData['student_name'] = (!empty($studentDetails['student_name']) ? $studentDetails['student_name'] : "N/A");
            $this->load->view('marks/editStudnetMarks', $getData);
          } else {
            redirect('Marks/listStudentMarks', 'refresh');
          }
        } else {
          redirect('Marks/listStudentMarks', 'refresh');
        }
      } else {
        redirect('Marks/listStudentMarks', 'refresh');
      }
    } catch(Thorwable $e) {
      echo $e->getMessage(); die;
    }
  }


  /**
   * @purpose: update student mark
   */
  public function edit() {
    try {
      if((!empty($this->input->post())) && ($this->input->post('update') === 'Update') && (!empty($this->input->post('raw'))) ) {
        $raw_id = base64_decode($this->input->post('raw'));
        $status = $this->QueryModel->updateData(array('total_marks' => trim(strip_tags($this->input->post('total_marks'))), 'otained_marks' => trim(strip_tags($this->input->post('otained_marks')))), array('studentMarksId' => $raw_id, 'isDelete' => '0'), 'student_marks');
        if($status === true) {
          $this->session->set_flashdata('success', 'Student update sucessfully done.');
          redirect('Marks/fetch/'.$this->input->post('raw'), 'refresh');
        } else {
          $this->session->set_flashdata('error', 'Try again!');
          redirect('Marks/fetch/'.$this->input->post('raw'), 'refresh');
        }
      } else {
        redirect('Marks/listStudentMarks', 'refresh');
      }
    } catch(Thorwable $e) {
      echo $e->getMessage(); die;
    }
  }


  /**
   * @purpose: marks entry for single student
   */
  public function marksEntry() {
    try {
      if(!empty($this->input->post())) {
        echo "<pre>"; print_r($this->input->post()); die;
      } else {
        $data['exams']              =   $this->QueryModel->getMultipleRow(array('isDelete' => '0'), 'exams');
        $data['classes'] 			      = 	$this->QueryModel->getMultipleRow(array('isDelete' => '0'), 'classes');
        $this->load->view('marks/singlestudentmarks', $data);
      }
    } catch(Thorwable $e) {
      echo $e->getMessage(); die;
    }
  }
}
