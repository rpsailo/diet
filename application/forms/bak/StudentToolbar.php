<?php
class Form_StudentToolbar extends Twitter_Bootstrap_Form_Inline
{
    public function init()
    {
        $this->setMethod('post')->setAttrib('class','form form-inline well');
        $this->setAction('/student/list/');
        $this->_addClassNames('well');
        
        $this->addElement('text', 'search', array(
            'label'             => 'Student Name/Roll No',
            'placeholder'       => 'Name / Roll No',
            'class'             => 'input-medium',
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $course_model = new Model_Course();
        $courses = array('0'=>'All Courses');
        foreach($course_model->fetchAll() as $row)
            $courses[$row->course_id] = $row->course_name;

        $this->addElement('select', 'course', array(
            'label'             => 'Course',
            'class'             => 'input-medium',
            'multioptions'      => $courses
        ));
		
        $this->addElement('button', 'submit', array(
            'label'         => 'Search',
            'type'          => 'submit',
            'buttonType'    => 'primary',
            'icon'          => 'search',
            'escape'        => false
        ));

    }
}