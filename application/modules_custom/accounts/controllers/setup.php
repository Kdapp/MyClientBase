<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Setup extends Admin_Controller {

	function __construct() {

		parent::__construct(TRUE);

	}

	function index() {}

	function install() {

		$queries = array(
		"CREATE  TABLE IF NOT EXISTS `mcb_expenses_categories` (
			`expense_category_id` INT(11) NOT NULL AUTO_INCREMENT ,
			`expense_category_desc` VARCHAR(100) NOT NULL ,
			PRIMARY KEY (`expense_category_id`) 
		);",	
		"INSERT INTO `mcb_expenses_categories` (expense_category_desc) values ('GENERIC')",	
		"CREATE TABLE IF NOT EXISTS `mcb_expenses` (
			`expense_id` int(11) NOT NULL auto_increment,
			`expense_category_id` int(11) NOT NULL DEFAULT '1',            
			`expense_date` varchar(25) NOT NULL,
			`amount` decimal(10,2) NOT NULL,
			`expense_for` longtext NOT NULL,
			PRIMARY KEY  (`expense_id`),
      		INDEX `expense_category_id` (`expense_category_id` ASC) ,
      		CONSTRAINT `expense_category_id`
			FOREIGN KEY (`expense_category_id` )
			REFERENCES `mcb_expenses_categories` (`expense_category_id` )
			ON DELETE NO ACTION
			ON UPDATE NO ACTION
		);"
		);

		foreach ($queries as $query) {

			$this->db->query($query);

		}

	}

	function uninstall() {

		$queries = array(
			"DROP TABLE IF EXISTS `mcb_expenses`",
			"DROP TABLE IF EXISTS `mcb_expenses_categories`"
		);

		foreach ($queries as $query) {

			$this->db->query($query);

		}

	}

	function upgrade() {

		$accounts_module = $this->mdl_mcb_modules->custom_modules['accounts'];

		if ($accounts_module->module_version < '0.2.6') {

			$this->upgrade_to_026();

		}
		
		//Bersan Paolo 27/02/2012: expenses category
		if ($accounts_module->module_version < '1.5.0') {
			$this->upgrade_to_150();
		}

	}

	function upgrade_to_026() {
		
		$db_array = array(
			'complete_date'	=>	''
		);

		$this->db->where('complete_date', 0);

		$this->db->update('mcb_expenses', $db_array);

		$db_array = array(
			'due_date'	=>	''
		);

		$this->db->where('due_date', 0);

		$this->db->update('mcb_expenses', $db_array);

		$db_array = array(
			'module_version'	=>	'0.1.3'
		);

		$this->db->where('module_path', 'accounts');
		$this->db->update('mcb_modules', $db_array);

	}

	function upgrade_to_150() {
		$queries = array(
			"CREATE  TABLE IF NOT EXISTS `mcb_expenses_categories` (
				`expense_category_id` INT(11) NOT NULL AUTO_INCREMENT ,
				`expense_category_desc` VARCHAR(100) NOT NULL ,
				PRIMARY KEY (`expense_category_id`) 
			);",	
			"INSERT INTO `mcb_expenses_categories` (expense_category_desc) values ('GENERIC')",
			"ALTER TABLE `mcb_expenses` ADD COLUMN `expense_category_id` INT(11) NOT NULL  DEFAULT '1' AFTER `expense_id` , 
			ADD CONSTRAINT `expense_category_id`
			FOREIGN KEY (`expense_category_id` )
			REFERENCES `mcb_expenses_categories` (`expense_category_id` )
			ON DELETE NO ACTION
			ON UPDATE NO ACTION
			, ADD INDEX `expense_category_id` (`expense_category_id` ASC) ;"	
		);
		

		//Updating tables and records
		foreach ($queries as $query) {
			$this->db->query($query);
		}		
		
		//Updating version
		$db_array = array(
			'module_version'	=>	'1.5.0'
		);

		$this->db->where('module_path', 'accounts');
		$this->db->update('mcb_modules', $db_array);		
	}
}

?>