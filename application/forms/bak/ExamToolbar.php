<?php
class Form_ExamToolbar extends Twitter_Bootstrap_Form_Inline
{
    public function init()
    {
        $this->setMethod('post')->setAttrib('class','form form-inline well');
        $this->setAction('/exam/list/');
        $this->_addClassNames('well');
        
        $this->addElement('text', 'search', array(
            'label'             => 'Exam Name',
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