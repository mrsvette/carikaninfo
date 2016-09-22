<?php
$this->breadcrumbs=array(
	ucfirst(Yii::app()->controller->module->id)=>array('/'.Yii::app()->controller->module->id.'/'),
	Yii::t('global','Manage').' '.Yii::t('EcommerceModule.client','Client'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' '.Yii::t('EcommerceModule.client','Client'), 'url'=>array('view')),
	array('label'=>Yii::t('global','Create').' '.Yii::t('EcommerceModule.client','Client'), 'url'=>'#new', 'linkOptions'=>array('data-toggle'=>'tab')),
);
?>
<style>
.form-horizontal .form-group{border-top: 1px solid #e7e7e7;clear: both;padding: 20px 16px;position: relative;margin:0;}
#ModClient_status input, #ModClient_type input, #ModClient_gender input, #ModClient_tax_exempt input{float:left;margin-right:10px;}
#ModClient_type label, #ModClient_gender label, #ModClient_tax_exempt label {width:25%;float:left;}
#ModClient_status label {width:20%;float:left;}
</style>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title"><?php echo Yii::t('EcommerceModule.client','Client');?> #<?php echo $model->id;?> - <b><?php echo $model->fullName;?></b></h4>
	</div>
	<div class="panel-body">
		<ul class="nav nav-tabs">
			<li class="active">
				<a data-toggle="tab" href="#general">
					<strong><?php echo Yii::t('EcommerceModule.client','Profile');?></strong>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#update">
					<strong><?php echo Yii::t('global','Update').' '.Yii::t('EcommerceModule.client','Client');?></strong>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#order">
					<strong><?php echo Yii::t('EcommerceModule.client','Order');?></strong>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#invoice">
					<strong><?php echo Yii::t('EcommerceModule.client','Invoice');?></strong>
				</a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="general" class="tab-pane active">
				<div class="table-responsive">
				<?php $this->widget('zii.widgets.CDetailView', array(
					'data'=>$model,
					'htmlOptions'=>array('class'=>'table table-striped mb30'),
					'attributes'=>array(
						array(
							'label'=>'id',
							'type'=>'raw',
							'value'=>$model->id,
						),
						array(
							'label'=>'Name',
							'type'=>'raw',
							'value'=>$model->fullName,
						),
						array(
							'name'=>'company',
							'type'=>'raw',
							'value'=>(!empty($model->company))? $model->company : '-',
						),
						array(
							'name'=>'email',
							'type'=>'raw',
							'value'=>$model->email,
						),
						array(
							'name'=>'status',
							'type'=>'raw',
							'value'=>ucfirst($model->status),
						),
						array(
							'name'=>'client_group_id',
							'type'=>'raw',
							'value'=>$model->group_rel->title,
						),
						array(
							'name'=>'ip',
							'type'=>'raw',
							'value'=>$model->ip,
						),
						array(
							'name'=>'currency',
							'type'=>'raw',
							'value'=>(!empty($model->currency))? $model->currency : 'IDR',
						),
						array(
							'name'=>'address_1',
							'type'=>'raw',
							'value'=>$model->address_1.', '.ucfirst($model->city).', '.ucfirst($model->state).', '.$model->postcode,
						),
						array(
							'name'=>'phone',
							'type'=>'raw',
							'value'=>$model->phone,
						),
						array(
							'name'=>'date_entry',
							'type'=>'raw',
							'value'=>$model->date_entry,
						),
					),
				)); ?>
				</div>
				<?php echo CHtml::link('<i class="fa fa-lock"></i> '.Yii::t('EcommerceModule.client','Login to client area'),array('/ecommerce/clients/login','id'=>$model->id),array('class'=>'btn btn-default','target'=>'_newtab'));?>
				<?php echo CHtml::link('<i class="fa fa-trash-o"></i> '.Yii::t('global','Delete'),array('/ecommerce/clients/delete','id'=>$model->id),array('class'=>'btn btn-default','onclick'=>'return hapus(this);'));?>
			</div>
			<div id="update" class="tab-pane">
				<?php echo $this->renderPartial('_form_manage',array('model'=>$model));?>
			</div>
			<div id="order" class="tab-pane">
				<?php echo $this->renderPartial('_client_order',array('dataProvider'=>$orderProvider));?>
			</div>
			<div id="invoice" class="tab-pane">
				<?php echo $this->renderPartial('_client_invoice',array('dataProvider'=>$invoiceProvider));?>
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
</script>
