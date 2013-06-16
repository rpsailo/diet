<?php
class Form_Program extends Twitter_Bootstrap_Form_Horizontal
{
    public function init()
    {
        $this->setMethod('post')->setAttrib('class','form form-horizontal well');
        $this->_addClassNames('well');
      
        $this->addElement('text', 'programname', array(
            'label'             => 'Program Name',
            'class'             => 'input-xxlarge',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'duration', array(
            'label'             => 'Duration',
            'class'             => 'input-large',
            'append'            => 'Days/Weeks',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'target', array(
            'label'             => 'Target',
            'class'             => 'input-xxlarge',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('textarea', 'objectives', array(
            'label'             => 'Objectives',
            'class'             => 'input-xxlarge',
            'rows'              => 4,
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