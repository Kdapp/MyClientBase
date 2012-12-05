<?php $this->load->view('dashboard/header'); ?>
<div class="grid_7" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black">
			<?php echo $this->lang->line('reports'); ?>
			<?php $this->load->view('btn_print'); ?>
		</h3>

		<?php $this->load->view('dashboard/system_messages'); ?>

		<div class="content toggle no_padding">
		<?php 
		 $this->load->view('options');?></div>
		 <div class="content toggle no_padding">
		
		<table class="advancedtable" width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
		    <th scope="row"><?php echo $this->lang->line('total_payment');?></th>
		    <th><?php echo $this->lang->line('total_pending_payment');?></th>
		    <th><?php echo $this->lang->line('total_expense');?></th>
		    <th><?php echo $this->lang->line('balance');?></th>
		  </tr>
		  <tr>
		    <td scope="row">
		    <?php  $from_date='';
			       $to_date='';
				   if($this->input->post('from_date'))         	  $from_date=strtotime(standardize_date($this->input->post('from_date')));
		  	       if($this->input->post('to_date'))         	  $to_date=strtotime(standardize_date($this->input->post('to_date')));
				        
		 ?>
    	
		<?php
		$total_payment=$this->mdl_accounts->total_payment($from_date,$to_date);
		$total_expense=$this->mdl_accounts->total_expense($from_date,$to_date);
		$total_pending=$this->mdl_accounts->custome_total_pending($from_date,$to_date);
		
		echo anchor('payments',display_currency($total_payment));
		 //echo anchor('payments', display_currency($this->mdl_accounts->total_payment($from_date,$to_date))); ?></td>
	    <td><?php echo anchor('invoices/index/status/open',display_currency($total_pending)); ?></td>
	    <td><?php echo anchor('accounts/',display_currency($total_expense));?></td>
	    <td><?php echo display_currency($total_payment-$total_expense); ?>
    
    </td>

 
</tr>
</table>

</div>
</div>

</div>
<?php $this->load->view('sidebar');?>
<?php $this->load->view('dashboard/sidebar'); ?>

<?php $this->load->view('dashboard/footer'); ?>