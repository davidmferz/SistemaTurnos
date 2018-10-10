<?php
require_once dirname(__FILE__) . '/pjCalendar.component.php';
class pjHBCalendar extends pjCalendar
{
	private $monthStatus = array();
	
	public function __construct()
	{
		parent::__construct();
		
		$this->classTable = "hbCalendarTable";
		$this->classWeekDay = "hbCalendarWeekDay";
		$this->classMonth = "hbCalendarMonth";
		$this->classMonthOuter = "hbCalendarMonthOuter";
		$this->classMonthInner = "hbCalendarMonthInner";
		$this->classMonthPrev = "hbCalendarMonthPrev";
		$this->classMonthNext = "hbCalendarMonthNext";
		$this->classFully = "hbCalendarFully";
		$this->classCalendar = "hbCalendarDate";
		$this->classCell = "hbCalendarCell";
		$this->classToday = "hbCalendarToday";
		$this->classSelected = "hbCalendarSelected";
		$this->classEmpty = "hbCalendarEmpty";
		$this->classWeekNum = "hbCalendarWeekNum";
		$this->classPast = "hbCalendarPast";
		$this->classDisabled = "hbCalendarDisabled";
		$this->classDateOuter = "hbCalendarDateOuter";
		$this->classDateInner = "hbCalendarDateInner";
		
		$this->showNextMonthDays = true;
		
		$this->showPrevMonthDays = true;
	}
	
	public function getMonthView($month, $year)
    {
        return $this->getMonthHTML($month, $year, 1);
    }
    
	public function getCalendarLink($month, $year)
	{
		return array('href' => '#', 'onclick' => '', 'class' => 'hbCalendarLinkMonth');
	}
	
	public function set($key, $value)
	{
		if (in_array($key, array('calendarId', 'showMonthName', 'options', 'monthStatus', 'minDate')))
		{
			$this->$key = $value;
		}

		return $this;
	}
	
	private function getClass($status)
	{
		$class = $this->classCalendar;
		
		switch ($status)
		{
			case 'partly':
				$class = $this->classCalendar ." ". $this->classPartly;
				break;
			case 'fully':
				$class = $this->classFully;
				break;
			case 'available':
			default:
				$class = $this->classCalendar;
				break;
		}
		
		return $class;
	}
	
	protected function onBeforeShow($timestamp, $iso, $today, $current, $year, $month, $d)
    {
    	$date = getdate($timestamp);
    	$weekday = strtolower($date['weekday']);
    	
    	if (!is_null($this->minDate) && $timestamp < $this->minDate)
    	{
    		$class = $this->classDisabled;
    	} elseif ($timestamp < $today[0]) {
			$class = $this->classPast;
		} else {
			$class = $this->getClass(@$this->monthStatus[$iso]['text']);
		}
		
		if ($class == $this->classCalendar)
		{
			if ($year == $today["year"] && $month == $today["mon"] && $d == $today["mday"])
			{
				$class .= " " . $this->classToday;
			}
			if ($current[0] == $timestamp)
			{
				$class .= " " . $this->classSelected;
			}
		}

		return array(
			'class' => $class,
			'html' => '<div class="'.$this->classDateOuter.'"><p class="'.$this->classDateInner.'">'.$d.'</p></div>'
		);
    }
    
    public function getHeader($month, $year)
    {
    	$prev_month = $month - 1 > 0 ? $month - 1 : 12;
		$prev_year = $month - 1 > 0 ? $year : $year - 1;
		$next_month = $month + 1 < 13 ? $month + 1 : 1;
		$next_year = $month + 1 < 13 ? $year : $year + 1;
    	$months = __('months', true);
    	$html = '<div class="hbTopCalName">'. @$months[(int) $month] .' '. $year .'</div>'.
				'<div class="hbTopCalNav"><a href="#" class="hbSelectorGetCal" data-month="'.$prev_month.'" data-year="'.$prev_year.'" title="'. __('front_prev_month', true, true) .'"></a><a href="#" class="hbSelectorGetCal" data-month="'. $next_month .'" data-year="'. $next_year .'" title="'. __('front_next_month', true, true) .'"></a></div>';
		
		return $html;
	}
}
?>