<?php
$this->breadcrumbs=array(
	ucfirst(Yii::app()->controller->module->id)=>array('/'.Yii::app()->controller->module->id.'/'),
	'Profile'=>array('update'),
	Yii::t('global','Change').' Password',
);

$this->menu=array(
	array('label'=>Yii::t('menu','Account Setting'), 'url'=>array('update')),
);
?>

<h3><?php echo Yii::t('global','Change');?> Password</h3>

<?php if(Yii::app()->user->hasFlash('changepass')): ?>
<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('changepass'); ?>
</div>
<?php endif; ?>

<div class="form wide">
	<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'changepass-form',
			'enableAjaxValidation'=>false,
	)); ?>

			<p class="note"><?php echo Yii::t('global','Fields with <span class="required">*</span> are required.');?></p>
			
			<?php echo $form->errorSummary($model); ?>

			<div class="row">
				<?php echo $form->labelEx($model,'passwordlm'); ?>
				<?php echo $form->passwordField($model,'passwordlm'); ?>
				<?php echo $form->error($model,'passwordlm'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($model,'passwordbr'); ?>
				<?php echo $form->passwordField($model,'passwordbr'); ?>
				<?php echo $form->error($model,'passwordbr'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($model,'passwordbr_repeat'); ?>
				<?php echo $form->passwordField($model,'passwordbr_repeat'); ?>
				<?php echo $form->error($model,'passwordbr_repeat'); ?>
			</div>
			
			<?php if(CCaptcha::checkRequirements()): ?>
			<div class="row">
				<?php echo $form->labelEx($model,'verifyCode'); ?>
				<?php $this->widget('CCaptcha',array('clickableImage'=>true,'buttonLabel'=>'','imageOptions'=>array('class'=>'captcha'))); ?>
				<br class="clear" />
			</div>
			<div class="row">
				<label>&nbsp;</label>
				<?php echo $form->textField($model,'verifyCode'); ?>
				<br class="clear" />
			</div>
			<?php endif; ?>

			<div class="row cbtn">
				<?php echo CHtml::submitButton(Yii::t('global','Save'),array('style'=>'min-width:100px;')); ?>
			</div>

	<?php $this->endWidget(); ?>
</div>
