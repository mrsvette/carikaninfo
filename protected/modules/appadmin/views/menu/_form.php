<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'menu-form',
	'enableAjaxValidation'=>false,
)); ?>

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
		<div class="form-group col-md-6">
			<?php echo $form->labelEx($model2,'nama_menu'); ?>
			<?php echo $form->textField($model2,'nama_menu['.$lang.']',array('size'=>60,'maxlength'=>128,'value'=>(!$model2->isNewRecord)? $model2->getValue('nama_menu',$model->id,$lang):'')); ?>
		</div>

		<div class="form-group col-md-6">
			<?php echo $form->labelEx($model2,'icon_fa'); ?>
			<div class="input-group">
			<?php
				$this->widget('application.extensions.IonPicker.IonPicker', array(
								'model'=>$model2,
								'attribute'=>'icon_fa['.$lang.']',
								'options'=>array(
									'placement'=>'bottomRight', //inline topLeftCorner topLeft top topRight topRightCorner
									'hideOnSelect'=>true,
								),
								'value'=>(!$model2->isNewRecord)? $model2->getValue('icon_fa',$model->id,$lang) : '',
							'htmlOptions'=>array('class'=>'form-control icp icp-auto','value'=>(!$model2->isNewRecord)? $model2->getValue('icon_fa',$model->id,$lang) : ''),
						));
			?>
            <span class="input-group-addon"><i class="fa fa-fw"></i></span>
            </div>
			<?php echo $form->error($model2,'icon_fa'); ?>
		</div>

		<div class="form-group col-md-6">
			<?php echo $form->labelEx($model2,'keterangan'); ?>
			<?php echo $form->textField($model2,'keterangan['.$lang.']',array('size'=>60,'maxlength'=>255,'value'=>(!$model2->isNewRecord)? $model2->getValue('nama_menu',$model->id,$lang):'')); ?>
		</div>
				
		<div class="form-group col-md-6">
			<?php echo $form->labelEx($model2,'link_action'); ?>
			<?php echo $form->dropDownList($model2,'link_type['.$lang.']',Menu::listLinkType('- Choose -'),array('onchange'=>'reloadMe(this);','lang'=>$lang,'class'=>'types')); ?>
		</div>
	
		<div id="show_items_<?php echo $lang;?>">
			<?php if(!$model->isNewRecord):?>
				<?php if($model->content_rel->rel_id>0): ?>
				<div class="form-group  col-md-6">
					<label>&nbsp;</label>
					<?php echo $form->dropDownList($model2,'page['.$lang.']',Post::listItems(),array('class'=>'form-control')); ?>
				</div>
				<?php else:?>
				<div class="form-group  col-md-6">
					<label>&nbsp;</label>
					<?php echo $form->textField($model2,'link_action['.$lang.']',array('size'=>60,'maxlength'=>255,'value'=>(!$model2->isNewRecord)? $model2->getValue('link_action',$model->id,$lang):'')); ?>
				</div>
				<?php endif;?>
			<?php endif;?>
		</div>
	</div>
	<?php endforeach;?>

	<div class="form-group col-md-6">
		<?php echo $form->labelEx($model,'parent_id'); ?>
		<?php //echo $form->dropDownList($model,'parent_id',Menu::getDropdownItems(),array('multiple'=>true,'style'=>'width:200px;height:100px;')); ?>
		<?php echo $form->dropDownList($model,'parent_id',Menu::getDropdownItems()); ?>
		<?php echo $form->error($model,'parent_id'); ?>
	</div>

	<?php if(!$model->isNewRecord):?>
	<div class="form-group col-md-6">
		<?php echo $form->labelEx($model,'urutan'); ?>
		<?php echo $form->textField($model,'urutan',array('size'=>2,'maxlength'=>2)); ?>
		<?php echo $form->error($model,'urutan'); ?>
	</div>
	<?php endif;?>
	
	<div class="form-group  col-md-6">
		<?php echo $form->labelEx($model,'group_id'); ?>
		<?php echo $form->dropDownList($model,'group_id',MenuGroup::listData()); ?>
		<?php echo $form->error($model,'group_id'); ?>
	</div>

	<div class="form-group col-md-6">
		<?php echo $form->labelEx($model,'notaktif'); ?>
		<?php echo $form->radioButtonList($model,'notaktif',Lookup::items('MenuStatus'),array('template'=>'{input} {label}','separator'=>'')); ?>
		<?php echo $form->error($model,'notaktif'); ?>
	</div>

	<div class="form-group col-md-6">
		<?php echo $form->labelEx($model,'role'); ?>
		<?php echo $form->radioButtonList($model,'role',Lookup::items('VisibleMenu'),array('template'=>'{input} {label}','separator'=>'')); ?>
		<?php echo $form->error($model,'role'); ?>
	</div>

	<div class="form-group  col-md-12">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('global','Create') : Yii::t('global','Save'),array('style'=>'min-width:100px;')); ?>
	</div>

</div><!-- tab-content -->
<?php $this->endWidget(); ?>
<script type="text/javascript">
function reloadMe(data){
	var lang=$(data).attr('lang');
	$.ajax({
		'beforeSend': function() { Loading.show(); },
		'complete': function() { Loading.hide(); },
		'url': "<?php echo Yii::app()->createUrl('/appadmin/menu/ajax');?>",
		'dataType': 'json',
		'type':'post',
		'data':{'link_type':$(data).val(),'lang':lang},
		'success': function(data) {
			if(data.status=='success'){
				$('#show_items_'+lang).html(data.div);
			}
		}
	});
}
$(function(){
	$('.nav-tabs').find('li:first').addClass('active');
	$('.tab-pane:first').addClass('active');
});
</script>
<style>
#Menu_notaktif input, #Menu_role input {float:left;margin-right:10px;}
#Menu_notaktif label, #Menu_role label {width:25%;float:left;}
</style>
