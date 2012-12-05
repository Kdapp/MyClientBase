<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

$config = array(
	'module_path'			=>	'accounts',
	'module_name'			=>	'Accounts-Expenses',
	'module_description'	=>	'Manage your Accounts and Expenses',
	'module_author'			=>	'Help2Design',
	'module_homepage'		=>	'http://help2design.com/downloads/accounts-and-expense-module-for-myclientbase/',
	'module_version'		=>	'1.5.0',
	'module_config'			=>	array(
		'dashboard_widget'	=>	'accounts/dashboard_widget',
		'settings_view'		=>	'accounts/account_settings/display',
		'settings_save'		=>	'accounts/account_settings/save',
		'dashboard_menu'	=>	'accounts/header_menu'
	)
);

//$base_module_url=explode("/index.php",$_SERVER['SCRIPT_NAME']);

?>