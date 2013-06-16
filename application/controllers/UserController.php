<?php

class UserController extends Zend_Controller_Action
{
	protected $auth;

	protected $usermodel;
	protected $userform;

	public function init()
	{
		$this->usermodel = new Model_User();
		$this->userform = new Form_User();
		$this->usertoolbarform = new Form_UserToolbar();

		$this->_alert = $this->_helper->getHelper("FlashMessenger");
		$this->auth = Zend_Auth::getInstance();
    }

	public function indexAction()
    {
		$url_params = '';

    	$search = $this->_request->getParam('search', null);
    	$role = $this->_request->getParam('role', null);
    	$limit = $this->_request->getParam('limit', 20);
    	$page = $this->_request->getParam('page', 1);

    	$params = array(
    		'limit' 	=> $limit,
    		'page'		=> $page,
    		'condition'	=> array()
		);

		$this->usertoolbarform->limit->setValue($limit);

    	if($search != null)
    	{
    		$url_params .= '/search/'.$search;
    		$params['condition'][] = "`username` LIKE '%".$search."%'";
    		$this->usertoolbarform->search->setValue($search);
    	}
    	if($role != null)
    	{
    		$url_params .= '/role/'.$role;
    		$params['condition'][] = "`role` = '".$role."'";
    		$this->usertoolbarform->role->setValue($role);
    	}

		if($this->_request->isPost())
    		$this->_redirect('/user/index'.$url_params.'/page/'.$page.'/limit/'.$limit);

    	$this->view->data = $this->usermodel->paginate($params);
    	$this->view->form = $this->usertoolbarform;
	}

	public function addAction()
	{
	 	if($this->_request->isPost())
        {
        	if($this->userform->isValid($_POST))
        	{
	            $result = $this->usermodel->create($this->_request->getPost());
	            if($result)
	            {
	                $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i> New user added.', "status"=>"success"));
	                $this->_redirect("/user/");
	            }
        	}
        }

		$this->view->form = $this->userform;
	}

	public function viewAction()
	{
		$id = $this->_request->getParam('id');

		if($id)
		{
			$user = $this->usermodel->find($id)->current();
			if($user)
			{
				$this->view->viewuser = $user;
			}
			else
			{
				$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Invalid user ID.', "status"=>"error"));
				$this->_redirect('/user/');
			}	
		}
		else
		{
			$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect user ID.', "status"=>"error"));
			$this->_redirect('/user/');
		}
	}

	public function removeAction()
	{
		$id = $this->_request->getParam('id');

		if($id)
		{
			$user = $this->usermodel->find($id)->current();
			$active_user = $this->auth->getIdentity();

			if($user)
			{
				if($user->id == $active_user->id)
				{
					$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> You cannot delete yourself.', "status"=>"warning"));
				}
				else
				{
					$username = $user->username;
					$user->delete();

					$this->_alert->addMessage(array("message"=>'<i class="icon icon-trash"></i> User "'.$username.'" deleted.', "status"=>"success"));
				}
			}
		}
		else
		{
			$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect user ID.', "status"=>"error"));
		}
		$this->_redirect('/user/');
	}

	public function editAction()
	{
		$id = $this->_request->getParam('id');

		if($id)
		{
			$user = $this->usermodel->find($id)->current();
			
			if($user)
			{
				$this->userform->username->setAttrib('readonly', 'readonly');
				$this->userform->username->setRequired(false);
				$this->userform->username->setValidators(array()); // Clear validators for username
				$this->userform->removeElement('password');
				$this->userform->removeElement('repeat_password');

			 	if($this->_request->isPost())
		        {
		        	if($this->userform->isValid($_POST))
		        	{
			            $result = $this->usermodel->edit($this->_request->getPost(), $user->id);
			            if($result)
			            {
			                $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i>  User "'.$user->username.'" updated.', "status"=>"success"));
			                $this->_redirect("/user");
			            }
		        	}
		        }
				
				$this->userform->populate($user->toArray());
				$this->view->form = $this->userform;
				$this->view->user = $user;
			}
			else
			{
				$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Invalid user ID.', "status"=>"error"));
				$this->_redirect('/user/');
			}
		}
		else
		{
			$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect user ID.', "status"=>"error"));
			$this->_redirect('/user/');
		}
	}

	public function resetPasswordAction()
	{
		$id = $this->_request->getParam('id');

		if($id)
		{
			$user = $this->usermodel->find($id)->current();
			
			$this->userform->password->setLabel('New Password');
			$this->userform->repeat_password->setLabel('Confirm New Password');
			$this->userform->removeElement('username');
			$this->userform->removeElement('role');
			$this->userform->removeElement('dob');
			$this->userform->removeElement('date_of_joining');
			$this->userform->removeElement('educational_qualification');
			$this->userform->removeElement('specialization');
			$this->userform->removeElement('phone');
			$this->userform->removeElement('address');
			$this->userform->cancel->setAttrib('onclick', 'window.location="/user/"');
			$this->userform->add->setLabel('Submit');
			
			if($user)
			{
			 	if($this->_request->isPost())
		        {
		        	if($this->userform->isValid($_POST))
		        	{
			            $result = $this->usermodel->change_password($this->_request->getParam('password'), $user->id);
			            if($result)
			            {
			                $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i> Password reset succesful for "'.$user->username.'".', "status"=>"success"));
			                $this->_redirect("/user/");
			            }
		        	}
		        }
				
				$this->userform->populate($user->toArray());
				$this->view->form = $this->userform;
				$this->view->user = $user;
			}
			else
			{
				$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Invalid user ID.', "status"=>"error"));
				$this->_redirect('/user/');
			}
		}
		else
		{
			$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect user ID.', "status"=>"error"));
			$this->_redirect('/user/');
		}
	}
}



