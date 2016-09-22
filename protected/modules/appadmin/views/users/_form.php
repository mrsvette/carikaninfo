<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

	<div class="form-group col-md-6">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>30,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<?php if($model->isNewRecord): ?>
	<div class="form-group col-md-6">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>
	<?php endif;?>

	<div class="form-group col-md-6">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="form-group col-md-6">
		<?php echo $form->labelEx($model,'group_id'); ?>
		<span id="list-group"><?php echo $form->dropDownList($model,'group_id',RbacGroup::items()); ?></span>
		<?php echo CHtml::button('Add Group',array('style'=>'min-width:100px;','class'=>'btn btn-primary','onclick'=>"{addgroup();$('#dialogGroupUser').dialog('open');}"));?>
		<?php echo $form->error($model,'group_id'); ?>
	</div>

	<div class="form-group buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('global','Save') : Yii::t('global','Update'),array('style'=>'min-width:100px;')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<script type="text/javascript">
function addgroup()
{
	$.ajax({
      'url': '<?php echo Yii::app()->createUrl($this->module->id.'/rbacGroup/addgroup');?>',
      'data':$(this).serialize(),
      'type': 'post',
      'dataType': 'json',
      'success': function( data ){
		if(data.status=='failure'){
			$('#div-for-add-group').html(data.div);
			$('#div-for-add-group form').submit(addgroup);
		}else if(data.status=='success'){
			$('#div-for-add-group').html(data.div);
            setTimeout("$('#dialogGroupUser').dialog('close') ",3000);
			$('#list-group').html(data.list);
			//window.location.reload(true);
		}else{
			$('#div-for-add-group').html(data.div);
			setTimeout("$('#dialogGroupUser').dialog('close') ",3000);
		}
      },
    });
    return false; 
 
}
</script>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
	'id'=>'dialogGroupUser',
	// additional javascript options for the dialog plugin
	'options'=>array(
		'title'=>'Add Group',
		'autoOpen'=>false,
		'modal'=>true,
		'width'=>600,
		'height'=>250,
		'position'=>'center',
		'hide' => 'clip',
        	'show' => 'fade',
	),
	//'cssFile'=>Yii::app()->request->baseUrl.'/css/'.Yii::app()->theme->name.'/jui/jquery-ui.css',
	'themeUrl'=>Yii::app()->request->baseUrl.'/css/'.Yii::app()->theme->name,
	'theme'=>'jui',
));?>
<div id="div-for-add-group"></div>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog');?>
