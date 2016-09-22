<?php
$this->breadcrumbs=array(
	ucfirst(Yii::app()->controller->module->id)=>array('/'.Yii::app()->controller->module->id.'/'),
	Yii::t('global','Manage').' '.Yii::t('EcommerceModule.product','Product'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' '.Yii::t('EcommerceModule.product','Product'), 'url'=>array('view')),
	array('label'=>Yii::t('global','Create').' '.Yii::t('EcommerceModule.product','Product'), 'url'=>'#new', 'linkOptions'=>array('data-toggle'=>'tab')),
);
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title"><?php echo Yii::t('global','Manage').' '.Yii::t('EcommerceModule.product','Product');?></h4>
	</div>
	<div class="panel-body">
		<ul class="nav nav-tabs">
			<li class="active">
				<a data-toggle="tab" href="#general">
					<strong><?php echo Yii::t('EcommerceModule.product','Product');?></strong>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#new">
					<strong><?php echo Yii::t('global','Create').' '.Yii::t('EcommerceModule.product','Product');?></strong>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#category">
					<strong><?php echo Yii::t('EcommerceModule.product','Category');?></strong>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#new-category">
					<strong><?php echo Yii::t('global','Create').' '.Yii::t('EcommerceModule.product','Category');?></strong>
				</a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="general" class="tab-pane active">
				<div class="table-responsive">
				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'dataProvider'=>$dataProvider,
					'filter'=>$dataProvider->model,
					'itemsCssClass'=>'table table-striped mb30',
					'id'=>'product-grid',
					'afterAjaxUpdate' => 'reloadGrid',
					'columns'=>array(
						array(
							'value'=>'$this->grid->dataProvider->getPagination()->getOffset()+$row+1',
						),
						array(
							'name'=>'title',
							'type'=>'raw',
							'value'=>'CHtml::link($data->title,array(\'products/manage\',\'id\'=>$data->id))'
						),
						array(
							'name'=>'status',
							'type'=>'raw',
							'value'=>'ucfirst($data->status)',
						),
						array(
							'name'=>'product_category_id',
							'type'=>'raw',
							'value'=>'$data->category_rel->title',
							'filter'=>CHtml::listData(ModProductCategory::model()->findAll(), 'id', 'title')
						),
						array(
							'name'=>'type',
							'type'=>'raw',
							'value'=>'ucfirst($data->type)'
						),
						array(
							'class'=>'CButtonColumn',
							'template'=>'{view}{delete}',
							'buttons'=>array
								(
									'view'=>array(
											'label'=>'<i class="fa fa-pencil"></i>',
											'imageUrl'=>false,
											'options'=>array('title'=>'Manage'),
											'url'=>'Yii::app()->createUrl(\'ecommerce/products/manage\',array(\'id\'=>$data->id))',
											'visible'=>'Rbac::ruleAccess(\'read_p\')',
										),
									'delete'=>array(
											'label'=>'<i class="fa fa-trash-o"></i>',
											'imageUrl'=>false,
											'options'=>array('title'=>'Delete'),
											'visible'=>'Rbac::ruleAccess(\'delete_p\')',
										),
								),
							'htmlOptions'=>array('style'=>'width:10%;','class'=>'table-action'),
						),
					),
				)); ?>
				</div>
			</div>
			<div id="new" class="tab-pane">
				<?php echo $this->renderPartial('_form_product',array('model'=>new ModProduct));?>
			</div>
			<div id="category" class="tab-pane">
				<?php echo $this->renderPartial('_category',array('dataProvider'=>$categoryProvider));?>
			</div>
			<div id="new-category" class="tab-pane">
				<?php echo $this->renderPartial('_form_category',array('model'=>new ModProductCategory));?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(function(){
	var tabs = "<?php echo $_GET['tabs'];?>";
	if(tabs.length>0){
		$('.tab-pane').removeClass('active');
		$('#'+tabs).addClass('active');
		$('ul.nav-tabs').find('li.active').removeClass('active');
		$('a[href="#'+tabs+'"]').parent().addClass('active');
	}
});
</script>
