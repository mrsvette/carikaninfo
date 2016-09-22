<?php
$this->breadcrumbs=array(
	ucfirst(Yii::app()->controller->module->id)=>array('/'.Yii::app()->controller->module->id.'/'),
	Yii::t('global','Manage'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' Page', 'url'=>array('admin')),
	array('label'=>Yii::t('global','Create').' Page', 'url'=>array('create')),
);
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-btns">
			<a class="panel-close" href="#">×</a>
			<a class="minimize" href="#">−</a>
		</div>
		<h4 class="panel-title"><?php echo Yii::t('global','Manage');?> Page</h4>
	</div>
	<div class="panel-body">
		<p>
		<?php echo Yii::t('global','You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.');?>
		</p>
		<div class="table-responsive">
		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'dataProvider'=>$model->search(),
			'filter'=>$model,
			'itemsCssClass'=>'table table-striped mb30',
			'afterAjaxUpdate' => 'reloadGrid',
			'columns'=>array(
				array(
					'value'=>'$this->grid->dataProvider->getPagination()->getOffset()+$row+1',
				),
				array(
					'name'=>'content_rel.title',
					'type'=>'raw',
					'value'=>'CHtml::link(CHtml::encode($data->content_rel->title), $data->url)'
				),
				array(
					'name'=>'status',
					'value'=>'Lookup::item("PostStatus",$data->status)',
					'filter'=>Lookup::items('PostStatus'),
				),
				array(
					'name'=>'create_time',
					'type'=>'datetime',
					'filter'=>false,
				),
				array(
					'class'=>'CButtonColumn',
					'template'=>'{view}{update}{delete}',
					'buttons'=>array(
						'view'=>array(
							'imageUrl'=>false,
							'label'=>'<span class="glyphicon glyphicon-search"></span>',
							'options'=>array('title'=>'View'),
							'visible'=>'Rbac::ruleAccess(\'read_p\')',
						),
						'update'=>array(
							'imageUrl'=>false,
							'label'=>'<span class="glyphicon glyphicon-pencil"></span>',
							'options'=>array('title'=>'Update'),
							'visible'=>'Rbac::ruleAccess(\'update_p\')',
						),
						'delete'=>array(
							'imageUrl'=>false,
							'label'=>'<span class="glyphicon glyphicon-trash"></span>',
							'options'=>array('title'=>'Delete'),
							'visible'=>'Rbac::ruleAccess(\'delete_p\')',
						),	
					),
					'visible'=>Rbac::ruleAccess('update_p'),
					'htmlOptions'=>array('style'=>'width:10%;','class'=>'table-action'),
				),
			),
		)); ?>
		</div>
	</div>
</div>
