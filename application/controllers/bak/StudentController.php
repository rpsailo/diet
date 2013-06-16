<?php

class StudentController extends Zend_Controller_Action
{
	protected $userm;
	protected $studentm;
	protected $studentf;
	protected $studenttoolbarf;

	public function init()
	{
		$this->_alert = $this->_helper->getHelper("FlashMessenger");

		$this->userm = new Model_User();
		$this->studentm = new Model_Student();
        $this->studentf = new Form_Student();
        $this->studenttoolbarf = new Form_StudentToolbar();
	}
	
	public function indexAction()
	{
		$this->_redirect('/student/list/');
	}

	public function addAction()
	{
	 	if($this->_request->isPost())
        {
        	if($this->studentf->isValid($_POST))
        	{
        		$post_data = $this->_request->getPost();
        		$post_data['username'] = $post_data['roll_no'];
        		// Create User
	            $user_id = $this->userm->create($post_data, 'student');

	            // Create Student
	            $result = $this->studentm->create($post_data, $user_id);
	            if($result)
	            {
	            	$this->upload($result);
	                $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i> New student added.', "status"=>"success"));
	                $this->_redirect("/student/add/");
	            }
        	}
        }

		$this->view->form = $this->studentf;
	}
	
	public function listAction()
	{
		$url_params = '';

    	$search = $this->_request->getParam('search', null);
    	$course = $this->_request->getParam('course', null);
    	$page = $this->_request->getParam('page', 1);

    	$params = array(
    		'page'			=> $page,
    		'condition'		=> array("`type` = 'student'"),
    		'field'			=> array('id','username', 'type', 'loggedin_at'),
    		'join_table'	=> 'student',
    		'join_on'		=> 'user.id = student.user_id',
    		'join_field'	=> array('student_id','roll_no','student_name', 'course_id', 'parent_name')
		);

    	if($search != null)
    	{
    		$url_params .= '/search/'.$search;
    		$params['condition'][] = "`student_name` LIKE '%".$search."%' OR `roll_no` = '".$search."'";
    		$this->studenttoolbarf->search->setValue($search);
    	}
    	if($course != null && $course != 0)
    	{
    		$url_params .= '/course/'.$course;
    		$params['condition'][] = "`course_id` = ".$course;
    		$this->studenttoolbarf->course->setValue($course);
    	}

		if($this->_request->isPost())
    		$this->_redirect('/student/list'.$url_params.'/page/'.$page);

    	$this->view->data = $this->userm->paginate($params);
    	$this->view->form = $this->studenttoolbarf;
	}

	public function deleteAction()
	{
		$id = $this->_request->getParam('id');

		if($id)
		{
			$student = $this->studentm->find($id)->current();

			if($student)
			{
				$student_name = $student->student_name;
				$student_user_id = $student->user_id;
				
				$student->delete();
				
				$this->userm->delete('id = '.$student_user_id);

				$this->_alert->addMessage(array("message"=>'<i class="icon icon-trash"></i> Student "'.$student_name.'" deleted.', "status"=>"success"));
			}
		}
		else
		{
			$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect student ID.', "status"=>"error"));
		}
		$this->_redirect('/student/list/');
	}

	public function resetPasswordAction()
	{
		$id = $this->_request->getParam('id');

		if($id)
		{
			$student = $this->studentm->find($id)->current();
			$user = $this->userm->find($student->user_id)->current();
			
			$this->studentf->password->setLabel('New Password');
			$this->studentf->repeat_password->setLabel('Confirm New Password');
			$this->studentf->removeElement('roll_no');
			$this->studentf->removeElement('student_name');
			$this->studentf->removeElement('parent_name');
			$this->studentf->removeElement('dob');
			$this->studentf->removeElement('email_id');
			$this->studentf->removeElement('contact_no');
			$this->studentf->removeElement('address');
			$this->studentf->removeElement('centre');
			$this->studentf->removeElement('institution');
			$this->studentf->removeElement('sex');
			$this->studentf->removeElement('nationality');
			$this->studentf->removeElement('passport');
			$this->studentf->removeElement('signature');
			$this->studentf->removeElement('date_of_registration');
			$this->studentf->removeElement('category');
			$this->studentf->removeElement('course');
			
			if($user)
			{
			 	if($this->_request->isPost())
		        {
		        	if($this->studentf->isValid($_POST))
		        	{
			            $result = $this->userm->change_password($this->_request->getParam('password'), $user->id);
			            if($result)
			            {
			                $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i> Password reset succesful for student "'.$student->student_name.'".', "status"=>"success"));
			                $this->_redirect("/student/list");
			            }
		        	}
		        }
				
				$this->view->form = $this->studentf;
				$this->view->user = $user;
				$this->view->student = $student;
			}
			else
			{
				$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Invalid student ID.', "status"=>"error"));
				$this->_redirect('/student/list/');
			}
		}
		else
		{
			$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect student ID.', "status"=>"error"));
			$this->_redirect('/student/list/');
		}
	}

