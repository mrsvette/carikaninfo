<div class="form row">
	<div class="upload-image">
	<?php foreach($images as $index=>$data){?>
				<?php echo CHtml::link(CHtml::image(Yii::app()->request->baseUrl.'/upload/'.$data,'',array('style'=>'max-width:200px;'),array('/'.$this->route.'/image/'.$data)));?>

	<?php } ?>
	</div>	
</div>