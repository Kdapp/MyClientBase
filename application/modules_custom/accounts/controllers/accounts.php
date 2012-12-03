<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class accounts extends Admin_Controller {

	function __construct() {

		parent::__construct();

		/* Make sure this module is enabled before letting user access it. */

		if (!$this->mdl_mcb_modules->check_enable('accounts')) {

			redirect('dashboard');

		}

		/* Load the module's model. */

		$this->load->model('mdl_accounts');
	}

	function index() {

		$this->_post_handler();

		$this->redir->set_last_index();	

		$params = array(
			'limit'		=>	20000,
			'paginate'	=>	TRUE,
			'page'		=>	uri_assoc('page', 3)//,
		);
	
		$data = array(
			'accounts' =>	$this->mdl_accounts->get_records($params)
		);
		
		$this->load->view('index', $data);

	}

	function reports($from_date='',$to_date='') {

		$this->_post_handler();
		$this->redir->set_last_index();				
	 	$this->load->view('reports');

	}

	function form() {

		$this->_post_handler();
			
		$expense_id = uri_assoc('expense_id', 3);

		$this->load->model('mdl_categories');	
		$categories=$this->mdl_categories->get_records();	
		
		if (!$this->mdl_accounts->validate()) {

			$this->load->helper('form');
			
			$this->load->helper('text');
			
			if (!$_POST AND $expense_id) {
				
				$this->mdl_accounts->prep_validation($expense_id);
				
				$params = array(
					'where'		=>	array(
						'expense_id' => $expense_id
					)
				);
				
				$data = array(
					'form_value'	=>	$this->mdl_accounts->get_records($params),
					'category_list' =>  $categories
				);
				
			} elseif (!$_POST AND !$expense_id) {
			
				$this->mdl_accounts->set_form_value('expense_date', format_date(time()));
				
				$data = array(
					'category_list' =>  $categories
				);
			}
							
			$this->load->view('form',$data);

		} else {
			/* Yippee!  The form has successfully validated, so let's save. */

			$this->mdl_accounts->save();			
			$this->redir->redirect('accounts');

		}

	}

	function delete() {

		/* First make sure a task has been specified to delete. */

		if (uri_assoc('expense_id', 3)) {

			/* The uri segment variable exists, so delete the task. */

			$this->mdl_accounts->delete(array('expense_id'=>uri_assoc('expense_id', 3)));

		}

		$this->redir->redirect('accounts');

	}

	/* BERSAN PAOLO 04/02/2012: delete attachment from file system */
	function delete_attachment() {
		$this->mdl_accounts->delete_attachment();
	}
	/* END BERSAN PAOLO 04/02/2012: delete attachment from file system */	
	
	function save_settings() {

		/*
		 * As per the config file, this function will
		 * execute when the core system settings are saved.
		*/

		if ($this->input->post('dashboard_show_open_accounts')) {

			$this->mdl_mcb_data->save('dashboard_show_open_accounts', "TRUE");

		}

		else {

			$this->mdl_mcb_data->save('dashboard_show_open_accounts', "FALSE");

		}

	}

	function dashboard_widget() {

		/*
		 * As per the module config file, this function will execute when
		 * the core dashboard calls for it.
		*/

		if ($this->mdl_mcb_data->setting('dashboard_show_open_accounts') == "TRUE") {

			/*
			 * Only execute if the module's custom system setting has been saved
			 * as TRUE.
			*/

			$params = array(
				'limit'	=>	10);

			$data = array(
				'accounts'				=>	$this->mdl_accounts->get_records($params)
			);

			$this->load->view('dashboard_widget', $data);

		}

	}

	function _post_handler() {

		/* Has the Add Task button been pressed? */

		if ($this->input->post('btn_add')) {

			redirect('accounts/form');

		}

		/* Has the Cancel button been pressed? */

		elseif ($this->input->post('btn_cancel')) {

			redirect('accounts/index');

		}		

	}

	/*
	function categories() {
		$params = array(
			'limit'		=>	20,
			'paginate'	=>	TRUE
			//,'page'		=>	uri_assoc('page', 3)
		);

		$data = array(
			'accounts' =>	$this->mdl_accounts->get_categories($params)
		);
				
		$this->load->view('categories',$data);

	}
	*/
}

?>