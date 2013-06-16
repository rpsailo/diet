<?php

class SettingController extends Zend_Controller_Action
{
    protected $settingm;
    protected $settingf;

    public function init()
    {
        $this->_alert = $this->_helper->getHelper("FlashMessenger");

        $this->settingm = new Model_Setting();
        $this->settingf = new Form_Setting();
    }
    
    public function indexAction()
    {
        if($this->_request->isPost())
        {
            if($this->settingf->isValid($_POST))
            {
                $post_data = $this->_request->getPost();
                unset($post_data['submit']);
                foreach($post_data as $key=>$data)
                    $result = $this->settingm->update_data($key, $data);
                
                $this->_alert->addMessage(array("message"=>'<i class="icon icon-ok"></i> Settings updated.', "status"=>"success"));
                $this->_redirect("/setting/");
            }
        }
        $setting_data = $this->settingm->fetchAll();
        $settings = array();
        foreach($setting_data as $setting)
            $settings[$setting->key] = $setting->data;

        $this->settingf->populate($settings);
        $this->view->form = $this->settingf;
    }
}



