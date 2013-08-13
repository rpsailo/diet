<?php
class Form_Backup extends Twitter_Bootstrap_Form_Inline
{
    public function init()
    {
        $this->setMethod('post')->setAttrib('class','form form-inline form-toolbar');
        $this->setAction('/backup/');
        		
        $this->addElement('button', 'submit', array(
            'label'         => 'Backup Now',
            'type'          => 'submit',
            'buttonType'    => 'success',
            'icon'          => 'download-alt',
            'class'          => 'btn-lg pull-right',
            'escape'        => false
        ));

    }
}