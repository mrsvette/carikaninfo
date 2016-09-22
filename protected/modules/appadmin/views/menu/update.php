<?php
$this->breadcrumbs=array(
	ucfirst(Yii::app()->controller->module->id)=>array('/'.Yii::app()->controller->module->id.'/'),
	'Menu'=>array('view'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('global','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' Menu', 'url'=>array('view')),
	array('label'=>Yii::t('global','Create').' Menu', 'url'=>array('create')),
);
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-btns">
			<a class="panel-close" href="#">×</a>
			<a class="minimize" href="#">−</a>
		</div>
		<h4 class="panel-title"><?php echo Yii::t('global','Update');?> Menu #<?php echo $model->id; ?></h4>
	</div>
	<div class="panel-body">
		<?php echo $this->renderPartial('_form', array('model'=>$model,'model2'=>$model2)); ?>
	</div>
</div>
<!--<script type="text/javascript">
$(function(){
	var type = $('select.types').find('option:selected').val();
	$('select.types').find('option').each(function(){
		if($(this).val()==type){
			$(this).trigger('change');
		}
	});
});
</script>-->
