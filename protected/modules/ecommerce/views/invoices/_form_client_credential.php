<div class="alert alert-success hide">
	<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
	<span id="message-client"></span>
</div>
<?php $form=$this->beginWidget('CActiveForm',array('id'=>'invoice-client-form','htmlOptions'=>array('class'=>'form-horizontal'))); ?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

	<h4><?php echo Yii::t('EcommerceModule.invoice','Client details at the moment of purchase');?></h4>

	<div class="form-group">
		<?php echo $form->labelEx($model,'buyer_first_name',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->textField($model,'buyer_first_name',array('placeholder'=>$model->getAttributeLabel('buyer_first_name'),'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'buyer_first_name'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'buyer_last_name',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->textField($model,'buyer_last_name',array('placeholder'=>$model->getAttributeLabel('buyer_last_name'),'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'buyer_last_name'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'buyer_company',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->textField($model,'buyer_company',array('placeholder'=>$model->getAttributeLabel('buyer_company'),'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'buyer_company'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'buyer_company_vat',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->textField($model,'buyer_company_vat',array('placeholder'=>$model->getAttributeLabel('buyer_company_vat'),'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'buyer_company_vat'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'buyer_company_number',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->textField($model,'buyer_company_number',array('placeholder'=>$model->getAttributeLabel('buyer_company_number'),'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'buyer_company_number'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'buyer_address',array('class'=>'col-md-3')); ?>
		<div class="col-md-6">
			<?php echo $form->textField($model,'buyer_address',array('placeholder'=>$model->getAttributeLabel('buyer_address'),'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'buyer_address'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'buyer_city',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->textField($model,'buyer_city',array('placeholder'=>$model->getAttributeLabel('buyer_city'),'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'buyer_city'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'buyer_state',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->textField($model,'buyer_state',array('placeholder'=>$model->getAttributeLabel('buyer_state'),'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'buyer_state'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'buyer_phone',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->textField($model,'buyer_phone',array('placeholder'=>$model->getAttributeLabel('buyer_phone'),'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'buyer_phone'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'buyer_zip',array('class'=>'col-md-3')); ?>
		<div class="col-md-3">
			<?php echo $form->textField($model,'buyer_zip',array('placeholder'=>$model->getAttributeLabel('buyer_zip'),'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'buyer_zip'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'buyer_email',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->textField($model,'buyer_email',array('placeholder'=>$model->getAttributeLabel('buyer_email'),'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'buyer_email'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'buyer_country',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->dropDownList($model,'buyer_country',ModClient::getCountries(),array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'buyer_country'); ?>
		</div>
	</div>

	<div class="form-group buttons col-md-12">
		<?php 
			echo CHtml::ajaxSubmitButton(Yii::t('global', 'Update'),CHtml::normalizeUrl(array('invoices/update','id'=>$model->id)),array('dataType'=>'json','success'=>'js:
				function(data){
					if(data.status=="success"){
						$("#message-client").html(data.div);
						$("#message-client").parent().removeClass("hide");
						return false;
					}else{
						$("form[id=\'invoice-client-form\']").parent().html(data.div);
					}
					return false;
				}'
			),
			array('style'=>'width:100px','class'=>'btn btn-success','id'=>uniqid()));
		?>
	</div>

<?php $this->endWidget(); ?>
