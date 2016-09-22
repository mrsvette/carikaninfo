<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'group_id'); ?>
		<?php echo $form->dropDownList($model,'group_id',RbacGroup::items('- Choose -')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status',Lookup::items('UserStatus')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date_entry'); ?>
		<?php
		$this->widget('zii.widgets.jui.CJuiDatePicker', array(
			'model'=>$model, //Model object
			'attribute'=>'date_entry', //attribute name
			'id'=>'entry-date',
			// additional javascript options for the date picker plugin
			'options'=>array(
				'showAnim'=>'fold',
				'dateFormat'=>'yy-mm-dd',
				'changeMonth' => 'true',
				'changeYear'=>'true',
				'constrainInput' => 'false'
			),
			'htmlOptions'=>array(
				'style'=>'height:20px;'
			),
		));
		?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'user_entry'); ?>
		<?php echo $form->textField($model,'user_entry'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date_update'); ?>
		<?php
		$this->widget('zii.widgets.jui.CJuiDatePicker', array(
			'model'=>$model, //Model object
			'attribute'=>'date_update', //attribute name
			'id'=>'update-date',
			// additional javascript options for the date picker plugin
			'options'=>array(
				'showAnim'=>'fold',
				'dateFormat'=>'yy-mm-dd',
				'changeMonth' => 'true',
				'changeYear'=>'true',
				'constrainInput' => 'false'
			),
			'htmlOptions'=>array(
				'style'=>'height:20px;'
			),
		));
		?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'user_update'); ?>
		<?php echo $form->textField($model,'user_update'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
