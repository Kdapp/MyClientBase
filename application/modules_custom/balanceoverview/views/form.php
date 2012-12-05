<?php $this->load->view('dashboard/header', array('header_insert'=>array('dashboard/jquery_date_picker','invoices/jquery_choose_client'))); ?>

<div class="grid_10" id="content_wrapper">

    <div class="section_wrapper">

        <h3 class="title_black"><?php echo $this->lang->line('balance_modgroup'); ?></h3>

        <div class="content toggle">

            <form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>">
				<dl>
					<dt><label><?php echo $this->lang->line('balance_costgroup');?>: </label></dt>
					<dd><input id="expense_category_desc" type="text" name="expense_category_desc" value="<?php echo $this->mdl_balanceoverview->form_value('expense_category_desc');?>" /></dd>
				</dl>
				
				<input type="submit" id="btn_submit" name="btn_submit" value="<?php echo $this->lang->line('submit');?>" />
				<input type="submit" id="btn_cancel" name="btn_cancel" value="<?php echo $this->lang->line('cancel');?>" />

			</form>

		</div>

	</div>

</div>

<?php $this->load->view('dashboard/footer'); ?>