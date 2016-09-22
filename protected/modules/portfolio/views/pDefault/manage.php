<?php
$this->breadcrumbs=array(
	ucfirst(Yii::app()->controller->module->id)=>array('/'.Yii::app()->controller->module->id.'/'),
	Yii::t('global','Manage').' '.Yii::t('PortfolioModule.portfolio','Portfolio'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' '.Yii::t('PortfolioModule.portfolio','Portfolio'), 'url'=>array('view')),
	array('label'=>Yii::t('global','Create').' '.Yii::t('PortfolioModule.portfolio','Portfolio'), 'url'=>array('view#new'), 'linkOptions'=>array('data-toggle'=>'tab')),
);
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title"><?php echo Yii::t('global','Manage').' '.Yii::t('PortfolioModule.portfolio','Portfolio');?> #<?php echo $model->id;?> <?php echo $model->content_rel->title;?> </h4>
	</div>
	<div class="panel-body">
		<?php $this->renderPartial('_form_portfolio',array('model'=>$model,'model2'=>$model2,'imagesProvider'=>$imagesProvider));?>
	</div>
</div>
