<style>
#ModEmailTemplate_enabled input {float:left;margin-right:10px;}
#ModEmailTemplate_enabled label, #ModEmailTemplate_enabled label {width:15%;float:left;}
</style>
<div class="alert alert-success hide">
	<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
	<span id="template-message"></span>
</div>
<?php $form=$this->beginWidget('CActiveForm',array('id'=>'email-template-form','htmlOptions'=>array('class'=>'form-horizontal'))); ?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

	<h4><?php echo Yii::t('EmailModule.template','Where I can use new email template?');?></h4>

	<div class="form-group">
		<?php echo $form->labelEx($model,'enabled',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->radioButtonList($model,'enabled',array('No','Yes'),array('template'=>'{input} {label}','separator'=>'')); ?>
			<?php echo $form->error($model,'enabled'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'category',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->textField($model,'category',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'category'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'action_code',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->textField($model,'action_code',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'action_code'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'subject',array('class'=>'col-md-3')); ?>
		<div class="col-md-7">
			<?php echo $form->textField($model,'subject',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'subject'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'content',array('class'=>'col-md-3')); ?>
		<div class="col-md-9">
			<?php echo $form->textArea($model,'content',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'content'); ?>
		</div>
	</div>

	<div class="form-group buttons col-md-12">
		<?php 
		if($model->isNewRecord):
			echo CHtml::ajaxSubmitButton(Yii::t('global', 'Save'),CHtml::normalizeUrl(array('templates/create')),array('dataType'=>'json','success'=>'js:
				function(data){
					if(data.status=="success"){
						$("#template-message").html(data.div);
						$("#template-message").parent().removeClass("hide");
						$.fn.yiiGridView.update("email-template-grid", {
							data: $(this).serialize()
						});
						return false;
					}else{
						$("form[id=\'email-template-form\']").parent().html(data.div);
					}
					return false;
				}'
			),
			array('style'=>'width:100px','class'=>'btn btn-success','id'=>uniqid()));
		else:
			echo CHtml::ajaxSubmitButton(Yii::t('global', 'Update'),CHtml::normalizeUrl(array('templates/update','id'=>$model->id)),array('dataType'=>'json','success'=>'js:
				function(data){
					if(data.status=="success"){
						$("#template-message").html(data.div);
						$("#template-message").parent().removeClass("hide");
						return false;
					}else{
						$("form[id=\'email-template-form\']").parent().html(data.div);
					}
					return false;
				}'
			),
			array('style'=>'width:100px','class'=>'btn btn-success','id'=>uniqid()));
		endif;
		?>
	</div>

<?php $this->endWidget(); ?>
