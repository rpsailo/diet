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
}


