<?php
class Form_Options extends Twitter_Bootstrap_Form_Horizontal
{
    public function init()
    {
        $this->setMethod('post')->setAttrib('class','form form-horizontal');
        // $this->_addClassNames('well');
      
        $this->addElement('select', 'diet_district', array(
            'label'             => 'District',
            'class'             => '',
            'required'          => true
        ));
        $subdivisionmodel = new Model_SubDivisionBlock();
        $this->diet_district->addMultiOptions($subdivisionmodel->districts());

        $this->addElement('text', 'colors', array(
            'label'             => 'Colors',
            'class'             => 'input-xlarge',
            'description'       => 'Hex Colors Stack: Color1 (Navigation bar background), Color2 (Link normal), Color3 (Link hover and active), Color4 (Dropdown menu hover). Default: #D0DF80,#B2C25C,#BCCE5E,#9CAC48'
        ));

        $this->addElement('button', 'save', array(
            'label'         => 'Save',
            'type'          => 'submit',
            'buttonType'    => 'success',
            'icon'          => 'save',
            'escape'        => false
        ));

        $this->addDisplayGroup(
            array('save'),
            'actions',
            array(
                'disableLoadDefaultDecorators' => true,
                'decorators' => array('Actions')
            )
        );
    }
}