<?php
$this->breadcrumbs=array(
	ucfirst(Yii::app()->controller->module->id)=>array('/'.Yii::app()->controller->module->id.'/'),
	Yii::t('global','Manage').' '.Yii::t('EcommerceModule.product','Product'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' '.Yii::t('EcommerceModule.product','Category'), 'url'=>array('view','tabs'=>'category')),
	array('label'=>Yii::t('global','Create').' '.Yii::t('EcommerceModule.product','Category'), 'url'=>array('view','tabs'=>'new-category')),
);
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">#<?php echo $model->id;?> - <b><?php echo $model->title;?></b></h4>
	</div>
	<div class="panel-body">
		<ul class="nav nav-tabs">
			<li class="active">
				<a data-toggle="tab" href="#general">
					<strong><?php echo Yii::t('global','Update').' '.Yii::t('EcommerceModule.product','Category');?></strong>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#list">
					<strong><?php echo Yii::t('global','List').' '.Yii::t('EcommerceModule.product','Category');?></strong>
				</a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="general" class="tab-pane active">
				<div class="mt20"></div>
				<?php echo $this->renderPartial('_form_category',array('model'=>$model));?>
			</div>
			<div id="list" class="tab-pane">
				<?php echo $this->renderPartial('_category',array('dataProvider'=>$dataProvider));?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
function hapus(data){
	if(confirm('Are you sure to delete this?')){
		$.ajax({
			'beforeSend': function() { Loading.show(); },
			'complete': function() { Loading.hide(); },
			'url': $(data).attr('href'),
			'type':'post',
		   	'dataType': 'json',
		  	'success': function(data){
				if(data.status=='success'){
					
				}
			},
		});
	}
	return false;
}
</script>
