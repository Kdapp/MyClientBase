<?php $this->load->view('dashboard/header'); ?>

<div class="grid_7" id="content_wrapper">

	<div class="section_wrapper">

		<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" style="display: inline;">

		<h3 class="title_black"><?php echo $this->lang->line('balance_costgroups'); ?>
		
			<input type="submit" name="btn_add" style="float: right; margin-top: 10px; margin-right: 10px;" value="<?php echo $this->lang->line('add'); ?>" />
			<input type="submit" id="btn_create_mti" name="btn_create_mti" style="float: right; margin-top: 10px; margin-right: 10px; display: none;" value="<?php echo $this->lang->line('create_mti'); ?>" />
		
		</h3>

		<?php $this->load->view('dashboard/system_messages'); ?>

		<div class="content toggle no_padding">

			<table style="width: 100%;">
				<tr>
					<?php if (isset($show_task_selector)) { ?><th scope="col" class="first">&nbsp;</th><?php } ?>
					<th scope="col" <?php if (!isset($show_task_selector)) { ?>class="first"<?php } ?>><?php echo $this->lang->line('balance_costgroup');?></th>
					<th scope="col"><?php echo $this->lang->line('edit');?></th>
					<th scope="col" class="last"><?php echo $this->lang->line('delete');?></th>
				</tr>
				<?php foreach ($groups as $group) {?>
				<tr>
					<td><?php echo $group->expense_category_desc;?></td>
					<td><?php echo anchor('balanceoverview/form/expense_category_id/' . $group->expense_category_id, $this->lang->line('edit'), array('class'=>'edit'));?></td>
					<td class="last"><?php echo anchor('balanceoverview/delete/expense_category_id/' . $group->expense_category_id, $this->lang->line('delete'), array('class'=>'delete', 'onclick'=>"javascript:if(!confirm('" . $this->lang->line('confirm_delete') . "')) return false"));?></td>
				</tr>
				<?php }?>
			</table>

		</div>

		</form>

	</div>

</div>

<?php $this->load->view('dashboard/footer'); ?>