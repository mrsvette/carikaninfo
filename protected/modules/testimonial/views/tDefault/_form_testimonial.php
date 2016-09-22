<div class="alert alert-success hide">
	<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
	<span id="testimonial-message"></span>
</div>

<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'testimonial-form',
		'enableAjaxValidation'=>false,
		'htmlOptions'=>array('class'=>'form-horizontal','enctype' =>'multipart/form-data'),
)); ?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

	<div class="form-group">
			<?php echo $form->labelEx($model,'name',array('class'=>'col-md-3')); ?>
			<div class="col-md-6">
				<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'name'); ?>
			</div>
	</div>
	<div class="form-group">
			<?php echo $form->labelEx($model,'activity',array('class'=>'col-md-3')); ?>
			<div class="col-md-6">
				<?php echo $form->textField($model,'activity',array('size'=>60,'maxlength'=>128,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'activity'); ?>
			</div>
	</div>
	<div class="form-group">
			<?php echo $form->labelEx($model,'website',array('class'=>'col-md-3')); ?>
			<div class="col-md-6">
				<?php echo $form->textField($model,'website',array('size'=>60,'maxlength'=>128,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'website'); ?>
			</div>
	</div>
	<div class="form-group">
			<?php echo $form->labelEx($model,'image',array('class'=>'col-md-3')); ?>
			<div class="col-md-9">
				<?php if(!$model->isNewRecord):?>
					<?php echo CHtml::image(Yii::app()->request->baseUrl.'/'.$model->thumb.$model->image,'',array('class'=>'mb10'));?>
				<?php endif;?>
				<?php echo $form->fileField($model,'image',array('class'=>'')); ?>
				<?php echo $form->error($model,'image'); ?>
			</div>
	</div>
	<div class="form-group">
			<?php echo $form->labelEx($model,'comment',array('class'=>'col-md-3')); ?>
			<div class="col-md-9">
				<?php echo $form->textArea($model,'comment',array('rows'=>3,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'comment'); ?>
			</div>
	</div>

	<div class="form-group buttons col-md-12">
		<?php 
			if($model->isNewRecord):
				echo CHtml::submitButton(
					Yii::t('global', 'Create'),
					array(
						'style'=>'width:100px',
						'class'=>'btn btn-success',
						'id'=>'testimonial-submit-btn',
						'href'=>CHtml::normalizeUrl(array('tDefault/create'))
					)
				);
			else:
				echo CHtml::submitButton(
					Yii::t('global', 'Update'),
					array(
						'style'=>'width:100px',
						'class'=>'btn btn-success',
						'id'=>'testimonial-submit-btn',
						'href'=>CHtml::normalizeUrl(array('tDefault/update','id'=>$model->id))
					)
				);
			endif;
			?>
	</div>

<?php $this->endWidget(); ?>
<script type="text/javascript">
$(function(){
	$('#testimonial-submit-btn').click(function(){
		var formData = new FormData($('form[id="testimonial-form"]')[0]);
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
					$("#testimonial-message").html(data.div);
					$("#testimonial-message").parent().removeClass("hide");
					$.fn.yiiGridView.update('testimonial-grid', {
						data: $(this).serialize()
					})
					return false;
				}else{
					$("form[id='testimonial-form']").parent().html(data.div);
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
