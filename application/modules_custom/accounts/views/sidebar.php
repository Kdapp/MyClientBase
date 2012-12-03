<div class="grid_3" id="sidebar">

	<div class="section_wrapper">
	
		<h3 class="title_white"><?php echo $this->lang->line('accounts'); ?></h3>
		
		<ul class="quicklinks content toggle" id="quicklinks_client_center">
			<li><?php echo anchor('accounts/categories/data_table', $this->lang->line('cat_expenses')); ?></li>
			<li><?php echo anchor('accounts', $this->lang->line('expenses')); ?></li>
			<li class="last"><?php echo anchor('accounts/reports', $this->lang->line('reports')); ?></li>			
		</ul>

	</div>


</div>