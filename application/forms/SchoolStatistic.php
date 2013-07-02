<?php
class Form_SchoolStatistic extends Twitter_Bootstrap_Form_Horizontal
{
    public function init()
    {
        $this->setMethod('post')->setAttrib('class','form form-horizontal well');
        $this->_addClassNames('well');
      
        $this->addElement('select', 'class', array(
            'label'             => 'Class',
            'class'             => 'input-medium',
            'required'          => true,
            'multioptions'      => array(
                '1' => 'Class I',
                '2' => 'Class II',
                '3' => 'Class III',
                '4' => 'Class IV',
                '5' => 'Class V',
                '6' => 'Class VI',
                '7' => 'Class VII',
                '8' => 'Class VIII'
                )
        ));

        $this->addElement('text', 'year', array(
            'label'             => 'Year',
            'class'             => 'input-medium',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags"),
            'validators'        => array(new Zend_Validate_Digits())
        ));

        $this->addElement('text', 'boys', array(
            'label'             => 'Boys',
            'class'             => 'input-medium',
            'required'          => true,
            'filters'           => array(new Zend_Filter_StringTrim(), "StripTags"),
            'validators'        => array(new Zend_Validate_Digits())
        ));

        $this->addElement('text', 'girls', array(
            'label'             => 'Girls',
            'class'             => 'input-medium',
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