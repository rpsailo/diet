<?php 
class Model_Exam extends System_DbTable
{
	protected $_name='exam';
	
	public function init()
	{
	}
	
	public function create($data)
	{
		$new_row = $this->createRow();
        $new_row->course_id = $data['course_id'];
        $new_row->exam_name = $data['exam_name'];
        $new_row->exam_duration = $data['exam_duration'];
        $new_row->start_at = $data['start_at'];
        $new_row->end_at = new Zend_Db_Expr('DATE_ADD("'.$data['start_at'].'", INTERVAL '.$data['exam_duration'].' MINUTE)');
        $new_row->no_of_question= $data['no_of_question'];
        $new_row->no_of_student = $data['no_of_student'];
        $new_row->faculty_id = $data['faculty_id']; // Invigilator faculty
        $new_row->status = $data['status'];
       	$new_row->created_at = new Zend_Db_Expr('NOW()');
        $new_row->updated_at = new Zend_Db_Expr('NOW()');
        return $new_row->save();
	}

	public function edit($data, $id)
	{
		if(is_numeric($id))
		{
			$row = $this->find($id)->current();
	        $row->course_id = $data['course_id'];
	        $row->exam_name = $data['exam_name'];
	        $row->exam_duration = $data['exam_duration'];
	        $row->start_at = $data['start_at'];
	        $row->end_at = new Zend_Db_Expr('DATE_ADD("'.$data['start_at'].'", INTERVAL '.$data['exam_duration'].' MINUTE)');
	        $row->no_of_question= $data['no_of_question'];
	        $row->no_of_student = $data['no_of_student'];
	        $row->faculty_id = $data['faculty_id']; // Invigilator faculty
	        $row->status = $data['status'];
	        $row->updated_at = new Zend_Db_Expr('NOW()');
	        return $row->save();
		}
		else return false;        
	}
	
	public function with_course()
	{
		$select = $this->select();
		$select->from($this->_name, array($this->_name.'.*'));
		$select->setIntegrityCheck(false);
		$select->join('course', $this->_name.'.course_id=course.course_id', array('course_name', 'course_shortname', 'course_duration'));
		$select->order($this->_name.'.start_at DESC');

		return $this->fetchAll($select);
	}
	
	public function in_course($course_id)
	{
		$select = $this->select();
		$select->where('course_id = '.$course_id);
		return $this->fetchAll($select);
	}

	public function exam_today($course_id)
	{
		$select = $this->select();
		$select->where('course_id = '.$course_id);
		$select->where(new Zend_Db_Expr("DATE(`start_at`) = '".date('Y-m-d')."'"));
		$select->where(new Zend_Db_Expr("`end_at` >= '".date('Y-m-d H:i:s')."'"));
		$select->where('status = "ready" OR status = "active"');
		$select->order('start_at DESC');
		return $this->fetchRow($select);
	}

	public function completed($student_id, $exam = null)
	{
		if(is_numeric($student_id))
		{
			$select = $this->select();
			$select->setIntegrityCheck(false);
			$select->from($this->_name, array("exam_name", "exam_duration", "no_of_question", "no_of_student", "faculty_id"));
			$select->join("examsession","exam.exam_id = examsession.exam_id", array("*"));
			$select->where("`examsession`.`student_id` = ".$student_id);
			$select->where("`examsession`.`status` = 'completed'");
			if($exam != null)
			{
				$select->where("`examsession`.`exam_id` = ".$exam);
				return $this->fetchRow($select);
			}
			else
			{
				return $this->fetchAll($select);
			}
		}
		else return null;
	}
}