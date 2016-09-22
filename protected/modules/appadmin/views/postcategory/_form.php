<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'post-category-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('global','Fields with <span class="required">*</span> are required.');?></p>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

	<div class="form-group col-md-4">
		<?php echo $form->labelEx($model,'category_name'); ?>
		<?php echo $form->textField($model,'category_name',array('size'=>60,'maxlength'=>128,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'category_name'); ?>
	</div>

	<div class="form-group col-md-4">
		<?php echo $form->labelEx($model,'parent_id'); ?>
		<?php echo $form->dropDownList($model,'parent_id',PostCategory::listItems(),array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'parent_id'); ?>
	</div>
	<div class="form-group col-md-12">
		<?php echo $form->labelEx($model,'notes'); ?>
		<?php echo $form->textArea($model,'notes',array('rows'=>3, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'notes'); ?>
	</div>
	<div class="form-group col-md-12">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('global','Create') : Yii::t('global','Save'),array('style'=>'min-width:100px;','class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>
