<style>
.form-horizontal .form-group{border-top: 1px solid #e7e7e7;clear: both;padding: 20px 16px;position: relative;margin:0;}
</style>
<div class="alert alert-success hide">
	<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
	<span id="message"></span>
</div>
<?php $form=$this->beginWidget('CActiveForm',array('id'=>'setting-form','htmlOptions'=>array('class'=>'form-horizontal'))); ?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>
	
	<h3 class="no-margin"><?php echo Yii::t('GalleryModule.gallery','Configure gallery options');?></h3>
	<p class="mt10 mb10"><?php echo Yii::t('GalleryModule.gallery','Gallery need several configuration. Please separated allowed file type with comas "," and fill file size in bytes.');?></p>

	<?php if($model['configs']): ?>
	<?php foreach($model['configs'] as $id=>$config):?>
	<div class="form-group row <?php if($config['hidden']):?>hide<?php endif;?>">
		<label class="col-md-3"><?php echo Yii::t('GalleryModule.gallery',$config['title']);?></label>
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
		echo CHtml::ajaxSubmitButton(Yii::t('global', 'Save'),CHtml::normalizeUrl(array('/email/templates/setting')),array('dataType'=>'json','success'=>'js:
				function(data){
					if(data.status=="success"){
						$("#message").html(data.div);
						$("#message").parent().removeClass("hide");
						$.fn.yiiGridView.update("setting-grid", {
							data: $(this).serialize()
						});
						return false;
					}else{
						$("form[id=\'setting-form\']").parent().html(data.div);
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
