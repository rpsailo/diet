<?php
class Zend_View_Helper_Faculties
{
	public function Faculties($ids)
	{
		$usermodel = new Model_User();
		if($ids != '')
		{
			$faculties = $usermodel->faculties($ids);
			return $faculties;
		}
		else return array();
	}
}