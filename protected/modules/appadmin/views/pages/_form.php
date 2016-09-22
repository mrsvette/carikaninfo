<?php $form=$this->beginWidget('CActiveForm'); ?>

<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>
<ul class="nav nav-tabs nav-justified" <?php if(count(PostLanguage::items(null))<=1):?>style="display:none;"<?php endif;?>>
	<?php foreach(PostLanguage::items(null) as $lang1=>$name1):?>
	<li>
		<a data-toggle="tab" href="#language-<?php echo $lang1;?>">
			<i class="glyphicon glyphicon-tasks"></i> <strong><?php echo $name1;?></strong>
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
						'convertDivs'=> false,
					),
					'htmlOptions'=>array('value'=>(!$model->isNewRecord)? $model2->getValue('content',$model->id,$lang):''),
				));
			?>
			<?php /*$this->widget('application.extensions.eckeditor.ECKEditor', array(
                'model'=>$model2,
                'attribute'=>'content['.$lang.']',
				'htmlOptions'=>array('value'=>(!$model->isNewRecord)? $model2->getValue('content',$model->id,$lang):''),
                'config' => array(
                    /*'toolbar'=>array(
                        array( 'Source', '-', 'Bold', 'Italic', 'Underline', 'Strike' ),
                        array( 'Image', 'Link', 'Unlink', 'Anchor' ) ,
                    ),*
					'toolbar'=>CJSON::decode("[
							{ name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
							{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
							{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
							{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl' ] },
							{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
							{ name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
							{ name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
							{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
							{ name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
							{ name: 'others', items: [ '-' ] }
						]"),//please see on buld-config.js
                	'language'=>'en',
                	//'toolbar'=>'full',
                  ),
                )); */?>
			<?php echo $form->error($model2,'content'); ?>
		</div>
		<div class="form-group col-sm-8">
			<?php echo $form->labelEx($model2,'meta_keywords'); ?>
			<?php echo $form->textField($model2,'meta_keywords['.$lang.']',array('value'=>(!$model->isNewRecord)? $model2->getValue('meta_keywords',$model->id,$lang):'')); ?>
			<?php echo $form->error($model2,'meta_keywords'); ?>
		</div>
		<div class="form-group col-sm-12">
			<?php echo $form->labelEx($model2,'meta_description'); ?>
			<?php echo $form->textField($model2,'meta_description['.$lang.']',array('value'=>(!$model->isNewRecord)? $model2->getValue('meta_description',$model->id,$lang):'')); ?>
			<?php echo $form->error($model2,'meta_description'); ?>
		</div>
	</div>
	<?php endforeach;?>

	<div class="form-group col-sm-4">
		<?php echo $form->labelEx($model,'tags'); ?>
		<?php $this->widget('CAutoComplete', array(
				'model'=>$model,
				'attribute'=>'tags',
				'url'=>array('suggestTags'),
				'multiple'=>true,
				'htmlOptions'=>array('size'=>50),
		)); ?>
		<p class="hint">Please separate different tags with commas.</p>
		<?php echo $form->error($model,'tags'); ?>
	</div>

	<div class="form-group col-sm-4">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status',Lookup::items('PostStatus')); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<?php echo $form->hiddenField($model,'allow_comment',array('value'=>0)); ?>

	<div class="form-group col-sm-4">
		<?php echo $form->labelEx($model,'layout'); ?>
		<?php echo $form->dropDownList($model,'layout',$model->getLayoutItems(null,'.html')); ?>
		<?php echo $form->error($model,'layout'); ?>
	</div>

	<div class="form-group col-sm-12">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('global','Create') : Yii::t('global','Save'),array('style'=>'min-width:100px;')); ?>
	</div>

<?php $this->endWidget(); ?>
<script type="text/javascript">
function getSlug(data){
	var lang=$(data).attr('lang');
	$.ajax({
		'beforeSend': function() { Loading.show(); },
		'complete': function() { Loading.hide(); },
		'url': "<?php echo Yii::app()->createUrl('/appadmin/pages/getSlug');?>",
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