	public function editAction()
	{
		$id = $this->_request->getParam('id');

		if($id)
		{
			$student = $this->studentm->find($id)->current();
			$user = $this->userm->find($student->user_id)->current();
			
			if($student)
			{
				$this->studentf->removeElement('password');
				$this->studentf->removeElement('repeat_password');

			 	if($this->_request->isPost())
		        {
		        	if($this->studentf->isValid($_POST))
		        	{
		        		$post_data = $this->_request->getPost();
			            
			            $result = $this->studentm->edit($post_data, $student->student_id);
			            
			            if($result)
			            {
			            	// Update username if roll no is changed, since roll no is used as username.
			            	$this->userm->update(array('username'=>$post_data['roll_no']), 'id = '.$student->user_id);
			                
			                $this->upload($student->student_id);
			                
			                $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i> Student updated.', "status"=>"success"));
			                $this->_redirect("/student/edit/id/".$student->student_id);
			            }
		        	}
		        }
		        else
					$this->studentf->populate($student->toArray());

				$this->view->form = $this->studentf;
			}
			else
			{
				$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Invalid student ID.', "status"=>"error"));
				$this->_redirect('/student/list/');
			}
		}
		else
		{
			$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect student ID.', "status"=>"error"));
			$this->_redirect('/student/list/');
		}
	}

	public function upload($student_id)
	{
		$adapter = new Zend_File_Transfer_Adapter_Http();
		
		$adapter->addValidator('Extension', false, 'jpg,png,gif,jpeg');
		$datas = array();
		
		$files = $adapter->getFileInfo();
		foreach ($files as $file => $info)
		{
			$name = $adapter->getFileName($file);			
			$fileName = $adapter->getFileName($file,false);
			
			// file uploaded & is valid
			if (!$adapter->isUploaded($file)) continue; 
			if (!$adapter->isValid($file))
			{
				$datas[] = array('error'=>'Files of type jpg, png or gif are allowed. Maximum allowed file size is 2mb.');
				continue;
			}
			
			$adapter->receive($file);

			if(!is_dir($this->_uploads_rel.'/students/'))
				mkdir('uploads/students/');
			
			$resizer = new System_Resize();
			
			if($file == 'passport')
			{	
				$iWidth = 200;
				$iHeight = 200;
				
				$resizer->setImage($name);
				$resizer->resizeImage($iWidth,$iHeight);
				
				$resizer->saveImage('uploads/students/'.$student_id.'-passport'.$resizer->getExtension(),100);
				
				unlink($name);
				
				$this->studentm->update(array('passport'=>'/uploads/students/'.$student_id.'-passport'.$resizer->getExtension()),'student_id='.$student_id);
			}
			else if($file == 'signature')
			{
				$iWidth = 200;
				$iHeight = 200;
				
				$resizer->setImage($name);
				$resizer->resizeImage($iWidth,$iHeight);
				
				$resizer->saveImage('uploads/students/'.$student_id.'-signature'.$resizer->getExtension(),100);
				
				unlink($name);
				
				$this->studentm->update(array('signature'=>'/uploads/students/'.$student_id.'-signature'.$resizer->getExtension()),'student_id='.$student_id);
			}
		}
	}
}