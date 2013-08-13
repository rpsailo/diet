<?php

class ReportController extends Zend_Controller_Action
{
	protected $auth;

	protected $programmodel;
	protected $programform;
	protected $programtoolbarform;
	protected $trainingmodel;
	protected $traineeform;
	protected $schoolmodel;
	protected $schoolstatisticmodel;

	public function init()
	{
		$this->programmodel = new Model_Program();
		$this->programform = new Form_Program();
		$this->programtoolbarform = new Form_ProgramToolbar();
		$this->trainingmodel = new Model_Training();
		$this->traineeform = new Form_Trainee();
		$this->schoolmodel = new Model_School();
		$this->schoolstatisticmodel = new Model_SchoolStatistic();

		$this->_alert = $this->_helper->getHelper("FlashMessenger");
		$this->auth = Zend_Auth::getInstance();
    }

	public function indexAction()
    {
	}

	public function generateAction()
	{
		if($this->_request->isPost())
		{
			$export = $this->_request->getParam('export', 0);
			$from = $this->_request->getParam('from', null);
			$to = $this->_request->getParam('to', null);
			
			if($export != 0)
			{
				switch ($export) {
					case 1:
						$this->numberOfStudentInPrimarySchool($from, $to);
						break;

					case 2:
						$this->numberOfStudentInMiddleSchool($from, $to);
						break;
					
					case 3:
						$this->numberOfSchoolInDivBlock();
						break;
					
					case 4:
						// Teacher pupils ratio school wise
						break;
					
					default:
						# code...
						break;
				}
			}
			else
				exit('Invalid action');
		}
	}

	protected function numberOfStudentInPrimarySchool($from, $to)
	{
		// Number of student in primary school
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=number_of_student_in_primary_school-'.date('dmy').'.csv');
		
		// create a file pointer connected to the output stream
		$output = fopen('php://output', 'w');

		// output the column headings
		fputcsv($output, array(
		"Sl No",
		"School Name",
		"School Address",
		"Sub Division",
		"Phone",
		"Type",
		"Statistic Year",
		"Number of Students",
		"No of Teachers",
		"Teacher Pupils Ratio"
		));

		// Add one blank line after headings
		fputcsv($output, array(
		"",
		"",
		"",
		"",
		"",
		"",
		"",
		"",
		"",
		""
		));


		$primary_schools = $this->schoolmodel->getByLevel('Primary School');

		$year_condition = array();

		if($from != null)
			$year_condition[] = "`year` >= ".$from;

		if($to != null)
			$year_condition[] = "`year` <= ".$to;

		$i = 0;
		
		foreach($primary_schools as $ps)
		{
			$condition = $year_condition;
			$condition[] = 'school_id = '.$ps->id;

			$students = $this->schoolstatisticmodel->rows(array(
				'condition' => $condition,
				'order'	=> 'year desc'
				));

			if($students->count())
			{
				foreach($students as $s)
				{
					$total_boys = $s->boys_1 + $s->boys_2 + $s->boys_3 + $s->boys_4;
					$total_girls = $s->girls_1 + $s->girls_2 + $s->girls_3 + $s->girls_4;
					$total_students = $total_boys + $total_girls;
					$row = array(
						++$i,
						$ps->name,
						$ps->address,
						$ps->sub_division,
						$ps->phone,
						$ps->type,
						$s->year,
						$total_students." (".$total_boys." boys, ".$total_girls." girls)",
						$ps->no_of_teachers,
						"1 : ".round($total_students/$ps->no_of_teachers, 2),
						);
					fputcsv($output, $row);
				}
			}
			else
			{
				$row = array(
					++$i,
					$ps->name,
					$ps->address,
					$ps->sub_division,
					$ps->phone,
					$ps->type,
					'n/a',
					0,
					$ps->no_of_teachers,
					"n/a"
					);
				fputcsv($output, $row);
			}
		}

		exit;
	}

	protected function numberOfStudentInMiddleSchool($from, $to)
	{
		// Number of student in middle school
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=number_of_student_in_middle_school-'.date('dmy').'.csv');
		
		// create a file pointer connected to the output stream
		$output = fopen('php://output', 'w');

		// output the column headings
		fputcsv($output, array(
		"Sl No",
		"School Name",
		"School Address",
		"Sub Division",
		"Phone",
		"Type",
		"Statistic Year",
		"Number of Students",
		"No of Teachers",
		"Teacher Pupils Ratio"
		));

		// Add one blank line after headings
		fputcsv($output, array(
		"",
		"",
		"",
		"",
		"",
		"",
		"",
		"",
		"",
		""
		));


		$middle_schools = $this->schoolmodel->getByLevel('Middle School');

		$year_condition = array();

		if($from != null)
			$year_condition[] = "`year` >= ".$from;

		if($to != null)
			$year_condition[] = "`year` <= ".$to;

		$i = 0;
		
		foreach($middle_schools as $ps)
		{
			$condition = $year_condition;
			$condition[] = 'school_id = '.$ps->id;

			$students = $this->schoolstatisticmodel->rows(array(
				'condition' => $condition,
				'order'	=> 'year desc'
				));

			if($students->count())
			{
				foreach($students as $s)
				{
					$total_boys = $s->boys_5 + $s->boys_6 + $s->boys_7 + $s->boys_8;
					$total_girls = $s->girls_5 + $s->girls_6 + $s->girls_7 + $s->girls_8;
					$total_students = $total_boys + $total_girls;
					$row = array(
						++$i,
						$ps->name,
						$ps->address,
						$ps->sub_division,
						$ps->phone,
						$ps->type,
						$s->year,
						$total_students." (".$total_boys." boys, ".$total_girls." girls)",
						$ps->no_of_teachers,
						"1 : ".round($total_students/$ps->no_of_teachers, 2),
						);
					fputcsv($output, $row);
				}
			}
			else
			{
				$row = array(
					++$i,
					$ps->name,
					$ps->address,
					$ps->sub_division,
					$ps->phone,
					$ps->type,
					'n/a',
					0,
					$ps->no_of_teachers,
					"n/a"
					);
				fputcsv($output, $row);
			}
		}

		exit;
	}

