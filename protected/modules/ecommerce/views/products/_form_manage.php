<div class="alert alert-success hide">
	<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
	<span id="message"></span>
</div>
<?php $form=$this->beginWidget('CActiveForm',array('id'=>'general-form','htmlOptions'=>array('class'=>'form-horizontal'))); ?>

<?php echo $form->errorSummary(array($model,$model2),null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

<div class="form-group">
	<?php echo $form->labelEx($model,'product_category_id',array('class'=>'col-md-3')); ?>
	<div class="col-md-4">
		<?php echo $form->dropDownList($model,'product_category_id',CHtml::listData(ModProductCategory::model()->findAll(), 'id', 'title'),array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'product_category_id'); ?>
	</div>
</div>

<div class="form-group">
	<?php echo $form->labelEx($model,'status',array('class'=>'col-md-3')); ?>
	<div class="col-md-4">
		<?php echo $form->radioButtonList($model,'status',$model->statuses,array('template'=>'{input} {label}','separator'=>'')); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>
</div>

<div class="form-group">
	<?php echo $form->labelEx($model,'hidden',array('class'=>'col-md-3')); ?>
	<div class="col-md-4">
		<?php echo $form->radioButtonList($model,'hidden',$model->hiddenStatus,array('template'=>'{input} {label}','separator'=>'')); ?>
		<?php echo $form->error($model,'hidden'); ?>
	</div>
</div>

<div class="form-group">
	<?php echo $form->labelEx($model,'setup',array('class'=>'col-md-3')); ?>
	<div class="col-md-8">
		<?php echo $form->radioButtonList($model,'setup',$model->setupStatus,array('template'=>'{input} {label}','separator'=>'')); ?>
		<?php echo $form->error($model,'setup'); ?>
	</div>
</div>

<div class="form-group">
	<?php echo $form->labelEx($model,'title',array('class'=>'col-md-3')); ?>
	<div class="col-md-8">
		<?php echo $form->textField($model,'title',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
</div>

<div class="form-group">
	<?php echo $form->labelEx($model,'slug',array('class'=>'col-md-3')); ?>
	<div class="col-md-8">
		<?php echo $form->textField($model,'slug',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'slug'); ?>
	</div>
</div>

<div class="form-group">
	<?php echo $form->labelEx($model2,'type',array('class'=>'col-md-3')); ?>
	<div class="col-md-8">
		<?php echo $form->radioButtonList($model2,'type',$model2->types,array('template'=>'{input} {label}','separator'=>'')); ?>
		<?php echo $form->error($model2,'type'); ?>
	</div>
</div>

<div class="form-group" id="once-price">
	<?php echo $form->labelEx($model2,'once_price',array('class'=>'col-md-3 mt20')); ?>
	<div class="col-md-3">
		<label><?php echo Yii::t('EcommerceModule.product','Setup Price (IDR)'); ?></label>
		<?php echo $form->textField($model2,'once_setup_price',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
	</div>
	<div class="col-md-3">
		<label><?php echo Yii::t('EcommerceModule.product','Price (IDR)'); ?></label>
		<?php echo $form->textField($model2,'once_price',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
	</div>
	<div class="col-md-3">
		<label><?php echo Yii::t('EcommerceModule.product','Total'); ?></label>
		<?php echo $form->textField($model2,'total',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
	</div>
</div>

				<div class="form-group hide" id="recurrent-price">
					<?php echo $form->labelEx($model2,'w_price',array('class'=>'col-md-3 mt20')); ?>
					<div class="col-md-3">
					<label><?php echo Yii::t('EcommerceModule.product','Setup Price (IDR)'); ?></label>
					<?php echo $form->textField($model2,'w_setup_price',array('size'=>80,'maxlength'=>128,'class'=>'form-control setup-price')); ?>
					</div>
					<div class="col-md-3">
					<label><?php echo Yii::t('EcommerceModule.product','Price (IDR)'); ?></label>
					<?php echo $form->textField($model2,'w_price',array('size'=>80,'maxlength'=>128,'class'=>'form-control price')); ?>
					</div>
					<div class="col-md-2">
					<label><?php echo Yii::t('EcommerceModule.product','Total'); ?></label>
					<?php echo $form->textField($model2,'total',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
					</div>
					<div class="col-md-1">
					<label><?php echo Yii::t('EcommerceModule.product','On'); ?></label>
					<?php echo $form->checkBox($model2,'w_enabled'); ?>
					</div>
				</div>

				<div class="form-group hide" id="recurrent-price">
					<?php echo $form->labelEx($model2,'m_price',array('class'=>'col-md-3')); ?>
					<div class="col-md-3">
					<?php echo $form->textField($model2,'m_setup_price',array('size'=>80,'maxlength'=>128,'class'=>'form-control setup-price')); ?>
					</div>
					<div class="col-md-3">
					<?php echo $form->textField($model2,'m_price',array('size'=>80,'maxlength'=>128,'class'=>'form-control price')); ?>
					</div>
					<div class="col-md-2">
					<?php echo $form->textField($model2,'total',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
					</div>
					<div class="col-md-1">
					<?php echo $form->checkBox($model2,'m_enabled'); ?>
					</div>
				</div>

				<div class="form-group hide" id="recurrent-price">
					<?php echo $form->labelEx($model2,'q_price',array('class'=>'col-md-3')); ?>
					<div class="col-md-3">
					<?php echo $form->textField($model2,'q_setup_price',array('size'=>80,'maxlength'=>128,'class'=>'form-control setup-price')); ?>
					</div>
					<div class="col-md-3">
					<?php echo $form->textField($model2,'q_price',array('size'=>80,'maxlength'=>128,'class'=>'form-control price')); ?>
					</div>
					<div class="col-md-2">
					<?php echo $form->textField($model2,'total',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
					</div>
					<div class="col-md-1">
					<?php echo $form->checkBox($model2,'q_enabled'); ?>
					</div>
				</div>

				<div class="form-group hide" id="recurrent-price">
					<?php echo $form->labelEx($model2,'b_price',array('class'=>'col-md-3')); ?>
					<div class="col-md-3">
					<?php echo $form->textField($model2,'b_setup_price',array('size'=>80,'maxlength'=>128,'class'=>'form-control setup-price')); ?>
					</div>
					<div class="col-md-3">
					<?php echo $form->textField($model2,'b_price',array('size'=>80,'maxlength'=>128,'class'=>'form-control price')); ?>
					</div>
					<div class="col-md-2">
					<?php echo $form->textField($model2,'total',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
					</div>
					<div class="col-md-1">
					<?php echo $form->checkBox($model2,'b_enabled'); ?>
					</div>
				</div>

				<div class="form-group hide" id="recurrent-price">
					<?php echo $form->labelEx($model2,'a_price',array('class'=>'col-md-3')); ?>
					<div class="col-md-3">
					<?php echo $form->textField($model2,'a_setup_price',array('size'=>80,'maxlength'=>128,'class'=>'form-control setup-price')); ?>
					</div>
					<div class="col-md-3">
					<?php echo $form->textField($model2,'a_price',array('size'=>80,'maxlength'=>128,'class'=>'form-control price')); ?>
					</div>
					<div class="col-md-2">
					<?php echo $form->textField($model2,'total',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
					</div>
					<div class="col-md-1">
					<?php echo $form->checkBox($model2,'a_enabled'); ?>
					</div>
				</div>

				<div class="form-group hide" id="recurrent-price">
					<?php echo $form->labelEx($model2,'bia_price',array('class'=>'col-md-3')); ?>
					<div class="col-md-3">
					<?php echo $form->textField($model2,'bia_setup_price',array('size'=>80,'maxlength'=>128,'class'=>'form-control setup-price')); ?>
					</div>
					<div class="col-md-3">
					<?php echo $form->textField($model2,'bia_price',array('size'=>80,'maxlength'=>128,'class'=>'form-control price')); ?>
					</div>
					<div class="col-md-2">
					<?php echo $form->textField($model2,'total',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
					</div>
					<div class="col-md-1">
					<?php echo $form->checkBox($model2,'bia_enabled'); ?>
					</div>
				</div>

<div class="form-group hide" id="recurrent-price">
	<?php echo $form->labelEx($model2,'tria_price',array('class'=>'col-md-3')); ?>
	<div class="col-md-3">
		<?php echo $form->textField($model2,'tria_setup_price',array('size'=>80,'maxlength'=>128,'class'=>'form-control setup-price')); ?>
	</div>
	<div class="col-md-3">
		<?php echo $form->textField($model2,'tria_price',array('size'=>80,'maxlength'=>128,'class'=>'form-control price')); ?>
	</div>
	<div class="col-md-2">
		<?php echo $form->textField($model2,'total',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
	</div>
	<div class="col-md-1">
		<?php echo $form->checkBox($model2,'tria_enabled'); ?>
	</div>
</div>

<div class="form-group">
	<?php echo $form->labelEx($model,'description',array('class'=>'col-md-3')); ?>
	<div class="col-md-8">
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
</div>

<div class="form-group buttons col-md-12">
	<?php 
		echo CHtml::ajaxSubmitButton(Yii::t('global', 'Update'),CHtml::normalizeUrl(array('products/update','id'=>$model->id)),array('dataType'=>'json','success'=>'js:
				function(data){
					if(data.status=="success"){
						$("#message").html(data.div);
						$("#message").parent().removeClass("hide");
						return false;
					}else{
						$("form[id=\'general-form\']").parent().html(data.div);
					}
					return false;
				}'
			),
			array('style'=>'width:100px','class'=>'btn btn-success','id'=>uniqid()));
	?>
</div>

<?php $this->endWidget(); ?>
<script type="text/javascript">
$(function(){
	$('#ModProductPayment_type').change(function(){
		var type = $(this).find('input[type="radio"]:checked').val();
		if(type=='free'){
			$('#once-price').addClass('hide');
			$('div[id="recurrent-price"]').addClass('hide');
		}else if(type=='once'){
			$('#once-price').removeClass('hide');
			$('div[id="recurrent-price"]').addClass('hide');
		}else if(type=='requrrent'){
			$('#once-price').addClass('hide');
			$('div[id="recurrent-price"]').removeClass('hide');
		}
	});
	$('#ModProductPayment_type').find('input[type="radio"]:checked').trigger('change');
	$('#ModProductPayment_once_price').keyup(function(){
		var price = $(this).val()*1;
		var setup_price = $('#ModProductPayment_once_setup_price').val()*1;
		var tot_price = price + setup_price;
		$('#ModProductPayment_total').val(tot_price);
	});
	$('#ModProductPayment_once_setup_price').keyup(function(){
		var setup_price = $(this).val()*1;
		var price = $('#ModProductPayment_once_price').val()*1;
		var tot_price = price + setup_price;
		$('#ModProductPayment_total').val(tot_price);
	});
	$('div[id="recurrent-price"]').find('.setup-price').keyup(function(){
		var setup_price = $(this).val()*1;
		var price = $(this).parent().parent().find('.price').val()*1;
		var tot_price = price + setup_price;
		$(this).parent().parent().find('#ModProductPayment_total').val(tot_price);
	});
	$('div[id="recurrent-price"]').find('.price').keyup(function(){
		var price = $(this).val()*1;
		var setup_price = $(this).parent().parent().find('.setup-price').val()*1;
		var tot_price = price + setup_price;
		$(this).parent().parent().find('#ModProductPayment_total').val(tot_price);
	});
});
</script>
