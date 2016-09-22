<div class="alert alert-success hide">
	<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
	<span id="message"></span>
</div>
<?php $form=$this->beginWidget('CActiveForm',array('id'=>'client-form','htmlOptions'=>array('class'=>'form-horizontal'))); ?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

	<h4><?php echo Yii::t('EcommerceModule.client','Client profile details');?></h4>

	<div class="form-group">
		<?php echo $form->labelEx($model,'status',array('class'=>'col-md-3')); ?>
		<div class="col-md-6">
			<?php echo $form->radioButtonList($model,'status',$model->statuses,array('template'=>'{input} {label}','separator'=>'')); ?>
			<?php echo $form->error($model,'status'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'type',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->radioButtonList($model,'type',$model->types,array('template'=>'{input} {label}','separator'=>'')); ?>
			<?php echo $form->error($model,'type'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'client_group_id',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->dropDownList($model,'client_group_id',CHtml::listData(ModClientGroup::model()->findAll(), 'id', 'title'),array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'client_group_id'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'email',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->textField($model,'email',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'email'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'full_name',array('class'=>'col-md-3')); ?>
		<div class="col-md-3">
		<?php echo $form->textField($model,'first_name',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'first_name'); ?>
		</div>
		<div class="col-md-3">
		<?php echo $form->textField($model,'last_name',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'last_name'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'gender',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->radioButtonList($model,'gender',$model->genders,array('template'=>'{input} {label}','separator'=>'')); ?>
			<?php echo $form->error($model,'gender'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'birthday',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
		<?php 
			$this->widget('ext.CJuiDateTimePicker.CJuiDateTimePicker',array(
						'model'=>$model, //Model object
						'attribute'=>'birthday', //attribute name
						'mode'=>'date', //use "time","date" or "datetime" (default)
						'options'=>array(
							'showAnim'=>'fold',
							'dateFormat'=>'yy-mm-dd',
							'changeMonth' => 'true',
							'changeYear'=>'true',
							'constrainInput' => 'true'
						),
						'htmlOptions'=>array('placeholder'=>'yyyy-mm-dd','class'=>'form-control'),
					));
		?>
		<?php echo $form->error($model,'birthday'); ?>
		</div>
	</div>

	<div class="form-group">
		<label class="col-md-3"><?php echo Yii::t('EcommerceModule.client','Company Detail');?></label>
		<div class="col-md-3">
		<?php echo $form->textField($model,'company',array('placeholder'=>$model->getAttributeLabel('company'),'maxlength'=>128,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'company'); ?>
		</div>
		<div class="col-md-3">
		<?php echo $form->textField($model,'company_vat',array('placeholder'=>$model->getAttributeLabel('company_vat'),'maxlength'=>128,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'company_vat'); ?>
		</div>
		<div class="col-md-3">
		<?php echo $form->textField($model,'company_number',array('placeholder'=>$model->getAttributeLabel('company_number'),'maxlength'=>128,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'company_number'); ?>
		</div>
	</div>

	<h4><?php echo Yii::t('EcommerceModule.client','Address and contact details');?></h4>

	<div class="form-group">
		<?php echo $form->labelEx($model,'address_1',array('class'=>'col-md-3')); ?>
		<div class="col-md-9">
		<?php echo $form->textField($model,'address_1',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'address_1'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'address_2',array('class'=>'col-md-3')); ?>
		<div class="col-md-9">
		<?php echo $form->textField($model,'address_2',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'address_2'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'country',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->dropDownList($model,'country',$model->countries,array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'country'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'state',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->textField($model,'state',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'state'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'city',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->textField($model,'city',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'city'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'postcode',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->textField($model,'postcode',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'postcode'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'phone',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->textField($model,'phone',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'phone'); ?>
		</div>
	</div>

	<h4><?php echo Yii::t('EcommerceModule.client','Additional settings');?></h4>

	<div class="form-group">
		<?php echo $form->labelEx($model,'aid',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->textField($model,'aid',array('placeholder'=>$model->getAttributeLabel('aid'),'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'aid'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'currency',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->dropDownList($model,'currency',$model->currencies,array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'currency'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'tax_exempt',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->radioButtonList($model,'tax_exempt',array('No','Yes'),array('template'=>'{input} {label}','separator'=>'')); ?>
			<?php echo $form->error($model,'tax_exempt'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'notes',array('class'=>'col-md-3')); ?>
		<div class="col-md-9">
			<?php echo $form->textArea($model,'notes',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'notes'); ?>
		</div>
	</div>

	<div class="form-group buttons col-md-12">
		<?php 
		if($model->isNewRecord):
			echo CHtml::ajaxSubmitButton(Yii::t('global', 'Save'),CHtml::normalizeUrl(array('clients/create')),array('dataType'=>'json','success'=>'js:
				function(data){
					if(data.status=="success"){
						$("#message").html(data.div);
						$("#message").parent().removeClass("hide");
						$.fn.yiiGridView.update("client-grid", {
							data: $(this).serialize()
						});
						return false;
					}else{
						$("form[id=\'client-form\']").parent().html(data.div);
					}
					return false;
				}'
			),
			array('style'=>'width:100px','class'=>'btn btn-success','id'=>uniqid()));
		else:
			echo CHtml::ajaxSubmitButton(Yii::t('global', 'Update'),CHtml::normalizeUrl(array('clients/update','id'=>$model->id)),array('dataType'=>'json','success'=>'js:
				function(data){
					if(data.status=="success"){
						$("#message").html(data.div);
						$("#message").parent().removeClass("hide");
						return false;
					}else{
						$("form[id=\'client-form\']").parent().html(data.div);
					}
					return false;
				}'
			),
			array('style'=>'width:100px','class'=>'btn btn-success','id'=>uniqid()));
		endif;
		?>
	</div>

<?php $this->endWidget(); ?>
