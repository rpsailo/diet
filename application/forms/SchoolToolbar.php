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
            'multiOptions'      => array(0=>'Limit: All', 10=>10, 20=>20, 30=>30,40=>40,50=>50,60=>60,100=>100),
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $subdivisionmodel = new Model_SubDivisionBlock();
        $this->addElement('select', 'district', array(
            'label'             => 'District',
            'title'             => 'District',
            'class'             => 'input-medium action-tooltip',
            'multiOptions'      => array(''=>'All District'),
            'onchange'           => 'getByDistrict(this.value);'
        ));
        $this->district->addMultiOptions($subdivisionmodel->districts());

        $this->addElement('select', 'sub_division', array(
            'title'             => 'Sub Division / Block',
            'label'             => 'Sub Division / Block',
            'class'             => 'input-medium action-tooltip',
            'multiOptions'      => array(''=>'All Sub Division / Block')
        ));
        foreach($subdivisionmodel->all() as $s)
            $this->sub_division->addMultiOption($s->name, $s->name);
        
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
            'buttonType'    => 'success',
            'icon'          => 'search',
            'escape'        => false
        ));

    }
}