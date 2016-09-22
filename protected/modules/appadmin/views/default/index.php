<?php
$this->breadcrumbs=array(
	$this->module->id,
);
?>
<link href="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/tisa/css/morris.css" rel="stylesheet">
<div class="row">
	<div class="col-sm-6 col-md-3">
          <div class="panel panel-success panel-stat">
            <div class="panel-heading">
            	<small class="stat-label">Kunjungan Hari Ini</small>
        	</div>
            <div class="panel-body">
              <div class="stat">
                <div class="row">
                  <div class="col-xs-4">
					<i class="fa fa-users fa-2x"></i>
                  </div>
                  <div class="col-xs-8">
                    <h1><?php echo Visitor::getCountVisitor(date("Y-m-d"),'session');?></h1>
                  </div>
                </div><!-- row -->
                
                <div class="mb15"></div>
                
                <div class="row">
                  <div class="col-xs-6">
                    <small class="stat-label">Kemarin</small>
                    <h4><?php echo Visitor::getCountVisitor(date("Y-m-d",(time()-86400)),'session');?></h4>
                  </div>
                  
                  <div class="col-xs-6">
                    <small class="stat-label">Bulan Ini</small>
                    <h4><?php echo Visitor::getCountVisitor(date("Y-m-d"),'sessionmonthly');?></h4>
                  </div>
                </div><!-- row -->
              </div><!-- stat -->
              
            </div><!-- panel-heading -->
          </div><!-- panel -->
        </div><!-- col-sm-6 -->
        
        <div class="col-sm-6 col-md-3">
          <div class="panel panel-default">
            <div class="panel-heading">
          		<small class="stat-label">Hal. Terkunjungi Hari Ini</small>
			</div>
            <div class="panel-body">
              <div class="stat">
                <div class="row">
                  <div class="col-xs-4">
                  		<i class="fa fa-search fa-2x"></i>
                  </div>
                  <div class="col-xs-8">
                    <h1><?php echo Visitor::getCountVisitor(date("Y-m-d"),'pageview');?></h1>
                  </div>
                </div><!-- row -->
                <div class="mb15"></div>
				<div class="row">
                  <div class="col-xs-6">
                    <small class="stat-label">Kemarin</small>
                    <h4><?php echo Visitor::getCountVisitor(date("Y-m-d",(time()-86400)),'pageview');?></h4>
                  </div>
                  
                  <div class="col-xs-6">
                    <small class="stat-label">Bulan Ini</small>
                    <h4><?php echo Visitor::getCountVisitor(date("Y-m"),'pageviewmonthly');?></h4>
                  </div>
                </div><!-- row -->
                  
              </div><!-- stat -->
              
            </div><!-- panel-heading -->
          </div><!-- panel -->
        </div><!-- col-sm-6 -->
        
        <div class="col-sm-6 col-md-3">
          <div class="panel panel-default">
            <div class="panel-heading">
    			<small class="stat-label">Unique Visitors</small>
            </div>
            <div class="panel-body">
              <div class="stat">
                <div class="row">
                  <div class="col-xs-4">
           				<i class="fa fa-user fa-2x"></i>
                  </div>
                  <div class="col-xs-8">
                    <h1><?php echo Visitor::getUniqueVisitors();?></h1>
                  </div>
                </div><!-- row -->
                
                <div class="mb15"></div>
                
                <small class="stat-label">Avg. Visit Duration</small>
                <h4><?php echo Visitor::getAverageDuration();?></h4>
                  
              </div><!-- stat -->
              
            </div><!-- panel-heading -->
          </div><!-- panel -->
        </div><!-- col-sm-6 -->
        
        <div class="col-sm-6 col-md-3">
          <div class="panel panel-default">
            <div class="panel-heading">
         		<small class="stat-label">Total Posts</small>
            </div>
            <div class="panel-body">
              <div class="stat">
                <div class="row">
                  <div class="col-xs-4">
                  		<i class="fa fa-rss fa-2x"></i>
                  </div>
                  <div class="col-xs-8">
                    <h1><?php echo Post::getTotalPost();?></h1>
                  </div>
                </div><!-- row -->
                
                <div class="mb15"></div>
                <small class="stat-label">Komentar</small>
                <h4><?php echo Post::getTotalComment();?></h4>
                  
              </div><!-- stat -->
              
            </div><!-- panel-heading -->
          </div><!-- panel -->
        </div><!-- col-sm-6 -->
      </div><!-- row -->
<div class="row">
	<div class="col-sm-12 col-md-12">
		<div class="panel panel-default">
            <div class="panel-body">
              	<div class="row">
                	<div class="col-sm-12">
                  		<h5 class="subtitle mb5">Website Traffic</h5>
                  		<div id="area-chart" style="height: 300px;"></div>
                	</div><!-- col-sm-12 -->
              	</div><!-- row -->
            </div><!-- panel-body -->
        </div><!-- panel -->
 	</div><!-- col-sm-12 -->
</div><!-- row -->
<script async src="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/tisa/js/morris.min.js"></script>
<script async src="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/tisa/js/raphael-2.1.0.min.js"></script>
<script type="text/javascript">
function morris_area(data){
	new Morris.Area({
        element: 'area-chart',
		data: data,
        xkey: 'y',
        ykeys: ['a','b'],
        labels: ['Page View','Session'],
        lineColors: ['#1CAF9A','#F0AD4E'],
        lineWidth: '1px',
        fillOpacity: 0.8,
        smooth: false,
        hideHover: true
    });
}
function draw_chart(){
	$.ajax({
		'url': "<?php echo Yii::app()->createUrl('/analitik/summary/pageViewInterval');?>",
		'dataType': 'json',
		'type':'post',
		'success': function(data) {
			if(data.status=='success'){
				morris_area(data.result);
			}
		}
	});
}
if (window.addEventListener){ // W3C standard
	window.addEventListener('load', draw_chart, false); // NB **not** 'onload'
}else if (window.attachEvent){ // Microsoft
	window.attachEvent('onload', draw_chart);
}
</script>
