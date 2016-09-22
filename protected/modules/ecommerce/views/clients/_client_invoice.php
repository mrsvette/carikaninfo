<div class="table-responsive">
	<?php $this->widget('zii.widgets.grid.CGridView', array(
			'dataProvider'=>$dataProvider,
			'itemsCssClass'=>'table table-striped mb30',
			'id'=>'invoice-grid',
			'afterAjaxUpdate' => 'reloadGrid',
			'columns'=>array(
						array(
							'name'=>'id',
							'type'=>'raw',
							'value'=>'CHtml::link($data->id,array(\'ecommerce/invoices/manage\',\'id\'=>$data->id))',
							'htmlOptions'=>array('style'=>'width:5%;'),
						),
						array(
							'header'=>'No. Invoice',
							'type'=>'raw',
							'value'=>'$data->formatedNumber',
						),
						array(
							'name'=>'base_income',
							'type'=>'raw',
							'value'=>'number_format($data->base_income,2,\',\',\'.\')',
						),
						array(
							'name'=>'status',
							'type'=>'raw',
							'value'=>'ucfirst($data->getStatuses($data->status))',
						),
						array(
							'name'=>'date_entry',
							'type'=>'raw',
							'value'=>'$data->date_entry',
						),
						array(
							'name'=>'paid_at',
							'type'=>'raw',
							'value'=>'$data->paid_at',
						),
						array(
							'class'=>'CButtonColumn',
							'template'=>'{view}',
							'buttons'=>array
								(
									'view'=>array(
											'label'=>'<i class="fa fa-pencil"></i>',
											'imageUrl'=>false,
											'options'=>array('title'=>'View'),
											'url'=>'Yii::app()->createUrl(\'ecommerce/invoices/manage\',array(\'id\'=>$data->id))',
											'visible'=>'Rbac::ruleAccess(\'read_p\')',
										),
								),
							'htmlOptions'=>array('style'=>'width:10%;','class'=>'table-action'),
						),
					),
				)); ?>
</div>
