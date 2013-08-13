<?php
class Form_StatisticToolbar extends Twitter_Bootstrap_Form_Inline
{
    public function init()
    {
        $this->setMethod('post')->setAttrib('class','form form-inline form-toolbar');
        // $this->_addClassNames('well');
        
        $this->addElement('select', 'limit', array(
            'label'             => 'Limit',
            'class'             => 'input-small',
            'multiOptions'      => array(0=>'Limit: All', 10=>10, 20=>20, 30=>30,40=>40,50=>50,60=>60,100=>100),
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('select', 'type', array(
            'label'             => 'Type',
            'class'             => 'input-medium',
            'multiOptions'      => array(''=>'Type - All','Govt'=>'Govt','Deficit'=>'Deficit','Adhoc'=>'Adhoc','Aided'=>'Aided','Private'=>'Private')
        ));

        $this->addElement('select', 'level', array(
            'label'             => 'Level',
            'class'             => 'input-medium',
            'multiOptions'      => array(
                ''=>'Level - All',
                'Primary School'=>'Primary School',
                'Middle School'=>'Middle School'
                )
        ));        

        $this->addElement('text', 'year', array(
            'label'             => 'Year',
            'placeholder'       => 'Year',
            'title'       => 'Year',
            'class'             => 'input-mini action-tooltip',
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));
		
        $this->addElement('text', 'name', array(
            'label'             => 'Name',
            'class'             => 'input-medium action-tooltip',
            'placeholder'       => 'Name of School',
            'title'       => 'Name of School',
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('button', 'new', array(
            'label'         => 'New Statistic',
            'type'          => 'button',
            'buttonType'    => 'success',
            'icon'          => 'plus',
            'class'         => 'pull-right',
            'escape'        => false
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