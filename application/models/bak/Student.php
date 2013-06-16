<?php 
 class Model_Student extends Zend_Db_Table_Abstract
{
	protected $_name='student';
	
	public function init()
	{
	}
		
	public function create($data, $user_id)
	{
		$new_row = $this->createRow();
        $new_row->user_id = $user_id;
        $new_row->course_id= $data['course'];

        $new_row->student_name = $data['student_name'];
        $new_row->roll_no = $data['roll_no'];
        $new_row->parent_name = $data['parent_name'];
        $new_row->dob = $data['dob'];
		$new_row->email_id = $data['email_id'];
		$new_row->contact_no = $data['contact_no'];
		$new_row->address = $data['address'];
		$new_row->centre = $data['centre'];
		$new_row->institution = $data['institution'];
		$new_row->sex = $data['sex'];
		$new_row->nationality = $data['nationality'];
		$new_row->passport = '';
		$new_row->signature = '';
		$new_row->date_of_registration = $data['date_of_registration'];
		$new_row->category = $data['category'];
       	$new_row->created_at = new Zend_Db_Expr('NOW()');
        $new_row->updated_at = new Zend_Db_Expr('NOW()');

        return $new_row->save();
	}

	public function edit($data, $student_id)
	{
		if(is_numeric($student_id))
		{
			$row = $this->find($student_id)->current();

	        $row->student_name = $data['student_name'];
	        $row->roll_no = $data['roll_no'];
	        $row->parent_name = $data['parent_name'];
	        $row->dob = $data['dob'];
			$row->email_id = $data['email_id'];
			$row->contact_no = $data['contact_no'];
			$row->address = $data['address'];
			$row->centre = $data['centre'];
			$row->institution = $data['institution'];
			$row->sex = $data['sex'];
			$row->nationality = $data['nationality'];
			$row->date_of_registration = $data['date_of_registration'];
			$row->category = $data['category'];
	        
	        $row->updated_at = new Zend_Db_Expr('NOW()');
	        
	        return $row->save();
		}
		else return false;
	}

	public function in_course($course_id)
	{
		$select = $this->select();
		$select->where('course_id = '.$course_id);
		return $this->fetchAll($select);
	}
}