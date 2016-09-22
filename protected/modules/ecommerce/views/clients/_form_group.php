<div class="alert alert-success hide">
	<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
	<span id="cmessage"></span>
</div>
<?php $form=$this->beginWidget('CActiveForm',array('id'=>'group-form')); ?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>
	<div class="form-group col-md-12">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="form-group col-md-12">
			<?php echo $form->labelEx($model,'description'); ?>
			<?php
				$this->widget('application.extensions.wysihtml.EWysiHtml', array(
								'model'=>$model,
								'attribute'=>'description', //Model attribute name. Nome do atributo do modelo.
								'options'=>array(
									'color'=>true,
									'html'=>true,
									'controls'=>'bold italic underline | alignleft center alignright justify | cut copy paste pastetext | numbering image source',
								),
								'value'=>$model->description,
							'htmlOptions'=>array('class'=>'form-control','rows'=>'5'),
						));
			?>
			<?php echo $form->error($model,'content'); ?>
	</div>

	<div class="form-group buttons col-md-12">
		<?php 
		if($model->isNewRecord):
			echo CHtml::ajaxSubmitButton(Yii::t('global', 'Save'),CHtml::normalizeUrl(array('clients/createGroup')),array('dataType'=>'json','success'=>'js:
				function(data){
					if(data.status=="success"){
						$("#cmessage").html(data.div);
						$("#cmessage").parent().removeClass("hide");
						$.fn.yiiGridView.update("group-grid", {
							data: $(this).serialize()
						});
						return false;
					}else{
						$("form[id=\'group-form\']").parent().html(data.div);
					}
					return false;
				}'
			),
			array('style'=>'width:100px','class'=>'btn btn-success','id'=>uniqid()));
		else:
			echo CHtml::ajaxSubmitButton(Yii::t('global', 'Update'),CHtml::normalizeUrl(array('clients/updateGroup','id'=>$model->id)),array('dataType'=>'json','success'=>'js:
				function(data){
					if(data.status=="success"){
						$("#cmessage").html(data.div);
						$("#cmessage").parent().removeClass("hide");
						return false;
					}else{
						$("form[id=\'group-form\']").parent().html(data.div);
					}
					return false;
				}'
			),
			array('style'=>'width:100px','class'=>'btn btn-success','id'=>uniqid()));
		endif;
		?>
	</div>

<?php $this->endWidget(); ?>
