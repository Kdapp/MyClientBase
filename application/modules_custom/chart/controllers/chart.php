<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Chart extends Admin_Controller {

	function __construct() {
		parent::__construct();
		
		if (!$this->mdl_mcb_modules->check_enable('chart')) {

			redirect('dashboard');

		}

		$this->load->model('mdl_chart');
	}

	function index() {
		$this->load->view('index');
	}
	
	function install() {
	}
	
	function uninstall() {
	}
	
	function get_chart_data() {
		header('Content-type: application/json');
		
		switch($this->input->get('type')) {
			case 'day':
				echo $this->get_chart_data_by_day($this->input->get('offset'));
				break;
			case 'week':
				echo $this->get_chart_data_by_week($this->input->get('offset'));
				break;
			case 'month':
				echo $this->get_chart_data_by_month($this->input->get('offset'));
				break;
			case 'year':
				echo $this->get_chart_data_by_year($this->input->get('offset'));
				break;
		}
		
		die();
	}
	
	function get_chart_data_by_day($offset = 0) {
		$timestamp = time();
		
		if($offset < 0)
			$timestamp_d = strtotime('-' . abs($offset) * 7 . 'days', $timestamp);
		else
			$timestamp_d = strtotime('+' . $offset * 7 . 'days', $timestamp);
		
		$day = date("w", $timestamp_d);
		if($day == 0)
			$day = 7;
		
		$from_date_timestamp = strtotime('-' . $day + 1 .' days', $timestamp_d);
		$from_date = date("Y-m-d", $from_date_timestamp);
		
		$to_date_timestamp = strtotime('+' . 7 - $day .' days', $timestamp_d);
		$to_date = date("Y-m-d", $to_date_timestamp);
		
		$results = $this->mdl_chart->get_chart_data_by_day($from_date, $to_date);
		
		$formatted_result = array();
		
		foreach($results as $result) {
			$formatted_result[$result->payment_date] = (float)$result->payment_amount;
		}		
		
		$json['title'] = date("l, F jS, Y", $from_date_timestamp) . ' - ' . date("l, F jS, Y", $to_date_timestamp);
		$json['haxistitle'] = 'Day';
		$json['vaxistitle'] = 'Income($)';
		$json['data'][] = array('Day', 'Income($)');
		
		$loop_from_date = $from_date_timestamp;
		
		while($loop_from_date <= $to_date_timestamp) {			
			if(array_key_exists(date("Y-m-d", $loop_from_date), $formatted_result)) {
				$json['data'][] = array(date("D, M jS", $loop_from_date), $formatted_result[date("Y-m-d", $loop_from_date)]);
			} else {
				$json['data'][] = array(date("D, M jS", $loop_from_date), 0);
			}
			
			$loop_from_date = strtotime("+1 day", $loop_from_date);
		}
		
		return json_encode($json);
	}
	
	function get_chart_data_by_week($offset = 0) {
		$timestamp = time();
		
		if($offset < 0)
			$timestamp_w = strtotime('- ' . abs($offset) * 7 . ' weeks', $timestamp);
		else
			$timestamp_w = strtotime('+ ' . (int)$offset * 7 . ' weeks', $timestamp);
		
		$first_week = strtotime('- 6 weeks', $timestamp_w);
		$last_week = $timestamp_w;
		
//		$from_date_obj = new DateTime();
//		$from_date_obj->setISODate(date('Y', $first_week), date('W', $first_week));
//		$from_date_timestamp = $from_date_obj->getTimestamp();
		$from_date_timestamp = $this->get_first_day_week(date('Y', $first_week), date('W', $first_week));
		$from_date = date('Y-m-d', $from_date_timestamp);
		
//		$to_date_obj = new DateTime();
//		$to_date_obj->setISODate(date('Y', $last_week), date('W', $last_week));
//		$to_date_timestamp = strtotime('+ 6 days', $to_date_obj->getTimestamp());
		$to_date_timestamp = strtotime('+ 6 days', $this->get_first_day_week(date('Y', $last_week), date('W', $last_week)));
		$to_date = date('Y-m-d', $to_date_timestamp);
		
		$loop_from_date = $from_date_timestamp;
		
		$cases = '';
		
		while($loop_from_date <= $to_date_timestamp) {
			$cases .= "WHEN DATE_FORMAT(FROM_UNIXTIME(payment_date),'%Y-%m-%d') >= '" . date('Y-m-d', $loop_from_date) . "' AND DATE_FORMAT(FROM_UNIXTIME(payment_date),'%Y-%m-%d') <= '" . date('Y-m-d', strtotime('+ 6 days', $loop_from_date)) . "' THEN '" . date('Y-W', $loop_from_date) . "'
";
			
			$loop_from_date = strtotime("+1 week", $loop_from_date);
		}
		
		$results = $this->mdl_chart->get_chart_data_by_week($from_date, $to_date, $cases);
		
		$formatted_result = array();
		
		foreach($results as $result) {
			$formatted_result[$result->payment_week] = (float)$result->payment_amount;
		}
				
		$json['title'] = date("F jS, Y", $from_date_timestamp) . ' - ' . date("F jS, Y", $to_date_timestamp);
		$json['haxistitle'] = 'Month';
		$json['vaxistitle'] = 'Income($)';
		$json['data'][] = array('Month', 'Income($)');
		
		$loop_from_date = $from_date_timestamp;
		
		while($loop_from_date <= $to_date_timestamp) {
			$first_day_week = date('M j', $loop_from_date);
			$last_day_week = date('M j', strtotime('+ 6 days', $loop_from_date));
			
			if(array_key_exists(date("Y-W", $loop_from_date), $formatted_result)) {
				$json['data'][] = array($first_day_week . ' - ' . $last_day_week, $formatted_result[date("Y-W", $loop_from_date)]);
			} else {
				$json['data'][] = array($first_day_week . ' - ' . $last_day_week, 0);
			}
			
			$loop_from_date = strtotime("+1 week", $loop_from_date);
		}
		
		return json_encode($json);
	}
	
	function get_chart_data_by_month($offset = 0) {
		$timestamp = time();
		
		if($offset < 0) {
			$timestamp_m = strtotime('-' . abs($offset) * 6 . 'months', $timestamp);
		} else {
			$timestamp_m = strtotime('+' . $offset * 6 . 'months', $timestamp);
		}
		
		$from_date_timestamp = strtotime('-5 months', $timestamp_m);
		$from_date = date("Y-m-1", $from_date_timestamp);
		
		$to_date_timestamp = $timestamp_m;
		$to_date = date("Y-m-t", $timestamp_m);
		
		$results = $this->mdl_chart->get_chart_data_by_month($from_date, $to_date);
		
		$formatted_result = array();
		
		foreach($results as $result) {
			$formatted_result[$result->payment_month] = (float)$result->payment_amount;
		}
		
		$json['title'] = date("F Y", $from_date_timestamp) . ' - ' . date("F Y", $to_date_timestamp);
		$json['haxistitle'] = 'Month';
		$json['vaxistitle'] = 'Income($)';
		$json['data'][] = array('Month', 'Income($)');
		
		$loop_from_date = $from_date_timestamp;
		
		while($loop_from_date <= $to_date_timestamp) {			
			if(array_key_exists(date("Y-m", $loop_from_date), $formatted_result)) {
				$json['data'][] = array(date("M Y", $loop_from_date), $formatted_result[date("Y-m", $loop_from_date)]);
			} else {
				$json['data'][] = array(date("M Y", $loop_from_date), 0);
			}
			
			$loop_from_date = strtotime("+1 month", $loop_from_date);
		}
		
		return json_encode($json);
	}
	
	function get_chart_data_by_year($offset = 0) {
		$timestamp = time();
		
		if($offset < 0) {
			$timestamp_y = strtotime('-' . abs($offset) * 6 . 'years', $timestamp);
		} else {
			$timestamp_y = strtotime('+' . $offset * 6 . 'years', $timestamp);
		}
		
		$from_date_timestamp = strtotime('-5 years', $timestamp_y);
		$from_date = date("Y-01-01", $from_date_timestamp);
		
		$to_date_timestamp = $timestamp_y;
		$to_date = date("Y-12-31", $to_date_timestamp);
		
		$results = $this->mdl_chart->get_chart_data_by_year($from_date, $to_date);
		
		$formatted_result = array();
		
		foreach($results as $result) {
			$formatted_result[$result->payment_year] = (float)$result->payment_amount;
		}
		
		$json['title'] = date("Y", $from_date_timestamp) . ' - ' . date("Y", $to_date_timestamp);
		$json['haxistitle'] = 'Year';
		$json['vaxistitle'] = 'Income($)';
		$json['data'][] = array('Year', 'Income($)');
		
		$loop_from_date = $from_date_timestamp;
		
		while($loop_from_date <= $to_date_timestamp) {			
			if(array_key_exists(date("Y", $loop_from_date), $formatted_result)) {
				$json['data'][] = array(date("Y", $loop_from_date), $formatted_result[date("Y", $loop_from_date)]);
			} else {
				$json['data'][] = array(date("Y", $loop_from_date), 0);
			}
			
			$loop_from_date = strtotime("+1 year", $loop_from_date);
		}
		
		return json_encode($json);
	}
	
	function get_first_day_week($year, $week_number) {
		$offset = date('w', mktime(0, 0, 0, 1, 1, $year));
		$offset = ($offset < 5) ? 1 - $offset : 8 - $offset;
		$monday = mktime(0, 0, 0, 1, 1 + $offset, $year);
		
		return strtotime('+' . ($week_number - 1) . ' weeks', $monday);
	}
}

?>
