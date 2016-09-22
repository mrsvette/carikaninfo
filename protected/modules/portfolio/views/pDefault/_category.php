<div class="table-responsive">
	<?php $this->widget('zii.widgets.grid.CGridView', array(
			'dataProvider'=>$categoryProvider,
			'filter'=>$categoryProvider->model,
			'itemsCssClass'=>'table table-striped mb30',
			'id'=>'portfolio-category-grid',
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
							'value'=>'CHtml::link($data->slug,array(\'/portfolio/pCategory/index\',\'slug\'=>$data->slug))'
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
											'url'=>'Yii::app()->createUrl(\'portfolio/pDefault/deleteCategory\',array(\'id\'=>$data->id))',
											'visible'=>'Rbac::ruleAccess(\'delete_p\')',
										),
								),
							'htmlOptions'=>array('style'=>'width:10%;','class'=>'table-action'),
						),
			),
	)); ?>
</div>
