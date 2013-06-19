<?php

class TeacherController extends Zend_Controller_Action
{
	protected $auth;

	protected $teachermodel;
	protected $teacherform;
	protected $trainingform;
	protected $teachertoolbarform;

	public function init()
	{
		$this->teachermodel = new Model_Teacher();
		$this->trainingmodel = new Model_Training();
		$this->teacherform = new Form_Teacher();
		$this->trainingform = new Form_Training();
		$this->teachertoolbarform = new Form_TeacherToolbar();

		$this->_alert = $this->_helper->getHelper("FlashMessenger");
		$this->_uploads_rel = SITE_PATH.DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR;
		$this->auth = Zend_Auth::getInstance();
    }

	public function indexAction()
    {
		$url_params = '';

    	$search = $this->_request->getParam('search', null);
    	$year_of_retirement = $this->_request->getParam('year_of_retirement', null);
    	$training_status = $this->_request->getParam('training_status', null);
    	$specialization = $this->_request->getParam('specialization', null);
    	$limit = $this->_request->getParam('limit', 20);
    	$page = $this->_request->getParam('page', 1);

    	$params = array(
    		'limit' 	=> $limit,
    		'page'		=> $page,
    		'order'		=> 'name asc',
    		'condition'	=> array()
		);

		$this->teachertoolbarform->limit->setValue($limit);

    	if($search != null)
    	{
    		$url_params .= '/search/'.$search;
    		$params['condition'][] = "`name` LIKE '%".$search."%'";
    		$this->teachertoolbarform->search->setValue($search);
    	}
    	if($year_of_retirement != null)
    	{
    		$url_params .= '/year_of_retirement/'.$year_of_retirement;
    		$params['condition'][] = "`year_of_retirement` = ".$year_of_retirement;
    		$this->teachertoolbarform->year_of_retirement->setValue($year_of_retirement);
    	}
    	if($training_status != null)
    	{
    		$url_params .= '/training_status/'.$training_status;
    		$params['condition'][] = "`training_status` = '".$training_status."'";
    		$this->teachertoolbarform->training_status->setValue($training_status);
    	}
    	if($specialization != null)
    	{
    		$url_params .= '/specialization/'.$specialization;
    		$params['condition'][] = "`specialization` = '".$specialization."'";
    		$this->teachertoolbarform->specialization->setValue($specialization);
    	}

		if($this->_request->isPost())
    		$this->_redirect('/teacher/index'.$url_params.'/page/'.$page.'/limit/'.$limit);

    	$this->view->data = $this->teachermodel->paginate($params);
    	$this->view->form = $this->teachertoolbarform;
	}

	public function viewAction()
	{
		$id = $this->_request->getParam('id');

		if($id)
		{
			$teacher = $this->teachermodel->find($id)->current();
			if($teacher)
			{
				$this->view->teacher = $teacher;
			}
			else
			{
				$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Invalid teacher ID.', "status"=>"error"));
				$this->_redirect('/teacher/');
			}	
		}
		else
		{
			$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect teacher ID.', "status"=>"error"));
			$this->_redirect('/teacher/');
		}
	}

	public function addAction()
	{
	 	if($this->_request->isPost())
        {
        	if($this->teacherform->isValid($_POST))
        	{
	            $result = $this->teachermodel->create($this->_request->getPost());
	            if($result)
	            {
	            	$this->upload($result);
	                $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i> New teacher added.', "status"=>"success"));
	                $this->_redirect("/teacher/");
	            }
        	}
        }

		$this->view->form = $this->teacherform;
	}

	public function removeAction()
	{
		$id = $this->_request->getParam('id');

		if($id)
		{
			$teacher = $this->teachermodel->find($id)->current();

			if($teacher)
			{
				$teachername = $teacher->name;
				$teacher->delete();
				$this->_alert->addMessage(array("message"=>'<i class="icon icon-trash"></i> "'.$teachername.'" deleted.', "status"=>"success"));
			}
			else
			{
				$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect teacher ID.', "status"=>"error"));
			}			
		}
		else
		{
			$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect teacher ID.', "status"=>"error"));
		}

		$this->_redirect('/teacher/');
	}

