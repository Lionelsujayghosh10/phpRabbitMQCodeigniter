<?php

require_once '/var/www/html/ReportCard/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


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
      $studentMarksArray = $this->QueryModel->fetchDataWithLimitOffset('student_marks', $config['per_page'], $offset, array('isDelete' => '0'));
      if(!empty($studentMarksArray)) {
        foreach($studentMarksArray as $single_studentMarks) {
          $examDetails = $this->QueryModel->getWhere(array('examId' => $single_studentMarks['exam_id'], 'isdelete' => '0'), 'exams');
          $classDetails = $this->QueryModel->getWhere(array('classId' => $single_studentMarks['class_id'], 'isdelete' => '0'), 'classes');
          $sectionDetails = $this->QueryModel->getWhere(array('sectionId' => $single_studentMarks['section_id'], 'isdelete' => '0'), 'sections');
          $subjectDetals = $this->QueryModel->getWhere(array('subjectId' => $single_studentMarks['subject_id'], 'isdelete' => '0'), 'subjects');
          $single_studentMarks['exam_name'] = $examDetails['exam_name'];
          $single_studentMarks['class_name'] = $classDetails['class_name'];
          $single_studentMarks['section_name'] = $sectionDetails['section_name'];
          $single_studentMarks['subject_name'] = $subjectDetals['subject_name'];
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
  public function sectionResult(){
    try {
      echo $this->config->item('rabbitmq')['host']; die;
      if(!empty($this->input->post()) && $this->input->post('button') === 'Generate') {
        $this->form_validation->set_rules('exam_id', 'Exam', 'required');
        $this->form_validation->set_rules('class_id', 'Class', 'required');
        $this->form_validation->set_rules('section_id', 'Section', 'required');
        if ($this->form_validation->run() === FALSE) {
          $this->session->set_flashdata('error',validation_errors());
          redirect('Marks/sectionResult', 'refresh');
        } else {
          $sheetAlreadyGenerateOrNot = $this->QueryModel->getWhere(array('exam_id' => trim(strip_tags($this->input->post('exam_id'))), 'class_id' => trim(strip_tags($this->input->post('class_id'))), 'section_id' => trim(strip_tags($this->input->post('section_id'))), 'isComplete' => '1'), 'tabulation_sheet_track');
          if(!empty($sheetAlreadyGenerateOrNot)) {
            $this->session->set_flashdata('success', 'Tabulation Sheet generated succesfully. Download from list');
            redirect('Marks/sectionResult', 'refresh');
          } else {
            $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
            $channel = $connection->channel();
            $channel->queue_declare('hello', false, false, false, false);
            $msg = new AMQPMessage(json_encode(array('exam_id' => trim(strip_tags($this->input->post('exam_id'))), 'class_id' => trim(strip_tags($this->input->post('class_id'))), 'section_id' => trim(strip_tags($this->input->post('section_id'))))));
            $channel->basic_publish($msg, '', 'hello');
            $channel->close();
            $connection->close();
            $this->session->set_flashdata('success', 'Tabulation Sheet will be avaliable after some times.');
            redirect('Marks/sectionResult', 'refresh');
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
  public function tabulationSheet($offset = 0){
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
}
