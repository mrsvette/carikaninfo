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
		<?php echo $form->label($model,'category_name'); ?>
		<?php echo $form->textField($model,'category_name',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="col-md-6">
		<?php echo $form->label($model,'parent_id'); ?>
		<?php echo $form->dropDownList($model,'parent_id',PostCategory::listItems()); ?>
	</div>

	<div class="col-md-12 mt10 buttons">
		<?php echo CHtml::submitButton(Yii::t('global','Search'),array('style'=>'min-width:100px;')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
