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
		$this->_uploads_rel = SITE_PATH.DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR;
		$this->auth = Zend_Auth::getInstance();
    }

	public function indexAction()
    {
        $schemes = array(
        	'scheme1'=>'#879B1D,#718310,#718310,#718310',
        	'scheme2'=>'#2FA3CF,#106383,#106383,#106383',
        	'scheme3'=>'#2BC5CC,#16989E,#16989E,#16989E',
        	'scheme4'=>'#D1BD51,#97841A,#97841A,#97841A',
        	'scheme5'=>'#8ABD30,#679711,#679711,#679711',
        	'scheme6'=>'#D8604F,#AD3828,#AD3828,#AD3828',
        	'scheme7'=>'#9968E2,#6538A8,#6538A8,#6538A8',
        	'scheme8'=>'#DF4B80,#C5386B,#C5386B,#C5386B',
        	);

	 	if($this->_request->isPost())
        {
        	if($this->optionform->isValid($_POST))
        	{
        		$postData = $this->_request->getPost();

	            $result = $this->optionmodel->update(array('value'=>$postData['diet_district']), "`key`='diet_district'");
	            $result = $this->optionmodel->update(array('value'=>$schemes[$postData['colors']]), "`key`='colors'");
	            $this->uploadLogo();

                $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i> Options updated.', "status"=>"success"));
                $this->_redirect("/options/");
        	}
        }

        $data1 = $this->optionmodel->row(array(
        	'condition' => array('`key` = "diet_district"')
        	));
        $data2 = $this->optionmodel->row(array(
        	'condition' => array("`key` = 'colors'")
        	));
        $data3 = $this->optionmodel->row(array(
        	'condition' => array("`key` = 'logo'")
        	));

        $this->optionform->diet_district->setValue($data1->value);

        $scheme = array_search($data2->value, $schemes);

        $this->optionform->colors->setValue($scheme);

		$this->optionform->logo->setDescription('<img src="'.$data3->value.'" width="100px" height="auto" />');

    	$this->view->form = $this->optionform;
    }

    public function uploadLogo()
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

			if(!is_dir($this->_uploads_rel.'/logo/') && !file_exists($this->_uploads_rel.'/logo/'))
				mkdir('uploads/logo/');
			
			$resizer = new System_Resize();
			
			$iWidth = 200;
			$iHeight = 200;
			
			$resizer->setImage($name);
			$resizer->resizeImage($iWidth,$iHeight);
			
			$resizer->saveImage('uploads/logo/logo'.$resizer->getExtension(),100);
				
			unlink($name);
				
			$this->optionmodel->update(array('value'=>'/uploads/logo/logo'.$resizer->getExtension()),"`key`= 'logo'");
		}
	}
}