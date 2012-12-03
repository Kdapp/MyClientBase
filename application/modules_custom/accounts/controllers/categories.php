<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Categories extends Admin_Controller {

	function __construct() {

		parent::__construct();

		/* Make sure this module is enabled before letting user access it. */

		if (!$this->mdl_mcb_modules->check_enable('accounts')) {

			redirect('dashboard');

		}

		/* Load the module's model. */

		$this->load->model('mdl_categories');

	}

	function data_table() {
		
		$params = array(
			'limit'		=>	20,
			'paginate'	=>	TRUE
		);
		
		$data = array(
			'categories' =>	$this->mdl_categories->get($params)
		);
				
		$this->load->view('categories',$data);
	}	
	
	function edit_category() {
		$this->load->helper('form');
		
		$this->mdl_categories->validate();
		
		if (uri_assoc('expense_category_id', 4)) {
			$id=uri_assoc('expense_category_id', 4);
			
			$data = array(
				'category'	=>	$this->mdl_categories->get_record_by_id($id)
			);
			
			$this->load->view('form_categories', $data);
		} else {
			$this->create_category();
		}
	}
	
	function create_category() {
		$this->load->view('form_categories');
	}	

	
	function delete_category() {
		if (uri_assoc('expense_category_id', 4)) {
			$id=uri_assoc('expense_category_id', 4);
			
			$this->mdl_categories->delete(array('expense_category_id'=>$id));
		}
		
		$this->data_table();
	}
	
	function save() {
		$this->load->helper('form');
		
		$this->form_validation->set_rules('expense_category_desc', $this->lang->line('cat_desc'), 'required');
				
		if ($this->mdl_categories->validate()) {
			//if (uri_assoc('expense_category_id', 4)) {
				$this->mdl_categories->save();
			
				$this->data_table();
			//}
		} else {
			$this->edit_category();
		}
		
	}
	
	function post_handler() {
		if ($this->input->post('btn_submit')) {
			$this->save();
		} else if ($this->input->post('btn_cancel')) {
			$this->data_table();
		} else if ($this->input->post('btn_add')) {
			$this->create_category();
		} else {
			$this->data_table();
		}
	}	
}

?>