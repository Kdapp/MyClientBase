<?php if ($accounts) {?>

<table style="width: 100%;">
    <tr>
		<th scope="col" style="width:80px;"><?php echo $this->lang->line('expense_date');?></th>
		<th scope="col" style="width:80px;"><?php echo $this->lang->line('category');?></th>
		<th scope="col" style="width:60px;"><?php echo $this->lang->line('amount');?></th>
        <th scope="col"><?php echo $this->lang->line('expense_for');?></th>
		<th scope="col" style="width:60px;"><?php echo $this->lang->line('edit');?></th>
		<th scope="col" class="last" style="width:60px;"><?php echo $this->lang->line('delete');?></th>
    </tr>
	<?php foreach ($accounts as $account) {?>
    <tr>
		
		<td><?php if($account->expense_date){echo format_date($account->expense_date);}?></td>
		<td><?php if($account->expense_date){echo $account->expense_category_desc;}?></td>
		<td><?php echo display_currency($account->amount); ?></td>
        <td><?php echo $account->expense_for;?></td>
		<td><?php echo anchor('accounts/form/expense_id/' . $account->expense_id, $this->lang->line('edit'), array('class'=>'edit'));?></td>
		<td class="last"><?php echo anchor('accounts/delete/expense_id/' . $account->expense_id, $this->lang->line('delete'), array('class'=>'delete', 'onclick'=>"javascript:if(!confirm('" . $this->lang->line('confirm_delete') . "')) return false"));?></td>
    </tr>
	<?php }?>
</table>

<?php if ($this->mdl_accounts->page_links) { ?>
<div id="pagination">
	<?php echo $this->mdl_accounts->page_links; ?>
</div>
<?php } ?>

<?php } else {?>
	<p><?php echo $this->lang->line('no_records_found');?>.</p><br />
<?php }?>