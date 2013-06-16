<?php

class TeacherController extends Zend_Controller_Action
{
	protected $auth;

	protected $teachermodel;
	protected $teacherform;

	public function init()
	{
		$this->teachermodel = new Model_Teacher();
		$this->teacherform = new Form_Teacher();

		$this->_alert = $this->_helper->getHelper("FlashMessenger");
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
	            $result = $this->teachermodel->create($this->_request->getPost());
	            if($result)
	            {
	                $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i> New Teacher added.', "status"=>"success"));
	                $this->_redirect("/teacher/");
	            }
        	}
        }

		$this->view->form = $this->programform;
	}
}



