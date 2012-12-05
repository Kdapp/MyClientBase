<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/* BERSAN PAOLO 04/02/2012: default separator for renema file during saving */
define("FILE_NAME_SEPARATOR","___");
define("DEF_SAVE_TO_PATH","uploads/modules_custom/accounts/");
/* END BERSAN PAOLO 04/02/2012: default separator for renema file during saving */
	
class Mdl_accounts extends MY_Model {
	/* Bersan Paolo 2012-02-27: category tables */
	private $cat_table_name='mcb_expenses_categories';
	
	function __construct() {

		/* MY_Model does a lot of legwork for us. */

		parent::__construct();

		/* Define the database table name. */

		$this->table_name = 'mcb_expenses';
        $pending->table_name = 'mcb_invoice_amounts';

		/* Define the primary key of the table. */

		$this->primary_key = 'mcb_expenses.expense_id';

		/* Define SQL_CALC_FOUND_ROWS * as a minimum for pagination. */

		$this->select_fields = "
		SQL_CALC_FOUND_ROWS mcb_expenses.*";

		$this->order_by = 'mcb_expenses.expense_date, mcb_expenses.expense_id DESC';
		$expense_date = 'test';
	}

	function validate() {
		$this->form_validation->set_rules('expense_date', $this->lang->line('expense_date'), 'required');
		$this->form_validation->set_rules('amount', $this->lang->line('amount'), 'required');
		$this->form_validation->set_rules('expense_for', $this->lang->line('expense_for'), 'required');
		$this->form_validation->set_rules('expense_category_id', $this->lang->line('category'), 'required');
		$this->form_validation->set_rules('title', $this->lang->line('title'), 'required');
		$this->form_validation->set_rules('expense_invoice_group_id', $this->lang->line('expense_for'), 'required');
		
		return parent::validate();
	}

	function save() {

		/* Prepare the default $db_array */

		$db_array = parent::db_array();
		
		if (isset($db_array['expense_date']) and $db_array['expense_date']) {

			$db_array['expense_date'] = strtotime(standardize_date($db_array['expense_date']));
		}

		parent::save($db_array, uri_assoc('expense_id', 3));
		
		// $this->saveAttachment($db_array);

	}
	
	/* BERSAN PAOLO 04/02/2012: manage attachment */
	function saveAttachment($db_array) {
		$date_parts=explode("/",date("m/d/Y",$db_array['expense_date']));
		$def_file_path=DEF_SAVE_TO_PATH.$date_parts[2]."/".$date_parts[0]."/";
		$new_file_name=uri_assoc('expense_id', 3).FILE_NAME_SEPARATOR.date("d_m_Y",$db_array['expense_date']).FILE_NAME_SEPARATOR.rand(1,999999999).FILE_NAME_SEPARATOR.$_FILES["attachment_1"]["name"];

		if (!is_dir($def_file_path)) {
			mkdir($def_file_path, 0, true);
		}
		
		if ($_FILES["attachment_1"]["error"] > 0) {
			echo "Error: " . $_FILES["attachment_1"]["error"] . "<br />";
		} else {
			move_uploaded_file($_FILES["attachment_1"]["tmp_name"],$def_file_path.$new_file_name); 			
		}
	}
	
	function delete_attachment() {
		$db_array = parent::db_array();
		
		if (uri_assoc('file_name', 3)) {
			$file_name=uri_assoc('file_name', 3);
			$file_parts=explode(FILE_NAME_SEPARATOR,$file_name);
			$date_val=explode("_",$file_parts[1]);
			
			//Delete File
			unlink(DEF_SAVE_TO_PATH.$date_val[2]."/".$date_val[1]."/".$file_name);
						
			redirect('accounts/form/expense_id/'.$file_parts[0]);
		}
	}
	/* END BERSAN PAOLO 04/02/2012: manage attachment */

	function prep_validation($key) {

		/* First prepare the default validation */
		parent::prep_validation($key);

		if (!$_POST) {

			if ($this->form_value('expense_date')) {

				/* Convert to a human readable date if the unix timestamp exists. */
				$this->set_form_value('expense_date', format_date($this->form_value('expense_date')));

			} 

		} 

	}

	function delete($params) {

		/* Run the standard delete function. */

		parent::delete($params);

		/*
		 * And delete records from mcb_expenses_invoices as well.
		 * This does NOT delete the actual invoices.
		*/
		

	}
	
	function report(){
		
	}

	function total_payment($from_date='',$to_date=''){
		
		$this->db->select_sum('payment_amount');		
		if($from_date!='')	$this->db->where('payment_date >=',$from_date);
		
		if($to_date!='')	$this->db->where('payment_date <=', $to_date);
		
		$query = $this->db->get('mcb_payments'); 
		$row = $query->row(); 
		return $row->payment_amount;
	}
	
	function total_expense($from_date='',$to_date=''){
	
		$this->db->select_sum('amount'); 
		
		if($from_date!='')	$this->db->where('expense_date >=',$from_date);		
		if($to_date!='')	$this->db->where('expense_date <=', $to_date);
		
		$query = $this->db->get('mcb_expenses'); 
		$row = $query->row(); 
		return ''.$row->amount;
	}
	
	function total_expanse5($from_date,$to_date){

		$this->db->select_sum('amount'); 
		if(($from_date!=FALSE)&&($to_date!=FALSE))
		{
		
			$this->db->where('expense_date >=',strtotime(standardize_date($from_date)));
			$this->db->where('expense_date <=', strtotime(standardize_date($to_date)));
	
			
		}
		$query = $this->db->get('mcb_expenses'); 
		$row = $query->row(); 
		return ''.$row->amount;
	}

	function total_pending(){
		$this->db->select_sum('invoice_balance'); 		
		$query = $this->db->get('mcb_invoice_amounts'); 
		$row = $query->row();
		return $row->invoice_balance; 
	}
	
	function balance($from_date,$to_date){
		$amount = $this->total_payment($from_date,$to_date) - $this->total_expanse($from_date,$to_date);
		return $amount;
	}
	
	function custome_total_pending($from_date='',$to_date=''){
		$amount_balance=0;
		$this->db->select('invoice_id'); 	
		//if($from_date!='')	$this->db->where('invoice_date_entered >=',strtotime(standardize_date($from_date)));
		 if($to_date!='')	$this->db->where('invoice_date_entered <=', strtotime(standardize_date($to_date)));
					
		$query = $this->db->get('mcb_invoices'); 		
		

		foreach ($query->result() as $row)
		{
			 
			$this->db->select('invoice_balance'); 
			$this->db->where('invoice_id',$row->invoice_id);	
			$query2 = $this->db->get('mcb_invoice_amounts'); 
			$row2 = $query2->row();
			$amount_balance=$amount_balance+$row2->invoice_balance ;	 			 
			 
		}
		
		return $amount_balance;
	}
	
	function get_records($params) {
		$this->db->select('*,t2.expense_category_desc');
 		$this->db->from('mcb_expenses as t1');
 		$this->db->join('mcb_expenses_categories  as t2', 't2.expense_category_id = t1.expense_category_id');
 		
 		if (isset($params['where'])) {
 			foreach($params['where'] as $field=>$val) {
 				$this->db->where($field,$val);
 			}
 		}
 		
 		$this->db->order_by('t1.expense_date,t2.expense_category_desc asc');
 		
 		if (isset($params['limit'])) {
 			$this->db->limit($params['limit']);
 		}
 		
		$query = $this->db->get();
				
		return $query->result();
	}	

}

?>