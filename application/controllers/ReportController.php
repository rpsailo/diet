<?php

class ReportController extends Zend_Controller_Action
{
	protected $auth;

	protected $programmodel;
	protected $programform;
	protected $programtoolbarform;
	protected $trainingmodel;
	protected $traineeform;

	public function init()
	{
		$this->programmodel = new Model_Program();
		$this->programform = new Form_Program();
		$this->programtoolbarform = new Form_ProgramToolbar();
		$this->trainingmodel = new Model_Training();
		$this->traineeform = new Form_Trainee();

		$this->_alert = $this->_helper->getHelper("FlashMessenger");
		$this->auth = Zend_Auth::getInstance();
    }

	public function indexAction()
    {
	}

	public function schoolAction()
	{
		if($this->_request->isPost())
		{
			$export = $this->_request->getParam('export', 0);
			
			if($export != 0)
			{
				switch ($export) {
					case 1:
						// Number of school in sub division and block
						break;

					case 2:
						// Number of student in primary school
						break;
					
					case 3:
						// Number of student in primary school
						break;
					
					case 4:
						// Number of student in primary school
						break;
					
					default:
						# code...
						break;
				}
			}
			else
				exit('Invalid action');
		}
	}

	public function teacherAction()
	{
		# code...
	}

	public function programmeAction()
	{
		# code...
	}
}


