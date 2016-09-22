<div class="table-responsive">
	<?php $this->widget('zii.widgets.grid.CGridView', array(
			'dataProvider'=>$dataProvider,
			'itemsCssClass'=>'table table-striped mb30',
			'id'=>'invoice-grid',
			'afterAjaxUpdate' => 'reloadGrid',
			'summaryText'=>'',
			'columns'=>array(
				/*array(
					'value'=>'$this->grid->dataProvider->getPagination()->getOffset()+$row+1',
				),*/
				array(
					'name'=>'id',
					'type'=>'raw',
					'value'=>'CHtml::link($data->id,array(\'invoice/manage\',\'id\'=>$data->id))',
					'htmlOptions'=>array('style'=>'width:5%;'),
				),
				array(
					'header'=>'Amount',
					'type'=>'raw',
					'value'=>'number_format($data->base_income,2,\',\',\'.\')',
					'htmlOptions'=>array('style'=>'text-align:left;'),
				),
				array(
					'header'=>'Issued At',
					'type'=>'raw',
					'value'=>'$data->date_entry',
				),
				array(
					'name'=>'paid_at',
					'type'=>'raw',
					'value'=>'$data->paid_at',
				),
				array(
					'name'=>'status',
					'type'=>'raw',
					'value'=>'ucfirst($data->status)',
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
