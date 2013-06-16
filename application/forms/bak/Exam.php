<?php
class Form_Exam extends Twitter_Bootstrap_Form_Horizontal
{
    public function init()
    {
        $this->setMethod('post')->setAttrib('class','form form-horizontal well');
        $this->_addClassNames('well');
        		
		$course_model = new Model_Course();
		$courses = array();
		foreach($course_model->fetchAll() as $course)
			$courses[$course->course_id] = $course->course_name." (".$course->course_duration.")";
		
        $this->addElement('select', 'course_id', array(
            'label'             => 'Course',
            'class'             => 'input-xlarge',
            'multioptions'           => $courses
        ));
        		
        $this->addElement('text', 'exam_name', array(
            'label'             => 'Exam Name',
            'class'             => 'input-xlarge',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'start_at', array(
            'label'             => 'Start At',
            'class'             => 'input-medium datetimepicker',
            'append'            =>  '<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        // $this->addElement('text', 'end_at', array(
        //     'label'             => 'End At',
        //     'class'             => 'input-medium datetimepicker',
        //     'append'            =>  '<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>',
        //     'required'          => true,
        //     'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        // ));

        $this->addElement('text', 'exam_duration', array(
            'label'             => 'Exam Duration',
            'class'             => 'input-small',
            'required'          => true,
            'append'            => 'mins',
            'validators'        => array(new Zend_Validate_Digits()),
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'no_of_question', array(
            'label'             => 'No Of Question',
            'class'             => 'input-small',
            'required'          => true,
            'validators'        => array(new Zend_Validate_Digits()),
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));
	
        $this->addElement('text', 'no_of_student', array(
            'label'             => 'No of Student',
            'class'             => 'input-small',
			'required'          => true,
            'validators'        => array(new Zend_Validate_Digits()),
			'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));
		
		$faculty_model = new Model_Faculty();
		$faculty = array();
		foreach($faculty_model->fetchAll() as $f)
			$faculty[$f->faculty_id] = $f->faculty_name." (".$f->faculty_designation.")";
		
		$this->addElement('select', 'faculty_id', array(
            'label'             => 'Invigilator',
            'class'             => 'input-xlarge',
            'multioptions'      => $faculty
        ));

        $this->addElement('select', 'status', array(
            'label'             => 'Exam Status',
            'class'             => 'input-xlarge',
            'multioptions'      => array(
                'ready' => 'Ready', // Exam is ready for student
                'active'    => 'Active', // Exam is currently taken
                'inactive'  => 'Inactive', // Exam is not available
                'completed'  => 'Completed' // Exam is completed
                )
            ));
			

        $this->addElement('button', 'add', array(
            'label'         => 'Save',
            'type'          => 'submit',
            'buttonType'    => 'success',
            'icon'          => 'save',
            'escape'        => false
        ));

        $this->addDisplayGroup(
            array('add'),
            'actions',
            array(
                'disableLoadDefaultDecorators' => true,
                'decorators' => array('Actions')
            )
        );
    }
}