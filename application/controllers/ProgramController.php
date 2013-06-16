<?php

class ProgramController extends Zend_Controller_Action
{
	protected $auth;

	protected $programmodel;
	protected $programform;

	public function init()
	{
		$this->programmodel = new Model_Program();
		$this->programform = new Form_Program();

		$this->auth = Zend_Auth::getInstance();
    }

	public function indexAction()
    {
		if($this->auth->hasIdentity())
			$current_user = $this->auth->getIdentity();
	}

	public function addAction()
	{
	 	if($this->_request->isPost())
        {
        	if($this->programform->isValid($_POST))
        	{
	            $result = $this->programmodel->create($this->_request->getPost());
	            if($result)
	            {
	                $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i> New program added.', "status"=>"success"));
	                $this->_redirect("/program/");
	            }
        	}
        }

		$this->view->form = $this->programform;
	}
}



