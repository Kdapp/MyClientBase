<?php $this->load->view('dashboard/header'); ?>

	<div class="grid_7" id="content_wrapper">

		<div class="section_wrapper">

			<h3 class="title_black"><?php echo $this->lang->line('balance_overview');?></h3>
			<?php $this->load->view('dashboard/system_messages'); ?>
			<h4 class="title_black"><?php echo $this->lang->line('balance_income');?></h3>
			<div class="content toggle no_padding">
				<table style="width: 100%;">
					<tr>	
						<th scope="col" class="first" width="200"><?php echo $this->lang->line('balance_costgroup');?></th>
						<th scope="col" width="200"><?php echo $this->lang->line('lastmonth');?></th>
						<th scope="col" class="last" width="200"><?php echo $this->lang->line('thismonth');?></th>
						<th scope="col" class="last" width="200"><?php echo $this->lang->line('lastquarter');?></th>
						<th scope="col" class="last" width="200"><?php echo $this->lang->line('thisquarter');?></th>
						<th scope="col" class="last" width="200"><?php echo $this->lang->line('lastyear');?></th>
						<th scope="col" class="last" width="200"><?php echo $this->lang->line('thisyear');?></th>
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
				</table>
			<h4 class="title_black"><?php echo $this->lang->line('balance_expenses');?></h3>
				<table>
					<tr>	
						<th scope="col" class="first" width="200"><?php echo $this->lang->line('balance_costgroup');?></th>
						<th scope="col" width="200"><?php echo $this->lang->line('lastmonth');?></th>
						<th scope="col" class="last" width="200"><?php echo $this->lang->line('thismonth');?></th>
						<th scope="col" class="last" width="200"><?php echo $this->lang->line('lastquarter');?></th>
						<th scope="col" class="last" width="200"><?php echo $this->lang->line('thisquarter');?></th>
						<th scope="col" class="last" width="200"><?php echo $this->lang->line('lastyear');?></th>
						<th scope="col" class="last" width="200"><?php echo $this->lang->line('thisyear');?></th>
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
			</div>

		</div>

	</div>

<?php $this->load->view('dashboard/sidebar', array('side_block'=>'balanceoverview/sidebar')); ?>

<?php $this->load->view('dashboard/footer'); ?>