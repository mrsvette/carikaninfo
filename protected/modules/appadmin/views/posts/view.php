<?php
$this->breadcrumbs=array(
	ucfirst(Yii::app()->controller->module->id)=>array('/'.Yii::app()->controller->module->id.'/'),
	$model->content_rel->title,
);
$this->pageTitle=$model->content_rel->title;

$this->menu=array(
	array('label'=>Yii::t('global','List').' '.Yii::t('post','Post'), 'url'=>array('admin')),
	array('label'=>Yii::t('global','Create').' '.Yii::t('post','Post'), 'url'=>array('create')),
);
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-btns">
			<a class="panel-close" href="#">×</a>
			<a class="minimize" href="#">−</a>
		</div>
		<h4 class="panel-title"><?php echo $model->content_rel->title;?></h4>
	</div>
	<div class="panel-body">
		<?php $this->renderPartial('_view', array(
			'data'=>$model,
		)); ?>
	</div>
</div>


<div id="comments">
	<?php if($model->commentCount>=1): ?>
		<h3>
			<?php echo $model->commentCount>1 ? $model->commentCount . ' comments' : 'One comment'; ?>
		</h3>

		<?php $this->renderPartial('_comments',array(
			'post'=>$model,
			'comments'=>$model->comments,
		)); ?>
	<?php endif; ?>

</div><!-- comments -->
