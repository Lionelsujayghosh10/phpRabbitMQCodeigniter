<?php


require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;


function OpenCon() {
  $dbhost = "localhost";
  $dbuser = "root";
  $dbpass = "password";
  $db = "reportCard_system";
  $conn = mysqli_connect($dbhost, $dbuser, $dbpass,$db);
  if(!$conn) {
    die("Connect failed: %s\n". $conn -> error);
  } else {
    echo "connected succesfull.";
  }
  return $conn;
}


function CloseCon($conn) {
  $conn->close();
}


$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('hello', false, false, false, false);
echo " [*] Waiting for messages. To exit press CTRL+C\n";


$callback = function ($msg) {
  $data_array = json_decode($msg->body, true);
  $mysql_connection = OpenCon();
  $file_name = 'TB'.$data_array['exam_id'].$data_array['section_id'].$data_array['class_id'].".csv";
  $csv_handler = fopen('/var/www/html/ReportCard/CSV/TabulationSheet/'.$file_name,'w');
  fputcsv($csv_handler, array('Student Name' , 'Class Name' , 'Section Name' , 'Exam Name', 'Subject Name', 'Obtained Marks', 'Marks'));
  $marks_query = "SELECT `students`.`student_name` AS student_name, `classes`.`class_name` AS class_name, `sections`.`section_name` AS section_name, `exams`.`exam_name` AS `exam_name`, `subjects`.`subject_name` AS subject_name, `student_marks`.`otained_marks` AS obtained_marks, `student_marks`.`total_marks` AS total_marks FROM `students`, `classes`, `sections`, `exams`, `subjects`, `student_marks` WHERE  `students`.`studentId` = `student_marks`.`student_id` AND `classes`.`classId` = `student_marks`.`class_id` AND `sections`.`sectionId` = `student_marks`.`section_id` AND `exams`.`examId` = `student_marks`.`exam_id` AND `subjects`.`subjectId` = `student_marks`.`subject_id` AND `student_marks`.`class_id` = ".$data_array['class_id']." AND `student_marks`.`section_id` = ".$data_array['section_id']." AND `student_marks`.`exam_id` = ".$data_array['exam_id']." AND `student_marks`.`isDelete` = '0' ";
  if ($temp = mysqli_query($mysql_connection, $marks_query)) {
    while ($row = mysqli_fetch_assoc($temp)) {            
      $array['Student Name']      =   $row['student_name'];
      $array['Class Name']        =   $row['class_name'];
      $array['Section Name']      =   $row['section_name'];
      $array['Exam Name']         =   $row['exam_name'];
      $array['Subject Name']      =   $row['subject_name'];
      $array['Obtained Marks']    =   $row['obtained_marks'];
      $array['Marks']             =   $row['total_marks'];
      fputcsv($csv_handler, $array);
      unset($array);
    }
  }
  fclose($csv_handler);
  $update_query = "UPDATE `tabulation_sheet_track` SET `isComplete` = '1' , `csv_name` = '".$file_name."' WHERE sheetId = ".$data_array['sheet_id']." ";
  mysqli_query($mysql_connection, $update_query);
  unset($data_array);
// echo 'Data saved to csvfile.csv';

};

$channel->basic_consume('hello', '', false, true, false, false, $callback);

while ($channel->is_consuming()) {
  $channel->wait();
}


$channel->close();
$connection->close();
