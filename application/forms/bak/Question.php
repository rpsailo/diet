<?php
class Form_Question extends Twitter_Bootstrap_Form_Horizontal
{
    public function init()
    {
        $this->setMethod('post')->setAttrib('class','form form-horizontal well')->setAttrib('id','question_form');
        $this->_addClassNames('well');
		
        $this->addElement('select', 'question_type', array(
            'label'             => 'Question Type',
            'class'             => 'input-large',
            'multioptions'      => array('multiple'=>'Multiple','true_false'=>'True Or False')
        ));
        
        $exam_model = new Model_Exam();
        $exams = array();
        foreach($exam_model->with_course() as $row)
            $exams[$row->exam_id] = $row->exam_name.' ('.$row->course_name.') @ '.$row->start_at;
    		
		$this->addElement('select', 'exam_id', array(
            'label'             => 'Exam',
            'class'             => 'input-xxlarge',
            'multioptions'      => $exams
        ));
		
        $this->addElement('textarea', 'question', array(
            'label'             => 'Question',
            'class'             => 'input-xxlarge',
            'required'          => true,
            'attribs'          => array('rows'=>3),
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));
		
        $this->addElement('text', 'opt1', array(
            'label'             => 'Option 1',
            'class'             => 'input-xxlarge',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'opt2', array(
            'label'             => 'Option 2',
            'class'             => 'input-xxlarge',
            'required'          => true,
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));
	
        $this->addElement('text', 'opt3', array(
            'label'             => 'Option 3',
            'class'             => 'input-xxlarge',
			'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));
		
        $this->addElement('text', 'opt4', array(
            'label'             => 'Option 4',
            'class'             => 'input-xxlarge',
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));
		
		$this->addElement('select', 'ans', array(
            'label'             => 'Answer',
            'class'             => 'input-medium',
			'required'          => true,
			'multioptions'      => array('opt1'=>'Option 1','opt2'=>'Option 2', 'opt3'=>'Option 3', 'opt4'=>'Option 4'),
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