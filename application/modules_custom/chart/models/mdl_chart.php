<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Chart extends MY_Model {

	function __construct() {
		parent::__construct();
	}
	
	function get_chart_data_by_day($from_date, $to_date) {
		$query = $this->db->query("SELECT DATE_FORMAT(FROM_UNIXTIME(payment_date),'%Y-%m-%d') as payment_date, SUM(payment_amount) as payment_amount 
			FROM mcb_payments 
			WHERE DATE_FORMAT(FROM_UNIXTIME(payment_date),'%Y-%m-%d') >= '" . $from_date . "' AND 
				 DATE_FORMAT(FROM_UNIXTIME(payment_date),'%Y-%m-%d') <= '" . $to_date . "' 
			GROUP BY DATE_FORMAT(FROM_UNIXTIME(payment_date),'%Y-%m-%d')");
		
		return $query->result();
	}
	
	function get_chart_data_by_week($from_date, $to_date, $cases) {
		$query = $this->db->query("SELECT CASE 
				" . $cases . " 
			ELSE DATE_FORMAT(FROM_UNIXTIME(payment_date),'%Y-%m-%d')
			END AS payment_week, SUM(payment_amount) as payment_amount 
		FROM mcb_payments 
		WHERE DATE_FORMAT(FROM_UNIXTIME(payment_date),'%Y-%m-%d') >= '" . $from_date . "' AND 
				 DATE_FORMAT(FROM_UNIXTIME(payment_date),'%Y-%m-%d') <= '" . $to_date . "' 
		GROUP BY payment_week");
		
		return $query->result();
	}
	
	function get_chart_data_by_month($from_date, $to_date) {
		$query = $this->db->query("SELECT DATE_FORMAT(FROM_UNIXTIME(payment_date),'%Y-%m') as payment_month, SUM(payment_amount) as payment_amount 
			FROM mcb_payments 
			WHERE DATE_FORMAT(FROM_UNIXTIME(payment_date),'%Y-%m-%d') >= '" . $from_date . "' AND 
				 DATE_FORMAT(FROM_UNIXTIME(payment_date),'%Y-%m-%d') <= '" . $to_date . "' 
			GROUP BY DATE_FORMAT(FROM_UNIXTIME(payment_date),'%m-%Y')");
		
		return $query->result();
	}
	
	function get_chart_data_by_year($from_date, $to_date) {
		$query = $this->db->query("SELECT DATE_FORMAT(FROM_UNIXTIME(payment_date),'%Y') as payment_year, SUM(payment_amount) as payment_amount 
			FROM mcb_payments 
			WHERE DATE_FORMAT(FROM_UNIXTIME(payment_date),'%Y-%m-%d') >= '" . $from_date . "' AND 
				 DATE_FORMAT(FROM_UNIXTIME(payment_date),'%Y-%m-%d') <= '" . $to_date . "' 
			GROUP BY DATE_FORMAT(FROM_UNIXTIME(payment_date),'%Y')");
		
		return $query->result();
	}
}

?>