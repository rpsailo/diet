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
	protected $teachermodel;

	public function init()
	{
		$this->programmodel = new Model_Program();
		$this->programform = new Form_Program();
		$this->programtoolbarform = new Form_ProgramToolbar();
		$this->trainingmodel = new Model_Training();
		$this->traineeform = new Form_Trainee();
		$this->schoolmodel = new Model_School();
		$this->schoolstatisticmodel = new Model_SchoolStatistic();
		$this->teachermodel = new Model_Teacher();

		$this->_alert = $this->_helper->getHelper("FlashMessenger");
		$this->auth = Zend_Auth::getInstance();
    }

	public function indexAction()
    {
    	$this->view->schoolmodel = $this->schoolmodel;
    	$this->view->schoolstatisticmodel = $this->schoolstatisticmodel;
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
					case 11:
						$this->numberOfStudentInPrimarySchool($from, $to);
						break;

					case 12:
						$this->numberOfStudentInMiddleSchool($from, $to);
						break;
					
					case 13:
						$this->numberOfSchoolInDivBlock();
						break;
					
					case 14:
						$this->schoolsInDivBlock();
						break;

					case 15:
						$school_id = $this->_request->getParam('school_id', null);
						$this->schoolStatistics($school_id);
						break;

					case 21:
						$year = $this->_request->getParam('year', null);
						$this->numberOfTeachersInPrimarySchoolInDivBlock($year);
						break;

					case 22:
						$year = $this->_request->getParam('year', null);
						$this->numberOfTeachersInMiddleSchoolInDivBlock($year);
						break;
					
					case 23:
						$this->trainedUntrained();
						break;
					
					case 31:
						$this->allProgrammeDetail();
						break;
					
					default:
		                $this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation"></i> Invalid action. Please try again.', "status"=>"error"));
		                $this->_redirect("/report/");
						break;
				}
			}
			else
			{
                $this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation"></i> Invalid action. Please try again.', "status"=>"error"));
                $this->_redirect("/report/");
			}
		}

        $this->_alert->addMessage(array("message"=>'<i class="icon icon-exclamation"></i> Invalid action. Please try again.', "status"=>"error"));
        $this->_redirect("/report/");
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

			$statistics = $this->schoolstatisticmodel->rows(array(
				'condition' => $condition,
				'order'	=> 'year desc'
				));

			if($statistics->count())
			{
				foreach($statistics as $s)
				{
					$total_boys = $s->boys_pre + $s->boys_1 + $s->boys_2 + $s->boys_3 + $s->boys_4;
					$total_girls = $s->girls_pre +  $s->girls_1 + $s->girls_2 + $s->girls_3 + $s->girls_4;
					$total_students = $total_boys + $total_girls;

					$ratio = 'n/a';
					if($s->teachers > 0)
						$ratio = "1 : ".round($total_students/$s->teachers, 2);

					$row = array(
						++$i,
						$ps->name,
						$ps->address,
						$ps->sub_division,
						$ps->phone,
						$ps->type,
						$s->year,
						$total_students." (".$total_boys." boys, ".$total_girls." girls)",
						// $ps->no_of_teachers,
						$s->teachers,
						$ratio,
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
					'n/a',
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

			$statistics = $this->schoolstatisticmodel->rows(array(
				'condition' => $condition,
				'order'	=> 'year desc'
				));

			if($statistics->count())
			{
				foreach($statistics as $s)
				{
					$total_boys = $s->boys_5 + $s->boys_6 + $s->boys_7 + $s->boys_8;
					$total_girls = $s->girls_5 + $s->girls_6 + $s->girls_7 + $s->girls_8;
					$total_students = $total_boys + $total_girls;

					$ratio = 'n/a';
					if($s->teachers > 0)
						$ratio = "1 : ".round($total_students/$s->teachers, 2);

					$row = array(
						++$i,
						$ps->name,
						$ps->address,
						$ps->sub_division,
						$ps->phone,
						$ps->type,
						$s->year,
						$total_students." (".$total_boys." boys, ".$total_girls." girls)",
						$s->teachers,
						$ratio,
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
					'n/a',
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
	
	protected function schoolsInDivBlock()
	{
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=schools_in_division_and_block-'.date('dmy').'.csv');
		
		// create a file pointer connected to the output stream
		$output = fopen('php://output', 'w');
		
		// output the column headings
		fputcsv($output, array(
			"Sl No",
			"Name",
			"Address",
			"Sub Division",
			"Phone",
			"Year of Establishment",
			"Type",
			"Level",
			"No of Teachers (Latest)",
			"Number of Students (Latest)",
			"Teacher : Pupils (Latest)"
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

		$schools = $this->schoolmodel->all();

		$i = 0;
		foreach($schools as $s)
		{
			$statistic = $this->schoolstatisticmodel->currentStatistic($s->id);

			$ratio = "n/a";
			if($statistic && $statistic->teachers > 0)
				$ratio = "1 : ".round($statistic->students/$statistic->teachers, 2);

			$row = array(
				++$i,
				$s->name,
				$s->address,
				$s->sub_division,
				$s->phone,
				$s->year_of_establishment,
				$s->type,
				$s->level,
				$statistic->teachers,
				$statistic->students,
				$ratio
				);
			fputcsv($output, $row);
		}

		exit;
	}

	public function schoolStatistics($school_id)
	{
		$school = $this->schoolmodel->find($school_id)->current();
		$filename = str_replace(' ','_',strtolower($school->name."_".$school->level));
		$filename = str_replace(",","",$filename);
		$filename = str_replace("'","",$filename);
		$filename = urlencode($filename);

		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename='.$filename.'-'.date('dmy').'.csv');

		// create a file pointer connected to the output stream
		$output = fopen('php://output', 'w');

		fputcsv($output, array("NAME OF SCHOOL: ", strtoupper($school->name)));
		fputcsv($output, array("LEVEL: ", $school->level));
		fputcsv($output, array("ADDRESS: ", $school->address));
		fputcsv($output, array("SUB DIVISION/BLOCK: ", $school->sub_division));
		fputcsv($output, array("PHONE: ", $school->phone));
		fputcsv($output, array("YEAR OF ESTABLISHMENT: ", $school->year_of_establishment));
		fputcsv($output, array("TYPE: ", $school->type));
		
		fputcsv($output, array("","","","","","", "", "", "", "", "", "", ""));
		fputcsv($output, array("","","","","","", "", "", "", "", "", "", ""));

		if($school->level == "Primary School")
		{
			// output the column headings
			fputcsv($output, array(
				"Sl No",
				"Year",
				"Pre School (Boys)",
				"Pre School (Girls)",
				"Pre School (Total)",
				"Class I (Boys)",
				"Class I (Girls)",
				"Class I (Total)",
				"Class II (Boys)",
				"Class II (Girls)",
				"Class II (Total)",
				"Class III (Boys)",
				"Class III (Girls)",
				"Class III (Total)",
				"Class IV (Boys)",
				"Class IV (Girls)",
				"Class IV (Total)",
				"Total Student",
				"No of Teachers",
				"Teacher : Pupils",
				""
			));
		}
		else if($school->level == "Middle School")
		{
			// output the column headings
			fputcsv($output, array(
				"Sl No",
				"Year",
				"Class V (Boys)",
				"Class V (Girls)",
				"Class VI (Boys)",
				"Class VI (Girls)",
				"Class VII (Boys)",
				"Class VII (Girls)",
				"Class VIII (Boys)",
				"Class VIII (Girls)",
				"Total",
				"No of Teachers",
				"Teacher : Pupils"
			));
		}

		// Add one blank line after headings
		fputcsv($output, array("","","","","","", "", "", "", "", "", "", ""));

		$statistics = $this->schoolstatisticmodel->schoolStatistics($school->id);

		$i = 0;
		foreach($statistics as $s)
		{
			$ratio = "n/a";
			if($s->teachers > 0)
				$ratio = "1 : ".round($s->students/$s->teachers);

			if($school->level == "Primary School")
			{
				$row = array(
					++$i,
					$s->year,
					$s->boys_pre,
					$s->girls_pre,
					$s->boys_pre + $s->girls_pre,
					$s->boys_1,
					$s->girls_1,
					$s->boys_1 + $s->girls_1,
					$s->boys_2,
					$s->girls_2,
					$s->boys_2 + $s->girls_2,
					$s->boys_3,
					$s->girls_3,
					$s->boys_3 + $s->girls_3,
					$s->boys_4,
					$s->girls_4,
					$s->boys_4 + $s->girls_4,
					$s->students,
					$s->teachers,
					$ratio
				);
				fputcsv($output, $row);
			}
			else if($school->level == "Middle School")
			{
				$row = array(
					++$i,
					$s->year,
					$s->boys_1,
					$s->girls_1,
					$s->boys_1 + $s->girls_1,
					$s->boys_2,
					$s->girls_2,
					$s->boys_2 + $s->girls_2,
					$s->boys_3,
					$s->girls_3,
					$s->boys_3 + $s->girls_3,
					$s->boys_4,
					$s->girls_4,
					$s->boys_4 + $s->girls_4,
					$s->students,
					$s->teachers,
					$ratio
				);
				fputcsv($output, $row);
			}
		}

		exit;
	}

	public function allProgrammeDetail()
	{
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=all_programmes_detail-'.date('dmy').'.csv');
		
		// create a file pointer connected to the output stream
		$output = fopen('php://output', 'w');

		// output the column headings
		fputcsv($output, array(
			"Sl No",
			"Programme Name",
			"Duration",
			"Target",
			"Objectives",
			"No of Intake",
			"No of Faculties",
			"No of Teachers Trained Under Programme"
		));

		// Add one blank line after headings
		fputcsv($output, array("","","","","","", ""));

		$programs = $this->programmodel->all();

		$i = 0;
		foreach($programs as $p)
		{
			$training = $this->trainingmodel->teachersInProgram($p->id);

			$row = array(
				++$i,
				$p->name,
				$p->duration." days",
				$p->target,
				$p->objectives,
				$p->no_of_intake,
				sizeof(explode(",",$p->faculties)),
				sizeof($training->toArray())
				);
			fputcsv($output, $row);
		}

		exit;
	}

	public function numberOfTeachersInMiddleSchoolInDivBlock($year)
	{
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=teachers_in_middleschool_in_divisionblock_'.$year.'-'.date('dmy').'.csv');
		
		// create a file pointer connected to the output stream
		$output = fopen('php://output', 'w');

		// output the column headings
		fputcsv($output, array(
			"Sl No",
			"Year",
			"Sub Division / Block",
			"Government",
			"Deficit",
			"Aided",
			"Private",
			"Total"
		));

		// Add one blank line after headings
		fputcsv($output, array("","","","","","","","",""));

		$data = $this->schoolmodel->divisions();

		$i = 0;
		foreach($data as $d)
		{
			$teachers_govt = $this->schoolstatisticmodel->teachersInDivBlock($d->sub_division, $year, "Middle School", "Govt");
			$teachers_deficit = $this->schoolstatisticmodel->teachersInDivBlock($d->sub_division, $year, "Middle School", "Deficit");
			// $teachers_adhoc = $this->schoolstatisticmodel->teachersInDivBlock($d->sub_division, $year, "Middle School", "Adhoc");
			$teachers_aided = $this->schoolstatisticmodel->teachersInDivBlock($d->sub_division, $year, "Middle School", "Aided");
			$teachers_private = $this->schoolstatisticmodel->teachersInDivBlock($d->sub_division, $year, "Middle School", "Private");

			$c1 = $teachers_govt;
			$c2 = $teachers_deficit;
			$c3 = $teachers_aided;
			$c4 = $teachers_private;

			$row = array(
				++$i,
				$year,
				$d->sub_division,
				$c1,
				$c2,
				$c3,
				$c4,
				$c1+$c2+$c3+$c4
				);
			fputcsv($output, $row);
		}

		exit;
	}

	public function numberOfTeachersInPrimarySchoolInDivBlock($year)
	{
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=teachers_in_primaryschool_in_divisionblock_'.$year.'-'.date('dmy').'.csv');
		
		// create a file pointer connected to the output stream
		$output = fopen('php://output', 'w');

		// output the column headings
		fputcsv($output, array(
			"Sl No",
			"Year",
			"Sub Division / Block",
			"Government",
			"Deficit",
			"Aided",
			"Private",
			"Total"
		));

		// Add one blank line after headings
		fputcsv($output, array("","","","","","","","",""));

		$data = $this->schoolmodel->divisions();

		$i = 0;
		foreach($data as $d)
		{
			$teachers_govt = $this->schoolstatisticmodel->teachersInDivBlock($d->sub_division, $year, "Primary School", "Govt");
			$teachers_deficit = $this->schoolstatisticmodel->teachersInDivBlock($d->sub_division, $year, "Primary School", "Deficit");
			// $teachers_adhoc = $this->schoolstatisticmodel->teachersInDivBlock($d->sub_division, $year, "Primary School", "Adhoc");
			$teachers_aided = $this->schoolstatisticmodel->teachersInDivBlock($d->sub_division, $year, "Primary School", "Aided");
			$teachers_private = $this->schoolstatisticmodel->teachersInDivBlock($d->sub_division, $year, "Primary School", "Private");

			$c1 = $teachers_govt;
			$c2 = $teachers_deficit;
			$c3 = $teachers_aided;
			$c4 = $teachers_private;

			$row = array(
				++$i,
				$year,
				$d->sub_division,
				$c1,
				$c2,
				$c3,
				$c4,
				$c1+$c2+$c3+$c4
				);
			fputcsv($output, $row);
		}

		exit;
	}

	public function trainedUntrained()
	{
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=trained_untrained_ongoing-'.date('dmy').'.csv');
		
		// create a file pointer connected to the output stream
		$output = fopen('php://output', 'w');

		// output the column headings
		fputcsv($output, array(
			"School",
			"No of Teachers",
			"Trained",
			"Ongoing",
			"Untrained",
			"% of Trained",
			"% of Ongoing",
			"% of Untrained"
		));

		// Add one blank line after headings
		fputcsv($output, array("","","","","","", "", ""));

		$primary_school_teachers = $this->teachermodel->trainedUntrained('Primary School');
		$primary_school_teachers = sizeof($primary_school_teachers->toArray());

		$primary_school_teachers_trained = $this->teachermodel->trainedUntrained('Primary School', 'trained');
		$primary_school_teachers_trained = sizeof($primary_school_teachers_trained->toArray());

		$primary_school_teachers_ongoing = $this->teachermodel->trainedUntrained('Primary School', 'ongoing');
		$primary_school_teachers_ongoing = sizeof($primary_school_teachers_ongoing->toArray());

		$primary_school_teachers_untrained = $this->teachermodel->trainedUntrained('Primary School', 'untrained');
		$primary_school_teachers_untrained = sizeof($primary_school_teachers_untrained->toArray());

		if($primary_school_teachers > 0)
		{
			$percentage1 = round(($primary_school_teachers_trained/$primary_school_teachers) * 100, 2);
			$percentage2 = round(($primary_school_teachers_ongoing/$primary_school_teachers) * 100, 2);
			$percentage3 = round(($primary_school_teachers_untrained/$primary_school_teachers) * 100, 2);
		}
		else
			$percentage1 = $percentage2 = $percentage3 = 'n/a';

		fputcsv($output, array(
			'Primary',
			$primary_school_teachers,
			$primary_school_teachers_trained,
			$primary_school_teachers_ongoing,
			$primary_school_teachers_untrained,
			$percentage1,
			$percentage2,
			$percentage3
			));

		$middle_school_teachers = $this->teachermodel->trainedUntrained('Middle School');
		$middle_school_teachers = sizeof($middle_school_teachers->toArray());

		$middle_school_teachers_trained = $this->teachermodel->trainedUntrained('Middle School', 'trained');
		$middle_school_teachers_trained = sizeof($middle_school_teachers_trained->toArray());

		$middle_school_teachers_ongoing = $this->teachermodel->trainedUntrained('Middle School', 'ongoing');
		$middle_school_teachers_ongoing = sizeof($middle_school_teachers_ongoing->toArray());

		$middle_school_teachers_untrained = $this->teachermodel->trainedUntrained('Middle School', 'untrained');
		$middle_school_teachers_untrained = sizeof($middle_school_teachers_untrained->toArray());

		if($middle_school_teachers > 0)
		{
			$percentage4 = round(($middle_school_teachers_trained/$middle_school_teachers) * 100, 2);
			$percentage5 = round(($middle_school_teachers_ongoing/$middle_school_teachers) * 100, 2);
			$percentage6 = round(($middle_school_teachers_untrained/$middle_school_teachers) * 100, 2);
		}
		else
			$percentage4 = $percentage5 = $percentage6 = 'n/a';

		fputcsv($output, array(
			'Middle',
			$middle_school_teachers,
			$middle_school_teachers_trained,
			$middle_school_teachers_ongoing,
			$middle_school_teachers_untrained,
			$percentage4,
			$percentage5,
			$percentage6
			));

		exit;
	}

}


