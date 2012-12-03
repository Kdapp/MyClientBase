<?php $this->load->view('dashboard/header'); ?>
<?php $this->load->view('dashboard/jquery_date_picker'); ?>

<div class="grid_10" id="content_wrapper">
	<div class="section_wrapper">
		<h3 class="title_black"><?php echo $this->lang->line('progress_chart'); ?></h3>
		<div class="content toggle">
			<script type="text/javascript" src="https://www.google.com/jsapi"></script>
			<script type="text/javascript">
			google.load("visualization", "1", {
				packages: ["corechart"]
			});
			
			jQuery(function () {
				submitData();
			
				jQuery('#chart_range button').click(function () {
					if (jQuery(this).attr('name') == 'day' || jQuery(this).attr('name') == 'week' || jQuery(this).attr('name') == 'month' || jQuery(this).attr('name') == 'year') {
			
						jQuery(this).parent().find('span').remove();
						jQuery(this).prepend('<span>&bull; </span>');
			
						jQuery('#type').val(jQuery(this).attr('name'));
						jQuery('#offset').val(0);
					} else if (jQuery(this).attr('name') == 'prev') {
						jQuery('#offset').val(parseInt(jQuery('#offset').val()) - 1);
					} else if (jQuery(this).attr('name') == 'next') {
						jQuery('#offset').val(parseInt(jQuery('#offset').val()) + 1);
					}
			
					submitData();
				});
			});
			
			function submitData() {
				var type = jQuery('#type').val();
				var offset = jQuery('#offset').val();
			
				jQuery('#chart_div').html('<div style="font-weight: bold;position:absolute;width:70px;text-align:center;background-color:#F0F0F0;padding:6px;border:1px dashed #DDD;left:50%;top:50%;margin:-13px 0 0 -42px;">Loading...</div>');
			
				jQuery.ajax({
					url: '<?php echo site_url("chart/get_chart_data"); ?>',
					dataType: 'json',
					data: {
						type: type,
						offset: offset
					},
					success: function (json) {
						drawChart(json.data, json.title, json.haxistitle, json.vaxistitle);
					}
				});
			
				return false;
			}
			
			function drawChart(chart_data, title, hAxisTitle, vAxisTitle) {
				var chart_options = {
					title: title,
					animation: {
						duration: 1000,
						easing: 'out',
					},
					hAxis: {
						title: hAxisTitle,
						textStyle: {
							fontSize: 10
						}
					},
					vAxis: {
						title: vAxisTitle,
						viewWindowMode: "explicit",
						viewWindow: {
							min: 0
						},
						textStyle: {
							fontSize: 12
						}
					}
				};
			
				var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
				chart.draw(google.visualization.arrayToDataTable(chart_data), chart_options);
			}
			</script>
			<div id="chart_div" style="height:500px;position:relative;margin-bottom:20px;"></div>
			<div id="chart_range" style="text-align:center">
				<button name="prev" style="float:left;">Prev</button>
				<button name="day"><span>&bull; </span>Day</button>
				<button name="week">Week</button>
				<button name="month">Month</button>
				<button name="year">Year</button>
				<button name="next" style="float:right;">Next</button>
				<input type="hidden" name="type" id="type" value="day" />
				<input type="hidden" name="offset" id="offset" value="0" />
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('dashboard/footer'); ?>
