<?php
class Form_Login extends Twitter_Bootstrap_Form_Inline
{
    public function init()
    {
        $this->setMethod('post')->setAttrib('class','form form-inline');
        
        $this->addElement('text', 'user_login', array(
            'placeholder'       => 'Username',
            'class'             => 'input-block-level',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('password', 'user_password', array(
            'placeholder'       => 'Password',
            'class'             => 'input-block-level',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('button', 'login', array(
            'type'          => 'submit',
            'label'          => 'Sign In',
            'class'          => 'btn-primary btn-large',
            'buttonType'    => 'default',
            'escape'        => false
        ));
    }
}