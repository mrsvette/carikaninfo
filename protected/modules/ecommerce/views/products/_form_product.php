<div class="alert alert-success hide">
	<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
	<span id="message"></span>
</div>
<?php $form=$this->beginWidget('CActiveForm',array('id'=>'product-form')); ?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>
	<div class="form-group col-md-6">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="form-group col-md-3">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->dropDownList($model,'type',Lookup::items('ProductType'),array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="form-group col-md-3">
		<?php echo $form->labelEx($model,'product_category_id'); ?>
		<?php echo $form->dropDownList($model,'product_category_id',CHtml::listData(ModProductCategory::model()->findAll(), 'id', 'title'),array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'product_category_id'); ?>
	</div>

	<div class="form-group col-md-12">
			<?php echo $form->labelEx($model,'description'); ?>
			<?php
				$this->widget('application.extensions.wysihtml.EWysiHtml', array(
								'model'=>$model,
								'attribute'=>'description', //Model attribute name. Nome do atributo do modelo.
								'options'=>array(
									'color'=>false,
									'html'=>false,
									'controls'=>'bold italic underline | alignleft center alignright justify | cut copy paste pastetext | numbering image source',
								),
								'value'=>$model->description,
							'htmlOptions'=>array('class'=>'form-control','rows'=>'5'),
						));
			?>
			<?php 
				/*$this->widget('ext.redactor.ERedactorWidget',array(
					'model'=>$model,
					'attribute'=>'description',
					'options'=>array(
						'fileUpload'=>Yii::app()->createUrl('/appadmin/uploads/fileUpload',array(
							'attr'=>'content',
						)),
						'fileUploadErrorCallback'=>new CJavaScriptExpression(
							'function(obj,json) { alert(json.error); }'
						),
						'imageUpload'=>Yii::app()->createUrl('/appadmin/uploads/imageUpload',array(
							'attr'=>'content',
						)),
						'imageGetJson'=>Yii::app()->createUrl('/appadmin/uploads/imageList',array(
							'attr'=>'content',
						)),
						'imageUploadErrorCallback'=>new CJavaScriptExpression(
							'function(obj,json) { alert(json.error); }'
						),
					),
					'htmlOptions'=>array('value'=>(!$model->isNewRecord)? $model->description:''),
				));*/
			?>
			<?php echo $form->error($model,'content'); ?>
	</div>

	<div class="form-group buttons col-md-12">
		<?php 
		if($model->isNewRecord):
			echo CHtml::ajaxSubmitButton(Yii::t('global', 'Save'),CHtml::normalizeUrl(array('products/create')),array('dataType'=>'json','success'=>'js:
				function(data){
					if(data.status=="success"){
						$("#message").html(data.div);
						$("#message").parent().removeClass("hide");
						$.fn.yiiGridView.update("product-grid", {
							data: $(this).serialize()
						});
						return false;
					}else{
						$("form[id=\'product-form\']").parent().html(data.div);
					}
					return false;
				}'
			),
			array('style'=>'width:100px','class'=>'btn btn-success','id'=>uniqid()));
		else:
			echo CHtml::ajaxSubmitButton(Yii::t('global', 'Update'),CHtml::normalizeUrl(array('products/update','id'=>$model->id)),array('dataType'=>'json','success'=>'js:
				function(data){
					if(data.status=="success"){
						$("#message").html(data.div);
						$("#message").parent().removeClass("hide");
						return false;
					}else{
						$("form[id=\'product-form\']").parent().html(data.div);
					}
					return false;
				}'
			),
			array('style'=>'width:100px','class'=>'btn btn-success','id'=>uniqid()));
		endif;
		?>
	</div>

<?php $this->endWidget(); ?>
