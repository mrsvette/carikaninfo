<div class="alert alert-success hide">
	<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
	<span id="message-company"></span>
</div>
<?php $form=$this->beginWidget('CActiveForm',array('id'=>'invoice-company-form','htmlOptions'=>array('class'=>'form-horizontal'))); ?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

	<h4><?php echo Yii::t('EcommerceModule.invoice','Company details at the moment of purchase');?></h4>

	<div class="form-group">
		<?php echo $form->labelEx($model,'seller_company',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->textField($model,'seller_company',array('placeholder'=>$model->getAttributeLabel('seller_company'),'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'seller_company'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'seller_company_vat',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->textField($model,'seller_company_vat',array('placeholder'=>$model->getAttributeLabel('seller_company_vat'),'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'seller_company_vat'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'seller_company_number',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->textField($model,'seller_company_number',array('placeholder'=>$model->getAttributeLabel('seller_company_number'),'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'seller_company_number'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'seller_address',array('class'=>'col-md-3')); ?>
		<div class="col-md-6">
			<?php echo $form->textField($model,'seller_address',array('placeholder'=>$model->getAttributeLabel('seller_address'),'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'seller_address'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'seller_phone',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->textField($model,'seller_phone',array('placeholder'=>$model->getAttributeLabel('seller_phone'),'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'seller_phone'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'seller_email',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->textField($model,'seller_email',array('placeholder'=>$model->getAttributeLabel('seller_email'),'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'seller_email'); ?>
		</div>
	</div>

	<div class="form-group buttons col-md-12">
		<?php 
			echo CHtml::ajaxSubmitButton(Yii::t('global', 'Update'),CHtml::normalizeUrl(array('invoices/update','id'=>$model->id)),array('dataType'=>'json','success'=>'js:
				function(data){
					if(data.status=="success"){
						$("#message-company").html(data.div);
						$("#message-company").parent().removeClass("hide");
						return false;
					}else{
						$("form[id=\'invoice-company-form\']").parent().html(data.div);
					}
					return false;
				}'
			),
			array('style'=>'width:100px','class'=>'btn btn-success','id'=>uniqid()));
		?>
	</div>

<?php $this->endWidget(); ?>
