<?php
$this->breadcrumbs=array(
	ucfirst(Yii::app()->controller->module->id)=>array('/'.Yii::app()->controller->module->id.'/'),
	Yii::t('global','Manage').' '.Yii::t('PortfolioModule.portfolio','Portfolio'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' '.Yii::t('PortfolioModule.portfolio','Portfolio'), 'url'=>array('view')),
	array('label'=>Yii::t('global','Create').' '.Yii::t('PortfolioModule.portfolio','Portfolio'), 'url'=>'#new', 'linkOptions'=>array('data-toggle'=>'tab')),
);
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title"><?php echo Yii::t('global','Manage').' '.Yii::t('PortfolioModule.portfolio','Portfolio');?></h4>
	</div>
	<div class="panel-body">
		<ul class="nav nav-tabs">
			<li class="active">
				<a data-toggle="tab" href="#general">
					<strong><?php echo Yii::t('PortfolioModule.portfolio','Portfolio');?></strong>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#new">
					<strong><?php echo Yii::t('global','Create').' '.Yii::t('PortfolioModule.portfolio','Portfolio');?></strong>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#category">
					<strong><?php echo Yii::t('PortfolioModule.portfolio','Portfolio Category');?></strong>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#new-category">
					<strong><?php echo Yii::t('global','Create').' '.Yii::t('PortfolioModule.portfolio','Portfolio Category');?></strong>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#setting">
					<strong><?php echo Yii::t('PortfolioModule.portfolio','Portfolio Settings');?></strong>
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
					'id'=>'portfolio-grid',
					'afterAjaxUpdate' => 'reloadGrid',
					'columns'=>array(
						array(
							'value'=>'$this->grid->dataProvider->getPagination()->getOffset()+$row+1',
						),
						array(
							'name'=>'content_rel.title',
							'type'=>'raw',
							'value'=>'CHtml::link(CHtml::encode($data->content_rel->title), $data->url)."<br/>".
								CHtml::image(Yii::app()->request->baseUrl."/".$data->firstImage->thumb.$data->firstImage->image)',
							'filter'=>CHtml::activeTextField($dataProvider->model,'title_cr'),
						),
						array(
							'name'=>'content_rel.content',
							'type'=>'raw',
							'value'=>'$data->parseContent(50,false)',
							'filter'=>CHtml::activeTextField($dataProvider->model,'content_cr'),
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
											'url'=>'Yii::app()->createUrl(\'portfolio/pDefault/manage\',array(\'id\'=>$data->id))',
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
				<?php echo $this->renderPartial('_form_portfolio',array('model'=>new ModPortfolio('create'),'model2'=>new ModPortfolioContent('create')));?>
			</div>
			<div id="category" class="tab-pane">
				<?php echo $this->renderPartial('_category',array('categoryProvider'=>$categoryProvider));?>
			</div>
			<div id="new-category" class="tab-pane">
				<?php echo $this->renderPartial('_form_category',array('model'=>new ModPortfolioCategory));?>
			</div>
			<div id="setting" class="tab-pane">
				<?php echo $this->renderPartial('_form_setting',array('model'=>$setting));?>
			</div>
		</div>
	</div>
</div>
