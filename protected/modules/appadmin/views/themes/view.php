<?php
$this->breadcrumbs=array(
	'Themes'=>array('view'),
	Yii::t('global','Manage'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' Themes', 'url'=>array('view')),
	//array('label'=>Yii::t('global','Create').' Themes', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiListView.update('themes-list', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div class="row">
	<?php $this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'_view',
		'summaryText'=>'',
		'id'=>'themes-list',
	));?>
</div>
