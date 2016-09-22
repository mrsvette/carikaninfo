<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'menu-group-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('global','Fields with <span class="required">*</span> are required.');?></p>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

	<div class="form-group col-md-6">
		<?php echo $form->labelEx($model,'nama_group'); ?>
		<?php echo $form->textField($model,'nama_group',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'nama_group'); ?>
	</div>

	<div class="form-group col-md-6">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status',Lookup::items('MenuGroupStatus')); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="form-group col-md-12">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('global','Create') : Yii::t('global','Save'),array('style'=>'min-width:100px;')); ?>
	</div>

<?php $this->endWidget(); ?>
