<?php
class Form_Student extends Twitter_Bootstrap_Form_Horizontal
{
    public function init()
    {
        $this->setMethod('post')->setAttrib('class','form form-horizontal well');
        $this->_addClassNames('well');
        		
        $this->addElement('text', 'roll_no', array(
            'label'             => 'Roll no',
            'class'             => 'input-large',
            'required'          => true,
            'description'       => 'Roll number will be used as username for login.',
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

        $this->addElement('text', 'student_name', array(
            'label'             => 'Name',
            'class'             => 'input-large',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));
        
        $this->addElement('text', 'parent_name', array(
            'label'             => 'Parent/Guardian Name',
            'class'             => 'input-large',
			'required'          => true,
			'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));
		
		$this->addElement('text', 'dob', array(
            'label'             => 'Date of Birth',
            'class'             => 'datepicker input-large',
            'append'            =>  '<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>',
			'required'          => true,
			'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));
		
		$this->addElement('text', 'email_id', array(
            'label'             => 'Email ID',
            'class'             => 'input-large',
			'required'          => true,
			'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));
		
		$this->addElement('text', 'contact_no', array(
            'label'             => 'Contact Number',
            'class'             => 'input-large',
			'required'          => true,
			'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));
		
		$this->addElement('textarea', 'address', array(
            'label'             => 'Address',
            'class'             => 'input-large',
			'required'          => true,
            'attribs'           => array('rows'=>'3'),
			'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));
		
		$this->addElement('text', 'centre', array(
            'label'             => 'Centre',
            'class'             => 'input-large',
			'required'          => true,
			'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));
		
		$this->addElement('text', 'institution', array(
            'label'             => 'Institution',
            'class'             => 'input-large',
			'required'          => true,
			'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));
		
		$this->addElement('select', 'sex', array(
            'label'             => 'Sex',
            'class'             => 'input-large',
            'multioptions'           => array('Male'=>'Male','Female'=>'Female')
        ));
		
		$this->addElement('text', 'nationality', array(
            'label'             => 'Nationality',
            'class'             => 'input-large',
			'required'          => true,
            'value'             => 'Indian',
			'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));
		
		$this->addElement('file', 'passport', array(
            'label'             => 'Passport Photo',
            'class'             => 'input-large'
        ));
		
		$this->addElement('file', 'signature', array(
            'label'             => 'Signature',
            'class'             => 'input-large'
        ));
		
		$this->addElement('text', 'date_of_registration', array(
            'label'             => 'Date of Registration',
            'class'             => 'datepicker input-large',
            'append'            =>  '<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>',
			'required'          => true,
            'value'             => date('Y-m-d'),
			'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));
	
		$this->addElement('select', 'category', array(
            'label'             => 'Category',
            'class'             => 'input-large',
            'multioptions'      => array('General'=>'General','SC'=>'SC','ST'=>'ST','OBC'=>'OBC','Others'=>'Others')
        ));
	
        $course_model = new Model_Course();
        $courses = array();
        foreach($course_model->fetchAll() as $course)
            $courses[$course->course_id] = $course->course_name.' ('.$course->course_duration.')';
        
        $this->addElement('select', 'course', array(
            'label'             => 'Course',
            'class'             => 'input-large',
            'multioptions'           => $courses
        ));

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