<?php
class Zend_View_Helper_School
{
	public function School($id)
	{
		if($id)
		{
			$schoolmodel = new Model_School();
			$school = $schoolmodel->find($id)->current();
			return $school;
		}
		else return null;
	}
}