	protected function numberOfSchoolInDivBlock()
	{
		// Number of school in sub division and block
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=number_of_school_in_division_and_block-'.date('dmy').'.csv');
		
		// create a file pointer connected to the output stream
		$output = fopen('php://output', 'w');

		// output the column headings
		fputcsv($output, array(
		"Sl No",
		"Division/Block",
		"Government (Middle School)",
		"Government (Primary School)",
		"Deficit (Middle School)",
		"Deficit (Primary School)",
		"Adhoc Aided (Middle School)",
		"Adhoc Aided (Primary School)",
		"Private (Middle School)",
		"Private (Primary School)",
		"Total (Middle School)",
		"Total (Primary School)",
		"Total School"
		));

		// Add one blank line after headings
		fputcsv($output, array(
		"",
		"",
		"",
		"",
		"",
		"",
		"",
		"",
		"",
		"",
		""
		));

		$divisions = $this->schoolmodel->divisions();

		$col1_total = $col2_total = $col3_total = $col4_total = $col5_total = $col6_total = $col7_total = $col8_total = $col9_total = $col10_total = 0;

		foreach($divisions as $i=>$d)
		{
			$total_ms = 0;
			$total_ps = 0;
			$ms_government = $this->schoolmodel->rows(array('condition'=>array("type = 'Govt'", "level = 'Middle School'", "sub_division = '".$d->sub_division."'")));
			if($ms_government)
			{
				$total_ms += $ms_government = sizeof($ms_government->toArray());
				$col1_total += $ms_government;
			}

			$ps_government = $this->schoolmodel->rows(array('condition'=>array("type = 'Govt'", "level = 'Primary School'", "sub_division = '".$d->sub_division."'")));
			if($ps_government)
			{
				$total_ps += $ps_government = sizeof($ps_government->toArray());
				$col2_total += $ps_government;
			}

			$ms_deficit = $this->schoolmodel->rows(array('condition'=>array("type='Deficit'", "level = 'Middle School'", "sub_division = '".$d->sub_division."'")));
			if($ms_deficit)
			{
				$total_ms += $ms_deficit = sizeof($ms_deficit->toArray());
				$col3_total += $ms_deficit;
			}

			$ps_deficit = $this->schoolmodel->rows(array('condition'=>array("type='Deficit'", "level = 'Primary School'", "sub_division = '".$d->sub_division."'")));
			if($ps_deficit)
			{
				$total_ps += $ps_deficit = sizeof($ps_deficit->toArray());
				$col4_total += $ps_deficit;
			}

			$ms_adhoc = $this->schoolmodel->rows(array('condition'=>array("type='Adhoc Aided'", "level = 'Middle School'", "sub_division = '".$d->sub_division."'")));
			if($ms_adhoc)
			{
				$total_ms += $ms_adhoc = sizeof($ms_adhoc->toArray());
				$col5_total += $ms_adhoc;
			}

			$ps_adhoc = $this->schoolmodel->rows(array('condition'=>array("type='Adhoc Aided'", "level = 'Primary School'", "sub_division = '".$d->sub_division."'")));
			if($ps_adhoc)
			{
				$total_ps += $ps_adhoc = sizeof($ps_adhoc->toArray());
				$col6_total += $ps_adhoc;
			}

			$ms_private = $this->schoolmodel->rows(array('condition'=>array("type='Private'", "level = 'Middle School'", "sub_division = '".$d->sub_division."'")));
			if($ms_private)
			{
				$total_ms += $ms_private = sizeof($ms_private->toArray());
				$col7_total += $ms_private;
			}

			$ps_private = $this->schoolmodel->rows(array('condition'=>array("type='Private'", "level = 'Primary School'", "sub_division = '".$d->sub_division."'")));
			if($ps_private)
			{
				$total_ps += $ps_private = sizeof($ps_private->toArray());
				$col8_total += $ps_private;
			}

			$col9_total += $total_ms;
			$col10_total += $total_ps;

			$row = array(
				++$i,
				$d->sub_division,
				$ms_government,
				$ps_government,
				$ms_deficit,
				$ps_deficit,
				$ms_adhoc,
				$ps_adhoc,
				$ms_private,
				$ps_private,
				$total_ms,
				$total_ps,
				($total_ms+$total_ps)
				);
			fputcsv($output, $row);	
		}
		$row = array('','','','','','','','','','','','','');
		fputcsv($output, $row);
		$row = array(
			'',
			'Total',
			$col1_total,
			$col2_total,
			$col3_total,
			$col4_total,
			$col5_total,
			$col6_total,
			$col7_total,
			$col8_total,
			$col9_total,
			$col10_total,
			($col9_total + $col10_total)
			);
		fputcsv($output, $row);
		exit;
	}
}


