<?php
class Form_Training extends Twitter_Bootstrap_Form_Horizontal
{
    public function init()
    {
        $this->setMethod('post');
        $this->_addClassNames('form');
        $this->_addClassNames('form-horizontal');
        // $this->_addClassNames('well');
      
        $this->addElement('select', 'program_id', array(
            'label'             => 'Progamme',
            'class'             => 'input-large',
            'required'          => true,
            'multiOptions'      => array(''=>'---Select Programme---')
        ));
        $programmodel = new Model_Program();
        $programs = $programmodel->all();
        foreach($programs as $program)
            $this->program_id->addMultiOption($program->id, $program->name);

        $this->addElement('hidden', 'teacher_id', array(
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'from', array(
            'label'             => 'From',
            'class'             => 'pickadate input-medium',
            // 'class'             => 'datepicker input-medium',
            // 'append'            =>  '<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));


        $this->addElement('text', 'to', array(
            'label'             => 'To',
            'class'             => 'pickadate input-medium',
            // 'class'             => 'datepicker input-medium',
            // 'append'            =>  '<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('select', 'status', array(
            'label'             => 'Training Status',
            'class'             => 'input-large',
            'required'          => true,
            'multiOptions'      => array(''=>'---Select Training Status---', 'Active'=>'Active', 'Completed'=>'Completed', 'Cancelled'=>'Cancelled', 'Withheld'=>'Withheld')
        ));

        $this->addElement('button', 'add', array(
            'label'         => 'Add',
            'type'          => 'submit',
            'buttonType'    => 'success',
            'icon'          => 'save',
            'escape'        => false
        ));

        $this->addElement('button', 'cancel', array(
            'label'         => 'Reset',
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