<?php
class Form_Program extends Twitter_Bootstrap_Form_Horizontal
{
    public function init()
    {
        $this->setMethod('post');
        $this->_addClassNames('form');
        $this->_addClassNames('form-horizontal');
        $this->_addClassNames('well');
      
        $this->addElement('text', 'name', array(
            'label'             => 'Program Name',
            'class'             => 'input-xlarge',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'duration', array(
            'label'             => 'Duration',
            'class'             => 'input-large',
            'append'            => 'Days',
            'description'       => 'Enter the number of days.',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags"),
            'validators'        => array(new Zend_Validate_Digits())
        ));

        $this->addElement('text', 'no_of_intake', array(
            'label'             => 'No of Intake',
            'class'             => 'input-large',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags"),
            'validators'        => array(new Zend_Validate_Digits())
        ));

        $this->addElement('text', 'target', array(
            'label'             => 'Target',
            'class'             => 'input-xlarge',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('textarea', 'objectives', array(
            'label'             => 'Objectives',
            'class'             => 'input-xxlarge',
            'rows'              => 5,
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('multiselect', 'faculties', array(
            'label'             => 'Faculties',
            'class'             => 'input-xxlarge',
            'multiple'          => 'multiple',
            'style'             => 'height:200px',
            'description'       => 'Select faculties for this programme. Press and hold CTRL key to select more than one faculty.',
            'required'          => true
        ));
        $usermodel = new Model_User();
        $faculties = $usermodel->faculties();
		$faculty_data = array();
        foreach ($faculties as $key => $f)
			$faculty_data[$f->id] = $f->name." - ".$f->educational_qualification;
        $this->faculties->setMultiOptions($faculty_data);
        	
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