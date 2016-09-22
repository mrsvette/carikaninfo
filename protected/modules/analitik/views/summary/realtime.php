<?php
$this->breadcrumbs=array(
	'Analytics'=>array('realtime'),
	Yii::t('analytic','Realtime'),
);

$this->menu=array(
	array('label'=>Yii::t('analytics','Realtime'), 'url'=>array('realtime')),
	array('label'=>Yii::t('analytics','Audiences'), 'url'=>array('audiences')),
);
?>

<div id="bloglist" class="row">
	<div class="col-xs-6 col-sm-4 col-md-3">
		<div class="blog-item blog-quote">
			<div class="quote quote-primary">
				<a href="#">
				<h1 style="font-size:80px;" id="ol-user"><?php echo Visitor::getActiveVisitor();?></h1>
				<h3 class="quote-author nomargin">Online User</h3>
				</a>
			</div>
			<div class="blog-details">
				<ul class="blog-meta">
					<li>
					<a href="#"><?php echo date("l");?></a>
					</li>
					<li><?php echo date("M d, Y");?></li>
					<!--<li>
					<a href="#">2 Comments</a>
					</li>-->
				</ul>
			</div>
		</div>
		<div id="piechart" style="width: 100%; height: 80px"></div>
	</div>
	<div class="col-md-9">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title"><?php echo Yii::t('anaytics','Page Views Per Minute');?></h4>
			</div>
			<div class="panel-body">
				<div id="realtimechart" style="width: 100%; height: 220px"></div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-8">
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-btns">
				<a class="panel-close" href="#">×</a>
				<a class="minimize" href="#">−</a>
			</div>
			<h4 class="panel-title"><?php echo Yii::t('analytics','Top Active Pages');?>:</h4>
		</div>
		<div class="panel-body">
			<div class="table-responsive">
		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'active-page-grid',
			'dataProvider'=>$dataProvider,
			'itemsCssClass'=>'table table-striped mb30',
			'afterAjaxUpdate' => 'reloadGrid',
			'summaryText'=>'',
			'columns'=>array(
				array(
					'value'=>'$this->grid->dataProvider->getPagination()->getOffset()+$row+1',
				),
				array(
					'name'=>'Active Page',
					'type'=>'raw',
					'value'=>'parse_url($data[\'url\'])[\'path\']',
				),
				array(
					'name'=>'Active User',
					'type'=>'raw',
					'value'=>'Visitor::getCountActivePage($data[\'url\'])',
				),
				/*array(
					'class'=>'CButtonColumn',
					'template'=>'{update}{delete}',
					'buttons'=>array(
						'update'=>array(
							'imageUrl'=>false,
							'label'=>'<span class="glyphicon glyphicon-pencil"></span>',
							'options'=>array('title'=>'Update'),
							'visible'=>'Rbac::ruleAccess(\'update_p\')',
						),
						'delete'=>array(
							'imageUrl'=>false,
							'label'=>'<span class="glyphicon glyphicon-trash"></span>',
							'options'=>array('title'=>'Delete'),
							'visible'=>'Rbac::ruleAccess(\'delete_p\')',
						),	
					),
					'visible'=>Rbac::ruleAccess('update_p'),
					'htmlOptions'=>array('style'=>'width:10%;','class'=>'table-action'),
				),*/
			),
		)); ?>
		</div>
		</div>
	</div>
	</div>
	<div class="col-sm-4">
		
	</div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/tisa/js/flot/flot.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/tisa/js/flot/flot.pie.min.js"></script>
<script type="text/javascript">
$(function(){
/***** REAL TIME UPDATES *****/
    
    var data = [], totalPoints = 30;
	function getRandomData() {
        
        if (data.length > 0)
				data = data.slice(1);

		  // Do a random walk
		  while (data.length < totalPoints) {

            var prev = data.length > 0 ? data[data.length - 1] : 50,
                y = prev + Math.random() * 10 - 5;
    
            if (y < 0) {
                y = 0;
            } else if (y > 100) {
                y = 100;
            }
            data.push(y);
        }

        // Zip the generated y values with the x values
        var res = [];
		  for (var i = 0; i < data.length; ++i) {
            res.push([i, data[i]])
		  }
		return res;
	}

	 
    // Set up the control widget
	 var updateInterval = 10000;

	 var plot4 = jQuery.plot("#realtimechart", [ getRandomData() ], {
        colors: ["#F0AD4E"],
		  series: {
            lines: {
              fill: false,
              lineWidth: 1
            },
            shadowSize: 0	// Drawing is faster without shadows
		  },
        grid: {
            borderColor: '#ddd',
            borderWidth: 1,
            labelMargin: 10
		  },
        xaxis: {
            color: '#eee'
        },
		  yaxis: {
				min: 0,
				max: 10,
            color: '#eee'
		  }
	 });

	 function update() {

		  /*plot4.setData([getRandomData()]);

		  // Since the axes don't change, we don't need to call plot.setupGrid()
		  plot4.draw();
		  setTimeout(update, updateInterval);*/
		//var dt = '<?php echo Visitor::getGraphPerMinute();?>';
		//var dt =[[0,5],[1,7],[2,3],[3,1],[4,5],[5,5]];
		//alert(dt);
		//var dt=[[0,55.77610485102064],[1,57.58450040715075],[2,53.925440077693466],[3,51.83367309584633],[4,53.820095986030445],[5,52.504377415540965]];
		//alert(getRandomData());
		
		$.ajax({
			'url': "<?php echo Yii::app()->createUrl('/analitik/summary/graphPerMinute');?>",
			'dataType': 'json',
			'type':'post',
			'success': function(data) {
				if(data.status=='success'){
					plot4.setData([data.result]);
					plot4.draw();
		 			setTimeout(update, updateInterval);
				}
			}
		});
	 }

	update();
	function labelFormatter(label, series) {
		return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>" + Math.round(series.percent) + "%</div>";
	}
	olineUser();
	function olineUser() {
		$.ajax({
			'url': "<?php echo Yii::app()->createUrl('/analitik/summary/onlineUser');?>",
			'dataType': 'json',
			'type':'post',
			'success': function(data) {
				if(data.status=='success'){
					$('#ol-user').html(data.result);
					$.fn.yiiGridView.update('active-page-grid');
					setTimeout(olineUser, updateInterval);
				}
			}
		});
	}
	browserPercentage();
	var piedata = [
						{ label: "Desktop", data: [[1,0]], color: '#1CAF9A'},
						{ label: "Mobile", data: [[1,0]], color: '#D9534F'},
					 ];
	var plot5 = jQuery.plot('#piechart', piedata, {
						series: {
							pie: {
								show: true,
								radius: 500,
								label: {
								    show: true,radius: 0.5,formatter: labelFormatter,threshold: 0.1
								}
							}
						},
						grid: {hoverable: true,clickable: true },
						legend: {show: false,}
					});
	function browserPercentage() {
		$.ajax({
			'url': "<?php echo Yii::app()->createUrl('/analitik/summary/browserPercentage');?>",
			'dataType': 'json',
			'type':'post',
			'success': function(data) {
				if(data.status=='success'){
					var piedata = [
						{ label: "Desktop", data: [[1,data.desktop]], color: '#1CAF9A'},
						{ label: "Mobile", data: [[1,data.mobile]], color: '#D9534F'},
					 ];
					plot5.setData(piedata);
					plot5.draw();
					setTimeout(browserPercentage, updateInterval);
				}
			}
		});
	}
});
</script>
