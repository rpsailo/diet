<?php
class Form_School extends Twitter_Bootstrap_Form_Horizontal
{
    public function init()
    {
        $this->setMethod('post')->setAttrib('class','form form-horizontal well');
        $this->_addClassNames('well');
      
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
		 $this->addElement('select', 'sub_division', array(
            'label'             => 'Sub Division',
            'class'             => 'input-large',
            'required'          => true,
            'multioptions'      => array(
                ''=>'---Select Sub Division---',
                'Aizawl East Sub Division'=>'Aizawl East Sub Division',
                'Aizawl West Sub Division'=>'Aizawl West Sub Division',
                'Aizawl South Sub Division'=>'Aizawl South Sub Division',
                'Darlawn Sub Division'=>'Darlawn Sub Division',
				'Saitual Sub Division'=>'Saitual Sub Division',
				'Lunglei North Sub Division'=>'Lunglei North Sub Division',
				'Lunglei South Sub Division'=>'Lunglei South Sub Division',
				'Lungsen Sub Division'=>'Lungsen Sub Division',
				'Hnahthial Sub Division'=>'Hnahthial Sub Division',
				'Serchhip Sub Division'=>'Serchhip Sub Division',
				'North Vanlaiphai Sub Division'=>'North Vanlaiphai Sub Division',
				'Thenzawl Sub Division'=>'Thenzawl Sub Division',
				'Saiha Block'=>'Saiha Block',
				'Tuipang Block'=>'Tuipang Block',
				'Bungtlang South Block'=>'Bungtlang South Block',
				'Sangau Block'=>'Sangau Block',
				'Chawngte Block'=>'chawngte Block',
				'Bualpui N Block'=>'Bualpui N Block',
				'Lawngtlai Block'=>'Lawngtlai Block',
				'Champhai Block'=>'Champhai Block',
				'Khawbung Block'=>'Khawbung Block',
				'Khawzawl Block'=>'Khawzawl Block',
				'Ngopa Block'=>'Ngopa Block',
				'Mamit Sub Division'=>'Mamit Sub Division',
				'Kawrthah Sub Division'=>'Kawrthah Sub Division',
				'West Phaileng Sub Division'=>'West Phaileng Sub Division',
				'Kolasib Block'=>'Kolasib Block',
				'Kawnpui Block'=>'Kawnpui Block'
                )
        ));
        
        $this->addElement('text', 'phone', array(
            'label'             => 'Contact Number',
            'class'             => 'input-medium',
            'prepend'           => '+91',
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