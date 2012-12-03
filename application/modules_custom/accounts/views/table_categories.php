<?php if ($categories) {?>

<table style="width: 100%;">
    <tr>
		<th scope="col" style="width:40px;"><?php echo $this->lang->line('cat_id');?></th>
		<th scope="col"><?php echo $this->lang->line('cat_desc');?></th>
        <th scope="col" style="width:60px;"><?php echo $this->lang->line('edit');?></th>
		<th scope="col" class="last" style="width:60px;"><?php echo $this->lang->line('delete');?></th>
    </tr>
	<?php foreach ($categories as $cat) {?>
    <tr>
        <td><?php echo $cat->expense_category_id;?></td>
        <td><?php echo $cat->expense_category_desc;?></td>
		<td><?php echo anchor('accounts/categories/edit_category/expense_category_id/' . $cat->expense_category_id, $this->lang->line('edit'), array('class'=>'edit'));?></td>
		<td class="last"><?php echo anchor('accounts/categories/delete_category/expense_category_id/' . $cat->expense_category_id, $this->lang->line('delete'), array('class'=>'delete', 'onclick'=>"javascript:if(!confirm('" . $this->lang->line('confirm_delete') . "')) return false"));?></td>
    </tr>
	<?php }?>
</table>

<?php if ($this->mdl_categories->page_links) { ?>
<div id="pagination">
	<?php echo $this->mdl_categories->page_links; ?>
</div>
<?php } ?>

<?php } else {?>
	<p><?php echo $this->lang->line('no_records_found');?>.</p><br />
<?php }?>