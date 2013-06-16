<?php
class Form_Login extends Twitter_Bootstrap_Form_Horizontal
{
    public function init()
    {
        $this->setMethod('post')->setAttrib('class','form form-horizontal well');
        $this->_addClassNames('well');
        
        $this->addElement('text', 'user_login', array(
            'label'             => 'Username',
            'class'             => 'input-large',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('password', 'user_password', array(
            'label'             => 'Password',
            'class'             => 'input-large',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('button', 'login', array(
            'label'         => 'Login',
            'type'          => 'submit',
            'buttonType'    => 'default',
            'icon'          => 'lock',
            'escape'        => false
        ));

        $this->addDisplayGroup(
            array('login'),
            'actions',
            array(
                'disableLoadDefaultDecorators' => true,
                'decorators' => array('Actions')
            )
        );
    }
}