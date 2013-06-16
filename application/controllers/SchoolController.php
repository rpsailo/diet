<?php

class SchoolController extends Zend_Controller_Action
{
	protected $auth;

	protected $schoolmodel;
	protected $schoolform;

	public function init()
	{
		$this->schoolmodel = new Model_School();
		$this->schoolform = new Form_School();

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
        	if($this->schoolform->isValid($_POST))
        	{
	            $result = $this->schoolmodel->create($this->_request->getPost());
	            if($result)
	            {
	                $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i> New school added.', "status"=>"success"));
	                $this->_redirect("/school/");
	            }
        	}
        }

		$this->view->form = $this->schoolform;
	}
}



