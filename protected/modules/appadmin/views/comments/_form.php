<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'comments-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('global','Fields with <span class="required">*</span> are required.');?></p>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>
	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				<?php echo $form->labelEx($model,'author'); ?>
				<?php echo $form->textField($model,'author',array('size'=>30,'maxlength'=>128)); ?>
				<?php echo $form->error($model,'author'); ?>
			</div>

			<div class="form-group">
				<?php echo $form->labelEx($model,'email'); ?>
				<?php echo $form->textField($model,'email',array('size'=>30,'maxlength'=>128)); ?>
				<?php echo $form->error($model,'email'); ?>
			</div>

			<div class="form-group">
				<?php echo $form->labelEx($model,'url'); ?>
				<?php echo $form->textField($model,'url',array('size'=>30,'maxlength'=>128)); ?>
				<?php echo $form->error($model,'url'); ?>
			</div>
		</div>
		<div class="col-md-8">
			<div class="form-group">
				<?php echo $form->labelEx($model,'content'); ?>
				<?php echo $form->textArea($model,'content',array('rows'=>8, 'cols'=>40)); ?>
				<?php echo $form->error($model,'content'); ?>
			</div>
		</div>
	</div>
	<div class="row form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('global','Create') : Yii::t('global','Save'),array('style'=>'min-width:100px;')); ?>
	</div>

<?php $this->endWidget(); ?>
