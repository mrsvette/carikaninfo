<?php
$this->breadcrumbs=array(
	ucfirst(Yii::app()->controller->module->id)=>array('/'.Yii::app()->controller->module->id.'/'),
	'Slide Show'=>array('view'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('global','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' Slide Show', 'url'=>array('view')),
	array('label'=>Yii::t('global','Create').' Slide Show', 'url'=>array('create')),
);
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-btns">
			<a class="panel-close" href="#">×</a>
			<a class="minimize" href="#">−</a>
		</div>
		<h4 class="panel-title"><?php echo $model->name;?></h4>
	</div>
	<div class="panel-body">
		<ul class="nav nav-tabs">
			<li class="active">
				<a data-toggle="tab" href="#general">
					<strong><?php echo Yii::t('SlideshowModule.general','General Setting');?></strong>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#image">
					<strong><?php echo Yii::t('SlideshowModule.general','Slide Images');?></strong>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#preview" rel-href="<?php echo Yii::app()->createUrl('/slideshow/sDefault/preview',array('id'=>$model->id));?>">
					<strong><?php echo Yii::t('SlideshowModule.general','Slide Preview');?></strong>
				</a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="general" class="tab-pane active">
				<div class="mt20"></div>
				<div class="col-sm-12">
					<?php echo $this->renderPartial('_form',array('model'=>$model));?>
				</div>
			</div>
			<div id="image" class="tab-pane">
				<div class="mt20"></div>
				<?php echo $this->renderPartial('_item',array('model'=>$model2));?>
			</div>
			<div id="preview" class="tab-pane">
				<div class="mt20"></div>
				<?php //echo $this->renderPartial('_preview',array('model'=>$model));?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(function(){
	$('a[href="#preview"]').click(function(){
		$.ajax({
			beforeSend: function() { Loading.show(); },
			complete: function() { Loading.hide(); },
			url: $(this).attr('rel-href'),
			type: 'POST',
			dataType: 'json',
			success: function (data) {
				if(data.status=="success"){
					$("#preview").html(data.div);
					return false;
				}
			},
		});
		//return false;
	});
});
</script>
