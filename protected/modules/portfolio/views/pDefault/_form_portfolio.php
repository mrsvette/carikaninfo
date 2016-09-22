<div class="alert alert-success hide">
	<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
	<span id="portfolio-message"></span>
</div>

<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'portfolio-form',
		'enableAjaxValidation'=>false,
		'htmlOptions'=>array('class'=>'form-horizontal','enctype' =>'multipart/form-data'),
)); ?>

	<ul class="nav nav-tabs nav-portfolio nav-justified" <?php if(count(PostLanguage::items(null))<=1):?>style="display:none;"<?php endif;?>>
		<?php foreach(PostLanguage::items(null) as $lang1=>$name1):?>
		<li>
			<a data-toggle="tab" href="#language-<?php echo $lang1;?>">
				<i class="glyphicon glyphicon-tasks"></i> <strong><?php echo $name1;?></strong>
			</a>
		</li>
		<?php endforeach;?>
	</ul>
	<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>
	<div class="tab-content">
		<div class="mt20 clearfix"></div>
		<?php foreach(PostLanguage::items(null) as $lang=>$name):?>
		<div id="language-<?php echo $lang;?>" class="tab-pane tab-pane-portfolio">
			<div class="form-group">
				<?php echo $form->labelEx($model2,'title',array('class'=>'col-md-3')); ?>
				<div class="col-md-6">
					<?php echo $form->textField($model2,'title['.$lang.']',array('size'=>60,'maxlength'=>128,'class'=>'form-control','value'=>(!$model->isNewRecord)? $model2->getValue('title',$model->id,$lang):'')); ?>
					<?php echo $form->error($model2,'title'); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->labelEx($model2,'meta_description',array('class'=>'col-md-3')); ?>
				<div class="col-md-9">
					<?php echo $form->textArea($model2,'meta_description['.$lang.']',array('rows'=>2,'class'=>'form-control','value'=>(!$model->isNewRecord)? $model2->getValue('meta_description',$model->id,$lang):'')); ?>
					<?php echo $form->error($model2,'meta_description'); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->labelEx($model2,'content',array('class'=>'col-md-12')); ?>
				<div class="col-md-12">
					<?php 
						$this->widget('ext.redactor.ERedactorWidget',array(
							'model'=>$model2,
							'attribute'=>'content['.$lang.']',
							'options'=>array(
								'fileUpload'=>Yii::app()->createUrl('appadmin/uploads/fileUpload',array(
									'attr'=>'content',
								)),
								'fileUploadErrorCallback'=>new CJavaScriptExpression(
									'function(obj,json) { alert(json.error); }'
								),
								'imageUpload'=>Yii::app()->createUrl('appadmin/uploads/imageUpload',array(
									'attr'=>'content',
								)),
								'imageGetJson'=>Yii::app()->createUrl('appadmin/uploads/imageList',array(
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
					<?php echo $form->error($model2,'content'); ?>
				</div>
			</div>
			<?php /*<div class="form-group">
				<?php echo $form->labelEx($model2,'meta_keywords',array('class'=>'col-md-3')); ?>
				<div class="col-md-6">
					<?php echo $form->textField($model2,'meta_keywords['.$lang.']',array('size'=>60,'maxlength'=>128,'class'=>'form-control')); ?>
					<?php echo $form->error($model2,'meta_keywords'); ?>
				</div>
			</div>*/?>
		</div>
		<?php endforeach;?>
	</div>

	<?php if($model->isNewRecord):?>
	<div class="form-group">
		<?php echo $form->labelEx($model,'p_image',array('class'=>'col-md-3')); ?>
		<div class="col-md-9">
			<?php if(!$model->isNewRecord):?>
				<?php echo CHtml::image(Yii::app()->request->baseUrl.'/'.$model->firstImage->thumb.$model->firstImage->image,'',array('class'=>'mb10'));?>
			<?php endif;?>
			<?php echo $form->fileField($model,'p_image',array('class'=>'')); ?>
			<?php echo $form->error($model,'p_image'); ?>
		</div>
	</div>
	<?php else:?>
	<div class="form-group">
		<label class="col-md-3">Image</label>
		<div class="col-md-9">
			<div class="row">
				<div class="col-md-4">
					<?php echo $form->fileField($model,'p_image',array('id'=>'portfolio-image','href'=>Yii::app()->createUrl('/portfolio/pDefault/uploadImage',array('id'=>$model->id)))); ?>
				</div>
			<div class="col-md-8 table-responsive">
				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'dataProvider'=>$imagesProvider,
					'itemsCssClass'=>'table table-striped mb30',
					'id'=>'portfolio-image-grid',
					'afterAjaxUpdate' => 'reloadGrid',
					'hideHeader'=>true,
					'columns'=>array(
						array(
							'value'=>'$this->grid->dataProvider->getPagination()->getOffset()+$row+1',
						),
						array(
							'name'=>'content_rel.title',
							'type'=>'raw',
							'value'=>'CHtml::image(Yii::app()->request->baseUrl."/".$data->thumb.$data->image)',
						),
						array(
							'class'=>'CButtonColumn',
							'template'=>'{delete}',
							'buttons'=>array
								(
									'delete'=>array(
											'label'=>'<i class="fa fa-trash-o"></i>',
											'imageUrl'=>false,
											'options'=>array('title'=>'Delete'),
											'url'=>'Yii::app()->createUrl(\'portfolio/pDefault/deleteImage\',array(\'id\'=>$data->id))',
											'visible'=>'Rbac::ruleAccess(\'delete_p\')',
										),
								),
							'htmlOptions'=>array('style'=>'width:10%;','class'=>'table-action'),
						),
					),
				)); ?>
				</div>
			</div>
		</div>
	</div>
	<?php endif;?>
 
	<div class="form-group">
		<?php echo $form->labelEx($model,'client_name',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->textField($model,'client_name',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'client_name'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'website',array('class'=>'col-md-3')); ?>
		<div class="col-md-5">
			<?php echo $form->textField($model,'website',array('class'=>'form-control','placeholder'=>'www.domain-name.com')); ?>
			<?php echo $form->error($model,'website'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'category_id',array('class'=>'col-md-3')); ?>
		<div class="col-md-4">
			<?php echo $form->dropDownList($model,'category_id',CHtml::listData(ModPortfolioCategory::model()->findAll(),'id','title'),
			array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'category_id'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'start_date',array('class'=>'col-md-3')); ?>
		<div class="col-md-3">
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'model'=>$model,
				'attribute'=>'start_date',
				'options'=>array(
					'showAnim'=>'fold',
				),
				'htmlOptions'=>array(
					'class'=>'form-control','placeholder'=>date("Y-m-d")
				),
				'value'=>$model->end_date,
			)); ?>
			<?php echo $form->error($model,'start_date'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'end_date',array('class'=>'col-md-3')); ?>
		<div class="col-md-3">
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'model'=>$model,
				'attribute'=>'end_date',
				'options'=>array(
					'showAnim'=>'fold',
				),
				'htmlOptions'=>array(
					'class'=>'form-control','placeholder'=>date("Y-m-d")
				),
				'value'=>$model->end_date,
			)); ?>
			<?php echo $form->error($model,'end_date'); ?>
		</div>
	</div>

	<div class="form-group buttons col-md-12">
		<?php 
			if($model->isNewRecord):
				echo CHtml::submitButton(
					Yii::t('global', 'Create'),
					array(
						'style'=>'width:100px',
						'class'=>'btn btn-success',
						'id'=>'portfolio-submit-btn',
						'href'=>CHtml::normalizeUrl(array('pDefault/create'))
					)
				);
			else:
				echo CHtml::submitButton(
					Yii::t('global', 'Update'),
					array(
						'style'=>'width:100px',
						'class'=>'btn btn-success',
						'id'=>'portfolio-submit-btn',
						'href'=>CHtml::normalizeUrl(array('pDefault/update','id'=>$model->id))
					)
				);
			endif;
			?>
	</div>

<?php $this->endWidget(); ?>
<script type="text/javascript">
$(function(){
	$('#portfolio-submit-btn').click(function(){
		var formData = new FormData($('form[id="portfolio-form"]')[0]);
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
					$("#portfolio-message").html(data.div);
					$("#portfolio-message").parent().removeClass("hide");
					$.fn.yiiGridView.update('portfolio-grid', {
						data: $(this).serialize()
					})
					return false;
				}else{
					$("form[id='portfolio-form']").parent().html(data.div);
				}
			},
			cache: false,
			contentType: false,
			processData: false
		});
		return false;
	});

	$('.nav-portfolio').find('li:first').addClass('active');
	$('.tab-pane-portfolio:first').addClass('active');

	$('#portfolio-image').change(function(){
		var formData = new FormData($('form[id="portfolio-form"]')[0]);
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
					$.fn.yiiGridView.update('portfolio-image-grid');
					return false;
				}else{
					//$("form[id='portfolio-form']").parent().html(data.div);
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
