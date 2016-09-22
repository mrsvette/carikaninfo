<?php
$this->breadcrumbs=array(
	'Menu Groups'=>array('index'),
	Yii::t('global','Create'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' Menu Group', 'url'=>array('view')),
);
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-btns">
			<a class="panel-close" href="#">×</a>
			<a class="minimize" href="#">−</a>
		</div>
		<h4 class="panel-title"><?php echo Yii::t('global','Create');?> Menu Group</h4>
	</div>
	<div class="panel-body">
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>
