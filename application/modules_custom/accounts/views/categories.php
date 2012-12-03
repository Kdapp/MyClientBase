<?php $this->load->view('dashboard/header'); ?>

<div class="grid_7" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black">
			<?php echo $this->lang->line('cat_expenses'); ?>
				<form method="post"	action="<?php echo $_SERVER['SCRIPT_NAME']."/accounts/categories/post_handler" ?>" style="display: inline;">
					<?php $this->load->view('btn_print'); ?>
					<button name="btn_add" value='add' class="uibutton ui-button ui-widget ui-state-default ui-corner-all" style="float: right; margin-top: 10px; margin-right: 10px; padding-right: 1em; padding-left: 1em; padding-bottom: 0.4em; padding-top: 0.4em; font-size: 0.7em;"><?php echo $this->lang->line('add'); ?></button>
				</form>
		</h3><!--

		<?php $this->load->view('dashboard/system_messages'); ?>

		--><div class="content toggle no_padding" style="min-height: 300px;">
			<?php $this->load->view('table_categories');?>
		
		</div>

	</div>

</div>
<?php $this->load->view('sidebar');?>
<?php $this->load->view('dashboard/sidebar'); ?>

<?php $this->load->view('dashboard/footer'); ?>