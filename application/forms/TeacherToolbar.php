<?php
class Form_TeacherToolbar extends Twitter_Bootstrap_Form_Inline
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAction('/teacher/');
        $this->_addClassNames('form');
        $this->_addClassNames('form-inline');
        $this->_addClassNames('well');
        
        $this->addElement('select', 'limit', array(
            'label'             => 'Limit',
            'class'             => 'input-small',
            'multiOptions'      => array(0=>'Limit: All', 1=>10, 20=>20, 30=>30,40=>40,50=>50,60=>60,100=>100),
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('select', 'training_status', array(
            'label'             => 'Training Status',
            'class'             => 'input-medium',
            'multiOptions'      => array(''=>'Type - All', 'Trained'=>'Trained', 'Un-Trained'=>'Un-Trained')
        ));

        $this->addElement('select', 'specialization', array(
            'label'             => 'Specialization',
            'class'             => 'input-medium',
            'multiOptions'      => array(''=>'Specialization - All')
        ));
        $teachermodel = new Model_Teacher();
        $specializations = $teachermodel->specializations();
        foreach ($specializations as $key => $s) {
            $this->specialization->addMultiOption($s->specialization, $s->specialization);
        }

        $this->addElement('select', 'school', array(
            'label'             => 'School',
            'class'             => 'input-medium',
            'multiOptions'      => array(''=>'School - All')
        ));
        $schoolmodel = new Model_School();
        $schools = $schoolmodel->all();
        foreach ($schools as $key => $s) {
            $this->school->addMultiOption($s->id, $s->name);
        }

        $this->addElement('text', 'year_of_retirement', array(
            'label'             => 'Year of Retirement',
            'placeholder'             => 'Year of Retirement',
            'class'             => 'input-medium',
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'search', array(
            'label'             => 'Name',
            'class'             => 'input-medium',
            'placeholder'       => 'Name of Teacher',
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