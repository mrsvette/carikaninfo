<style>
.multiple {
  border: 1px solid #d5d5d5;
  height: 150px !important;
  padding: 5px;
  width: 100%;
}
#icon img{height:150px;}
#icon{margin-bottom:5px;}
</style>
<div class="alert alert-success hide">
	<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
	<span id="cmessage"></span>
</div>
<?php $form=$this->beginWidget('CActiveForm',array(
			'id'=>'category-form',
			'htmlOptions'=>array('enctype' =>'multipart/form-data')
	)); ?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>
	<div class="col-md-6">
		<div class="form-group">
			<?php echo $form->labelEx($model,'title'); ?>
			<?php echo $form->textField($model,'title',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'title'); ?>
		</div>

		<div class="form-group">
			<?php echo $form->labelEx($model,'childs'); ?>
			<?php echo $form->dropDownList($model,'childs',$model->getItems(null),array('class'=>'form-control multiple','multiple'=>'multiple')); ?>
			<?php echo $form->error($model,'childs'); ?>
		</div>

		<div class="form-group">
			<?php echo $form->labelEx($model,'icon_fa'); ?>
			<div class="input-group">
			<?php
				$this->widget('application.extensions.IonPicker.IonPicker', array(
								'model'=>$model,
								'attribute'=>'icon_fa',
								'options'=>array(
									'placement'=>'topRightCorner', //inline topLeftCorner topLeft top topRight topRightCorner
									'hideOnSelect'=>true,
								),
								'value'=>$model->icon_fa,
							'htmlOptions'=>array('class'=>'form-control icp icp-auto','value'=>$model->icon_fa),
						));
			?>
            <span class="input-group-addon"><i class="fa fa-fw"></i></span>
            </div>
			<?php echo $form->error($model,'icon_fa'); ?>
		</div>

		<?php /*<div class="form-group">
			<?php echo $form->labelEx($model,'level'); ?>
			<?php echo $form->textField($model,'level',array('size'=>2,'maxlength'=>2,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'level'); ?>
		</div>*/ ?>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			<?php echo $form->labelEx($model,'icon_url'); ?>
			<div id="icon" <?php if(empty($model->icon_url)):?>class="hide"<?php endif;?>><?php echo CHtml::image(Yii::app()->request->baseUrl.'/'.$model->icon_url);?></div>
			<?php echo $form->fileField($model,'icon_url',array('size'=>80,'class'=>'')); ?>
			<?php echo $form->error($model,'icon_url'); ?>
		</div>
		<div class="form-group">
			<?php echo $form->labelEx($model,'description'); ?>
			<?php
				$this->widget('application.extensions.wysihtml.EWysiHtml', array(
								'model'=>$model,
								'attribute'=>'description', //Model attribute name. Nome do atributo do modelo.
								'options'=>array(
									'color'=>false,
									'html'=>true,
									'controls'=>'bold italic underline | alignleft center alignright justify | cut copy paste pastetext | numbering image source',
								),
								'value'=>$model->description,
							'htmlOptions'=>array('class'=>'form-control','rows'=>'5'),
						));
			?>
			<?php echo $form->error($model,'content'); ?>
		</div>
	</div>

	<div class="form-group buttons col-md-12">
		<?php 
		if($model->isNewRecord):
			echo CHtml::submitButton(
				Yii::t('global', 'Save'),
				array(
					'style'=>'width:100px',
					'class'=>'btn btn-success',
					'id'=>'submit-btn',
					'href'=>CHtml::normalizeUrl(array('products/createCategory'))
				)
			);
			/*echo CHtml::ajaxSubmitButton(Yii::t('global', 'Save'),CHtml::normalizeUrl(array('products/createCategory')),array('dataType'=>'json','success'=>'js:
				function(data){
					if(data.status=="success"){
						$("#cmessage").html(data.div);
						$("#cmessage").parent().removeClass("hide");
						$.fn.yiiGridView.update("category-grid", {
							data: $(this).serialize()
						});
						return false;
					}else{
						$("form[id=\'product-form\']").parent().html(data.div);
					}
					return false;
				}'
			),
			array('style'=>'width:100px','class'=>'btn btn-success','id'=>uniqid()));*/
		else:
			echo CHtml::submitButton(
				Yii::t('global', 'Update'),
				array(
					'style'=>'width:100px',
					'class'=>'btn btn-success',
					'id'=>'submit-btn',
					'href'=>CHtml::normalizeUrl(array('products/updateCategory','id'=>$model->id))
				)
			);
			/*echo CHtml::ajaxSubmitButton(Yii::t('global', 'Update'),
				CHtml::normalizeUrl(array('products/updateCategory','id'=>$model->id)),
				array(
					//'dataType'=>'json',
					'async'=> false,
					//'data'=>'new FormData($("#category-form")[0])',	
					'cache'=> false,
					'contentType'=> false,
					'processData'=> false,
					'success'=>'js:
					function(data){
						if(data.status=="success"){
							$("#cmessage").html(data.div);
							$("#cmessage").parent().removeClass("hide");
							$.fn.yiiGridView.update("category-grid", {
								data: $(this).serialize()
							});
							return false;
						}else{
							$("form[id=\'category-form\']").parent().html(data.div);
						}
						return false;
					}'
			),
			array('style'=>'width:100px','class'=>'btn btn-success','id'=>uniqid()));*/
		endif;
		?>
	</div>

<?php $this->endWidget(); ?>
<script type="text/javascript">
$(function(){
	$('#submit-btn').click(function(){
		var formData = new FormData($('form[id="category-form"]')[0]);
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
					$.fn.yiiGridView.update("category-grid", {
						data: $(this).serialize()
					});
					if(data.icon){
						$('#icon').html(data.icon);
						$('#icon').removeClass('hide');
					}
					return false;
				}else{
					$("form[id='category-form']").parent().html(data.div);
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
