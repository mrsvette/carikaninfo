<style>
#ModEmailTemplate_enabled input {float:left;margin-right:10px;}
#ModEmailTemplate_enabled label, #ModEmailTemplate_enabled label {width:15%;float:left;}
</style>
<div class="alert alert-success hide">
	<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
	<span id="template-message"></span>
</div>
<?php $form=$this->beginWidget('CActiveForm',array('id'=>'email-template-form','htmlOptions'=>array('class'=>'form-horizontal'))); ?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>

	<h4><?php echo Yii::t('EmailModule.template','Manage email template');?></h4>
	<p><?php echo Yii::t('EmailModule.template','Most email templates receives parameters. 
	You can browse them on "Variables" tab. Copy variable name to email template and hit preview to see parsed content. 
	Please note that values will be different when actual email is sent.');?></p>

	<div class="form-group">
		<?php echo $form->labelEx($model,'enabled',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->radioButtonList($model,'enabled',array('No','Yes'),array('template'=>'{input} {label}','separator'=>'')); ?>
			<?php echo $form->error($model,'enabled'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'category',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->textField($model,'category',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'category'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'subject',array('class'=>'col-md-3')); ?>
		<div class="col-md-7">
			<?php echo $form->textField($model,'subject',array('size'=>80,'maxlength'=>128,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'subject'); ?>
		</div>
	</div>

	<div class="form-group">
		<div class="col-md-12">
			<?php 
				/*$this->widget('ext.redactor.ERedactorWidget',array(
					'model'=>$model,
					'attribute'=>'content',
					'options'=>array(
						'convertDivs'=> false,
					),
					'htmlOptions'=>array('value'=>$model->content),
				));*/
			?>
			<?php echo $form->textArea($model,'content',array('rows'=>10,'class'=>'form-control')); ?>
			<?php echo $form->error($model,'content'); ?>
		</div>
	</div>

	<div class="form-group buttons col-md-12">
		<?php 
			echo CHtml::ajaxSubmitButton(Yii::t('global', 'Update'),CHtml::normalizeUrl(array('templates/update','id'=>$model->id)),array('dataType'=>'json','success'=>'js:
				function(data){
					if(data.status=="success"){
						$("#template-message").html(data.div);
						$("#template-message").parent().removeClass("hide");
						return false;
					}else{
						$("form[id=\'email-template-form\']").parent().html(data.div);
					}
					return false;
				}'
			),
			array('style'=>'width:100px','class'=>'btn btn-success','id'=>uniqid()));
		?>
		<?php echo CHtml::link('Preview',array('templates/preview','id'=>$model->id),array('onclick'=>'return preview(this);','class'=>'btn btn-success','style'=>'width:100px;'));?>
	</div>

<?php $this->endWidget(); ?>
