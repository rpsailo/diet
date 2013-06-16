<?php
class Form_Program extends Twitter_Bootstrap_Form_Horizontal
{
    public function init()
    {
        $this->setMethod('post')->setAttrib('class','form form-horizontal well');
        $this->_addClassNames('well');
      
        $this->addElement('text', 'username', array(
            'label'             => 'Username',
            'class'             => 'input-xxlarge',
            'required'          => true,
            'validators'        => array(new Zend_Validate_Alnum(), new Zend_Validate_Db_NoRecordExists('user', 'username')),
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('password', 'password', array(
            'label'             => 'Password',
            'class'             => 'input-xxlarge',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('password', 'repeat_password', array(
            'label'             => 'Confirm Password',
            'class'             => 'input-xxlarge',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'dob', array(
            'label'             => 'Date of Birth',
            'class'             => 'datepicker input-xxlarge',
            'append'            =>  '<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'phone', array(
            'label'             => 'Mobile Phone',
            'class'             => 'input-xxlarge',
            'prepend'           => '+91',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'date_of_joining', array(
            'label'             => 'Date of Joining',
            'class'             => 'datepicker input-xxlarge',
            'append'            =>  '<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'educational_qualification', array(
            'label'             => 'Educational Qualification',
            'class'             => 'input-xxlarge',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'specialization', array(
            'label'             => 'Specialization',
            'class'             => 'input-xxlarge',
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('textarea', 'address', array(
            'label'             => 'Address',
            'class'             => 'input-xxlarge',
            'rows'              => 4,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('select', 'role', array(
            'label'             => 'Role',
            'class'             => 'input-xxlarge',
            'required'          => true,
            'multiOptions'      => array('administrator'=>'Administrator', 'faculty'=>'Faculty')
            ));

        $this->repeat_password->addValidator("identical", false, array("token" => "password", "messages" => "The two passwords should be identical"));
	
        $this->addElement('button', 'add', array(
            'label'         => 'Save',
            'type'          => 'submit',
            'buttonType'    => 'success',
            'icon'          => 'save',
            'escape'        => false
        ));

        $this->addElement('button', 'cancel', array(
            'label'         => 'Cancel',
            'type'          => 'reset',
            'buttonType'    => 'danger',
            'icon'          => 'danger',
            'escape'        => false
        ));

        $this->addDisplayGroup(
            array('add','cancel'),
            'actions',
            array(
                'disableLoadDefaultDecorators' => true,
                'decorators' => array('Actions')
            )
        );
    }
}