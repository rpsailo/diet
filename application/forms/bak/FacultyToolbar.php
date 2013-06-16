<?php
class Form_FacultyToolbar extends Twitter_Bootstrap_Form_Inline
{
    public function init()
    {
        $this->setMethod('post')->setAttrib('class','form form-inline well');
        $this->setAction('/faculty/list/');
        $this->_addClassNames('well');
        
        $this->addElement('text', 'search', array(
            'label'             => 'Faculty Name',
            'class'             => 'input-medium',
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $faculty_model = new Model_Faculty();
        $designations = array('0'=>'All Designation');
        foreach($faculty_model->designations() as $row)
            $designations[$row->faculty_designation] = $row->faculty_designation;

        $this->addElement('select', 'designation', array(
            'label'             => 'Designation',
            'class'             => 'input-medium',
            'multioptions'      => $designations
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