<?php
class Form_ProgramToolbar extends Twitter_Bootstrap_Form_Inline
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAction('/program/');
        $this->_addClassNames('form');
        $this->_addClassNames('form-inline');
        $this->_addClassNames('form-toolbar');
        // $this->_addClassNames('well');
        
        $this->addElement('select', 'limit', array(
            'label'             => 'Limit',
            'class'             => 'input-small',
            'multiOptions'      => array(0=>'Limit: All', 10=>10, 20=>20, 30=>30,40=>40,50=>50,60=>60,100=>100),
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'year', array(
            'label'             => 'Year',
            'class'             => 'input-medium action-tooltip',
            'placeholder'       => 'Year',
            'title'       => 'Enter programme year',
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'search', array(
            'label'             => 'Name',
            'class'             => 'input-medium action-tooltip',
            'placeholder'       => 'Name',
            'title'       => 'Enter programme Name',
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