<?php
class Form_TraineesToolbar extends Twitter_Bootstrap_Form_Inline
{
    public function init()
    {
        $this->setMethod('post')->setAttrib('class','form-toolbar form form-inline');
        $this->setAction('/program/trainees');
        // $this->_addClassNames('well');
        
        $this->addElement('select', 'limit', array(
            'label'             => 'Limit',
            'class'             => 'input-small',
            'multiOptions'      => array(0=>'Limit: All', 10=>10, 20=>20, 30=>30,40=>40,50=>50,60=>60,100=>100),
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $programmodel = new Model_Program();
        $this->addElement('select', 'program', array(
            'label'             => 'Programme',
            'title'             => 'Programme',
            'class'             => 'input-medium action-tooltip',
            'multiOptions'      => array(''=>'All Programme')
        ));
        $programs = $programmodel->all();
        foreach($programs as $p)
            $this->program->addMultiOption($p->id, $p->name);

        $schoolmodel = new Model_School();
        $this->addElement('select', 'school', array(
            'label'             => 'School',
            'title'             => 'School',
            'class'             => 'input-medium action-tooltip',
            'multiOptions'      => array(''=>'All School')
        ));
        $schools = $schoolmodel->all();
        foreach($schools as $s)
            $this->school->addMultiOption($s->id, $s->name);
        
        $this->addElement('text', 'search', array(
            'label'             => 'Name',
            'class'             => 'input-medium action-tooltip',
            'placeholder'       => 'Search teacher name',
            'title'       => 'Enter teacher name',
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));
		
        $this->addElement('button', 'submit', array(
            'label'         => 'Search',
            'type'          => 'submit',
            'buttonType'    => 'success',
            'icon'          => 'search',
            'escape'        => false
        ));

    }
}