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
		<h4 class="panel-title"><?php echo Yii::t('global','Manage').' '.Yii::t('GalleryModule.gallery','Gallery');?></h4>
	</div>
	<div class="panel-body">
		<ul class="nav nav-tabs">
			<li class="active">
				<a data-toggle="tab" href="#general">
					<strong><?php echo Yii::t('GalleryModule.gallery','Gallery');?></strong>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#new">
					<strong><?php echo Yii::t('global','Create').' '.Yii::t('GalleryModule.gallery','Gallery');?></strong>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#category">
					<strong><?php echo Yii::t('GalleryModule.gallery','Category');?></strong>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#new-category">
					<strong><?php echo Yii::t('global','Create').' '.Yii::t('GalleryModule.gallery','Category');?></strong>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#setting">
					<strong><?php echo Yii::t('GalleryModule.gallery','Gallery Settings');?></strong>
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
					'id'=>'gallery-grid',
					'afterAjaxUpdate' => 'reloadGrid',
					'columns'=>array(
						array(
							'value'=>'$this->grid->dataProvider->getPagination()->getOffset()+$row+1',
						),
						array(
							'name'=>'name',
							'type'=>'raw',
							'value'=>'$data->name."<br/>".CHtml::image(Yii::app()->request->baseUrl."/".$data->thumb.$data->image)'
						),
						array(
							'name'=>'category_id',
							'type'=>'raw',
							'value'=>'$data->category_rel->title',
							'filter'=>CHtml::listData(ModGalleryCategory::model()->findAll(),'id','title'),
						),
						array(
							'name'=>'date_entry',
							'type'=>'raw',
							'value'=>'$data->date_entry',
						),
						array(
							'class'=>'CButtonColumn',
							'template'=>'{view}{delete}',
							'buttons'=>array
								(
									'view'=>array(
											'label'=>'<i class="fa fa-pencil"></i>',
											'imageUrl'=>false,
											'options'=>array('title'=>'View'),
											'url'=>'Yii::app()->createUrl(\'gallery/gDefault/manage\',array(\'id\'=>$data->id))',
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
				<?php echo $this->renderPartial('_form_gallery',array('model'=>new ModGallery('create')));?>
			</div>
			<div id="category" class="tab-pane">
				<div class="table-responsive">
				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'dataProvider'=>$categoryProvider,
					'filter'=>$categoryProvider->model,
					'itemsCssClass'=>'table table-striped mb30',
					'id'=>'gallery-category-grid',
					'afterAjaxUpdate' => 'reloadGrid',
					'columns'=>array(
						array(
							'value'=>'$this->grid->dataProvider->getPagination()->getOffset()+$row+1',
						),
						array(
							'name'=>'title',
							'type'=>'raw',
							'value'=>'$data->title'
						),
						array(
							'name'=>'slug',
							'type'=>'raw',
							'value'=>'$data->slug'
						),
						array(
							'name'=>'description',
							'type'=>'raw',
							'value'=>'$data->description'
						),
						array(
							'class'=>'CButtonColumn',
							'template'=>'{delete}',
							'buttons'=>array
								(
									'delete'=>array(
											'label'=>'<i class="fa fa-trash-o"></i>',
											'imageUrl'=>false,
											'options'=>array('title'=>'Delete'),
											'url'=>'Yii::app()->createUrl(\'gallery/gDefault/deleteCategory\',array(\'id\'=>$data->id))',
											'visible'=>'Rbac::ruleAccess(\'delete_p\')',
										),
								),
							'htmlOptions'=>array('style'=>'width:10%;','class'=>'table-action'),
						),
					),
				)); ?>
				</div>
			</div>
			<div id="new-category" class="tab-pane">
				<?php echo $this->renderPartial('_form_category',array('model'=>new ModGalleryCategory));?>
			</div>
			<div id="setting" class="tab-pane">
				<?php echo $this->renderPartial('_form_setting',array('model'=>$setting));?>
			</div>
		</div>
	</div>
</div>
