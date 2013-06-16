<?php
class Model_Course extends System_DbTable
{
    protected $_name = 'course';
    protected $_primary = 'course_id';
		
	public function create($data)
	{
		$new_row = $this->createRow();
        $new_row->course_name = $data['course_name'];
        $new_row->course_shortname = $data['course_shortname'];
        $new_row->course_duration = $data['course_duration'];
       	$new_row->created_at = new Zend_Db_Expr('NOW()');
        $new_row->updated_at = new Zend_Db_Expr('NOW()');
		return $new_row->save();
	}

	public function edit($data, $id)
	{
		if(is_numeric($id))
		{
			$row = $this->find($id)->current();
	        $row->course_name = $data['course_name'];
	        $row->course_shortname = $data['course_shortname'];
	        $row->course_duration = $data['course_duration'];
	        $row->updated_at = new Zend_Db_Expr('NOW()');
			return $row->save();
		}
		else return false;
	}
}