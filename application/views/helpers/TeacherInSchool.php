<?php
class Zend_View_Helper_TeacherInSchool
{
	public function TeacherInSchool($schoolid)
	{
		if($schoolid)
		{
			$teachermodel = new Model_Teacher();
			$teachers = $teachermodel->rows(array(
				'condition' => array(
					'school_id='.$schoolid
					)
				));
			return $teachers->count();
		}
		else return null;
	}
}