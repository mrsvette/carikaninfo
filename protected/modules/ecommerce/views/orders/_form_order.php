<div class="alert alert-success hide">
	<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
	<span id="message"></span>
</div>
<?php $form=$this->beginWidget('CActiveForm',array('id'=>'order-form')); ?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>
	<div class="col-md-6">
		<div class="form-group">
			<?php echo $form->labelEx($model,'client_id'); ?>
			<?php //echo $form->textField($model,'client_id',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
			<?php $this->widget('ext.bootstrap-select.TbSelect',array(
						'name'=>'client_id',
						'model'=>$model,
						'data' => ModClient::items(null,'Pilih Klien'),
						'htmlOptions' => array(
							//'multiple' => true,
							'data-live-search'=>true,
							'class'=>'form-control no-margin',
						),
					)); 
			?>
			<?php echo $form->error($model,'client_id'); ?>
		</div>

		<div class="form-group">
			<?php echo $form->labelEx($model,'product_id'); ?>
			<?php echo $form->dropDownList($model,'product_id',ModProduct::items(),array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'product_id'); ?>
		</div>

		<div class="form-group">
			<?php echo $form->labelEx($model,'promo_id'); ?>
			<?php echo $form->textField($model,'promo_id',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'promo_id'); ?>
		</div>

		<div class="form-group">
			<?php echo $form->labelEx($model,'period'); ?>
			<?php echo $form->dropDownList($model,'period',ModProduct::periods(false),array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'period'); ?>
		</div>
	</div>
	<div class="col-md-6">
	<div class="form-group">
			<?php echo $form->labelEx($model,'notes'); ?>
			<?php
				$this->widget('application.extensions.wysihtml.EWysiHtml', array(
								'model'=>$model,
								'attribute'=>'notes', //Model attribute name. Nome do atributo do modelo.
								'options'=>array(
									'color'=>true,
									'html'=>true,
									'controls'=>'bold italic underline | alignleft center alignright justify | cut copy paste pastetext | numbering image source',
								),
								'value'=>$model->notes,
							'htmlOptions'=>array('class'=>'form-control','rows'=>'2'),
						));
			?>
			<?php echo $form->error($model,'notes'); ?>
	</div>
	</div>
	<div class="form-group buttons col-md-12">
		<?php 
		if($model->isNewRecord):
			echo CHtml::ajaxSubmitButton(Yii::t('global', 'Save'),CHtml::normalizeUrl(array('orders/create')),array('dataType'=>'json','success'=>'js:
				function(data){
					if(data.status=="success"){
						$("#message").html(data.div);
						$("#message").parent().removeClass("hide");
						$.fn.yiiGridView.update("order-grid", {
							data: $(this).serialize()
						});
						return false;
					}else{
						$("form[id=\'order-form\']").parent().html(data.div);
					}
					return false;
				}'
			),
			array('style'=>'width:100px','class'=>'btn btn-success','id'=>uniqid()));
		else:
			echo CHtml::ajaxSubmitButton(Yii::t('global', 'Update'),CHtml::normalizeUrl(array('orders/update','id'=>$model->id)),array('dataType'=>'json','success'=>'js:
				function(data){
					if(data.status=="success"){
						$("#message").html(data.div);
						$("#message").parent().removeClass("hide");
						return false;
					}else{
						$("form[id=\'order-form\']").parent().html(data.div);
					}
					return false;
				}'
			),
			array('style'=>'width:100px','class'=>'btn btn-success','id'=>uniqid()));
		endif;
		?>
	</div>

<?php $this->endWidget(); ?>
