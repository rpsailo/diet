<?php

class OptionsController extends Zend_Controller_Action
{
	protected $auth;

	protected $optionmodel;
	protected $optionform;

	public function init()
	{
		$this->optionmodel = new Model_Options();
		$this->optionform = new Form_Options();

		$this->_alert = $this->_helper->getHelper("FlashMessenger");
		$this->auth = Zend_Auth::getInstance();
    }

	public function indexAction()
    {
	 	if($this->_request->isPost())
        {
        	if($this->optionform->isValid($_POST))
        	{
        		$postData = $this->_request->getPost();
	            $result = $this->optionmodel->update(array('value'=>$postData['diet_district']), "`key`='diet_district'");
	            $result = $this->optionmodel->update(array('value'=>$postData['colors']), "`key`='colors'");

	            if($result)
	            {
	                $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i> Options updated.', "status"=>"success"));
	                $this->_redirect("/options/");
	            }
        	}
        }

        $data1 = $this->optionmodel->row(array(
        	'condition' => array('`key` = "diet_district"')
        	));
        $data2 = $this->optionmodel->row(array(
        	'condition' => array("`key` = 'colors'")
        	));

        $this->optionform->diet_district->setValue($data1->value);
        $this->optionform->colors->setValue($data2->value);

    	$this->view->form = $this->optionform;
    }
}