<div class="alert alert-success hide">
	<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
	<span id="item-cmessage"></span>
</div>
<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'slideshow-item-form',
		'enableAjaxValidation'=>false,
		'htmlOptions'=>array('class'=>'form-horizontal','enctype' =>'multipart/form-data'),
)); ?>

		<p class="note"><?php echo Yii::t('global','Fields with <span class="required">*</span> are required.');?></p>

		<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

		<div class="form-group">
			<?php echo $form->labelEx($model,'title',array('class'=>'col-md-3')); ?>
			<div class="col-md-9">
				<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>128,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'title'); ?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->labelEx($model,'image_path',array('class'=>'col-md-3')); ?>
			<div class="col-md-9">
				<?php echo $form->fileField($model,'image_path',array('class'=>'')); ?>
				<?php echo $form->error($model,'image_path'); ?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->labelEx($model,'url',array('class'=>'col-md-3')); ?>
			<div class="col-md-9">
				<?php echo $form->textField($model,'url',array('size'=>60,'maxlength'=>128,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'url'); ?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->labelEx($model,'caption',array('class'=>'col-md-3')); ?>
			<div class="col-md-9">
				<?php echo $form->textArea($model,'caption',array('rows'=>3,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'caption'); ?>
			</div>
		</div>
		<?php echo $form->hiddenField($model,'slide_show_id',array('value'=>$model->slide_show_id));?>
		<div class="form-group">
			<?php 
			if($model->isNewRecord):
				echo CHtml::submitButton(
					Yii::t('global', 'Create'),
					array(
						'style'=>'width:100px',
						'class'=>'btn btn-success',
						'id'=>'item-submit-btn',
						'href'=>CHtml::normalizeUrl(array('sDefault/createItem','id'=>$model->id))
					)
				);
			else:
				echo CHtml::submitButton(
					Yii::t('global', 'Update'),
					array(
						'style'=>'width:100px',
						'class'=>'btn btn-success',
						'id'=>'item-submit-btn',
						'href'=>CHtml::normalizeUrl(array('sDefault/updateItem','id'=>$model->id))
					)
				);
			endif;
			?>
		</div>

<?php $this->endWidget(); ?>
<script type="text/javascript">
$(function(){
	$('#item-submit-btn').click(function(){
		var formData = new FormData($('form[id="slideshow-item-form"]')[0]);
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
					$("#item-cmessage").html(data.div);
					$("#item-cmessage").parent().removeClass("hide");
					$.fn.yiiListView.update('image-list-view', {
						data: $(this).serialize()
					});
					return false;
				}else{
					$("form[id='slideshow-item-form']").parent().parent().html(data.div);
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
