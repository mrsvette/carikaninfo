<style>
.form-horizontal .form-group{border-top: 1px solid #e7e7e7;clear: both;padding: 20px 16px;position: relative;margin:0;}
</style>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title"><?php echo Yii::t('global',ucfirst($model->name).' Extension Settings');?></h4>
	</div>
	<div class="panel-body">

		<?php if(Yii::app()->user->hasFlash('save')): ?>
		<div class="alert alert-success">
			<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
			<?php echo Yii::app()->user->getFlash('save'); ?>
		</div>
		<?php endif; ?>

		<?php $form=$this->beginWidget('CActiveForm',array('id'=>'setting-form','htmlOptions'=>array('class'=>'form-horizontal'))); ?>

			<?php //echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>
			<p class="mb10"><?php echo Yii::t('AppadminModule.extension','Every extension can have settings page. Only requirement is to have config value in manifest.json');?></p>
			<?php if($model['configs']): ?>
			<?php foreach($model['configs'] as $id=>$config):?>
			<div class="form-group <?php if($config['hidden']):?>hide<?php endif;?>">
				<label class="col-md-3"><?php echo $config['title'];?></label>
				<div class="col-md-4">
					<?php if(isset($config['options'])): ?>
					<?php echo CHtml::dropDownList($config['name'],$config['value'],$config['options'],array('class'=>'form-control','group'=>$config['group'],'toggle'=>$config['toggle'],'show'=>$config['show'])); ?>
					<?php else: ?>
						<?php if($config['type']=='password'):?>
						<?php echo CHtml::passwordField($config['name'],$config['value'],array('class'=>'form-control','group'=>$config['group'],'placeholder'=>$config['placeholder'])); ?>
						<?php else:?>
						<?php echo CHtml::textField($config['name'],$config['value'],array('class'=>'form-control','group'=>$config['group'],'placeholder'=>$config['placeholder'])); ?>
						<?php endif;?>
					<?php endif; ?>
				</div>
			</div>
			<?php endforeach;?>
			<?php endif; ?>

			<?php echo CHtml::hiddenField('module',$model->name); ?>

			<div class="form-group buttons col-md-12">
				<?php 
					echo CHtml::submitButton(Yii::t('global', 'Save'),
					array('style'=>'width:100px','class'=>'btn btn-success','id'=>uniqid()));
				?>
			</div>

		<?php $this->endWidget(); ?>
	</div>
</div>
<script type="text/javascript">
$(function(){
	//hidden input
	hiddenInput();
	//trigger onchange
	$('#setting-form').find('select[toggle="1"]').each(function(){
		var dest = $(this).attr('show');
		$(this).change(function(){
			if($(this).val()==dest){
				$('input[group="'+dest+'"]').parent().parent().removeClass('hide');
				$('input[group="'+dest+'"]').removeAttr('disabled');
			}else{
				$('input[group="'+dest+'"]').parent().parent().addClass('hide');
				hiddenInput();
			}
		});
		if($(this).val()==dest)
			$(this).trigger('change');
	});
});
function hiddenInput(){
	$('#setting-form').find('.form-group').each(function(){
		if($(this).hasClass('hide'))
			$(this).find('input').attr("disabled","disabled");
	});
}
</script>
