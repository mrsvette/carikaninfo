<?php
$this->breadcrumbs=array(
	ucfirst(Yii::app()->controller->module->id)=>array('/'.Yii::app()->controller->module->id.'/'),
	'Post Categories'=>array('view'),
	Yii::t('global','Manage'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' Slide Show', 'url'=>array('view')),
	array('label'=>Yii::t('global','Create').' Slide Show ', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('slideshow-grid', {
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
		<h4 class="panel-title"><?php echo Yii::t('global','Manage');?> Slide Show</h4>
	</div>
	<div class="panel-body">
		<p>
		<?php echo Yii::t('global','You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.');?>
		</p>

		<?php echo CHtml::link(Yii::t('global','Advanced Search'),'#',array('class'=>'search-button')); ?>
		<div class="search-form" style="display:none">
		<?php $this->renderPartial('_search',array(
			'model'=>$model,
		)); ?>
		</div><!-- search-form -->
		<div class="table-responsive">
		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'slideshow-grid',
			'dataProvider'=>$model->search(),
			'filter'=>$model,
			'itemsCssClass'=>'table table-striped mb30',
			'afterAjaxUpdate' => 'reloadGrid',
			'columns'=>array(
				array(
					'value'=>'$this->grid->dataProvider->getPagination()->getOffset()+$row+1',
				),
				'name',
				array(
					'name'=>'slug',
					'value'=>'$data->slug',
				),
				array(
					'name'=>'status',
					'value'=>'CHtml::activeDropDownList($data,\'status\',$data->statuses,array(\'onchange\'=>\'changeStatus(this);\',\'attr-id\'=>$data->id))',
					'type'=>'raw',
					'filter'=>$model->statuses,
				),
				array(
					'header'=>'Usage',
					'value'=>'$data->usage',
				),
				array(
					'class'=>'CButtonColumn',
					'template'=>'{update}{delete}',
					'buttons'=>array
						(
							'update'=>array(
									'label'=>'<i class="fa fa-pencil"></i>',
									'imageUrl'=>false,
									'visible'=>'Rbac::ruleAccess(\'update_p\')',
								),
							'delete'=>array(
									'label'=>'<i class="fa fa-trash-o"></i>',
									'imageUrl'=>false,
									'visible'=>'Rbac::ruleAccess(\'delete_p\')',
								),
						),
				),
			),
		)); ?>
		</div>
	</div>
</div>
<script type="text/javascript">
function changeStatus(data){
	$.ajax({
		'beforeSend': function() { Loading.show(); },
		'complete': function() { Loading.hide(); },
		'url': "<?php echo Yii::app()->createUrl('/slideshow/sDefault/updateStatus');?>",
		'type':'post',
		'data':{'id':$(data).attr('attr-id'),'status':$(data).val()},
		'dataType': 'json',
		'success': function(result){
			if(result.status=='success'){
				
			}
		},
	});
	return false;
}
</script>
