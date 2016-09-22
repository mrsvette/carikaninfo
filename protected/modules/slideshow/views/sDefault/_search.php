<div class="row form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="col-md-6">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="col-md-6">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="col-md-12">
		<?php echo $form->label($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="col-md-12 buttons mt10">
		<?php echo CHtml::submitButton(Yii::t('global','Search'),array('style'=>'min-width:100px;')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
