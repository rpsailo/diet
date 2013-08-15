<?php
class Form_SchoolStatistic extends Twitter_Bootstrap_Form_Horizontal
{
    public function init()
    {
        $this->setMethod('post')->setAttrib('class','form form-horizontal');
        // $this->_addClassNames('well');
      
        $this->addElement('select', 'school_level', array(
            'label'             => 'Level',
            'class'             => 'input-xlarge',
            'value'             => 'Primary School',
            'multiOptions'      => array(
                'Primary School'=>'Primary School',
                'Middle School'=>'Middle School'
                ),
            'onchange'           => 'getSchoolByLevel(this.value);'
        ));
      
        $this->addElement('select', 'school_id', array(
            'label'             => 'School',
            'class'             => 'input-xlarge',
            'required'          => true,
            'multiOptions'      => array(''=>'---Select School---')
        ));
        $schoolmodel = new Model_School();
        $schools = $schoolmodel->all();
        foreach($schools as $s)
            $this->school_id->addMultiOption($s->id, $s->name);

        $this->addElement('text', 'year', array(
            'label'             => 'Year',
            'class'             => 'input-medium',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags"),
            'validators'        => array(new Zend_Validate_Digits())
        ));

        $this->addElement('text', 'teachers', array(
            'label'             => 'No of Teachers',
            'class'             => 'input-large',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags"),
            'validators'        => array(new Zend_Validate_Digits())
        ));
            
        $this->addElement('text', 'boys_1', array(
            'class'             => 'input-mini boys',
            'label'            => 'Class I: Boys',
            'filters'           => array(new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'girls_1', array(
            'label'             => 'Girls',
            'class'             => 'input-mini girls',
            // 'append'            => 'Girls',
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'boys_2', array(
            'class'             => 'input-mini boys',
            'label'            => 'Class II: Boys',
            'filters'           => array(new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'girls_2', array(
            'label'             => 'Girls',
            'class'             => 'input-mini girls',
            // 'append'            => 'Girls',
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'boys_3', array(
            'class'             => 'input-mini boys',
            'label'            => 'Class III: Boys',
            'filters'           => array(new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'girls_3', array(
            'label'             => 'Girls',
            'class'             => 'input-mini girls',
            // 'append'            => 'Girls',
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'boys_4', array(
            'class'             => 'input-mini boys',
            'label'            => 'Class IV: Boys',
            'filters'           => array(new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'girls_4', array(
            'label'             => 'Girls',
            'class'             => 'input-mini girls',
            // 'append'            => 'Girls',
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'boys_5', array(
            'class'             => 'input-mini boys',
            'label'            => 'Class V: Boys',
            'filters'           => array(new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'girls_5', array(
            'label'             => 'Girls',
            'class'             => 'input-mini girls',
            // 'append'            => 'Girls',
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'boys_6', array(
            'class'             => 'input-mini boys',
            'label'            => 'Class VI: Boys',
            'filters'           => array(new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'girls_6', array(
            'label'             => 'Girls',
            'class'             => 'input-mini girls',
            // 'append'            => 'Girls',
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'boys_7', array(
            'class'             => 'input-mini boys',
            'label'            => 'Class VII: Boys',
            'filters'           => array(new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'girls_7', array(
            'label'             => 'Girls',
            'class'             => 'input-mini girls',
            // 'append'            => 'Girls',
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'boys_8', array(
            'class'             => 'input-mini boys',
            'label'            => 'Class VIII: Boys',
            'filters'           => array(new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'girls_8', array(
            'label'             => 'Girls',
            'class'             => 'input-mini girls',
            // 'append'            => 'Girls',
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