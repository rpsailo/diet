<?php

class IndexController extends Zend_Controller_Action
{
	protected $auth;

	public function init()
	{
		$this->auth = Zend_Auth::getInstance();
    }

	public function indexAction()
    {
    	$this->view->programmodel = new Model_Program();
    	$this->view->teachermodel = new Model_Teacher();
    	$this->view->schoolmodel = new Model_School();
    	$this->view->usermodel = new Model_User();
	}
}



