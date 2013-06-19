<?php
class Zend_View_Helper_Teacher
{
	public function Teacher($id)
	{
		if($id)
		{
			$teachermodel = new Model_Teacher();
			$teacher = $teachermodel->find($id)->current();
			return $teacher;
		}
		else return null;
	}
}