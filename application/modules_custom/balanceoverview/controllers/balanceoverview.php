<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Balanceoverview extends Admin_Controller {

    function __construct() {

        parent::__construct();

        if (!$this->mdl_mcb_modules->check_enable('balanceoverview')) {

            redirect('mcb_modules');

        }

        $this->load->model('mdl_balanceoverview');

        $this->_post_handler();

    }

    function index() {

		$data['get_invoice_amounts'] = $this->mdl_balanceoverview->get_invoice_amounts();
		$data['get_expense_amounts'] = $this->mdl_balanceoverview->get_expense_amounts();
		
        $this->load->view('index', $data);

    }
	
	function all_groups () {
		
		$this->_post_handler();

		$this->redir->set_last_index();

		$params = array(
			'limit'		=>	20,
			'paginate'	=>	TRUE,
			'page'		=>	uri_assoc('page', 3)
		);

		$data = array(
			'groups'				=>	$this->mdl_balanceoverview->_get_expense_groups()->result(),
		);

		$this->load->view('all_groups', $data);
	}
	
	function form() {

		/* Check for form submission and handle appropriately. */

		$this->_post_handler();

		$expense_category_id = uri_assoc('expense_category_id', 3);
		
		if (!$this->mdl_balanceoverview->validate()) {

			/* Load the text helper */

			$this->load->helper('form');
			$this->load->helper('text');
			
			if (!$_POST AND $expense_category_id) {

				$this->mdl_balanceoverview->prep_validation($expense_category_id);

			}

			elseif (!$_POST AND !$expense_category_id) {
				

			}
			/* Load the view. */

			$this->load->view('form');

		}

		else {

			/* Yippee!  The form has successfully validated, so let's save. */

			$this->mdl_balanceoverview->save();	

			if (!$expense_category_id) {

				$expense_category_id = $this->db->insert_id();

			}
			$this->redir->redirect('balanceoverview/all_groups');

		}

	}
	
	public function balance_list() {

        $data = array(
            'output_types'  =>  array('pdf','csv','view')
        );

        $this->load->view('balance_list', $data);

    }
	
	function delete() {

		if (uri_assoc('expense_category_id', 3)) {

			$this->mdl_balanceoverview->delete(array('expense_category_id'=>uri_assoc('expense_category_id', 3)));

		}

		$this->redir->redirect('balanceoverview');

	}
	
	public function jquery_display_results($output_type = 'view') {
	        
        $data['get_invoice_amounts'] = $this->mdl_balanceoverview->get_invoice_amounts();
		$data['get_expense_amounts'] = $this->mdl_balanceoverview->get_expense_amounts();

        if ($output_type == 'view') {
          
        	$this->load->view('balance_list_view', $data);

        }

        elseif ($output_type == 'pdf') {

            // $this->load->helper($this->mdl_mcb_data->setting('pdf_plugin'));
			
            $html = $this->load->view('balance_list_pdf', $data, TRUE);
			
			$this->pdf_create($html, url_title($this->lang->line('balance_list'), '_'), TRUE);

        }

        elseif ($output_type == 'csv') {
			
			$i = 1;
			
            $this->load->dbutil();

            $this->load->helper('../helpers/csv');
			
			$result[0] = array($this->lang->line('costgroup'), $this->lang->line('lastmonth'),  $this->lang->line('thismonth'), $this->lang->line('lastquarter'), $this->lang->line('thisquarter'), $this->lang->line('lastyear'), $this->lang->line('thisyear'));
			foreach($data['get_invoice_amounts'] AS $group_title => $group_values) {
				$result[$i] = array($group_title, $group_values->l_month, $group_values->t_month, $group_values->l_quarter, $group_values->t_quarter, $group_values->l_year, $group_values->t_year);
				$i = $i + 1;
			}
			foreach($data['get_expense_amounts'] AS $group_title => $group_values) {
				if (strpos($group_title, '=>') === false ) { 
					$name = $group_title;
				}
				else {
					$name = substr($group_title, strpos($group_title, '=>'));
				}
				$result[$i] = array($name, $group_values->l_month, $group_values->t_month, $group_values->l_quarter, $group_values->t_quarter, $group_values->l_year, $group_values->t_year);
				$i = $i + 1;
			}
			$result[$i] = array($this->lang->line('costgroup'), $this->lang->line('lastmonth'),  $this->lang->line('thismonth'), $this->lang->line('lastquarter'), $this->lang->line('thisquarter'), $this->lang->line('lastyear'), $this->lang->line('thisyear'));
			
            array_to_csv($result, $this->lang->line('balance_list') . '.csv');

        }

    }

	function pdf_create($html, $filename='', $stream=TRUE) {
	
		require_once(APPPATH . 'helpers/dompdf/dompdf_config.inc.php');
		
		$dompdf = new DOMPDF();
		$dompdf->load_html($html);
		$dompdf->set_paper('a4', 'landscape');
		$dompdf->render();
		if ($stream) {
			$dompdf->stream($filename.".pdf");
		} else {
			return $dompdf->output();
		}
	}

    function _post_handler() {
		/* Has the Add Task button been pressed? */

		if ($this->input->post('btn_add')) {

			redirect('./balanceoverview/form');

		}

		/* Has the Cancel button been pressed? */

		elseif ($this->input->post('btn_cancel')) {

			redirect('./balanceoverview/all_groups');

		}
    }

}

?>