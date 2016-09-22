<div class="alert alert-success hide">
	<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
	<span id="message"></span>
</div>
<?php $form=$this->beginWidget('CActiveForm',array('id'=>'client-form','htmlOptions'=>array('class'=>'form-horizontal'))); ?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

	<h4><?php echo Yii::t('EcommerceModule.order','Order management');?></h4>

	<div class="form-group">
		<?php echo $form->labelEx($model,'title',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->textField($model,'title',array('placeholder'=>$model->getAttributeLabel('title'),'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'title'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'status',array('class'=>'col-md-3')); ?>
		<div class="col-md-3">
			<?php echo $form->dropDownList($model,'status',$model->statuses); ?>
			<?php echo $form->error($model,'status'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'invoice_option',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->dropDownList($model,'invoice_option',$model->invoiceOptions); ?>
			<?php echo $form->error($model,'invoice_option'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'price',array('class'=>'col-md-3')); ?>
		<div class="col-md-3">
			<?php echo $form->textField($model,'price',array('placeholder'=>$model->getAttributeLabel('price'),'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'price'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'period',array('class'=>'col-md-3')); ?>
		<div class="col-md-3">
			<?php echo $form->dropDownList($model,'period',ModProduct::periods(false)); ?>
			<?php echo $form->error($model,'period'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'expires_at',array('class'=>'col-md-3')); ?>
		<div class="col-md-3">
		<?php 
			$this->widget('ext.CJuiDateTimePicker.CJuiDateTimePicker',array(
						'model'=>$model, //Model object
						'attribute'=>'expires_at', //attribute name
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
		<?php echo $form->error($model,'expires_at'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'activated_at',array('class'=>'col-md-3')); ?>
		<div class="col-md-3">
		<?php 
			$this->widget('ext.CJuiDateTimePicker.CJuiDateTimePicker',array(
						'model'=>$model, //Model object
						'attribute'=>'activated_at', //attribute name
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
		<?php echo $form->error($model,'activated_at'); ?>
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
			echo CHtml::ajaxSubmitButton(Yii::t('global', 'Save'),CHtml::normalizeUrl(array('orders/create')),array('dataType'=>'json','success'=>'js:
				function(data){
					if(data.status=="success"){
						$("#message").html(data.div);
						$("#message").parent().removeClass("hide");
						$.fn.yiiGridView.update("order-grid", {
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
			echo CHtml::ajaxSubmitButton(Yii::t('global', 'Update'),CHtml::normalizeUrl(array('orders/update','id'=>$model->id)),array('dataType'=>'json','success'=>'js:
				function(data){
					if(data.status=="success"){
						$("#message").html(data.div);
						$("#message").parent().removeClass("hide");
						return false;
					}else{
						$("form[id=\'order-form\']").parent().html(data.div);
					}
					return false;
				}'
			),
			array('style'=>'width:100px','class'=>'btn btn-success','id'=>uniqid()));
		endif;
		?>
	</div>

<?php $this->endWidget(); ?>
