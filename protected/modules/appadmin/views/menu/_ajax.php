<?php if($link_type==1):?>
<div class="form-group col-md-6">
	<label>&nbsp;</label>
	<?php echo CHtml::activeTextField($model,'link_action['.$lang.']',array('class'=>'form-control','maxlength'=>255,'placeholder'=>'ketikkan link, ex: /site/contact atau http://www.google.com')); ?>
</div>
<?php elseif($link_type==2):?>
<div class="form-group col-md-6">
	<label>&nbsp;</label>
	<?php echo CHtml::activeDropDownList($model,'page['.$lang.']',Post::listItems(),array('class'=>'form-control')); ?>
</div>
<?php endif;?>
