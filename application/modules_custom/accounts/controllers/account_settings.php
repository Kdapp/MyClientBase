<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Account_Settings extends Admin_Controller {

	function display() {

		$this->load->view('settings');

	}

	function save() {

		/*
		 * As per the config file, this function will
		 * execute when the system settings are saved.
		 */

		if ($this->input->post('dashboard_show_open_accounts')) {

			$this->mdl_mcb_data->save('dashboard_show_open_accounts', "TRUE");

		}

		else {

			$this->mdl_mcb_data->save('dashboard_show_open_accounts', "FALSE");

		}

	}

}

?>