	public function editAction()
	{
		$id = $this->_request->getParam('id');

		if($id)
		{
			$teacher = $this->teachermodel->find($id)->current();
			
			if($teacher)
			{
			 	if($this->_request->isPost())
		        {
		        	if($this->teacherform->isValid($_POST))
		        	{
			            $result = $this->teachermodel->edit($this->_request->getPost(), $teacher->id);
			            if($result)
			            {
			            	$this->upload($teacher->id);
			                $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i> "'.$teacher->name.'" updated.', "status"=>"success"));
			                $this->_redirect("/teacher/");
			            }
		        	}
		        }
				
				$this->teacherform->populate($teacher->toArray());
				$this->teacherform->picture->setDescription('Upload new picture to change current picture.<br><img src="'.$teacher->picture.'" width="100px" height="auto" />');
				$this->view->form = $this->teacherform;
				$this->view->teacher = $teacher;
			}
			else
			{
				$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Invalid teacher ID.', "status"=>"error"));
				$this->_redirect('/teacher/');
			}
		}
		else
		{
			$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect teacher ID.', "status"=>"error"));
			$this->_redirect('/teacher/');
		}
	}

	public function upload($teacher_id)
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

			if(!is_dir($this->_uploads_rel.'/teachers/'))
				mkdir('uploads/teachers/');
			
			$resizer = new System_Resize();
			
			$iWidth = 200;
			$iHeight = 200;
			
			$resizer->setImage($name);
			$resizer->resizeImage($iWidth,$iHeight);
			
			echo $resizer->saveImage('uploads/teachers/'.$teacher_id.$resizer->getExtension(),100);
				
			unlink($name);
				
			$this->teachermodel->update(array('picture'=>'/uploads/teachers/'.$teacher_id.$resizer->getExtension()),'id='.$teacher_id);
		}
	}

	public function trainingAction()
	{
		$id = $this->_request->getParam('id', null);
		$page = $this->_request->getParam('page', 1);
		
		if($id)
		{
			$teacher = $this->teachermodel->find($id)->current();
			
			if($teacher)
			{
				$params = array(
		    		'page'		=> $page,
		    		'order'		=> 'created_at desc',
		    		'condition'	=> array(
		    			'teacher_id'	=> $teacher->id
		    			)
				);
				$this->view->data = $this->trainingmodel->paginate($params);
				$this->view->teacher = $teacher;
			}
			else
			{
				$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Invalid teacher ID.', "status"=>"error"));
				$this->_redirect('/teacher/');
			}
		}
		else
		{
			$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect teacher ID.', "status"=>"error"));
			$this->_redirect('/teacher/');
		}
	}

	public function addTrainingAction()
	{
		$id = $this->_request->getParam('id'); // Teacher ID

		if($id)
		{
			$teacher = $this->teachermodel->find($id)->current();
			
			if($teacher)
			{
			 	if($this->_request->isPost())
		        {
		        	if($this->trainingform->isValid($_POST))
		        	{
			            $result = $this->trainingmodel->create($this->_request->getPost());
			            if($result)
			            {
			                $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i> New training added for "'.$teacher->name.'".', "status"=>"success"));
			                $this->_redirect("/teacher/training/id/".$teacher->id);
			            }
		        	}
		        }
				
				$this->trainingform->teacher_id->setValue($teacher->id);
				$this->trainingform->from->setValue(date('Y-m-d',strtotime('today')));
				$this->trainingform->to->setValue(date('Y-m-d',strtotime('tomorrow')));
				$this->trainingform->status->setValue('Active');
				$this->view->form = $this->trainingform;
				$this->view->teacher = $teacher;
			}
			else
			{
				$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Invalid teacher ID.', "status"=>"error"));
				$this->_redirect('/teacher/');
			}
		}
		else
		{
			$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect teacher ID.', "status"=>"error"));
			$this->_redirect('/teacher/');
		}
	}
}



