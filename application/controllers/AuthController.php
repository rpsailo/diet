<?php
class AuthController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_auth = Zend_Auth::getInstance();
        $this->_model_user = new Model_User();
        $this->_form = new Form_Login();
        $this->_flashMessenger = $this->_helper->getHelper("FlashMessenger");
    }
    
    public function loginAction()
    {
        if(!$this->_auth->hasIdentity())
        {
            if($this->_request->isPost() && $this->_form->isValid($_POST))
            {               
                $db = $this->_getParam('db');
                
                $adapter = new Zend_Auth_Adapter_DbTable($db);
                
                $adapter->setTableName("user")
                    ->setIdentityColumn("username")
                    ->setCredentialColumn("password")
                    ->setCredentialTreatment("MD5(CONCAT(?, password_salt))");
                
                $adapter->setIdentity($this->_form->getValue("user_login"))
                    ->setCredential($this->_form->getValue("user_password"));
                    
                $authentication = $this->_auth->authenticate($adapter);
                
                if($authentication->isValid())
                {
                    $storage = $this->_auth->getStorage();
                    
                    $storage->write($adapter->getResultRowObject(array('id', 'username', 'role')));
                    
                    $identity = $this->_auth->getIdentity();

                    $this->_model_user->update(array(
                        'loggedin_at' => new Zend_Db_Expr('NOW()')
                        ), 'id='.$identity->id);
					
                    $this->_redirect("/");
                }
                else {
                    $this->_flashMessenger->addMessage(array('message' => 'Invalid username or password', 'status' => 'error'));
                    $this->_redirect("/");
                }
                
            }
        }
        else {
            $this->_redirect("/");
        }
        
        $this->view->form = $this->_form;
    }
    
    public function logoutAction()
    {
        $this->_auth->clearIdentity();

        if($this->_request->getParam('callback')){
            $this->view->callback = $this->_request->getParam('callback');
            $this->view->response = array('status'=>'success');
            $this->_helper->layout->setLayout('json');
            $this->renderScript('index/json.phtml');
        }
        else
        {
            $this->_redirect("/");
        }
    }
}