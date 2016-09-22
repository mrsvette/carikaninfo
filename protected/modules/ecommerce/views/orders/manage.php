<?php
$this->breadcrumbs=array(
	ucfirst(Yii::app()->controller->module->id)=>array('/'.Yii::app()->controller->module->id.'/'),
	Yii::t('global','Manage').' '.Yii::t('EcommerceModule.order','Order'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' '.Yii::t('EcommerceModule.order','Order'), 'url'=>array('view')),
	array('label'=>Yii::t('global','Create').' '.Yii::t('EcommerceModule.order','Order'), 'url'=>'#new', 'linkOptions'=>array('data-toggle'=>'tab')),
);
?>
<style>
.form-horizontal .form-group{border-top: 1px solid #e7e7e7;clear: both;padding: 20px 16px;position: relative;margin:0;}
#ModClient_status input, #ModClient_type input, #ModClient_gender input, #ModClient_tax_exempt input{float:left;margin-right:10px;}
#ModClient_status label, #ModClient_type label, #ModClient_gender label, #ModClient_tax_exempt label {width:25%;float:left;}
</style>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title"><?php echo Yii::t('EcommerceModule.order','Order');?> #<?php echo $model->id;?></b></h4>
	</div>
	<div class="panel-body">
		<ul class="nav nav-tabs">
			<li class="active">
				<a data-toggle="tab" href="#general">
					<strong><?php echo Yii::t('EcommerceModule.order','Details');?></strong>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#update">
					<strong><?php echo Yii::t('global','Update').' '.Yii::t('EcommerceModule.order','Order');?></strong>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#invoice">
					<strong><?php echo Yii::t('EcommerceModule.order','Invoice');?></strong>
				</a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="general" class="tab-pane active">
				<div class="table-responsive">
				<div class="mt10"></div>
				<h4 class="no-margin"><?php echo $model->title;?></h4>
				<div class="mb20"></div>
				<?php $this->widget('zii.widgets.CDetailView', array(
					'data'=>$model,
					'htmlOptions'=>array('class'=>'table table-striped mb30'),
					'attributes'=>array(
						array(
							'label'=>'order',
							'type'=>'raw',
							'value'=>'#'.$model->id.' ('.$model->product_rel->type.')',
						),
						array(
							'label'=>'Client',
							'type'=>'raw',
							'value'=>CHtml::link(
								$model->client_rel->fullName.' '.(!empty($model->client_rel->company))? $model->client_rel->company : '-',
								array('/ecommerce/clients/manage','id'=>$model->client_id)
							)
						),
						array(
							'name'=>'title',
							'type'=>'raw',
							'value'=>CHtml::link($model->title,array('/ecommerce/products/manage','id'=>$model->product_id)),
						),
						array(
							'name'=>'status',
							'type'=>'raw',
							'value'=>$model->getStatuses($model->status),
						),
						array(
							'name'=>'notes',
							'type'=>'raw',
							'value'=>$model->notes,
						),
						array(
							'name'=>'promo_id',
							'type'=>'raw',
							'value'=>($model->promo_id)? $model->promo_rel->code : '-' ,
						),
						array(
							'name'=>'date_entry',
							'type'=>'raw',
							'value'=>$model->date_entry,
						),
						array(
							'name'=>'activated_at',
							'type'=>'raw',
							'value'=>$model->activated_at,
							'visible'=>($model->status==ModClientOrder::ACTIVE)? true : false,
						),
						array(
							'name'=>'expires_at',
							'type'=>'raw',
							'value'=>$model->expires_at,
							'visible'=>($model->status==ModClientOrder::ACTIVE)? true : false,
						),
						array(
							'name'=>'suspended_at',
							'type'=>'raw',
							'value'=>$model->suspended_at,
							'visible'=>($model->status==ModClientOrder::SUSPENDED)? true : false,
						),
						array(
							'name'=>'canceled_at',
							'type'=>'raw',
							'value'=>$model->canceled_at,
							'visible'=>($model->status==ModClientOrder::CANCELED)? true : false,
						),
					),
				)); ?>
				</div>
				<?php if($model->status == ModClientOrder::ACTIVE): ?>
					<?php echo CHtml::link('<i class="fa fa-ban"></i> '.Yii::t('EcommerceModule.order','Suspend'),array('/ecommerce/orders/suspend','id'=>$model->id),array('class'=>'btn btn-default act-btn','title'=>'Suspend'));?>
				<?php endif; ?>
				<?php if($model->status == ModClientOrder::PENDING_SETUP || $model->status == ModClientOrder::FAILED_SETUP): ?>
					<?php echo CHtml::link('<i class="fa fa-play"></i> '.Yii::t('EcommerceModule.order','Activate'),array('/ecommerce/orders/activate','id'=>$model->id),array('class'=>'btn btn-default act-btn','title'=>'Activate'));?>
				<?php endif; ?>
				<?php if($model->status == ModClientOrder::SUSPENDED): ?>
					<?php echo CHtml::link('<i class="fa fa-check-circle"></i> '.Yii::t('EcommerceModule.order','Unsuspend'),array('/ecommerce/orders/unsuspend','id'=>$model->id),array('class'=>'btn btn-default act-btn','title'=>'Unsuspend'));?>
				<?php endif; ?>
				<?php echo CHtml::link('<i class="glyphicon glyphicon-usd"></i> '.Yii::t('EcommerceModule.order','Issue Invoice'),array('/ecommerce/invoices/create','order_id'=>$model->id),array('class'=>'btn btn-default act-btn','title'=>'Issue Invoice'));?>
				<?php echo CHtml::link('<i class="fa fa-trash-o"></i> '.Yii::t('global','Delete'),array('/ecommerce/orders/delete','id'=>$model->id),array('class'=>'btn btn-default','onclick'=>'return hapus(this);'));?>
			</div>
			<div id="update" class="tab-pane">
				<?php echo $this->renderPartial('_form_manage',array('model'=>$model));?>
			</div>
			<div id="invoice" class="tab-pane">
				<h4><?php echo Yii::t('EcommerceModule.order','Order Invoices');?></h4>
				<?php echo $this->renderPartial('/invoices/_view',array('dataProvider'=>$dataProvider));?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
function hapus(data){
	if(confirm('Are you sure to delete this?')){
		$.ajax({
			'beforeSend': function() { Loading.show(); },
			'complete': function() { Loading.hide(); },
			'url': $(data).attr('href'),
			'type':'post',
		   	'dataType': 'json',
		  	'success': function(data){
				if(data.status=='success'){
					
				}
			},
		});
	}
	return false;
}
$(function(){
	$('a.act-btn').click(function(){
		var title = $(this).attr('title');
		if(confirm('Are you sure to "'+title+'" this order?')){
			$.ajax({
				'beforeSend': function() { Loading.show(); },
				'complete': function() { Loading.hide(); },
				'url': $(this).attr('href'),
				'type':'post',
			   	'dataType': 'json',
			  	'success': function(data){
					if(data.status=='success'){
						window.location.reload(true);
					}
				},
			});
		}
		return false;
	});
});
</script>
