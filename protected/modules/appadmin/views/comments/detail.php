<?php
$this->breadcrumbs=array(
	Yii::t('post','Comment')=>array('view'),
	Yii::t('global','Manage'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' '.Yii::t('post','Comment'), 'url'=>array('view')),
	array('label'=>Yii::t('global','Update').' '.Yii::t('post','Comment'), 'url'=>array('update','id'=>$model->id)),
);
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-btns">
			<a class="panel-close" href="#">×</a>
			<a class="minimize" href="#">−</a>
		</div>
		<h4 class="panel-title"><?php echo Yii::t('global','Detail').' '.Yii::t('post','Comment');?></h4>
	</div>
	<div class="panel-body">
		<div class="table-responsive">
			<?php
			$this->widget('zii.widgets.CDetailView', array(
				'data'=>$model,
				'itemCssClass'=>'table table-striped mb30',
				'attributes'=>array(
					'author',
					'email',
					'content',
					array(
						'label'=>'Post Title',
						'type'=>'raw',
						'value'=>CHtml::link($model->post->title,array('/'.Yii::app()->controller->module->id.'/posts/view','id'=>$model->post->id,'title'=>$model->post->title)),
					),
					array(
						'label'=>'Status',
						'type'=>'raw',
						'value'=>Lookup::item('CommentStatus',$model->status),
					),
				),
			));
			?>
		</div>
	</div>
</div>
