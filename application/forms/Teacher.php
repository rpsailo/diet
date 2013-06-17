<?php
class Form_Teacher extends Twitter_Bootstrap_Form_Horizontal
{
    public function init()
    {
        $this->setMethod('post')->setAttrib('class','form form-horizontal well');
        $this->_addClassNames('well');
      
        $this->addElement('select', 'school_id', array(
            'label'             => 'School',
            'class'             => 'input-large',
            'required'          => true,
            'multiOptions'      => array(''=>'---Select School---')
        ));
        $schoolmodel = new Model_School();
        $schools = $schoolmodel->all();
        foreach($schools as $school)
            $this->school_id->addMultiOption($school->id, $school->name);

        $this->addElement('text', 'name', array(
            'label'             => 'Name',
            'class'             => 'input-xlarge',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('select', 'sex', array(
            'label'             => 'Sex',
            'class'             => 'input-large',
            'required'          => true,
            'multiOptions'      => array(''=>'---Select Sex---', 'male'=>'Male','female'=>'Female')
        ));

        $this->addElement('text', 'dob', array(
            'label'             => 'Date of Birth',
            'class'             => 'datepicker input-medium',
            'append'            =>  '<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));


        $this->addElement('text', 'date_of_joining', array(
            'label'             => 'Date of Joining',
            'class'             => 'datepicker input-medium',
            'append'            =>  '<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));


        $this->addElement('text', 'educational_qualification', array(
            'label'             => 'Educational Qualification',
            'class'             => 'input-xlarge',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));


        $this->addElement('text', 'specialization', array(
            'label'             => 'Specialization',
            'class'             => 'input-xlarge',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('select', 'training_status', array(
            'label'             => 'Training Status',
            'class'             => 'input-large',
            'required'          => true,
            'multiOptions'      => array(''=>'---Select Training Status---', 'Trained'=>'Trained','Un-Trained'=>'Un-Trained')
        ));
        

        $this->addElement('text', 'year_of_retirement', array(
            'label'             => 'Year of Retirement',
            'class'             => 'input-medium',
            'append'            => 'YYYY',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));


        $this->addElement('file', 'picture', array(
            'label'             => 'Photo',
            'class'             => 'input-large',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
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