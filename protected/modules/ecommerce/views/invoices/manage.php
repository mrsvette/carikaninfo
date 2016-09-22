<?php
$this->breadcrumbs=array(
	ucfirst(Yii::app()->controller->module->id)=>array('/'.Yii::app()->controller->module->id.'/'),
	Yii::t('global','Manage').' '.Yii::t('EcommerceModule.invoice','Invoice'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' '.Yii::t('EcommerceModule.invoice','Invoice'), 'url'=>array('view')),
	array('label'=>Yii::t('global','Create').' '.Yii::t('EcommerceModule.invoice','Invoice'), 'url'=>'#new', 'linkOptions'=>array('data-toggle'=>'tab')),
);
?>
<style>
.form-horizontal .form-group{border-top: 1px solid #e7e7e7;clear: both;padding: 20px 16px;position: relative;margin:0;}
#ModInvoice_status input, #ModInvoice_approved input {float:left;margin-right:10px;}
#ModInvoice_status label, #ModInvoice_approved label {width:15%;float:left;}
</style>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title"><?php echo Yii::t('EcommerceModule.invoice','Invoice');?> #<?php echo $model->id;?></b></h4>
	</div>
	<div class="panel-body">
		<ul class="nav nav-tabs">
			<li class="active">
				<a data-toggle="tab" href="#general">
					<strong><?php echo Yii::t('EcommerceModule.invoice','Invoice Details');?></strong>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#update">
					<strong><?php echo Yii::t('global','Manage').' '.Yii::t('EcommerceModule.invoice','Invoice');?></strong>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#client">
					<strong><?php echo Yii::t('EcommerceModule.invoice','Client Credential');?></strong>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#company">
					<strong><?php echo Yii::t('EcommerceModule.invoice','Company Credential');?></strong>
				</a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="general" class="tab-pane row active">
				<div class="table-responsive col-sm-8">
				<div class="mt10"></div>
				<?php $this->widget('zii.widgets.CDetailView', array(
					'data'=>$model,
					'htmlOptions'=>array('class'=>'table table-striped mb30'),
					'attributes'=>array(
						array(
							'name'=>'id',
							'type'=>'raw',
							'value'=>'#'.$model->id,
						),
						array(
							'label'=>'Number',
							'type'=>'raw',
							'value'=>$model->formatedNumber,
						),
						array(
							'name'=>'currency',
							'type'=>'raw',
							'value'=>$model->currency,
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
							'name'=>'status',
							'type'=>'raw',
							'value'=>$model->getStatuses($model->status),
						),
						array(
							'label'=>'Total',
							'type'=>'raw',
							'value'=>$model->money_format($model->base_income),
						),
						array(
							'name'=>'date_entry',
							'type'=>'raw',
							'value'=>$model->date_entry,
						),
						array(
							'name'=>'due_at',
							'type'=>'raw',
							'value'=>$model->due_at,
						),
						array(
							'name'=>'paid_at',
							'type'=>'raw',
							'value'=>$model->paid_at,
						),
						array(
							'name'=>'reminded_at',
							'type'=>'raw',
							'value'=>$model->reminded_at,
						),
						array(
							'name'=>'gateway_id',
							'type'=>'raw',
							'value'=>$model->gateway_rel->name,
						),
						array(
							'name'=>'notes',
							'type'=>'raw',
							'value'=>$model->notes,
							'visible'=>(!empty($model->notes))? true : false,
						),
					),
				)); ?>
				</div>
				<div class="col-sm-12">
				<?php echo CHtml::link('<i class="fa fa-eye"></i> '.Yii::t('EcommerceModule.invoice','View As Client'),array('/invoice/view','id'=>$model->hash),array('class'=>'btn btn-default act-btn','title'=>'View As Client'));?>
				<?php if($model->status == ModInvoice::STATUS_PAID): ?>
					<?php echo CHtml::link('<i class="fa fa-ban"></i> '.Yii::t('EcommerceModule.invoice','Refund'),array('/ecommerce/invoices/refund','id'=>$model->id),array('class'=>'btn btn-default act-btn','title'=>'Refund'));?>
				<?php endif; ?>
				<?php if($model->status == ModInvoice::STATUS_UNPAID): ?>
					<?php echo CHtml::link('<i class="fa fa-play"></i> '.Yii::t('EcommerceModule.invoice','Mark As Paid'),array('/ecommerce/invoices/markAsPaid','id'=>$model->id),array('class'=>'btn btn-default act-btn','title'=>'Mark As Paid'));?>
					<?php echo CHtml::link('<i class="fa fa-envelope"></i> '.Yii::t('EcommerceModule.invoice','Send Reminder'),array('/ecommerce/invoices/sendReminder','id'=>$model->id),array('class'=>'btn btn-default act-btn','title'=>'Send Reminder'));?>
				<?php endif; ?>
				<?php echo CHtml::link('<i class="fa fa-trash-o"></i> '.Yii::t('global','Delete'),array('/ecommerce/invoices/delete','id'=>$model->id),array('class'=>'btn btn-default','onclick'=>'return hapus(this);'));?>
				</div>
			</div>
			<div id="update" class="tab-pane">
				<?php echo $this->renderPartial('_form_manage',array('model'=>$model));?>
			</div>
			<div id="client" class="tab-pane">
				<?php echo $this->renderPartial('_form_client_credential',array('model'=>$model));?>
			</div>
			<div id="company" class="tab-pane">
				<?php echo $this->renderPartial('_form_company_credential',array('model'=>$model));?>
			</div>
		</div>
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title"><?php echo Yii::t('EcommerceModule.invoice','Invoice Items');?></b></h4>
	</div>
	<div class="panel-body">
		<div class="table-responsive">
			<?php $form=$this->beginWidget('CActiveForm',array('id'=>'invoice-item-form')); ?>
			<table class="table table-bordered mb10">
				<tr>
					<th>&nbsp;</th>
					<th style="text-align:center;"><?php echo Yii::t('EcommerceModule.invoice','Item Name');?></th>
					<th style="text-align:center;"><?php echo Yii::t('EcommerceModule.invoice','Tax');?></th>
					<th style="text-align:center;"><?php echo Yii::t('EcommerceModule.invoice','Qty');?></th>
					<th style="text-align:center;"><?php echo Yii::t('EcommerceModule.invoice','Price');?></th>
					<th style="text-align:center;"><?php echo Yii::t('EcommerceModule.invoice','Total');?></th>
				</tr>
				<?php if($model->item_count>0): ?>
				<?php $no = 1; $tot = 0;?>
				<?php foreach($model->item_rel as $iitem):?>
					<tr>
						<td style="text-align:center;"><?php echo $no;?></td>
						<td><?php echo ($iitem->rel_id>0)? CHtml::link($iitem->title,array('/'.Yii::app()->controller->module->id.'/orders/manage','id'=>$iitem->rel_id)) : $iitem->title;?></td>
						<td style="text-align:center;">-</td>
						<td style="text-align:center;"><?php echo $iitem->quantity;?></td>
						<td style="text-align:right;"><?php echo $model->money_format($iitem->price);?></td>
						<?php $subtot = $iitem->price * $iitem->quantity; ?>
						<?php $tot = $tot + $subtot; ?>
						<td style="text-align:right;"><?php echo $model->money_format($subtot);?></td>
					</tr>
				<?php $no++;?>
				<?php endforeach;?>
				<?php else:?>
					<tr>
						<td style="text-align:center;"><?php echo CHtml::link('<i class="fa fa-plus fa-2x"></i>','javascript:void(0);',array('onclick'=>'addFunds(this);','title'=>'Tambah item'));?></td>
						<td><?php echo CHtml::textField('title[]','',array('placeholder'=>Yii::t('EcommerceModule.invoice','New line description')));?></td>
						<td style="text-align:center;">-</td>
						<td style="text-align:center;"><?php echo CHtml::textField('quantity[]',1,array('size'=>1,'onkeyup'=>'calculatePrice(this);'));?></td>
						<td style="text-align:right;"><?php echo CHtml::textField('price[]',0,array('size'=>3,'onkeyup'=>'calculatePrice(this);'));?></td>
						<td style="text-align:right;"><?php echo CHtml::textField('sub_total[]',0,array('size'=>3,'readOnly'=>true));?></td>
					</tr>
				<?php endif;?>
				<tr>
					<td colspan="6" style="text-align:right;">Sub Total : <span id="sub-total"><?php echo $model->money_format($tot);?></span></td>
				</tr>
				<tr>
					<td colspan="6" style="text-align:right;">Tax : -</td>
				</tr>
				<tr>
					<td colspan="6" style="text-align:right;font-weight:bold;">Total : <span id="grand-total"><?php echo $model->money_format($tot);?></span></td>
				</tr>
			</table>
		<?php 
		if($model->item_count<=0):
			echo CHtml::ajaxSubmitButton(Yii::t('global', 'Save'),CHtml::normalizeUrl(array('invoices/addItems','id'=>$model->id)),array('dataType'=>'json','success'=>'js:
				function(data){
					if(data.status=="success"){
						$("#message").html(data.div);
						$("#message").parent().removeClass("hide");
						window.location.reload(true);
						return false;
					}
					return false;
				}'
			),
			array('style'=>'width:100px','class'=>'btn btn-success','id'=>uniqid()));
		endif;
		?>
		<?php $this->endWidget(); ?>
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
		if(confirm('Are you sure to "'+title+'" this invoice?')){
			$.ajax({
				'beforeSend': function() { Loading.show(); },
				'complete': function() { Loading.hide(); },
				'url': $(this).attr('href'),
				'type':'post',
			   	'dataType': 'json',
			  	'success': function(data){
					if(data.status=='success'){
						if(!data.refresh)
							window.location.href = data.href;
						window.location.reload(true);
					}
				},
			});
		}
		return false;
	});
});
function addFunds(data){
	var tr = $(data).parent().parent();
	$('<tr>'+tr.html()+'</tr>').insertAfter(tr);
	$(data).attr('onclick','removeFund(this);');
	$(data).attr('title','Remove Item');
	$(data).html('<i class="fa fa-trash-o fa-2x"></i>');
	return false;
}
function removeFund(data){
	if(confirm('Are you sure to remove this item?')){
		var tr = $(data).parent().parent();
		tr.remove();
	}
	return false;
}
function calculatePrice(data){
	var price = $(data).parent().parent().find('#price').val();
	var quantity = $(data).parent().parent().find('#quantity').val();
	var sub_total = price*quantity;
	$(data).parent().parent().find('#sub_total').val(sub_total);
	grandTotal();
	return false;
}
function grandTotal(){
	var tot = 0;
	$('input[id="sub_total"]').each(function(){
		tot += parseInt($(this).val());
	});
	$('span[id="sub-total"]').html(tot);
	$('span[id="grand-total"]').html(tot);
	return false;
}
</script>
