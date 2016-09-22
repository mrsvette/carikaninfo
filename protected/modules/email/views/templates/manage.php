<?php
$this->breadcrumbs=array(
	ucfirst(Yii::app()->controller->module->id)=>array('/'.Yii::app()->controller->module->id.'/'),
	Yii::t('global','Manage').' '.Yii::t('EmailModule.template','Email Template'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' '.Yii::t('EmailModule.template','Email Template'), 'url'=>array('view')),
	array('label'=>Yii::t('global','Create').' '.Yii::t('EmailModule.template','Email Template'), 'url'=>'#new', 'linkOptions'=>array('data-toggle'=>'tab')),
);
?>
<style>
.form-horizontal .form-group{border-top: 1px solid #e7e7e7;clear: both;padding: 20px 16px;position: relative;margin:0;}
</style>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title"><?php echo Yii::t('EmailModule.template','Email Template');?> #<?php echo $model->id;?> - <b><?php echo $model->action_code;?></b></h4>
	</div>
	<div class="panel-body">
		<ul class="nav nav-tabs">
			<li class="active">
				<a data-toggle="tab" href="#general">
					<strong><?php echo Yii::t('EmailModule.template','Template');?></strong>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#variable">
					<strong><?php echo Yii::t('EmailModule.template','Variables');?></strong>
				</a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="general" class="tab-pane active">
				<?php echo $this->renderPartial('_form_manage',array('model'=>$model));?>
				<div id="preview" class="col-md-12"></div>
			</div>
			<div id="variable" class="tab-pane">
				<?php //echo $this->renderPartial('_form_manage',array('model'=>$model));?>
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
function preview(data){
	$.ajax({
		'beforeSend': function() { Loading.show(); },
		'complete': function() { Loading.hide(); },
		'url': $(data).attr('href'),
		'type':'post',
		'dataType': 'json',
		'success': function(data){
			if(data.status=='success'){
				$('#preview').html(data.div);
			}
		},
	});
	return false;
}
</script>
