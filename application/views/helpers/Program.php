<?php
class Zend_View_Helper_Program
{
	public function Program($id)
	{
		if($id)
		{
			$programmodel = new Model_Program();
			$program = $programmodel->find($id)->current();
			return $program;
		}
		else return null;
	}
}