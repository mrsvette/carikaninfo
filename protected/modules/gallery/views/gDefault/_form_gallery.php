<div class="alert alert-success hide">
	<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
	<span id="gallery-message"></span>
</div>

<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'gallery-form',
		'enableAjaxValidation'=>false,
		'htmlOptions'=>array('class'=>'form-horizontal','enctype' =>'multipart/form-data'),
)); ?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

	<h4><?php //echo Yii::t('EmailModule.template','Where I can use new email template?');?></h4>

	<div class="form-group">
			<?php echo $form->labelEx($model,'name',array('class'=>'col-md-3')); ?>
			<div class="col-md-6">
				<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'name'); ?>
			</div>
	</div>
	<div class="form-group">
			<?php echo $form->labelEx($model,'category_id',array('class'=>'col-md-3')); ?>
			<div class="col-md-5">
				<?php echo $form->dropDownList($model,'category_id',CHtml::listData(ModGalleryCategory::model()->findAll(),'id','title'),array('class'=>'')); ?>
				<?php echo $form->error($model,'category_id'); ?>
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
			<?php echo $form->labelEx($model,'description',array('class'=>'col-md-3')); ?>
			<div class="col-md-9">
				<?php echo $form->textArea($model,'description',array('rows'=>3,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'description'); ?>
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
						'id'=>'gallery-submit-btn',
						'href'=>CHtml::normalizeUrl(array('gDefault/create'))
					)
				);
			else:
				echo CHtml::submitButton(
					Yii::t('global', 'Update'),
					array(
						'style'=>'width:100px',
						'class'=>'btn btn-success',
						'id'=>'gallery-submit-btn',
						'href'=>CHtml::normalizeUrl(array('gDefault/update','id'=>$model->id))
					)
				);
			endif;
			?>
	</div>

<?php $this->endWidget(); ?>
<script type="text/javascript">
$(function(){
	$('#gallery-submit-btn').click(function(){
		var formData = new FormData($('form[id="gallery-form"]')[0]);
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
					$("#gallery-message").html(data.div);
					$("#gallery-message").parent().removeClass("hide");
					$.fn.yiiGridView.update('gallery-grid', {
						data: $(this).serialize()
					})
					return false;
				}else{
					$("form[id='gallery-form']").parent().html(data.div);
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
