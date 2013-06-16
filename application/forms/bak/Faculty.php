<?php
class Form_Faculty extends Twitter_Bootstrap_Form_Horizontal
{
    public function init()
    {
        $this->setMethod('post')->setAttrib('class','form form-horizontal well');
        $this->_addClassNames('well');
        
        $this->addElement('text', 'faculty_name', array(
            'label'             => 'Faculty Name',
            'class'             => 'input-large',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));
		
        $this->addElement('text', 'faculty_designation', array(
            'label'             => 'Designation',
            'class'             => 'input-large',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));
		
        $this->addElement('text', 'username', array(
            'label'             => 'Username',
            'class'             => 'input-large',
            'required'          => true,
            'validators'        => array(new Zend_Validate_Alnum(), new Zend_Validate_Db_NoRecordExists('user', 'username')),
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('password', 'password', array(
            'label'             => 'Password',
            'class'             => 'input-large',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('password', 'repeat_password', array(
            'label'             => 'Confirm Password',
            'class'             => 'input-large',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));
        $this->repeat_password->addValidator("identical", false, array("token" => "password", "messages" => "The two passwords should be identical"));


		$this->addElement('button', 'submit', array(
            'label'         => 'Save',
            'type'          => 'submit',
            'buttonType'    => 'success',
            'icon'          => 'save',
            'escape'        => false
        ));

        $this->addDisplayGroup(
            array('submit'),
            'actions',
            array(
                'disableLoadDefaultDecorators' => true,
                'decorators' => array('Actions')
            )
        );
    }
}