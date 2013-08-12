<?php
class Form_Backup extends Twitter_Bootstrap_Form_Inline
{
    public function init()
    {
        $this->setMethod('post')->setAttrib('class','form form-inline');
        $this->setAction('/backup/');
        		
        $this->addElement('button', 'submit', array(
            'label'         => 'Create Backup',
            'type'          => 'submit',
            'buttonType'    => 'primary',
            'icon'          => 'save',
            'escape'        => false
        ));

    }
}