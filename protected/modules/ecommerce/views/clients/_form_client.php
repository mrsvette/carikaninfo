<div class="alert alert-success hide">
	<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
	<span id="message"></span>
</div>
<?php $form=$this->beginWidget('CActiveForm',array('id'=>'client-form')); ?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>
	<div class="form-group col-md-4">
		<?php echo $form->labelEx($model,'first_name'); ?>
		<?php echo $form->textField($model,'first_name',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'first_name'); ?>
	</div>

	<div class="form-group col-md-4">
		<?php echo $form->labelEx($model,'last_name'); ?>
		<?php echo $form->textField($model,'last_name',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'last_name'); ?>
	</div>

	<div class="form-group col-md-4">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="form-group col-md-4">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>80,'maxlength'=>128,'class'=>'form-control','readOnly'=>($model->isNewRecord)? false:true)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="form-group col-md-4">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->dropDownList($model,'type',$model->types,array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="form-group col-md-4">
		<?php echo $form->labelEx($model,'gender'); ?>
		<?php echo $form->dropDownList($model,'gender',$model->genders,array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'gender'); ?>
	</div>

	<div class="form-group col-md-4">
		<?php echo $form->labelEx($model,'birthday'); ?>
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

	<div class="form-group col-md-4">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>

	<div class="form-group col-md-4">
		<?php echo $form->labelEx($model,'company'); ?>
		<?php echo $form->textField($model,'company',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'company'); ?>
	</div>

	<div class="form-group col-md-4">
		<?php echo $form->labelEx($model,'company_vat'); ?>
		<?php echo $form->textField($model,'company_vat',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'company_vat'); ?>
	</div>

	<div class="form-group col-md-4">
		<?php echo $form->labelEx($model,'company_number'); ?>
		<?php echo $form->textField($model,'company_number',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'company_number'); ?>
	</div>

	<div class="form-group col-md-4">
		<?php echo $form->labelEx($model,'client_group_id'); ?>
		<?php echo $form->dropDownList($model,'client_group_id',CHtml::listData(ModClientGroup::model()->findAll(), 'id', 'title'),array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'client_group_id'); ?>
	</div>

	<div class="form-group col-md-6">
		<?php echo $form->labelEx($model,'address_1'); ?>
		<?php echo $form->textArea($model,'address_1',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'address_1'); ?>
	</div>

	<div class="form-group col-md-6">
		<?php echo $form->labelEx($model,'address_2'); ?>
		<?php echo $form->textArea($model,'address_2',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'address_2'); ?>
	</div>

	<div class="form-group col-md-4">
		<?php echo $form->labelEx($model,'city'); ?>
		<?php echo $form->textField($model,'city',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'city'); ?>
	</div>

	<div class="form-group col-md-4">
		<?php echo $form->labelEx($model,'state'); ?>
		<?php echo $form->textField($model,'state',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'state'); ?>
	</div>

	<div class="form-group col-md-4">
		<?php echo $form->labelEx($model,'postcode'); ?>
		<?php echo $form->textField($model,'postcode',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'postcode'); ?>
	</div>

	<div class="form-group col-md-12">
			<?php echo $form->labelEx($model,'notes'); ?>
			<?php
				$this->widget('application.extensions.wysihtml.EWysiHtml', array(
								'model'=>$model,
								'attribute'=>'notes', //Model attribute name. Nome do atributo do modelo.
								'options'=>array(
									'color'=>true,
									'html'=>true,
									'controls'=>'bold italic underline | alignleft center alignright justify | cut copy paste pastetext | numbering image source',
								),
								'value'=>$model->notes,
							'htmlOptions'=>array('class'=>'form-control','rows'=>'2'),
						));
			?>
			<?php echo $form->error($model,'notes'); ?>
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
