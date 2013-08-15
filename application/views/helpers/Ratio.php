<?php
class Zend_View_Helper_Ratio
{
	public function Ratio($year = null)
	{
		if($year == null)
			$year = date('Y');

		$schoolstatisticmodel = new Model_SchoolStatistic();
		$schoolmodel = new Model_School();
		$schools = $schoolstatisticmodel->rows(array(
			'field' => array(new Zend_Db_Expr('DISTINCT(`school_id`)')),
			'condition' => array(
				'year='.$year
				)
			));

		$teacher = 0;
		$pupils = 0;

		foreach($schools as $s)
		{
			$school = $schoolmodel->find($s->school_id)->current();
			$statistic = $schoolstatisticmodel->row(array(
				'condition' => array(
					'year = '.$year,
					'school_id = '.$s->school_id
					),
				'order' => 'created_at desc'
				));

			$teacher += $school->no_of_teachers;
			$pupils += ($statistic->boys_1 + $statistic->boys_2 + $statistic->boys_3 + $statistic->boys_4 + $statistic->boys_5 + $statistic->boys_6 + $statistic->boys_7 + $statistic->boys_8 + $statistic->girls_1 + $statistic->girls_2 + $statistic->girls_3 + $statistic->girls_4 + $statistic->girls_5 + $statistic->girls_6 + $statistic->girls_7 + $statistic->girls_8);
		}

		if($teacher > 0)
			return "1 : ".round($pupils/$teacher);
		else
			return 'n/a';
	}
}