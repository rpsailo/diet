<?php
class Form_TeacherToolbar extends Twitter_Bootstrap_Form_Inline
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAction('/teacher/');
        $this->_addClassNames('form');
        $this->_addClassNames('form-inline');
        $this->_addClassNames('form-toolbar');
        $this->_addClassNames('teacher-toolbar');
        // $this->_addClassNames('well');
        
        $this->addElement('select', 'limit', array(
            'label'             => 'Limit',
            'class'             => 'input-mini action-tooltip pull-left',
            'title'             => 'Results in one page',
            'multiOptions'      => array(0=>'All', 10=>10, 20=>20, 30=>30,40=>40,50=>50,60=>60,100=>100),
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $subdivisionmodel = new Model_SubDivisionBlock();
        $this->addElement('select', 'district', array(
            'label'             => 'District',
            'title'             => 'District',
            'class'             => 'input-medium action-tooltip pull-left',
            'multiOptions'      => array(''=>'All District'),
            'onchange'           => 'getByDistrict(this.value);'
        ));
        $this->district->addMultiOptions($subdivisionmodel->districts());

        $this->addElement('select', 'sub_division', array(
            'title'             => 'Sub Division / Block',
            'label'             => 'Sub Division / Block',
            'class'             => 'input-medium action-tooltip pull-left',
            'multiOptions'      => array(''=>'All Sub Division / Block')
        ));
        foreach($subdivisionmodel->all() as $s)
            $this->sub_division->addMultiOption($s->name, $s->name);

        $this->addElement('select', 'school', array(
            'label'             => 'School',
            'class'             => 'input-large action-tooltip pull-left',
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
            'class'             => 'input-small action-tooltip pull-left',
            'title'             => 'Sex',
            'multiOptions'      => array(''=>'All Sex', 'male'=>'Male', 'female'=>'Female')
        ));

        $this->addElement('select', 'professional_qualification', array(
            'label'             => 'Professional Qualification',
            'title'             => 'Professional Qualification',
            'class'             => 'input-medium action-tooltip pull-left',
            'multiOptions'      => array(
                ''=>'All Professional Qualification', 
                'Master'=>'Master',
                'Bachelor'=>'Bachelor',
                'Diploma'=>'Diploma',
                'Ongoing'=>'Ongoing',
                'Nil'=>'Nil'
                )
        ));

        $this->addElement('select', 'tet', array(
            'label'             => 'TET',
            'class'             => 'input-small action-tooltip pull-left',
            'title'             => 'Teacher Eligibility Test',
            'multiOptions'      => array(''=>'All', 'A'=>'A', 'B'=>'B', 'None'=>'None')
        ));

        $this->addElement('select', 'status', array(
            'label'             => 'Status',
            'class'             => 'input-medium action-tooltip pull-left',
            'title'             => 'Status',
            'multiOptions'      => array(''=>'All Status', 'Regular'=>'Regular', 'Non Regular'=>'Non Regular', 'Private'=>'Private')
        ));

        $this->addElement('select', 'main_subject_taught', array(
            'class'             => 'input-medium action-tooltip pull-left',
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
                'Health and Physical Education'=>'Health & Physical Education',
                'ECCE'=>'ECCE',
                'Others'=>'Others'
                )
        ));

        $this->addElement('text', 'date_of_joining', array(
            'label'             => 'Date of Joining',
            'placeholder'       => 'Date of Joining',
            'title'             => 'Pick Date of Joining',
            'class'             => 'input-small action-tooltip pickadate pull-left',
            // 'class'             => 'datepicker input-medium',
            'append'            =>  '<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>',
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'year_of_retirement', array(
            'label'             => 'Year of Retirement',
            'placeholder'       => 'Year of Retirement',
            'title'             => 'Year of Retirement',
            'class'             => 'input-medium action-tooltip pull-left',
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('text', 'no_of_training', array(
            'label'             => 'Number of Training Attended',
            'placeholder'       => 'No. of Training',
            'title'             => 'Number of Training Attended. Try < 2, > 2, >= 2, <= 2, between 2 and 4',
            'class'             => 'input-medium action-tooltip pull-left',
            'filters'           => array( new Zend_Filter_StringTrim()),
            'validators'        => array(new Zend_Validate_Digits())
        ));

        $this->addElement('text', 'search', array(
            'label'             => 'Name',
            'class'             => 'input-medium action-tooltip pull-left',
            'placeholder'       => 'Name of Teacher',
            'title'             => 'Enter Name of Teacher',
            'filters'           => array( new Zend_Filter_StringTrim(), "StripTags")
        ));

        $this->addElement('hidden', 'as', array(
            'value'         => 0
        ));
		
        $this->addElement('button', 'advanced', array(
            'label'         => 'Show More Filter',
            'type'          => 'button',
            'buttonType'    => 'primary',
            // 'icon'          => 'search',
            'class'         => 'btn-medium   pull-left',
            'escape'        => false
        ));

        $this->addElement('button', 'submit', array(
            'label'         => 'Search',
            'type'          => 'submit',
            'buttonType'    => 'success',
            'icon'          => 'search',
            'class'         => 'btn-medium action-tooltip pull-left',
            'title'         => 'Click to search and filter results.',
            'escape'        => false
        ));

    }
}