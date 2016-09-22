<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="form-group">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'urutan'); ?>
		<?php echo $form->textField($model,'urutan'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'notaktif'); ?>
		<?php echo $form->dropDownList($model,'notaktif',Lookup::items('MenuStatus')); ?>
	</div>

	<div class="form-group buttons">
		<?php echo CHtml::submitButton(Yii::t('global','Search'),array('style'=>'min-width:100px;')); ?>
	</div>

<?php $this->endWidget(); ?>
