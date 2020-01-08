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
        }
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








}
