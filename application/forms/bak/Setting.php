<?php
class Form_Setting extends Twitter_Bootstrap_Form_Horizontal
{
    public function init()
    {
        $this->setMethod('post')->setAttrib('class','form form-horizontal well')->setAttrib('id','setting_form');
        $this->_addClassNames('well');

        $this->addElement('text', 'correct_mark', array(
            'label'             => '1 Correct Answer',
            'class'             => 'input-mini',
            'required'          => true,
            'prepend'             => '+',
            'append'             => 'mark(s)',
            'value'             => 1,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'incorrect_mark', array(
            'label'             => '1 Incorrect Answer',
            'class'             => 'input-mini',
            'prepend'             => '-',
            'append'             => 'mark(s)',
            'required'          => true,
            'value'             => 0,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'gradea', array(
            'label'             => 'Grade A',
            'class'             => 'input-mini',
            'prepend'             => 'Above',
            'append'             => '%',
            'required'          => true,
            'value'             => 80,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'gradebplus', array(
            'label'             => 'Grade B+',
            'class'             => 'input-mini',
            'prepend'             => 'Above',
            'append'             => '%',
            'required'          => true,
            'value'             => 70,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'gradeb', array(
            'label'             => 'Grade B',
            'class'             => 'input-mini',
            'prepend'             => 'Above',
            'append'             => '%',
            'required'          => true,
            'value'             => 60,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'gradecplus', array(
            'label'             => 'Grade C+',
            'class'             => 'input-mini',
            'prepend'             => 'Above',
            'append'             => '%',
            'required'          => true,
            'value'             => 50,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'gradec', array(
            'label'             => 'Grade C',
            'class'             => 'input-mini',
            'prepend'             => 'Above',
            'append'             => '%',
            'required'          => true,
            'value'             => 40,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('button', 'submit', array(
            'label'         => 'Save',
            'type'          => 'submit',
            'buttonType'    => 'success',
            'icon'          => 'save',
            'escape'        => false
        ));

        $this->addDisplayGroup(
            array('submit'),
            'actions',
            array(
                'disableLoadDefaultDecorators' => true,
                'decorators' => array('Actions')
            )
        );
    }
}