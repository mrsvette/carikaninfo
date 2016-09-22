<div class="alert alert-success hide">
	<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
	<span id="portfolio-category-message"></span>
</div>
<?php $form=$this->beginWidget('CActiveForm',array('id'=>'portfolio-category-form','htmlOptions'=>array('class'=>'form-horizontal'))); ?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

	<p class="mt20"><?php echo Yii::t('global','Fields with <span class="required">*</span> are required.');?></p>

	<div class="form-group">
		<?php echo $form->labelEx($model,'title',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->textField($model,'title',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'title'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'description',array('class'=>'col-md-3')); ?>
		<div class="col-md-9">
			<?php echo $form->textArea($model,'description',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'description'); ?>
		</div>
	</div>

	<div class="form-group buttons col-md-12">
		<?php 
		if($model->isNewRecord):
			echo CHtml::ajaxSubmitButton(Yii::t('global', 'Save'),CHtml::normalizeUrl(array('pDefault/createCategory')),array('dataType'=>'json','success'=>'js:
				function(data){
					if(data.status=="success"){
						$("#portfolio-category-message").html(data.div);
						$("#portfolio-category-message").parent().removeClass("hide");
						$.fn.yiiGridView.update("portfolio-category-grid", {
							data: $(this).serialize()
						});
						return false;
					}else{
						$("form[id=\'portfolio-category-form\']").parent().html(data.div);
					}
					return false;
				}'
			),
			array('style'=>'width:100px','class'=>'btn btn-success','id'=>uniqid()));
		else:
			echo CHtml::ajaxSubmitButton(Yii::t('global', 'Update'),CHtml::normalizeUrl(array('pDefault/updateCategory','id'=>$model->id)),array('dataType'=>'json','success'=>'js:
				function(data){
					if(data.status=="success"){
						$("#portfolio-category-message").html(data.div);
						$("#portfolio-category-message").parent().removeClass("hide");
						return false;
					}else{
						$("form[id=\'portfolio-category-form\']").parent().html(data.div);
					}
					return false;
				}'
			),
			array('style'=>'width:100px','class'=>'btn btn-success','id'=>uniqid()));
		endif;
		?>
	</div>

<?php $this->endWidget(); ?>
