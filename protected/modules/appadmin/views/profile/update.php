<?php
$this->breadcrumbs=array(
	ucfirst(Yii::app()->controller->module->id)=>array('/'.Yii::app()->controller->module->id.'/'),
	'Profile'=>array('update'),
	Yii::t('global','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('menu','Change Password'), 'url'=>array('changePassword')),
);
?>

<h3><?php echo Yii::t('global','Update');?> User <?php echo $model->id; ?></h3>

<?php if(Yii::app()->user->hasFlash('update')): ?>
<div class="flash-success">
	<?php 
		header('refresh: 3;');
		echo Yii::app()->user->getFlash('update'); 
	?>
</div>
<?php endif; ?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
