<?php $this->load->view('dashboard/jquery_date_picker'); ?>
<?php echo form_open('accounts/reports');?>
<div style="padding:5px 5px 5px 10px;background-color:#DDEEFF;border:solid 1px #A4D1FF;font-family:Georgia,Times,serif;">
<?php echo $this->lang->line('from_date');?> : <input class="datepicker" type="text" name="from_date" value="" size="10" />
&nbsp;&nbsp;<?php echo $this->lang->line('to_date');?>   : <input class="datepicker" type="text" name="to_date" value="" size="10" />
<?php /*echo $this->lang->line('category');?> : <?php
		$this->load->model('mdl_categories');	
		$category_list=$this->mdl_categories->get_records();	

		$choices=array();
	
		if (isset($category_list)) {
			$choices['all']=$this->lang->line('all_categories');
						
			foreach($category_list as $opt) {
				$choices[$opt->expense_category_id]=$opt->expense_category_desc;
			}
			
			echo form_dropdown('expense_category_id', $choices, "all","style='font-family: Open Sans,sans-serif;'");
		}
					 */?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="btn_generate_report" class="report" value="Generate" />		
</div>   
</form>