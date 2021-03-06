<?php
$this->breadcrumbs=array(
	'Users'=>array('view'),
	Yii::t('global','View'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' User', 'url'=>array('view'),'visible'=>Rbac::ruleAccess('read_p')),
	array('label'=>Yii::t('global','Create').' User', 'url'=>array('create'), 'visible'=>Rbac::ruleAccess('create_p')),
	array('label'=>'User Group', 'url'=>array('rbacGroup/view'),'visible'=>Rbac::isAllowed('appadmin/rbacGroup/view','read_p')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('user-grid', {
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
		<h4 class="panel-title"><?php echo Yii::t('global','List');?> Users</h4>
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
			'id'=>'user-grid',
			'dataProvider'=>$model->search(),
			'itemsCssClass'=>'table table-striped mb30',
			'afterAjaxUpdate' => 'reinstallDatePicker', // (#1
			'filter'=>$model,
			'columns'=>array(
				array(
					'value'=>'$this->grid->dataProvider->getPagination()->getOffset()+$row+1',
				),
				'username',
				'email',
				array(
					'name'=>'group_id',
					//'value'=>'$data->group->group_name',
					'filter'=>RbacGroup::items(''),
				),
				array(
					'name'=>'status',
					'value'=>'Lookup::item(\'UserStatus\',$data->status)',
					'filter'=>Lookup::items('UserStatus'),
				),
				array(
					'name'=>'date_entry',
					'value'=>'date("d M Y H:i:s",strtotime($data->date_entry))',
					'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'model'=>$model, 
						'attribute'=>'date_entry', 
						'language' => 'id',
						'i18nScriptFile' => 'jquery.ui.datepicker-ja.js', // (#2)
						'htmlOptions' => array(
							'id' => 'datepicker_for_date_entry',
							'size' => '10',
						),
						'defaultOptions' => array(  // (#3)
							'showOn' => 'focus', 
							'dateFormat' => 'yy-mm-dd',
							'showOtherMonths' => true,
							'selectOtherMonths' => true,
							'changeMonth' => true,
							'changeYear' => true,
							'showButtonPanel' => true,
						)
						), 
						true), // (#4)
					'htmlOptions'=>array('style'=>'text-align:center'),
				),
				/* 'user_entry',
				'date_update',
				'user_update', */
				array(
					'class'=>'CButtonColumn',
					'template'=>'{update}{delete}{priviledge}',
					'buttons'=>array
						(
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
							'priviledge' => array
								(
									'label'=>'<span class="glyphicon glyphicon-eye-open"></span>',
									'options'=>array('title'=>'Priviledge'),
									'url'=>'Yii::app()->createUrl("/appadmin/users/priviledge", array("id"=>$data->id))',
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
<?php
// (#5)
Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
    $('#datepicker_for_date_entry').datepicker();
}
");
?>
