<?php
class Form_CourseToolbar extends Twitter_Bootstrap_Form_Inline
{
    public function init()
    {
        $this->setMethod('post')->setAttrib('class','form form-inline well');
        $this->setAction('/course/list/');
        $this->_addClassNames('well');
        
        $this->addElement('text', 'search', array(
            'label'             => 'Course Name',
            'class'             => 'input-medium',
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
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