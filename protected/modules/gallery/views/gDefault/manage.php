<?php
$this->breadcrumbs=array(
	ucfirst(Yii::app()->controller->module->id)=>array('/'.Yii::app()->controller->module->id.'/'),
	Yii::t('global','Manage').' '.Yii::t('GalleryModule.gallery','Gallery'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' '.Yii::t('GalleryModule.gallery','Gallery'), 'url'=>array('view')),
	array('label'=>Yii::t('global','Create').' '.Yii::t('GalleryModule.gallery','Gallery'), 'url'=>'#new', 'linkOptions'=>array('data-toggle'=>'tab')),
);
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title"><?php echo Yii::t('global','Manage').' '.Yii::t('GalleryModule.gallery','Gallery');?> #<?php echo $model->id;?> <?php echo $model->name;?> </h4>
	</div>
	<div class="panel-body">
		<?php $this->renderPartial('_form_gallery',array('model'=>$model));?>
	</div>
</div>
