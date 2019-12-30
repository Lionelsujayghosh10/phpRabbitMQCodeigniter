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
  public function tabulationSheet(){
    try {
      if(!empty($this->input->post()) && $this->input->post('button') === 'Generate') {
        $this->form_validation->set_rules('exam_id', 'Exam', 'required');
        $this->form_validation->set_rules('class_id', 'Class', 'required');
        $this->form_validation->set_rules('section_id', 'Section', 'required');
        if ($this->form_validation->run() === FALSE) {
          $this->session->set_flashdata('error',validation_errors());
          redirect('Marks/sectionResult', 'refresh');
        } else {
          $sheetAlreadyGenerateOrNot = $this->QueryModel->getWhere(array('exam_id' => trim(strip_tags($this->input->post('exam_id'))), 'class_id' => trim(strip_tags($this->input->post('class_id'))), 'section_id' => trim(strip_tags($this->input->post('section_id')))), 'tabulation_sheet_track');
          if(!empty($sheetAlreadyGenerateOrNot)) {
            //die;
            if($sheetAlreadyGenerateOrNot['isComplete'] === '0') {
              $this->session->set_flashdata('error', 'Tabulation sheet of given section on the way. Please wait....');
              redirect('Marks/sectionResult', 'refresh');
            } else {
              $this->session->set_flashdata('success', 'Tabulation Sheet generated succesfully. Download from list or delete it from list to regenerate.');
              redirect('Marks/sectionResult', 'refresh');
            }
          } else {
            $checkStudentmarksExistsOrNot = $this->QueryModel->fetchDataWithLimitOffset('student_marks', 1, 0, array('exam_id' => trim(strip_tags($this->input->post('exam_id'))), 'class_id' => trim(strip_tags($this->input->post('class_id'))), 'section_id' => trim(strip_tags($this->input->post('section_id')))));
            if(empty($checkStudentmarksExistsOrNot)) {
              $this->session->set_flashdata('error', 'Please submit marks for tabulation sheet');
              redirect('Marks/sectionResult', 'refresh'); 
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
              redirect('Marks/sectionResult', 'refresh'); 
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
   * @purpose: Download section wise student
   */
  public function sectionWiseResult() {
    try {
      $html = '<!DOCTYPE html>
          <html>
            <head>
              <style>
                *{ font-size: 14px; border-color: #ddd !important;}
              </style>
            </head>
            <body>
              <table style="max-width: 100%; font-size: 20px; font-family: Arial, Helvetica, sans-serif; width:1000px; margin: 0 auto; ">
                <tr>
                  <td style="text-align: center; ">
                  
                  </td>
                </tr>
                <tr>
                  <td>
                  <div style="margin-bottom:10px; width:685px; text-align:center;">
                  Academic session 2019-2020<br/>
                  Mark sheet for Terminal Examination 
                  </div>
                    <div style="display:inline-block; text-align: left; width: 50%; font-size: 14px;"><strong>Name:</strong> SUJAY GHOSH </div>
                    <div style="display:inline-block; text-align: left; width: 50%; font-size: 14px;"><strong>Id No:</strong> STUD0123456 </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div style="display:inline-block; text-align: left; width: 25%; font-size: 14px; margin-bottom: 10px;"><strong>Class  :</strong> Class A </div>
                    <div style="display:inline-block; text-align: left; width: 25%; font-size: 14px;margin-bottom: 10px;"><strong>Stream:</strong> &nbsp; </div>
                    <div style="display:inline-block; text-align: left; width: 25%; font-size: 14px;margin-bottom: 10px;"><strong>Sec:</strong> Section A </div>
                    <div style="display:inline-block; text-align: left; width: 25%; font-size: 14px;margin-bottom: 10px;"><strong>Roll No.:</strong> &nbsp; </div>
                  </td>
                </tr>
                <tr>
                  <td style="text-align:center;">
                    <div style="width:180px; display:inline-block; border:1px solid #dbdbdb; height:108px; text-align:center; line-height:75px;"> Major Subject(s)
                    </div>
                    <span style="width:505px; display:inline-block; border:1px solid #dbdbdb; height:108px;"> 
                    <div style="border-bottom:1px solid #dbdbdb; height:36px; line-height:26px;">
                      Terminal
                    </div>
                    <div style="border-bottom:1px solid #dbdbdb; height:36px; line-height:26px;">
                      Marks OBTD
                    </div>
                    <div style="">
                      <span style="width:120px; margin-top:6px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px;">TH</span>
                      <span style="width:120px; margin-top:1px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px;">PR  Mks</span>
                      <span style="width:125px; margin-top:1px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px;">Total</span>
                      <span style="width:125px; margin-top:0px; border-right:none; display:inline-block; height:30px; line-height:20px;">HGST. MARKS</span>
                  
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div style="width:180px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; height:37px; margin-top: -8px;text-align:center;" >
                    English
                    </div>


                    <div style="width:505px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; margin-top: -8px;" >
                  
                      <div style="">
                        <span style="width:120px; margin-top:6px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">100</span>
                        <span style="width:120px; margin-top:1px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">&nbsp;</span>
                        <span style="width:125px; margin-top:1px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">100</span>
                        <span style="width:125px; margin-top:0px; border-right:none; display:inline-block; height:30px; line-height:20px;text-align:center;">&nbsp;</span>
                      
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div style="width:180px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; height:37px; margin-top: -8px;text-align:center;" >
                    English
                    </div>


                    <div style="width:505px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; margin-top: -8px;" >
                  
                      <div style="">
                        <span style="width:120px; margin-top:6px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">100</span>
                        <span style="width:120px; margin-top:1px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">&nbsp;</span>
                        <span style="width:125px; margin-top:1px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">100</span>
                        <span style="width:125px; margin-top:0px; border-right:none; display:inline-block; height:30px; line-height:20px;text-align:center;">&nbsp;</span>
                      
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div style="width:180px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; height:37px; margin-top: -8px;text-align:center;" >
                    English
                    </div>


                    <div style="width:505px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; margin-top: -8px;" >
                  
                      <div style="">
                        <span style="width:120px; margin-top:6px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">100</span>
                        <span style="width:120px; margin-top:1px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">&nbsp;</span>
                        <span style="width:125px; margin-top:1px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">100</span>
                        <span style="width:125px; margin-top:0px; border-right:none; display:inline-block; height:30px; line-height:20px;text-align:center;">&nbsp;</span>
                      
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div style="width:180px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; height:37px; margin-top: -8px;text-align:center;" >
                    English
                    </div>


                    <div style="width:505px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; margin-top: -8px;" >
                  
                      <div style="">
                        <span style="width:120px; margin-top:6px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">100</span>
                        <span style="width:120px; margin-top:1px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">&nbsp;</span>
                        <span style="width:125px; margin-top:1px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">100</span>
                        <span style="width:125px; margin-top:0px; border-right:none; display:inline-block; height:30px; line-height:20px;text-align:center;">&nbsp;</span>
                      
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div style="width:180px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; height:37px; margin-top: -8px;text-align:center;" >
                    English
                    </div>


                    <div style="width:505px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; margin-top: -8px;" >
                  
                      <div style="">
                        <span style="width:120px; margin-top:6px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">100</span>
                        <span style="width:120px; margin-top:1px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">&nbsp;</span>
                        <span style="width:125px; margin-top:1px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">100</span>
                        <span style="width:125px; margin-top:0px; border-right:none; display:inline-block; height:30px; line-height:20px;text-align:center;">&nbsp;</span>
                      
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div style="width:180px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; height:37px; margin-top: -8px;text-align:center;" >
                    English
                    </div>


                    <div style="width:505px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; margin-top: -8px;" >
                  
                      <div style="">
                        <span style="width:120px; margin-top:6px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">100</span>
                        <span style="width:120px; margin-top:1px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">&nbsp;</span>
                        <span style="width:125px; margin-top:1px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">100</span>
                        <span style="width:125px; margin-top:0px; border-right:none; display:inline-block; height:30px; line-height:20px;text-align:center;">&nbsp;</span>
                      
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div style="width:180px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; height:37px; margin-top: -8px;text-align:center;" >
                    English
                    </div>


                    <div style="width:505px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; margin-top: -8px;" >
                  
                      <div style="">
                        <span style="width:120px; margin-top:6px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">100</span>
                        <span style="width:120px; margin-top:1px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">&nbsp;</span>
                        <span style="width:125px; margin-top:1px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">100</span>
                        <span style="width:125px; margin-top:0px; border-right:none; display:inline-block; height:30px; line-height:20px;text-align:center;">&nbsp;</span>
                      
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div style="width:180px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; height:37px; margin-top: -8px;text-align:center;" >
                    English
                    </div>


                    <div style="width:505px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; margin-top: -8px;" >
                  
                      <div style="">
                        <span style="width:120px; margin-top:6px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">100</span>
                        <span style="width:120px; margin-top:1px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">&nbsp;</span>
                        <span style="width:125px; margin-top:1px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">100</span>
                        <span style="width:125px; margin-top:0px; border-right:none; display:inline-block; height:30px; line-height:20px;text-align:center;">&nbsp;</span>
                      
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div style="width:180px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; height:37px; margin-top: -8px;text-align:center;" >
                    English
                    </div>


                    <div style="width:505px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; margin-top: -8px;" >
                  
                      <div style="">
                        <span style="width:120px; margin-top:6px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">100</span>
                        <span style="width:120px; margin-top:1px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">&nbsp;</span>
                        <span style="width:125px; margin-top:1px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">100</span>
                        <span style="width:125px; margin-top:0px; border-right:none; display:inline-block; height:30px; line-height:20px;text-align:center;">&nbsp;</span>
                      
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div style="width:180px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; height:37px; margin-top: -8px;text-align:center;" >
                    English
                    </div>


                    <div style="width:505px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; margin-top: -8px;" >
                  
                      <div style="">
                        <span style="width:120px; margin-top:6px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">100</span>
                        <span style="width:120px; margin-top:1px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">&nbsp;</span>
                        <span style="width:125px; margin-top:1px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">100</span>
                        <span style="width:125px; margin-top:0px; border-right:none; display:inline-block; height:30px; line-height:20px;text-align:center;">&nbsp;</span>
                      
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div style="width:180px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; height:37px; margin-top: -8px;text-align:center;" >
                    English
                    </div>


                    <div style="width:505px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; margin-top: -8px;" >
                  
                      <div style="">
                        <span style="width:120px; margin-top:6px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">100</span>
                        <span style="width:120px; margin-top:1px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">&nbsp;</span>
                        <span style="width:125px; margin-top:1px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">100</span>
                        <span style="width:125px; margin-top:0px; border-right:none; display:inline-block; height:30px; line-height:20px;text-align:center;">&nbsp;</span>
                      
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div style="width:180px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; height:37px; margin-top: -8px;text-align:center;" >
                    English
                    </div>


                    <div style="width:505px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; margin-top: -8px;" >
                  
                      <div style="">
                        <span style="width:120px; margin-top:6px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">100</span>
                        <span style="width:120px; margin-top:1px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">&nbsp;</span>
                        <span style="width:125px; margin-top:1px; border-right:1px solid #dbdbdb; display:inline-block; height:30px; line-height:20px; text-align:center;">100</span>
                        <span style="width:125px; margin-top:0px; border-right:none; display:inline-block; height:30px; line-height:20px;text-align:center;">&nbsp;</span>
                      
                      </div>
                    </div>
                  </td>
                </tr>


                <tr>
											<td>
												<div style="width:180px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; height:37px; margin-top: -8px;text-align:center;" >
												Grand Total
												</div>
		
		
												<div style="width:505px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; margin-top: -8px;" >
											
													<div style="">
														<span style=" margin-top:1px; display:block; height:30px; line-height:26px; text-align:center;">100</span>
													</div>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div style="width:180px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; height:37px; margin-top: -8px;text-align:center;" >
												Percentage
												</div>
		
		
												<div style="width:505px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; margin-top: -8px;" >
											
													<div style="">
														<span style=" margin-top:1px;  display:block; height:30px; line-height:26px; text-align:center;">60 %</span>
													</div>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div style="width:180px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; height:37px; margin-top: -8px;text-align:center;" >
												S.U.P.W
												</div>
		
		
												<div style="width:505px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; margin-top: -8px;" >
											
													<div style="">
														<span style=" margin-top:1px;  display:block; height:30px; line-height:26px; text-align:center;">B</span>
													</div>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div style="width:180px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; height:37px; margin-top: -8px;text-align:center;" >
												C.C.A
												</div>
		
		
												<div style="width:505px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; margin-top: -8px;" >
											
													<div style="">
														<span style=" margin-top:1px;  display:block; height:30px; line-height:26px; text-align:center;">A</span>
													</div>
												</div>
											</td>
										</tr>
										
										<tr>
											<td>
												<div style="width:180px; display:inline-block; border:1px solid #dbdbdb; line-height:46px; height:60px; margin-top: -8px;text-align:center; border-top:none;" >
												Class Teacher Remark
												</div>
		
		
												<div style="width:505px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; margin-top: -8px; border-top:none;" >
											
													<div style="">
														<span style="margin-top:1px; width:500px;border-top:none;  display:inline-block; height:60px; line-height:40px; text-align:center;">
														&nbsp;
														</span>
													</div>
												</div>
											</td>
										</tr>
										<tr>
											<td>
		
		
												<div style="width:685px; display:inline-block; border:1px solid #dbdbdb; line-height:26px; margin-top: -10px;" >
											
													<div style="">
														<span style=" margin-top:1px; display:inline-block; height:60px; padding:0 10px; line-height:26px; text-align:left;">
														<strong>Note:</strong> For subjects where a grade is awarded, grading will be based on the following Scale and Standard:
														A : Very Good B : Good C : Satisfactory D : Needs improvment E : poor F : Fail 
														</span>
													</div>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div style="float: left; width: 200px; margin-top: 60px; padding-right: 30px; text-align: center; ">
												
													&nbsp;
												</div>
												<div style="float: left; width:  200px; margin-top: 60px; padding-right: 30px;text-align: center;">
													&nbsp;
												</div>
												<div style="float: left; width:  200px; margin-top: 60px; padding-left: 30px;text-align: center;">
													&nbsp;
												</div>
											</td>
										</tr>
										</table>
										</body>
										</html>
										
                
                
                
                
                
                ';
      $dompdf = new Dompdf();
      $dompdf->set_option('isHtml5ParserEnabled', true);
      $dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'portrait');
      $dompdf->render();
      $dompdf->stream("section.pdf", array("Attachment" => 1));
    } catch(Thorwable $e) {
      echo $e->getMessage(); die;
    }
  }


  /**
   * @purpose: csv upload for 
   */
}
