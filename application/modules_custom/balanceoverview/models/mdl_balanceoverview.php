<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Balanceoverview extends MY_Model {

    function __construct() {

		/* MY_Model does a lot of legwork for us. */

		parent::__construct();

		/* Define the database table name. */

		$this->table_name = 'mcb_expenses_categories';


		/* Define the primary key of the table. */
		$this->primary_key = 'mcb_expenses_categories.expense_category_id';

		/* Define SQL_CALC_FOUND_ROWS * as a minimum for pagination. */

		$this->select_fields = "
		SQL_CALC_FOUND_ROWS mcb_expenses_categories.*";

		$this->order_by = 'mcb_expenses_categories.expense_category_id';
	}
	
	function get_invoice_amounts() {
		
		$group_array = array();
		
		$start_last_year = mktime(0, 0, 0, 1, 1, date('Y')-1);
		$end_last_year = mktime(0, 0, 0, 1, 0, date('Y'));
		
		$start_this_year = mktime(0, 0, 0, 1, 1, date('Y'));
		$end_this_year = mktime(0, 0, 0, 1, 0, date('Y')+1);
		
		$start_last_month = mktime(0, 0, 0, date('m')-1, 1, date('Y'));
		$end_last_month = mktime(0, 0, 0, date('m'), 0, date('Y'));
				
		$start_this_month = mktime(0, 0, 0, date('m'), 1, date('Y'));
		$end_this_month = mktime(0, 0, 0, date('m')+1, 0, date('Y'));
		
		$last_quarter = $this->_getQuarter(date('Y').'-'.(date('m')-3).'-'.date('d'));
		$this_quarter = $this->_getQuarter(date('Y').'-'.date('m').'-'.date('d'));
		
		$query = $this->_get_invoice_groups()->result();

		foreach ($query as $group)
		{
			$group_array [$group->invoice_group_name] = $this->_get_total_income($start_last_year, $end_last_year, $start_this_year, $end_this_year, $start_last_month, $end_last_month, $start_this_month, $end_this_month, $last_quarter['start'], $last_quarter['end'], $this_quarter['start'], $this_quarter['end'], $group->invoice_group_id);
		}
		
		return $group_array;
    }
	
    public function _get_invoice_groups () {
		$this->db->select('invoice_group_id, invoice_group_name');
		return $this->db->get('mcb_invoice_groups');
	}
	
	public function _get_expense_groups () {
		$this->db->select('expense_category_id, expense_category_desc');
		return $this->db->get('mcb_expenses_categories');
		
	}
	
	function get_expense_amounts() {
	
		$group_array = array();
		$query_expense_groups = array();
		
		$start_last_year = mktime(0, 0, 0, 1, 1, date('Y')-1);
		$end_last_year = mktime(0, 0, 0, 1, 0, date('Y'));
		
		$start_this_year = mktime(0, 0, 0, 1, 1, date('Y'));
		$end_this_year = mktime(0, 0, 0, 1, 0, date('Y')+1);
		
		$start_last_month = mktime(0, 0, 0, date('m')-1, 1, date('Y'));
		$end_last_month = mktime(0, 0, 0, date('m'), 0, date('Y'));
				
		$start_this_month = mktime(0, 0, 0, date('m'), 1, date('Y'));
		$end_this_month = mktime(0, 0, 0, date('m')+1, 0, date('Y'));
		
		$last_quarter = $this->_getQuarter(date('Y').'-'.(date('m')-3).'-'.date('d'));
		$this_quarter = $this->_getQuarter(date('Y').'-'.date('m').'-'.date('d'));
		
		$query_groups = $this->_get_invoice_groups()->result();
		$query_expense_groups = $this->_get_expense_groups()->result();
		
		foreach ($query_groups as $group_invoices)
		{			
			$invoice_group_id = $group_invoices->invoice_group_id;
			
			$group_array ['<b>' . $group_invoices->invoice_group_name . '</b>'] = $this->_get_total_expense_total($start_last_year, $end_last_year, $start_this_year, $end_this_year, $start_last_month, $end_last_month, $start_this_month, $end_this_month, $last_quarter['start'], $last_quarter['end'], $this_quarter['start'], $this_quarter['end'], $invoice_group_id);
						
			foreach ($query_expense_groups as $group)
			{
				$group_array [$group_invoices->invoice_group_name . '=>' . $group->expense_category_desc] = $this->_get_total_expense($start_last_year, $end_last_year, $start_this_year, $end_this_year, $start_last_month, $end_last_month, $start_this_month, $end_this_month, $last_quarter['start'], $last_quarter['end'], $this_quarter['start'], $this_quarter['end'], $group->expense_category_id, $invoice_group_id);
			}
			
		}
		
		return $group_array;
	}
	
	function _getExport() {
		
	}
	
	private function _getQuarter($date) {
	
		$q = (int)floor(date('m', strtotime($date)) / 3.1) + 1;
		$quarters = array(
			1 => array('start' => mktime(0,0,0,1,1,date('Y', strtotime($date))), 'end' => mktime(0,0,0,4,0,date('Y', strtotime($date)))),
			2 => array('start' => mktime(0,0,0,4,1,date('Y', strtotime($date))), 'end' => mktime(0,0,0,7,0,date('Y', strtotime($date)))),
			3 => array('start' => mktime(0,0,0,7,1,date('Y', strtotime($date))), 'end' => mktime(0,0,0,10,0,date('Y', strtotime($date)))),
			4 => array('start' => mktime(0,0,0,10,1,date('Y', strtotime($date))), 'end' => mktime(0,0,0,0,0,date('Y', strtotime($date))-1))
		);
		return $quarters[$q];   
	}
	
	function validate() {
	
		$this->form_validation->set_rules('expense_category_desc', $this->lang->line('expense_group'), 'required');
		
		return parent::validate();

	}
	
	function prep_validation($key) {

		parent::prep_validation($key);

		if (!$_POST) {

			if ($this->form_value('expense_category_desc')) {

				$this->set_form_value('expense_category_desc', $this->form_value('expense_category_desc'));

			}

		}

	}
	
	function save() {

		/* Prepare the default $db_array */

		$db_array = parent::db_array();

		parent::save($db_array, uri_assoc('expense_category_id', 3));

	}
	
	function delete($params) {

		parent::delete($params);

		$this->db->where('expense_category_id', $params['expense_category_id']);

		$this->db->delete('mcb_expenses_categories');

	}
	
	private function _get_total_income ($from_last_year, $until_last_year, $from_this_year, $until_this_year, $from_last_month, $until_last_month, $from_this_month, $until_this_month, $from_last_quarter, $until_last_quarter, $from_this_quarter, $until_this_quarter, $group) {
		
		$this->db->select('* FROM ' .
			'(SELECT SUM(invoice_subtotal) as l_year FROM mcb_invoice_amounts CROSS JOIN mcb_invoices WHERE invoice_date_entered >= '.$from_last_year.' AND invoice_date_entered <= '.$until_last_year.' AND invoice_group_id = ' . $group . ' AND invoice_is_quote = 0 AND mcb_invoices.invoice_id = mcb_invoice_amounts.invoice_id ) as last_year, ' .
			'(SELECT SUM(invoice_subtotal) as t_year FROM mcb_invoice_amounts as a CROSS JOIN mcb_invoices as f WHERE invoice_date_entered >= '.$from_this_year.' AND invoice_date_entered <= '.$until_this_year.' AND invoice_group_id = ' . $group . ' AND invoice_is_quote = 0 AND a.invoice_id = f.invoice_id) as this_year, ' .
			'(SELECT SUM(invoice_subtotal) as l_month FROM mcb_invoice_amounts as b CROSS JOIN mcb_invoices as g WHERE invoice_date_entered >= '.$from_last_month.' AND invoice_date_entered <= '.$until_last_month.' AND invoice_group_id = ' . $group . ' AND invoice_is_quote = 0 AND b.invoice_id = g.invoice_id) as last_month, ' .
			'(SELECT SUM(invoice_subtotal) as t_month FROM mcb_invoice_amounts as c CROSS JOIN mcb_invoices as h WHERE invoice_date_entered >= '.$from_this_month.' AND invoice_date_entered <= '.$until_this_month.' AND invoice_group_id = ' . $group . ' AND invoice_is_quote = 0 AND c.invoice_id = h.invoice_id) as this_month, ' .
			'(SELECT SUM(invoice_subtotal) as l_quarter FROM mcb_invoice_amounts as d CROSS JOIN mcb_invoices as i WHERE invoice_date_entered >= '.$from_last_quarter.' AND invoice_date_entered <= '.$until_last_quarter.' AND invoice_group_id = ' . $group . ' AND invoice_is_quote = 0 AND d.invoice_id = i.invoice_id) as last_quarter, ' .
			'(SELECT SUM(invoice_subtotal) as t_quarter FROM mcb_invoice_amounts as e CROSS JOIN mcb_invoices as j WHERE invoice_date_entered >= '.$from_this_quarter.' AND invoice_date_entered <= '.$until_this_quarter.' AND invoice_group_id = ' . $group . ' AND invoice_is_quote = 0 AND e.invoice_id = j.invoice_id) as this_quarter'
		);
		return $this->db->get()->row();
	}
	
	private function _get_total_expense ($from_last_year, $until_last_year, $from_this_year, $until_this_year, $from_last_month, $until_last_month, $from_this_month, $until_this_month, $from_last_quarter, $until_last_quarter, $from_this_quarter, $until_this_quarter, $group, $group_expense) {
		
		$this->db->select('* FROM ' .
            '(SELECT SUM(amount) as l_year FROM mcb_expenses WHERE expense_date >= '.$from_last_year.' AND expense_date <= '.$until_last_year.' AND expense_category_id = ' . $group . ' AND expense_invoice_group_id = ' . $group_expense . ') as last_year, ' .
			'(SELECT SUM(amount) as t_year FROM mcb_expenses WHERE expense_date >= '.$from_this_year.' AND expense_date <= '.$until_this_year.' AND expense_category_id = ' . $group . ' AND expense_invoice_group_id = ' . $group_expense . ') as this_year, ' .
			'(SELECT SUM(amount) as l_month FROM mcb_expenses WHERE expense_date >= '.$from_last_month.' AND expense_date <= '.$until_last_month.' AND expense_category_id = ' . $group . ' AND expense_invoice_group_id = ' . $group_expense . ') as last_month, ' .
			'(SELECT SUM(amount) as t_month FROM mcb_expenses WHERE expense_date >= '.$from_this_month.' AND expense_date <= '.$until_this_month.' AND expense_category_id = ' . $group . ' AND expense_invoice_group_id = ' . $group_expense . ') as this_month, ' .
			'(SELECT SUM(amount) as l_quarter FROM mcb_expenses WHERE expense_date >= '.$from_last_quarter.' AND expense_date <= '.$until_last_quarter.' AND expense_category_id = ' . $group . ' AND expense_invoice_group_id = ' . $group_expense . ') as last_quarter, ' .
			'(SELECT SUM(amount) as t_quarter FROM mcb_expenses WHERE expense_date >= '.$from_this_quarter.' AND expense_date <= '.$until_this_quarter.' AND expense_category_id = ' . $group . ' AND expense_invoice_group_id = ' . $group_expense . ') as this_quarter'
        );	

		return $this->db->get()->row();
	}
	
	private function _get_total_expense_total ($from_last_year, $until_last_year, $from_this_year, $until_this_year, $from_last_month, $until_last_month, $from_this_month, $until_this_month, $from_last_quarter, $until_last_quarter, $from_this_quarter, $until_this_quarter, $group) {
		
		$this->db->select('* FROM ' .
			'(SELECT SUM(amount) as l_year FROM mcb_expenses WHERE expense_date >= '.$from_last_year.' AND expense_date <= '.$until_last_year.' AND expense_invoice_group_id = ' . $group . ') as last_year, ' .
			'(SELECT SUM(amount) as t_year FROM mcb_expenses WHERE expense_date >= '.$from_this_year.' AND expense_date <= '.$until_this_year.' AND expense_invoice_group_id = ' . $group . ') as this_year, ' .
			'(SELECT SUM(amount) as l_month FROM mcb_expenses WHERE expense_date >= '.$from_last_month.' AND expense_date <= '.$until_last_month.' AND expense_invoice_group_id = ' . $group . ') as last_month, ' .
			'(SELECT SUM(amount) as t_month FROM mcb_expenses WHERE expense_date >= '.$from_this_month.' AND expense_date <= '.$until_this_month.' AND expense_invoice_group_id = ' . $group . ') as this_month, ' .
			'(SELECT SUM(amount) as l_quarter FROM mcb_expenses WHERE expense_date >= '.$from_last_quarter.' AND expense_date <= '.$until_last_quarter.' AND expense_invoice_group_id = ' . $group . ') as last_quarter, ' .
			'(SELECT SUM(amount) as t_quarter FROM mcb_expenses WHERE expense_date >= '.$from_this_quarter.' AND expense_date <= '.$until_this_quarter.' AND expense_invoice_group_id = ' . $group . ') as this_quarter'
        );	
		return $this->db->get()->row();
		
	}

}

?>