<?php
class Form_School extends Twitter_Bootstrap_Form_Horizontal
{
    public function init()
    {
        $this->setMethod('post')->setAttrib('class','form form-horizontal');
      
        $this->addElement('text', 'name', array(
            'label'             => 'School Name',
            'class'             => 'input-xxlarge',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('textarea', 'address', array(
            'label'             => 'Address',
            'class'             => 'input-xxlarge',
            'rows'              => 2,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));
		
        $this->addElement('select', 'district', array(
            'label'             => 'District',
            'class'             => 'input-large',
            'required'          => true,
            'onchange'           => 'getByDistrict(this.value);'
        ));
        $subdivisionmodel = new Model_SubDivisionBlock();
        $i = 0;
        foreach($subdivisionmodel->districts() as $d)
        {
            if($i == 0)
            {
                $this->district->setValue($d);
                $i = 1;
            }
            $this->district->addMultiOption($d, $d);
        }

        $this->addElement('select', 'sub_division', array(
            'label'             => 'Sub Division',
            'class'             => 'input-large',
            'required'          => true
        ));
        foreach($subdivisionmodel->all() as $s)
        {
            $this->sub_division->addMultiOption($s->name, $s->name);
        }
        
        $this->addElement('text', 'phone', array(
            'label'             => 'Contact Number',
            'class'             => 'input-medium',
            // 'prepend'           => '+91',
            'description'       => 'Ex: 943XXXXXXX, 372XXXXXXX, 389XXXXXXX',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags"),
            'validators'        => array(new Zend_Validate_Digits())
        ));

        $this->addElement('text', 'year_of_establishment', array(
            'label'             => 'Year of Establishment',
            'class'             => 'input-large',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags"),
            'validators'        => array(new Zend_Validate_Digits())
        ));

        $this->addElement('select', 'type', array(
            'label'             => 'Type',
            'class'             => 'input-large',
            'required'          => true,
            'multioptions'      => array(
                ''=>'---Select Type---',
                'Govt'=>'Govt',
                'Deficit'=>'Deficit',
                'Aided'=>'Aided',
                'Private'=>'Private'
                )
        ));

        $this->addElement('select', 'level', array(
            'label'             => 'Level',
            'class'             => 'input-large',
            'required'          => true,
            'multioptions'      => array(
                ''=>'---Select Level---',
                'Primary School'=>'Primary School',
                'Middle School'=>'Middle School'
                )
        ));

        $this->addElement('text', 'no_of_teachers', array(
            'label'             => 'No of Teachers',
            'class'             => 'input-large',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags"),
            'validators'        => array(new Zend_Validate_Digits())
        ));

            
        $this->addElement('button', 'add', array(
            'label'         => 'Save',
            'type'          => 'submit',
            'buttonType'    => 'success',
            'icon'          => 'save',
            'escape'        => false
        ));

        $this->addElement('button', 'cancel', array(
            'label'         => 'Cancel',
            'type'          => 'reset',
            'buttonType'    => 'danger',
            'icon'          => 'danger',
            'onclick'       => 'window.location="/school"',
            'escape'        => false
        ));

        $this->addDisplayGroup(
            array('add','cancel'),
            'actions',
            array(
                'disableLoadDefaultDecorators' => true,
                'decorators' => array('Actions')
            )
        );
    }
}