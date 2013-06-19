<?php
class Zend_View_Helper_Faculties
{
	public function Faculties($ids)
	{
		$usermodel = new Model_User();
		$faculties = $usermodel->faculties($ids);
		return $faculties;
	}
}