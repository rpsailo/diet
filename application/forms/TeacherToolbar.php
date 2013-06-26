<?php
class Form_TeacherToolbar extends Twitter_Bootstrap_Form_Inline
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAction('/teacher/');
        $this->_addClassNames('form');
        $this->_addClassNames('form-inline');
        $this->_addClassNames('well');
        
        $this->addElement('select', 'limit', array(
            'label'             => 'Limit',
            'class'             => 'input-mini action-tooltip',
            'title'             => 'Results in one page',
            'multiOptions'      => array(0=>'All', 1=>10, 20=>20, 30=>30,40=>40,50=>50,60=>60,100=>100),
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        // $this->addElement('select', 'training_status', array(
        //     'label'             => 'Training Status',
        //     'class'             => 'input-medium',
        //     'multiOptions'      => array(''=>'Type - All', 'Trained'=>'Trained', 'Un-Trained'=>'Un-Trained')
        // ));

        $this->addElement('select', 'professional_qualification', array(
            'label'             => 'Professional Qualification',
            'title'             => 'Professional Qualification',
            'class'             => 'input-medium action-tooltip',
            'multiOptions'      => array(
                ''=>'All Professional Qualification', 
                'Master'=>'Master',
                'Bachelor'=>'Bachelor',
                'Diploma'=>'Diploma',
                'Nil'=>'Nil'
                )
        ));

        $this->addElement('select', 'school', array(
            'label'             => 'School',
            'class'             => 'input-large action-tooltip',
            'title'             => 'School',
            'multiOptions'      => array(''=>'All School')
        ));
        $schoolmodel = new Model_School();
        $schools = $schoolmodel->all();
        foreach ($schools as $key => $s) {
            $this->school->addMultiOption($s->id, $s->name);
        }

        $this->addElement('select', 'sex', array(
            'label'             => 'Sex',
            'class'             => 'input-small action-tooltip',
            'title'             => 'Sex',
            'multiOptions'      => array(''=>'All Sex', 'male'=>'Male', 'female'=>'Female')
        ));

        $this->addElement('select', 'tet', array(
            'label'             => 'TET',
            'class'             => 'input-small action-tooltip',
            'title'             => 'Teacher Eligibility Test',
            'multiOptions'      => array(''=>'All', 'A'=>'A', 'B'=>'B')
        ));

        $this->addElement('select', 'status', array(
            'label'             => 'Status',
            'class'             => 'input-medium action-tooltip',
            'title'             => 'Status',
            'multiOptions'      => array(''=>'All Status', 'Regular'=>'Regular', 'Non Regular'=>'Non Regular', 'Private'=>'Private')
        ));

        $this->addElement('select', 'main_subject_taught', array(
            'class'             => 'input-medium action-tooltip',
            'title'             => 'Main Subject Taught',
            'multiOptions'      => array(
                ''=>'All Subject',
                'First Language' => 'First Language',
                'English' => 'English',
                'Hindi'=>'Hindi',
                'Mathematics'=>'Mathematics',
                'EVS'=>'EVS',
                'SS'=>'SS',
                'Science'=>'Science',
                'WE'=>'WE',
                'Health and Physical Science'=>'Health & Physical Science',
                'ECCE'=>'ECCE',
                'Others'=>'Others'
                )
        ));

        $this->addElement('text', 'date_of_joining', array(
            'label'             => 'Date of Joining',
            'placeholder'       => 'Date of Joining',
            'title'             => 'Pick Date of Joining',
            'class'             => 'input-small action-tooltip datepicker',
            // 'class'             => 'datepicker input-medium',
            'append'            =>  '<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>',
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'year_of_retirement', array(
            'label'             => 'Year of Retirement',
            'placeholder'       => 'Year of Retirement',
            'title'             => 'Year of Retirement',
            'class'             => 'input-medium action-tooltip',
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'search', array(
            'label'             => 'Name',
            'class'             => 'input-medium action-tooltip',
            'placeholder'       => 'Name of Teacher',
            'title'             => 'Enter Name of Teacher',
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));
		
        $this->addElement('button', 'submit', array(
            'label'         => 'Search',
            'type'          => 'submit',
            'buttonType'    => 'success',
            'icon'          => 'search',
            'class'         => 'btn-medium action-tooltip',
            'title'         => 'Click to search and filter results.',
            'escape'        => false
        ));

    }
}