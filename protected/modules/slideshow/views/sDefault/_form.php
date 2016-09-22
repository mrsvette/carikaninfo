<div class="alert alert-success hide">
	<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
	<span id="cmessage"></span>
</div>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'slideshow-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('class'=>'form-horizontal'),
)); ?>

	<p class="note"><?php echo Yii::t('global','Fields with <span class="required">*</span> are required.');?></p>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model,'name',array('class'=>'col-md-3')); ?>
		<div class="col-md-8">
			<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'name'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'type',array('class'=>'col-md-3')); ?>
		<div class="col-md-8">
		<?php echo $form->radioButtonList($model,'type',$model->types,array('template'=>'{input} {label}','separator'=>'')); ?>
		<?php echo $form->error($model,'type'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'status',array('class'=>'col-md-3')); ?>
		<div class="col-md-8">
		<?php echo $form->radioButtonList($model,'status',$model->statuses,array('template'=>'{input} {label}','separator'=>'')); ?>
		<?php echo $form->error($model,'status'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'description',array('class'=>'col-md-3')); ?>
		<div class="col-md-8">
			<?php echo $form->textArea($model,'description',array('rows'=>3, 'cols'=>50,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'description'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php 
		if($model->isNewRecord):
			echo CHtml::submitButton(Yii::t('global','Create'),array('style'=>'min-width:100px;','class'=>'btn btn-primary')); 
		else:
			echo CHtml::submitButton(
				Yii::t('global', 'Update'),
				array(
					'style'=>'width:100px',
					'class'=>'btn btn-success',
					'id'=>'submit-btn',
					'href'=>CHtml::normalizeUrl(array('sDefault/update','id'=>$model->id))
				)
			);
		endif;
		?>
	</div>

<?php $this->endWidget(); ?>
<style>
.form-horizontal .form-group{border-top: 1px solid #e7e7e7;clear: both;padding: 20px 16px;position: relative;margin:0;}
#ModSlideShow_status input, #ModSlideShow_type input{float:left;margin-right:10px;}
#ModSlideShow_status label, #ModSlideShow_type label{width:20%;float:left;}
</style>
<script type="text/javascript">
$(function(){
	$('#submit-btn').click(function(){
		var formData = new FormData($('form[id="slideshow-form"]')[0]);
		$.ajax({
			beforeSend: function() { Loading.show(); },
			complete: function() { Loading.hide(); },
			url: $(this).attr('href'),
			type: 'POST',
			data: formData,
			dataType: 'json',
			async: false,
			success: function (data) {
				if(data.status=="success"){
					$("#cmessage").html(data.div);
					$("#cmessage").parent().removeClass("hide");
					return false;
				}else{
					$("form[id='slideshow-form']").parent().html(data.div);
				}
			},
			cache: false,
			contentType: false,
			processData: false
		});
		return false;
	});
});
</script>
