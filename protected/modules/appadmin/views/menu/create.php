<?php
$this->breadcrumbs=array(
	ucfirst(Yii::app()->controller->module->id)=>array('/'.Yii::app()->controller->module->id.'/'),
	'Menus'=>array('view'),
	Yii::t('global','Create'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' Menu', 'url'=>array('view')),
	array('label'=>'Group Menu', 'url'=>array('/'.Yii::app()->controller->module->id.'/menugroup/view')),
);
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-btns">
			<a class="panel-close" href="#">×</a>
			<a class="minimize" href="#">−</a>
		</div>
		<h4 class="panel-title"><?php echo Yii::t('global','Create');?> Menu</h4>
	</div>
	<div class="panel-body">
		<?php echo $this->renderPartial('_form', array('model'=>$model,'model2'=>$model2)); ?>
	</div>
</div>
