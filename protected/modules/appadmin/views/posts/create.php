<?php
$this->breadcrumbs=array(
	ucfirst(Yii::app()->controller->module->id)=>array('/'.Yii::app()->controller->module->id.'/'),
	Yii::t('global','Create').' '.Yii::t('post','Post'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' '.Yii::t('post','Post'), 'url'=>array('admin')),
);
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-btns">
			<a class="panel-close" href="#">×</a>
			<a class="minimize" href="#">−</a>
		</div>
		<h4 class="panel-title"><?php echo Yii::t('global','Create').' '.Yii::t('post','Post');?></h4>
	</div>
	<div class="panel-body">
		<?php echo $this->renderPartial('_form', array('model'=>$model,'model2'=>$model2)); ?>
	</div>
</div>
