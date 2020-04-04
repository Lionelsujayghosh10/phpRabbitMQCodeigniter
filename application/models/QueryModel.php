<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class QueryModel extends CI_Model {




    public function insertDataIntoTable($array, $table) {
        $this->db->insert($table, $array);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }


    public function updateData($updateArray, $conditionArray, $table){
        $status = $this->db->where($conditionArray)
                            ->set($updateArray)
                            ->update($table);
        return $status;
    }

    public function getWhere($conditionArray, $table) {
        switch($table) {
            case 'users':
                $sql = $this->db->select('email, password, userId')
                                ->where($conditionArray)
                                ->get($table);
                if($sql->row_array() > 0) {
                    $result = $sql->row_array();
                    return $result;
                } else {
                    return [];
                }
            case 'classes':
                $sql = $this->db->select('classId, class_code, class_name')
                                ->where($conditionArray)
                                ->get($table);
                if($sql->row_array() > 0) {
                    $result = $sql->row_array();
                    return $result;
                } else {
                    return [];
                }
            case 'sections':
                $sql = $this->db->select('class_id, section_code, section_name, sectionId')
                                ->where($conditionArray)
                                ->get($table);
                if($sql->row_array() > 0) {
                    $result = $sql->row_array();
                    return $result;
                } else {
                    return [];
                }
            case 'subjects':
                $sql = $this->db->select('subjectId, subject_code, subject_name')
                                ->where($conditionArray)
                                ->get($table);
                if($sql->row_array() > 0) {
                    $result = $sql->row_array();
                    return $result;
                } else {
                    return [];
                }
            case 'assign_subject':
                $sql = $this->db->select('subject_id, class_id, section_id, assignsubjectId')
                                ->where($conditionArray)
                                ->get($table);
                if($sql->row_array() > 0) {
                    $result = $sql->row_array();
                    return $result;
                } else {
                    return [];
                }
            case 'exams':
                $sql = $this->db->select('examId, exam_code, exam_name')
                                ->where($conditionArray)
                                ->get($table);
                if($sql->row_array() > 0) {
                    $result = $sql->row_array();
                    return $result;
                } else {
                    return [];
                }
            case 'students':
                $sql = $this->db->select('student_id, studentId, student_code, parent_id, student_name, class_id, section_id, student_rollNumber')
                                ->where($conditionArray)
                                ->get($table);
                if($sql->row_array() > 0) {
                    $result = $sql->row_array();
                    return $result;
                } else {
                    return [];
                }
            case 'tabulation_sheet_track':
                $sql = $this->db->select('sheetId, exam_id, class_id, section_id, csv_name, isComplete')
                                ->where($conditionArray)
                                ->get($table);
                if($sql->row_array() > 0) {
                    $result = $sql->row_array();
                    return $result;
                } else {
                    return [];
                }
            case 'student_marks':
                $sql = $this->db->select('studentMarksId, exam_id, class_id, student_id, section_id,  subject_id, total_marks, otained_marks')
                                ->where($conditionArray)
                                ->get($table);
                if($sql->row_array() > 0) {
                    $result = $sql->row_array();
                    return $result;
                } else {
                    return [];
                }
            case 'pdf_track':
                $sql = $this->db->select('id, isComplete, pdf_name')
                                ->where($conditionArray)
                                ->get($table);
                if($sql->row_array() > 0) {
                    $result = $sql->row_array();
                    return $result;
                } else {
                    return [];
                }
        }
    }



    public function search($data, $limit, $offset){
        $sql =$this->db->query("select  exams.exam_name, students.studentId, subjects.subject_name ,students.student_name, students.student_rollNumber, student_marks.class_id, student_marks.otained_marks,student_marks.total_marks,student_marks.section_id,student_marks.studentMarksId, students.student_code from student_marks inner join students on student_marks.student_id=students.studentId inner join subjects on student_marks.subject_id=subjects.subjectId inner join exams on student_marks.exam_id=exams.examId where (students.student_name like '%".$data."%' AND  student_marks.isDelete = '0')  or ( subjects.subject_name like '%".$data."%' AND  student_marks.isDelete = '0') LIMIT ".$offset.", ".$limit."  ");
        //$res= $sql->result_array();
        //echo "<pre>"; print_r($res); die;
        return $sql->result_array();
    }



    public function searchResultCount($data){
        $sql =$this->db->query("select count(students.studentId) as count from student_marks inner join students on student_marks.student_id=students.studentId inner join subjects on student_marks.subject_id=subjects.subjectId where students.student_name like '%".$data."%'  or subjects.subject_name like '%".$data."%'");

        $res= $sql->row_array();

        //echo "<pre>"; print_r($res); die;
        return  $res;
    }

    public function getNumberOfRows($table) {
        switch($table) {
            case 'classes':
                $result = $this->db->query("SELECT COUNT(classId) AS classCount FROM classes WHERE isDelete='0' ");
                return $result->row_array()['classCount'];
            case 'sections':
                $result = $this->db->query("SELECT COUNT(sectionId) AS sectionCount FROM sections WHERE isDelete='0' ");
                return $result->row_array()['sectionCount'];
            case 'subjects':
                $result = $this->db->query("SELECT COUNT(subjectId) AS subjectCount FROM subjects WHERE isDelete='0' ");
                return $result->row_array()['subjectCount'];
            case 'assign_subject':
                $result = $this->db->query("SELECT COUNT(assignSubjectId) AS assignSubjectCount FROM assign_subject WHERE isDelete='0' ");
                return $result->row_array()['assignSubjectCount'];
            case 'exams':
                $result = $this->db->query("SELECT COUNT(examId) AS examCount FROM exams WHERE isDelete='0' ");
                return $result->row_array()['examCount'];
            case 'students':
                $result = $this->db->query("SELECT COUNT(studentId) AS studentCount FROM students WHERE isDelete='0' ");
                return $result->row_array()['studentCount'];
            case 'student_marks':
                $result = $this->db->query("SELECT COUNT(studentMarksId) AS studentMarksCount FROM student_marks WHERE isDelete='0'");
                return $result->row_array()['studentMarksCount'];
            case 'tabulation_sheet_track':
                $result = $this->db->query("SELECT COUNT(sheetId) AS sheetCount FROM tabulation_sheet_track WHERE isDelete='0'");
                return $result->row_array()['sheetCount'];
            case 'pdf_track':
                $result = $this->db->query("SELECT COUNT(id) AS pdfCount FROM pdf_track WHERE isDelete='0'");
                return $result->row_array()['pdfCount'];
        }
    }


    public function fetchDataWithLimitOffset($table, $limit, $offset, $condition){
        switch($table){
            case 'classes':
                $sql = $this->db->select('classId, class_code, class_name')
                                ->where($condition)
                                ->limit($limit, $offset)
                                ->get($table);
                if($sql->num_rows() > 0) {
                    return $sql->result_array();
                } else {
                    return [];
                }
            case 'sections':
                $sql = $this->db->select('sectionId, section_code, section_name, class_id')
                                ->where($condition)
                                ->limit($limit, $offset)
                                ->get($table);
                if($sql->num_rows() > 0) {
                    return $sql->result_array();
                } else {
                    return [];
                }
            case 'subjects':
                $sql = $this->db->select('subjectId, subject_code, subject_name')
                                ->where($condition)
                                ->limit($limit, $offset)
                                ->get($table);
                if($sql->num_rows() > 0) {
                    return $sql->result_array();
                } else {
                    return [];
                }
            case 'assign_subject':
                $sql = $this->db->select('assignSubjectId, class_id, section_id, subject_id')
                                ->where($condition)
                                ->limit($limit, $offset)
                                ->get($table);
                if($sql->num_rows() > 0) {
                    return $sql->result_array();
                } else {
                    return [];
                }
            case 'exams':
                $sql = $this->db->select('examId, exam_code, exam_name')
                                ->where($condition)
                                ->limit($limit, $offset)
                                ->get($table);
                if($sql->num_rows() > 0) {
                    return $sql->result_array();
                } else {
                    return [];
                }
            case 'students':
                $sql = $this->db->select('student_id, studentId, student_code, parent_id, student_name, class_id, section_id, student_rollNumber')
                                ->where($condition)
                                ->limit($limit, $offset)
                                ->get($table);
                if($sql->num_rows() > 0) {
                    return $sql->result_array();
                } else {
                    return [];
                }
            case 'student_marks':
                $sql = $this->db->select('studentMarksId, exam_id, class_id, section_id, student_id, subject_id, total_marks, otained_marks')
                                                ->where($condition)
                                                ->limit($limit, $offset)
                                                ->get($table);
                if($sql->num_rows() > 0) {
                        
                        return $sql->result_array();
                } else {
                        return [];
                }
            case 'tabulation_sheet_track':
                $sql = $this->db->select('sheetId, exam_id, class_id, section_id, csv_name, isComplete, generate_time')
                                ->where($condition)
                                ->limit($limit, $offset)
                                ->get($table);
                if($sql->num_rows() > 0) {
                        return $sql->result_array();
                } else {
                        return [];
                }
            case 'pdf_track':
                $sql = $this->db->select('id, class_id, section_id, pdf_name, isComplete, created_on')
                                ->where($condition)
                                ->limit($limit, $offset)
                                ->get($table);
                if($sql->num_rows() > 0) {
                        return $sql->result_array();
                } else {
                        return [];
                }
        }
    }



    public function getMultipleRow($conditionArray, $table){
        switch($table){
            case 'classes':
                $sql = $this->db->select('classId, class_code, class_name')
                                ->where($conditionArray)
                                ->get($table);
                if($sql->row_array() > 0) {
                    $result = $sql->result_array();
                    return $result;
                } else {
                    return [];
                }
            case 'sections':
                $sql = $this->db->select('class_id, section_code, section_name, sectionId')
                                ->where($conditionArray)
                                ->get($table);
                if($sql->row_array() > 0) {
                    $result = $sql->result_array();
                    return $result;
                } else {
                    return [];
                }
            case 'exams':
                $sql = $this->db->select('examId, exam_code, exam_name')
                                ->where($conditionArray)
                                ->get($table);
                if($sql->row_array() > 0) {
                    $result = $sql->result_array();
                    return $result;
                } else {
                    return [];
                }
            case 'assign_subject':
                $sql = $this->db->select('assignSubjectId, subject_id, class_id, section_id')
                                ->where($conditionArray)
                                ->get($table);
                if($sql->row_array() > 0) {
                    $result = $sql->result_array();
                    return $result;
                } else {
                    return [];
                }
            case 'subjects':
                $sql = $this->db->select('subjectId, subject_code, subject_name')
                                ->where($conditionArray)
                                ->get($table);
                if($sql->row_array() > 0) {
                    $result = $sql->result_array();
                    return $result;
                } else {
                    return [];
                }
            case 'students':
                $sql = $this->db->select('studentId, student_name, student_id, parent_id, student_rollNumber')
                                ->where($conditionArray)
                                ->get($table);
                if($sql->row_array() > 0) {
                    $result = $sql->result_array();
                    return $result;
                } else {
                    return [];
                }
            case 'student_marks':
                $sql = $this->db->select('studentMarksId,student_id,highestMarks,total_marks,otained_marks')
                                ->where($conditionArray)
                                ->get($table);
                if($sql->row_array() > 0) {
                    $result = $sql->result_array();
                    return $result;
                } else {
                    return [];
                }

        }
    }


    public function deleteDataFromDataBase($conditionArray, $table) {
        $status = $this->db->delete($table, $conditionArray);
        return $status;
    }



    public function getSearchResult($search_data, $table, $conditionArray, $limit, $offset) {
        switch($table) {
            case 'students':
                $sql = $this->db->select('studentId, student_name, student_id, parent_id, student_rollNumber, class_id, section_id, student_code')
                                ->like('student_name', $search_data, 'both')
                                ->where($conditionArray)
                                ->limit($limit, $offset)
                                ->get($table);
                if($sql->row_array() > 0) {
                    $result = $sql->result_array();
                    return $result;
                } else {
                    return [];
                }
        }
    }


    public function getNumberOfRowsForSearch($search_data, $table) {
        switch($table) {
            case 'students':
                $result = $this->db->query("SELECT COUNT(studentId) AS studentCount FROM students WHERE isDelete='0' AND student_name like '%".$search_data."%' ");
                return $result->row_array()['studentCount']; 
        } 
    }



    public function countIds($table){
        switch($table) {
            case 'students':
                $res = $this->db->query("SELECT COUNT(studentId) FROM students WHERE isDelete='0'");
                return $res->row_array()['COUNT(studentId)']; 
            case 'classes':
                $res = $this->db->query("SELECT COUNT(classId) FROM classes WHERE isDelete='0'");
                return $res->row_array()['COUNT(classId)']; 
            case 'subjects':
                $res = $this->db->query("SELECT COUNT(subjectId) FROM subjects WHERE isDelete='0'");
                return $res->row_array()['COUNT(subjectId)']; 
        } 
    }



    public function getStudentmarks($array, $table){
        try {
            $sql = "SELECT students.student_name AS student_name, students.student_code AS student_code, exams.exam_name AS exam_name, classes.class_name AS class_name, sections.section_name AS section_name, subjects.subject_name AS subject_name, student_marks.total_marks AS total_marks, student_marks.otained_marks AS otained_marks FROM student_marks JOIN students ON students.studentId = student_marks.student_id JOIN exams ON exams.examId = student_marks.exam_id JOIN classes ON classes.classId = student_marks.class_id JOIN sections ON sections.sectionId = student_marks.section_id JOIN subjects ON subjects.subjectId = student_marks.subject_id where student_marks.student_id = ".$array['student_id']." AND student_marks.exam_id = ".$array['exam_id'];
            $query = $this->db->query($sql);
            if($query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return array();
            }
        } catch(Error $e) {
            return array();
        }
    }








}
