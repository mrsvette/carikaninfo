<?php
$this->breadcrumbs=array(
	'Analytics'=>array('audiences'),
	Yii::t('analytic','Audiences'),
);

$this->menu=array(
	array('label'=>Yii::t('analytics','Realtime'), 'url'=>array('realtime')),
	array('label'=>Yii::t('analytics','Audiences'), 'url'=>array('audiences')),
);
?>
<link href="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/tisa/css/morris.css" rel="stylesheet">
<div class="row">
	<div class="col-sm-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-btns">
				<a class="panel-close" href="#">×</a>
				<a class="minimize" href="#">−</a>
			</div>
			<h4 class="panel-title"><?php echo Yii::t('analytics','Audiences');?></h4>
		</div>
		<div class="panel-body">
			<div id="area-chart" style="height: 300px;"></div>
		</div>
	</div>
	</div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/tisa/js/flot/flot.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/tisa/js/flot/flot.pie.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/tisa/js/morris.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/tisa/js/raphael-2.1.0.min.js"></script>
<script type="text/javascript">
function morris_area(data){
	new Morris.Area({
        // ID of the element in which to draw the chart.
        element: 'area-chart',
        // Chart data records -- each entry in this array corresponds to a point on
        // the chart.
        /*data: [
            { y: '2006', a: 30 },
            { y: '2007', a: 75 },
            { y: '2008', a: 50 },
            { y: '2009', a: 75 },
            { y: '2010', a: 50 },
            { y: '2011', a: 75 },
            { y: '2012', a: 100 }
        ],*/
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
$(function(){
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
});
</script>
