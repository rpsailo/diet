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
    	$status = $this->_request->getParam('status', null);
    	$professional_qualification = $this->_request->getParam('professional_qualification', null);
    	$school = $this->_request->getParam('school', null);
    	$sex = $this->_request->getParam('sex', null);
    	$tet = $this->_request->getParam('tet', null);
    	$main_subject_taught = $this->_request->getParam('main_subject_taught', null);
    	$date_of_joining = $this->_request->getParam('date_of_joining', null);
    	$district = $this->_request->getParam('district', null);
    	$sub_division = $this->_request->getParam('sub_division', null);
    	$no_of_training = $this->_request->getParam('no_of_training', null);
    	$advanced_search = $this->_request->getParam('as', null);

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
    	if($status != null)
    	{
    		$url_params .= '/status/'.$status;
    		$params['condition'][] = "`status` = '".$status."'";
    		$this->teachertoolbarform->status->setValue($status);
    	}
    	if($professional_qualification != null)
    	{
    		$url_params .= '/professional_qualification/'.$professional_qualification;
    		$params['condition'][] = "`professional_qualification` = '".$professional_qualification."'";
    		$this->teachertoolbarform->professional_qualification->setValue($professional_qualification);
    	}
    	if($school != null)
    	{
    		$url_params .= '/school/'.$school;
    		$params['condition'][] = "`school_id` = ".$school;
    		$this->teachertoolbarform->school->setValue($school);
    	}
    	if($sex != null)
    	{
    		$url_params .= '/sex/'.$sex;
    		$params['condition'][] = "`sex` = '".$sex."'";
    		$this->teachertoolbarform->sex->setValue($sex);
    	}
    	if($tet != null)
    	{
    		$url_params .= '/tet/'.$tet;
    		$params['condition'][] = "`tet` = '".$tet."'";
    		$this->teachertoolbarform->tet->setValue($tet);
    	}
    	if($main_subject_taught != null)
    	{
    		$url_params .= '/main_subject_taught/'.$main_subject_taught;
    		$params['condition'][] = "`main_subject_taught` = '".$main_subject_taught."'";
    		$this->teachertoolbarform->main_subject_taught->setValue($main_subject_taught);
    	}
    	if($date_of_joining != null)
    	{
    		$url_params .= '/date_of_joining/'.$date_of_joining;
    		$params['condition'][] = "`date_of_joining` = '".$date_of_joining."'";
    		$this->teachertoolbarform->date_of_joining->setValue($date_of_joining);
    	}
    	if($district != null)
    	{
    		$url_params .= '/district/'.$district;
    		$params['condition'][] = "`district` = '".$district."'";
    		$this->teachertoolbarform->district->setValue($district);
    	}
    	if($sub_division != null)
    	{
    		$url_params .= '/sub_division/'.$sub_division;
    		$params['condition'][] = "`sub_division` = '".$sub_division."'";
    		$this->teachertoolbarform->sub_division->setValue($sub_division);
    	}
    	if($no_of_training != null)
    	{
    		$url_params .= '/no_of_training/'.$no_of_training;
    		if(in_array(substr($no_of_training, 0, 1), array('=','>','<', 'b')))
	    		$params['condition'][] = "`no_of_training` ".$no_of_training;
    		else
    			$params['condition'][] = "`no_of_training` = ".$no_of_training;

    		$this->teachertoolbarform->no_of_training->setValue($no_of_training);
    	}

    	if($advanced_search != 0)
    	{
    		$url_params .= '/as/'.$advanced_search;
    		$this->teachertoolbarform->as->setValue($advanced_search);
    		$this->teachertoolbarform->advanced->setLabel('Show Less Filter');
    	}


		if($this->_request->isPost())
    		$this->_redirect('/teacher/index'.$url_params.'/page/'.$page.'/limit/'.$limit);

    	$this->view->data = $this->teachermodel->paginate($params);
    	$this->view->form = $this->teachertoolbarform;
    	$this->view->trainingmodel = $this->trainingmodel;
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
				$this->view->trainingmodel = $this->trainingmodel;
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
		    			'`teacher_id` = '.$teacher->id
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
			            	$this->teachermodel->update(array('training_attended'=>'Yes'), 'id='.$teacher->id);

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

	public function removeTrainingAction()
	{
		$id = $this->_request->getParam('id'); // Training ID

		if($id)
		{
			$training = $this->trainingmodel->find($id)->current();
			
			if($training)
			{
				$teacher_id = $training->teacher_id;
				$training->delete();

				if(!$this->trainingmodel->teacherCount($teacher_id))
					$this->teachermodel->update(array('training_attended'=>'No'), 'id='.$teacher_id);
                
                $this->_redirect("/teacher/training/id/".$teacher_id);
			}
			else
			{
				$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Invalid training ID.', "status"=>"error"));
				$this->_redirect('/teacher/');
			}
		}
		else
		{
			$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect training ID.', "status"=>"error"));
			$this->_redirect('/teacher/');
		}
	}

	public function changeTrainingStatusAction()
	{
		$id = $this->_request->getParam('id'); // Training ID
		$status = $this->_request->getParam('status'); // Training ID

		if($id)
		{
			$training = $this->trainingmodel->find($id)->current();
			
			if($training)
			{
				$training->status = ucwords($status);
				$training->save();
                $this->_redirect("/teacher/training/id/".$training->teacher_id);
			}
			else
			{
				$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Invalid training ID.', "status"=>"error"));
				$this->_redirect('/teacher/');
			}
		}
		else
		{
			$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect training ID.', "status"=>"error"));
			$this->_redirect('/teacher/');
		}
	}

	public function changeTrainingDateAction()
	{
		$id = $this->_request->getParam('id'); // Training ID
		$field = $this->_request->getParam('field'); // From or To column
		$date = $this->_request->getParam('date'); // New date

		if($id)
		{
			$training = $this->trainingmodel->find($id)->current();
			
			if($training)
			{
				$newdate = date('dS F, Y', ($date / 1000));
				$date = date('Y-m-d', ($date / 1000));

				if($field == 'from')
					$training->from = $date;

				if($field == 'to')
					$training->to = $date;

				$training->save();
				if($this->_request->isXmlHttpRequest())
					$this->_helper->json(array('date'=>$newdate));
				else
                	$this->_redirect("/teacher/training/id/".$training->teacher_id);
			}
			else
			{
				$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Invalid training ID.', "status"=>"error"));
				$this->_redirect('/teacher/');
			}
		}
		else
		{
			$this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation-sign"></i> Incorrect training ID.', "status"=>"error"));
			$this->_redirect('/teacher/');
		}
	}

	public function typeaheadAction()
	{
		$q = $this->_request->getParam('q', '');
		$limit = $this->_request->getParam('limit', 8);

		$teachers = $this->teachermodel->typeahead($q, $limit);
		
		$jsonData = array();
		foreach($teachers as $t)
			$jsonData[] = $t->name." - ".$t->school_name." (ID:".$t->id.")";

		$this->_helper->json($jsonData);
	}
}



