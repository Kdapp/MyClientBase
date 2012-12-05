<?php $this->load->view('dashboard/header'); ?>

<?php $this->load->view('dashboard/jquery_date_picker'); ?>

<?php $this->load->model('balanceoverview/mdl_balanceoverview'); ?>

<div class="grid_10" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo $this->lang->line('expenses_form'); ?></h3>

		<?php $this->load->view('dashboard/system_messages'); ?>

		<div class="content toggle">

			<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>">

				<dl>
					<dt><label><?php echo $this->lang->line('category');?>: </label></dt>
					<dd><?php
						$choices=array();
					
						if (isset($category_list)) {
							foreach($category_list as $opt) {
								$choices[$opt->expense_category_id]=$opt->expense_category_desc;
							}
							echo form_dropdown('expense_category_id', $choices, (isset($form_value) ? $form_value[0]->expense_category_id : "") ,"style='font-family: Open Sans,sans-serif;'");
						}
					 ?></dd>
				</dl>
				<dl>
					<dt><label><?php echo $this->lang->line('group'); ?>: </label></dt>
					<dd>
						<select name="expense_invoice_group_id">
							<?php $result = $this->mdl_balanceoverview->_get_invoice_groups()->result();?>
							<?php foreach ($result as $output) {?> 
								<option value="<?php echo $output->invoice_group_id;?>"<?php  if(isset($form_value) AND $form_value[0]->expense_invoice_group_id == $output->invoice_group_id) {?>selected<?php }?>><?php echo $output->invoice_group_name;?></option>
							<?php }?>
						</select>
					</dd>
				</dl>
				<dl>
					<dt><label><?php echo $this->lang->line('expense_date');?>: </label></dt>
					<dd><input class="datepicker" type="text" name="expense_date" value="<?php echo isset($form_value) ? date("d/m/Y",$form_value[0]->expense_date) : "" ;?>" /></dd>
					
				</dl>
				<dl>
					<dt><label><?php echo $this->lang->line('amount');?>: </label></dt>
					<dd><input id="amount" type="text" name="amount" value="<?php echo isset($form_value) ? $form_value[0]->amount : ""; ?>" /></dd>
				</dl>
				<dl>
					<dt><label><?php echo $this->lang->line('title');?>: </label></dt>
					<dd><input id="title" type="text" name="title" value="<?php echo $this->mdl_accounts->form_value('title');?>" /></dd>
				</dl>
				<dl>
					<dt><label><?php echo $this->lang->line('expense_for');?>: </label></dt>
					<dd><textarea id="expense_for" name="expense_for" rows="10" cols="50"><?php echo isset($form_value) ? $form_value[0]->expense_for : ""; ?></textarea></dd>					
				</dl>
				
				<input type="submit" id="btn_submit" name="btn_submit" value="<?php echo $this->lang->line('submit');?>" />
				<input type="submit" id="btn_cancel" name="btn_cancel" value="<?php echo $this->lang->line('cancel');?>" />
				<!-- Bersan Paolo 27/02/2012: Add print button -->
				<input type="button" style="margin-left:8px;font-family:open sans;" onclick="print();return false;" id="btn_print" name="btn_print" value="<?php echo $this->lang->line('btn_print');?>" />

			</form>

		</div>

	</div>

</div>

<?php $this->load->view('dashboard/footer'); ?>