<dl>
	<dt><?php echo $this->lang->line('module_settings');?> (accounts)</dt>
</dl>
<dl>
	<dt><?php echo $this->lang->line('dashboard_show_open_accounts');?></dt>
	<dd>
		<input type="checkbox" name="dashboard_show_open_accounts" value="TRUE" <?php if($this->mdl_mcb_data->setting('dashboard_show_open_accounts') == "TRUE"){?>checked<?php }?> />
	</dd>
</dl>