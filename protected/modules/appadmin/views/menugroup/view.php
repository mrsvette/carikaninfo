<?php
$this->breadcrumbs=array(
	'Menu Group'=>array('index'),
	Yii::t('global','Manage'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' Menu Group', 'url'=>array('index')),
	array('label'=>Yii::t('global','Create').' Menu Group', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('menu-group-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-btns">
			<a class="panel-close" href="#">×</a>
			<a class="minimize" href="#">−</a>
		</div>
		<h4 class="panel-title"><?php echo Yii::t('global','Manage');?> Menu Group</h4>
	</div>
	<div class="panel-body">
		<p><?php echo Yii::t('global','You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.');?></p>

		<?php echo CHtml::link(Yii::t('global','Advanced Search'),'#',array('class'=>'search-button')); ?>
		<div class="search-form" style="display:none">
		<?php $this->renderPartial('_search',array(
			'model'=>$model,
		)); ?>
		</div><!-- search-form -->
		<div class="table-responsive">
		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'menu-group-grid',
			'dataProvider'=>$model->search(),
			'filter'=>$model,
			'itemsCssClass'=>'table table-striped mb30',
			'columns'=>array(
				array(
					'value'=>'$this->grid->dataProvider->getPagination()->getOffset()+$row+1',
				),
				'nama_group',
				'key',
				array(
					'name'=>'status',
					'value'=>'Lookup::item(\'MenuGroupStatus\',$data->status)',
					'filter'=>Lookup::items('MenuGroupStatus'),
					'htmlOptions'=>array('style'=>'text-align:center'),
				),
				array(
					'name'=>'user_entry',
					'value'=>'$data->userentry->username',
				),
				array(
					'class'=>'CButtonColumn',
					'template'=>'{update}{delete}',
				),
			),
		)); ?>
		</div>
	</div>
</div>
