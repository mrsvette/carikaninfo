<div class="infosummary">
	<h5 class="sidebartitle">Information Summary</h5>    
	<ul>
		<li>
			<div class="datainfo">
				<span class="text-muted">Page Viewed /day</span>
				<h4><?php echo $model->averagePageView;?></h4>
			</div>
			<div id="sidebar-chart" class="chart"></div>   
		</li>
		<li>
			<div class="datainfo">
				<span class="text-muted">Average Users /day</span>
				<h4><?php echo $model->averageUsers;?></h4>
			</div>
			<div id="sidebar-chart2" class="chart"></div>   
		</li>
		<li>
			<div class="datainfo">
				<span class="text-muted">Online User</span>
				<h4 id="left-ol-user"><?php echo $model->activeVisitor;?></h4>
			</div>
		 	<div id="sidebar-chart3" class="chart"></div>   
		</li>
	</ul>
</div><!-- infosummary -->
<script type="text/javascript">
$(function(){
	var updateInterval = 20000;
	leftOlineUser();
	function leftOlineUser() {
		$.ajax({
			'url': "<?php echo Yii::app()->createUrl('/appadmin/analytics/onlineUser');?>",
			'dataType': 'json',
			'type':'post',
			'success': function(data) {
				if(data.status=='success'){
					$('#left-ol-user').html(data.result);
					setTimeout(leftOlineUser, updateInterval);
				}
			}
		});
	}
});
</script>
