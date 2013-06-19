<?php
class Form_SchoolToolbar extends Twitter_Bootstrap_Form_Inline
{
    public function init()
    {
        $this->setMethod('post')->setAttrib('class','form form-inline well');
        $this->setAction('/school/');
        $this->_addClassNames('well');
        
        $this->addElement('select', 'limit', array(
            'label'             => 'Limit',
            'class'             => 'input-small',
            'multiOptions'      => array(0=>'Limit: All', 1=>10, 20=>20, 30=>30,40=>40,50=>50,60=>60,100=>100),
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('select', 'type', array(
            'label'             => 'Type',
            'class'             => 'input-medium',
            'multiOptions'      => array(''=>'Type - All', 'Adhoc'=>'Adhoc')
        ));

        $this->addElement('text', 'year_of_establishment', array(
            'label'             => 'Year of Establishment',
            'placeholder'             => 'Year of Establishment',
            'class'             => 'input-medium',
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'search', array(
            'label'             => 'Name',
            'class'             => 'input-medium',
            'placeholder'       => 'Name of School',
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