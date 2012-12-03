<table style="width: 100%;">
	<thead>
		<tr>
			<th colspan="7" align="center"><?php echo $this->lang->line('balance_income');?></th>
		</tr>
		<tr>	
			<th scope="col" class="first"><?php echo $this->lang->line('costgroup');?></th>
			<th scope="col"><?php echo $this->lang->line('lastmonth');?></th>
			<th scope="col" class="last"><?php echo $this->lang->line('thismonth');?></th>
			<th scope="col" class="last"><?php echo $this->lang->line('lastquarter');?></th>
			<th scope="col" class="last"><?php echo $this->lang->line('thisquarter');?></th>
			<th scope="col" class="last"><?php echo $this->lang->line('lastyear');?></th>
			<th scope="col" class="last"><?php echo $this->lang->line('thisyear');?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($get_invoice_amounts AS $group_title => $group_values):?>
			<tr>
				<td><?php echo $group_title;?></td>
				<td><?php echo display_currency($group_values->l_month);?></td>
				<td><?php echo display_currency($group_values->t_month);?></td>
				<td><?php echo display_currency($group_values->l_quarter);?></td>
				<td><?php echo display_currency($group_values->t_quarter);?></td>
				<td><?php echo display_currency($group_values->l_year);?></td>
				<td><?php echo display_currency($group_values->t_year);?></td>
			</tr>
		<?php endforeach;?>
	</tbody>
	<thead>
		<tr>
			<th colspan="7" align="center"><?php echo $this->lang->line('balance_expenses');?></th>
		</tr>
		<tr>	
			<th scope="col" class="first"><?php echo $this->lang->line('costgroup');?></th>
			<th scope="col"><?php echo $this->lang->line('lastmonth');?></th>
			<th scope="col" class="last"><?php echo $this->lang->line('thismonth');?></th>
			<th scope="col" class="last"><?php echo $this->lang->line('lastquarter');?></th>
			<th scope="col" class="last"><?php echo $this->lang->line('thisquarter');?></th>
			<th scope="col" class="last"><?php echo $this->lang->line('lastyear');?></th>
			<th scope="col" class="last"><?php echo $this->lang->line('thisyear');?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($get_expense_amounts AS $group_title => $group_values):?>
			<tr>
				<?php if (strpos($group_title, '=>') === false ) { ?>
					<td><?php echo $group_title;?></td>
				<?php } else { ?>
					<td><?php echo substr($group_title, strpos($group_title, '=>'));?></td>
				<?php } ?>
				<td><?php echo display_currency($group_values->l_month);?></td>
				<td><?php echo display_currency($group_values->t_month);?></td>
				<td><?php echo display_currency($group_values->l_quarter);?></td>
				<td><?php echo display_currency($group_values->t_quarter);?></td>
				<td><?php echo display_currency($group_values->l_year);?></td>
				<td><?php echo display_currency($group_values->t_year);?></td>
			</tr>
		<?php endforeach;?>
	</tbody>
</table>