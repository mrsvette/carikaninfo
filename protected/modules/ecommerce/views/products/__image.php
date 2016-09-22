<div class="col-xs-12 col-sm-6 col-md-6 image">
<div class="thmb">
	<div class="btn-group fm-group" style="display: none;">
		<button class="btn btn-default dropdown-toggle fm-toggle" data-toggle="dropdown" type="button">
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu fm-menu" role="menu">
			<li>
				<a href="javascript:void(0);" onclick="deleteImage('<?php echo $data->id;?>');"><i class="fa fa-trash-o"></i>Delete</a>
			</li>
		</ul>
	</div>
	<div class="thmb-prev">
		<a data-rel="prettyPhoto" href="<?php echo Yii::app()->request->baseUrl.'/'.$data->src.$data->image;?>" rel="prettyPhoto">
			<?php echo CHtml::image(Yii::app()->request->baseUrl.'/'.$data->src.$data->image,$data->image,array('class'=>'responsive','style'=>'height: auto;max-width: 100% !important;'));?>
		</a>
	</div>
	<h5 class="fm-title"><?php echo $data->image;?></h5>
	<small class="text-muted">Added: <?php echo date("d F Y",strtotime($data->date_entry));?></small>
</div>
</div>
