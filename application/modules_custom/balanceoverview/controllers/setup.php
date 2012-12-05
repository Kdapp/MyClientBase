<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Setup extends Admin_Controller {

    function index() {

    }

    function install() {
		$queries = array(
				"ALTER TABLE `mcb_expenses` ADD COLUMN `title` CHAR (40)",
				// "ALTER TABLE `mcb_expenses` ADD COLUMN `expense_group_id` int (11)",
				// "ALTER TABLE `mcb_expenses` ADD KEY `expense_group_id` (`expense_group_id`)",
				"ALTER TABLE `mcb_expenses` ADD COLUMN `expense_invoice_group_id` int (11)",
				"ALTER TABLE `mcb_expenses` ADD KEY `expense_invoice_group_id` (`expense_invoice_group_id`)" // , --> not used anymore with the latest accounts module version
				// "CREATE TABLE IF NOT EXISTS `mcb_expense_groups` (
				//	`expense_group_id` int(11) NOT NULL auto_increment,
				//	`expense_group_name` char(40) NOT NULL,
				//	PRIMARY KEY  (`expense_group_id`)
				// ) ENGINE=MyISAM DEFAULT CHARSET=utf8;"
			);

		foreach ($queries as $query) {

			$this->db->query($query);

		}
    }

    function uninstall() {
		if ($balanceoverview_module->module_version < '0.12') {
			$queries = array(
				"ALTER TABLE `mcb_expenses` DROP `title`",
				"ALTER TABLE `mcb_expenses` DROP `expense_invoice_group_id`"
			);
		}

		foreach ($queries as $query) {

			$this->db->query($query);

		}
    }

    function upgrade() {
		
		$balanceoverview_module = $this->mdl_mcb_modules->custom_modules['balanceoverview'];
	
		if ($balanceoverview_module->module_version < '0.12') {
			$queries = array(
				"ALTER TABLE `mcb_expenses` ADD COLUMN `title` CHAR (40)",
				//"ALTER TABLE `mcb_expenses` ADD COLUMN `expense_group_id` int (11)",
				//"ALTER TABLE `mcb_expenses` ADD KEY `expense_group_id` (`expense_group_id`)",
				"ALTER TABLE `mcb_expenses` ADD COLUMN `expense_invoice_group_id` int (11)",
				"ALTER TABLE `mcb_expenses` ADD KEY `expense_invoice_group_id` (`expense_invoice_group_id`)" //,
				//"CREATE TABLE IF NOT EXISTS `mcb_expense_groups` (
				//	`expense_group_id` int(11) NOT NULL auto_increment,
				//	`expense_group_name` char(40) NOT NULL,
				//	PRIMARY KEY  (`expense_group_id`)
				//) ENGINE=MyISAM DEFAULT CHARSET=utf8;"
			);
		}

		foreach ($queries as $query) {

			$this->db->query($query);

		}
		
		$this->db->set('module_version', '0.12');
		$this->db->where('module_path', 'balanceoverview');
		$this->db->update('mcb_modules');

	}

}

?>