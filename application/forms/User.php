<?php
class Form_User extends Twitter_Bootstrap_Form_Horizontal
{
    public function init()
    {
        $this->setMethod('post')->setAttrib('class','form form-horizontal');
        // $this->_addClassNames('well');
      
        $this->addElement('text', 'name', array(
            'label'             => 'Display Name',
            'class'             => 'input-xlarge',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'username', array(
            'label'             => 'Username',
            'class'             => 'input-xlarge',
            'required'          => true,
            'validators'        => array(new Zend_Validate_Alnum(), new Zend_Validate_Db_NoRecordExists('user', 'username')),
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('password', 'password', array(
            'label'             => 'Password',
            'class'             => 'input-xlarge',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('password', 'repeat_password', array(
            'label'             => 'Confirm Password',
            'class'             => 'input-xlarge',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'dob', array(
            'label'             => 'Date of Birth',
            // 'class'             => 'pickadate input-medium',
            'class'             => 'datepicker input-medium',
            'append'            =>  '<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'date_of_joining', array(
            'label'             => 'Date of Joining',
            // 'class'             => 'pickadate input-medium',
            'class'             => 'datepicker input-medium',
            'append'            =>  '<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'phone', array(
            'label'             => 'Mobile Phone',
            'class'             => 'input-medium',
            // 'prepend'           => '+91',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'educational_qualification', array(
            'label'             => 'Educational Qualification',
            'class'             => 'input-xlarge',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        // $this->addElement('text', 'professional_qualification', array(
        //     'label'             => 'Professional Qualification',
        //     'class'             => 'input-xlarge',
        //     'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        // ));

        $this->addElement('select', 'professional_qualification', array(
            'label'             => 'Professional Qualification',
            'class'             => 'input-xlarge',
            'required'          => true,
            'multiOptions'      => array(
                ''=>'---Select Professional Qualification---',
                'Master'=>'Master',
                'Bachelor'=>'Bachelor',
                'Diploma'=>'Diploma',
                'Ongoing'=>'Ongoing',
                'Nil'=>'Nil'
                )
        ));
        
        $this->addElement('text', 'other_qualification', array(
            'label'             => 'Other Qualification',
            'class'             => 'input-xlarge',
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('textarea', 'present_address', array(
            'label'             => 'Present Address',
            'class'             => 'input-xlarge',
            'rows'              => 2,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('textarea', 'permanent_address', array(
            'label'             => 'Permanent Address',
            'class'             => 'input-xlarge',
            'rows'              => 2,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('select', 'role', array(
            'label'             => 'Role',
            'class'             => 'input-xlarge',
            'required'          => true,
            'multiOptions'      => array('administrator'=>'Administrator', 'faculty'=>'Faculty', 'user'=>'User')
            ));

        $this->repeat_password->addValidator("identical", false, array("token" => "password", "messages" => "The two passwords should be identical"));
	
        $this->addElement('file', 'picture', array(
            'label'             => 'Picture',
            'class'             => 'input-large',
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));
        $this->picture->getDecorator('Description')->setOption('escape', false);

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