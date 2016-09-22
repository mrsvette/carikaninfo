<?php
$this->breadcrumbs=array(
	ucfirst(Yii::app()->controller->module->id)=>array('/'.Yii::app()->controller->module->id.'/'),
	Yii::t('global','Manage').' '.Yii::t('TestimonialModule.testimonial','Testimonial'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' '.Yii::t('TestimonialModule.testimonial','Testimonial'), 'url'=>array('view')),
	array('label'=>Yii::t('global','Create').' '.Yii::t('TestimonialModule.testimonial','Testimonial'), 'url'=>'#new', 'linkOptions'=>array('data-toggle'=>'tab')),
);
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title"><?php echo Yii::t('global','Manage').' '.Yii::t('TestimonialModule.testimonial','Testimonial');?> #<?php echo $model->id;?> <?php echo $model->name;?> </h4>
	</div>
	<div class="panel-body">
		<?php $this->renderPartial('_form_testimonial',array('model'=>$model));?>
	</div>
</div>
