<div class="alert alert-success hide">
	<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
	<span id="message"></span>
</div>
<?php $form=$this->beginWidget('CActiveForm',array('id'=>'invoice-form','htmlOptions'=>array('class'=>'form-horizontal'))); ?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

	<h4><?php echo Yii::t('EcommerceModule.invoice','Invoice management');?></h4>

	<div class="form-group">
		<?php echo $form->labelEx($model,'status',array('class'=>'col-md-3')); ?>
		<div class="col-md-9">
			<?php echo $form->radioButtonList($model,'status',$model->statuses,array('template'=>'{input} {label}','separator'=>'')); ?>
			<?php echo $form->error($model,'status'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'approved',array('class'=>'col-md-3')); ?>
		<div class="col-md-9">
			<?php echo $form->radioButtonList($model,'approved',array('Tidak','Ya'),array('template'=>'{input} {label}','separator'=>'')); ?>
			<?php echo $form->error($model,'approved'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'serie',array('class'=>'col-md-3')); ?>
		<div class="col-md-2">
			<?php echo $form->textField($model,'serie',array('placeholder'=>$model->getAttributeLabel('serie'),'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'serie'); ?>
		</div>
		<div class="col-md-3">
			<?php echo $form->textField($model,'nr',array('placeholder'=>$model->getAttributeLabel('nr'),'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'nr'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'gateway_id',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->dropDownList($model,'gateway_id',CHtml::listData(ModPayGateway::model()->findAll(), 'id', 'name'),array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'gateway_id'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'date_entry',array('class'=>'col-md-3')); ?>
		<div class="col-md-3">
			<?php echo $form->textField($model,'date_entry',array('readOnly'=>true,'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'date_entry'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'due_at',array('class'=>'col-md-3')); ?>
		<div class="col-md-3">
		<?php 
			$this->widget('ext.CJuiDateTimePicker.CJuiDateTimePicker',array(
						'model'=>$model, //Model object
						'attribute'=>'due_at', //attribute name
						'mode'=>'datetime', //use "time","date" or "datetime" (default)
						'options'=>array(
							'showAnim'=>'fold',
							'dateFormat'=>'yy-mm-dd hh:ii',
							'changeMonth' => 'true',
							'changeYear'=>'true',
							'constrainInput' => 'true'
						),
						'htmlOptions'=>array('placeholder'=>'yyyy-mm-dd','class'=>'form-control'),
					));
		?>
		<?php echo $form->error($model,'due_at'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'paid_at',array('class'=>'col-md-3')); ?>
		<div class="col-md-3">
		<?php 
			$this->widget('ext.CJuiDateTimePicker.CJuiDateTimePicker',array(
						'model'=>$model, //Model object
						'attribute'=>'paid_at', //attribute name
						'mode'=>'datetime', //use "time","date" or "datetime" (default)
						'options'=>array(
							'showAnim'=>'fold',
							'dateFormat'=>'yy-mm-dd hh:ii',
							'changeMonth' => 'true',
							'changeYear'=>'true',
							'constrainInput' => 'true'
						),
						'htmlOptions'=>array('placeholder'=>'yyyy-mm-dd','class'=>'form-control'),
					));
		?>
		<?php echo $form->error($model,'paid_at'); ?>
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
			echo CHtml::ajaxSubmitButton(Yii::t('global', 'Save'),CHtml::normalizeUrl(array('invoices/create')),array('dataType'=>'json','success'=>'js:
				function(data){
					if(data.status=="success"){
						$("#message").html(data.div);
						$("#message").parent().removeClass("hide");
						$.fn.yiiGridView.update("invoice-grid", {
							data: $(this).serialize()
						});
						return false;
					}else{
						$("form[id=\'order-form\']").parent().html(data.div);
					}
					return false;
				}'
			),
			array('style'=>'width:100px','class'=>'btn btn-success','id'=>uniqid()));
		else:
			echo CHtml::ajaxSubmitButton(Yii::t('global', 'Update'),CHtml::normalizeUrl(array('invoices/update','id'=>$model->id)),array('dataType'=>'json','success'=>'js:
				function(data){
					if(data.status=="success"){
						$("#message").html(data.div);
						$("#message").parent().removeClass("hide");
						return false;
					}else{
						$("form[id=\'invoice-form\']").parent().html(data.div);
					}
					return false;
				}'
			),
			array('style'=>'width:100px','class'=>'btn btn-success','id'=>uniqid()));
		endif;
		?>
	</div>

<?php $this->endWidget(); ?>
