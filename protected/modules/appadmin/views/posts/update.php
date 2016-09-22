<?php
$this->breadcrumbs=array(
	ucfirst(Yii::app()->controller->module->id)=>array('/'.Yii::app()->controller->module->id.'/'),
	$model->content_rel->title=>$model->url,
	Yii::t('global','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' '.Yii::t('post','Post'), 'url'=>array('admin')),
	array('label'=>Yii::t('global','View').' '.Yii::t('post','Post'), 'url'=>array('view', 'id'=>$model->id)),
);
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-btns">
			<a class="panel-close" href="#">×</a>
			<a class="minimize" href="#">−</a>
		</div>
		<h4 class="panel-title"><?php echo Yii::t('global','Update');?> <i><?php echo CHtml::encode($model->content_rel->title); ?></i></h4>
	</div>
	<div class="panel-body">
		<?php echo $this->renderPartial('_form', array('model'=>$model,'model2'=>$model2)); ?>
	</div>
</div>
