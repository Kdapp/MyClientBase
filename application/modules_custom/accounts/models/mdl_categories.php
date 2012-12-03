<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
	
class Mdl_categories extends MY_Model {
	
	function __construct() {
		parent::__construct();

		/* Define the database table name. */

		$this->table_name = 'mcb_expenses_categories';

		/* Define the primary key of the table. */

		$this->primary_key = 'mcb_expenses_categories.expense_category_id';

		/* Define SQL_CALC_FOUND_ROWS * as a minimum for pagination. */

		$this->select_fields = "SQL_CALC_FOUND_ROWS mcb_expenses_categories.*";

		$this->order_by = 'mcb_expenses_categories.expense_category_desc ASC';
	}

	function validate() {
		
		return parent::validate();

	}

	function save() {

		/* Prepare the default $db_array */

		$db_array = parent::db_array();

		parent::save($db_array, uri_assoc('expense_category_id', 4));

	}

	function delete($params) {

		/* Run the standard delete function. */

		parent::delete($params);
	}
	
	function get_record_by_id($params=false) {

		$query = $this->db->query('SELECT expense_category_id,expense_category_desc FROM '.$this->table_name.' WHERE expense_category_id ='.$params);

		return $query->result();
	}
	
	function get_records($params=null) {
		$this->db->select('expense_category_id,expense_category_desc');
 		$this->db->from($this->table_name);
 		
 		if (isset($params['where'])) {
 			foreach($params['where'] as $field=>$val) {
 				$this->db->where($field,$val);
 			}
 		}

 		$this->db->order_by('expense_category_desc asc');
 		
 		if (isset($params['limit'])) {
 			$this->db->limit($params['limit']);
 		}

		$query = $this->db->get();
				
		return $query->result();
	}	
	
}

?>