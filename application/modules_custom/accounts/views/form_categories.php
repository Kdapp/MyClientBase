<?php $this->load->view('dashboard/header'); ?>

<?php $this->load->view('dashboard/jquery_date_picker'); ?>

<div class="grid_10" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo $this->lang->line('category_form'); ?></h3>
		
		<?php echo validation_errors(); ?>
		
		<div class="content toggle">

			<form enctype='multipart/form-data' method="post" action="<?php echo $_SERVER['SCRIPT_NAME']."/accounts/categories/post_handler/expense_category_id/".(isset($category) ? $category[0]->expense_category_id : "") ?>">
				<dl>
					<dt><label><?php echo $this->lang->line('cat_id');?>: </label></dt>
						<dd><?php echo isset($category) ? $category[0]->expense_category_id : ""; ?></dd>
				</dl>
				<dl>
					<dt><label><?php echo $this->lang->line('cat_desc');?>: </label></dt>
					<dd><input id="expense_category_desc" type="text" name="expense_category_desc" value="<?php echo isset($category) ? $category[0]->expense_category_desc : ""; ?>" /></dd>
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