<li>
	<p><b><?php echo $data->title;?></b>
	<span class="pull-right">
		<?php echo CHtml::link('<i class="fa fa-pencil"></i>',array('sDefault/updateItem','id'=>$data->id),array('title'=>Yii::t('global','Update'),'onclick'=>'return updateItem(this);'));?> 
		<?php echo CHtml::link('<i class="fa fa-trash-o"></i>',array('sDefault/deleteItem','id'=>$data->id),array('title'=>Yii::t('global','Delete'),'onclick'=>'return deleteItem(this);'));?>
	</span></p>
	<?php echo CHtml::image(Yii::app()->request->baseUrl.'/'.$data->image_path,$data->title,array('class'=>'img-responsive','style'=>'max-height:150px;'));?>
	<p><?php //echo $data->caption;?></p>
</li>
