<style>
#Post_status input, #Post_allow_comment input, #Post_headline input {float:left;margin-right:10px;}
#Post_status label, #Post_allow_comment label, #Post_headline label {width:20%;float:left;}
</style>
<?php $form=$this->beginWidget('CActiveForm',array('id'=>'post-form','htmlOptions'=>array('class'=>''))); ?>

<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>
<ul class="nav nav-tabs nav-justified">
	<?php foreach(PostLanguage::items(null) as $lang1=>$name1):?>
	<li>
		<a data-toggle="tab" href="#language-<?php echo $lang1;?>">
			<i class="glyphicon glyphicon-file"></i> <strong><?php echo $name1;?></strong>
		</a>
	</li>
	<?php endforeach;?>
</ul>
<div class="tab-content">
	<?php foreach(PostLanguage::items(null) as $lang=>$name):?>
	<div id="language-<?php echo $lang;?>" class="tab-pane">
		<div class="form-group col-sm-6">
			<?php echo $form->labelEx($model2,'title'); ?>
			<?php echo $form->textField($model2,'title['.$lang.']',array('onchange'=>'getSlug(this);','lang'=>$lang,'value'=>(!$model->isNewRecord)? $model2->getValue('title',$model->id,$lang):'')); ?>
			<?php echo $form->error($model2,'title'); ?>
		</div>
		<div class="form-group col-sm-6">
			<?php echo $form->labelEx($model2,'slug'); ?>
			<?php echo $form->textField($model2,'slug['.$lang.']',array('value'=>(!$model->isNewRecord)? $model2->getValue('slug',$model->id,$lang):'')); ?>
			<?php echo $form->error($model2,'slug'); ?>
		</div>
		<div class="form-group col-sm-12">
			<?php echo $form->labelEx($model2,'content'); ?>
			<?php 
				$this->widget('ext.redactor.ERedactorWidget',array(
					'model'=>$model2,
					'attribute'=>'content['.$lang.']',
					'options'=>array(
						'fileUpload'=>Yii::app()->createUrl(Yii::app()->controller->module->id.'/uploads/fileUpload',array(
							'attr'=>'content',
						)),
						'fileUploadErrorCallback'=>new CJavaScriptExpression(
							'function(obj,json) { alert(json.error); }'
						),
						'imageUpload'=>Yii::app()->createUrl(Yii::app()->controller->module->id.'/uploads/imageUpload',array(
							'attr'=>'content',
						)),
						'imageGetJson'=>Yii::app()->createUrl(Yii::app()->controller->module->id.'/uploads/imageList',array(
							'attr'=>'content',
						)),
						'imageUploadErrorCallback'=>new CJavaScriptExpression(
							'function(obj,json) { alert(json.error); }'
						),
					),
					'htmlOptions'=>array('value'=>(!$model->isNewRecord)? $model2->getValue('content',$model->id,$lang):''),
				));
			?>
			<?php echo $form->error($model2,'content'); ?>
		</div>
		<div class="form-group col-sm-6">
			<?php echo $form->labelEx($model2,'meta_keywords'); ?>
			<?php echo $form->textField($model2,'meta_keywords['.$lang.']',array('value'=>(!$model->isNewRecord)? $model2->getValue('meta_keywords',$model->id,$lang):'')); ?>
			<?php echo $form->error($model2,'meta_keywords'); ?>
		</div>
		<div class="form-group col-sm-6">
			<?php echo $form->labelEx($model2,'meta_description'); ?>
			<?php echo $form->textField($model2,'meta_description['.$lang.']',array('value'=>(!$model->isNewRecord)? $model2->getValue('meta_description',$model->id,$lang):'')); ?>
			<?php echo $form->error($model2,'meta_description'); ?>
		</div>
	</div>
	<?php endforeach;?>
	<div class="form-group col-sm-6">
		<?php echo $form->labelEx($model,'tags'); ?>
		<?php $this->widget('CAutoComplete', array(
					'model'=>$model,
					'attribute'=>'tags',
					'url'=>array('suggestTags'),
					'multiple'=>true,
					'htmlOptions'=>array('size'=>50,'placeholder'=>'Please separate different tags with commas.'),
		)); ?>
		<!--<p class="hint">Please separate different tags with commas.</p>-->
		<?php echo $form->error($model,'tags'); ?>
	</div>
	<div class="form-group col-sm-6">
		<?php echo $form->labelEx($model,'post_category'); ?>
		<?php echo $form->dropDownList($model,'post_category',PostCategory::getDropDownItems(),array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'post_category'); ?>
	</div>
	<div class="form-group col-sm-6">
		<?php echo $form->labelEx($model,'status'); ?>
			<?php //echo $form->dropDownList($model,'status',Lookup::items('PostStatus')); ?>
			<?php echo $form->radioButtonList($model,'status',Lookup::items('PostStatus'),array('template'=>'{input} {label}','separator'=>'')); ?>
			<?php echo $form->error($model,'status'); ?>
	</div>
	<div class="form-group col-sm-6">
		<?php echo $form->labelEx($model,'allow_comment'); ?>
				<?php //echo $form->dropDownList($model,'allow_comment',Lookup::items('PostAllowComment')); ?>
		<?php echo $form->radioButtonList($model,'allow_comment',Lookup::items('PostAllowComment','global'),array('template'=>'{input} {label}','separator'=>'')); ?>
		<?php echo $form->error($model,'allow_comment'); ?>
	</div>
	<div class="form-group col-sm-6">
		<?php echo $form->labelEx($model,'headline'); ?>
			<?php //echo $form->dropDownList($model,'headline',Lookup::items('PostAllowComment'),array('class'=>'form-control')); ?>
		<?php echo $form->radioButtonList($model,'headline',Lookup::items('PostAllowComment','global'),array('template'=>'{input} {label}','separator'=>'')); ?>
		<?php echo $form->error($model,'headline'); ?>
	</div>
	<div class="col-sm-12 mt20">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('global','Create') : Yii::t('global','Save'),array('style'=>'min-width:100px;')); ?>
	</div>

<?php $this->endWidget(); ?>
<script type="text/javascript">
function getSlug(data){
	var lang=$(data).attr('lang');
	$.ajax({
		'beforeSend': function() { Loading.show(); },
		'complete': function() { Loading.hide(); },
		'url': "<?php echo Yii::app()->createUrl('/appadmin/posts/getSlug');?>",
		'dataType': 'json',
		'type':'post',
		'data':{'title':$(data).val()},
		'success': function(data) {
			if(data.status=='success'){
				$('#PostContent_slug_'+lang).attr('value',data.value);
			}
		}
	});
}
$(function(){
	$('.nav-tabs').find('li:first').addClass('active');
	$('.tab-pane:first').addClass('active');
});
</script>
