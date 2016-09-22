<?php
/* @var $this ExtensionsController */
/* @var $model Extension */

$this->breadcrumbs=array(
	'Extensions'=>array('view'),
	Yii::t('global','Manage'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' Extension', 'url'=>array('view')),
);

Yii::app()->clientScript->registerScript('search', "
initGrid();
");
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title"><?php echo Yii::t('global','Manage');?> Extension</h4>
	</div>
	<div class="panel-body">
		<div class="table-responsive">
			<?php $this->widget('zii.widgets.grid.CGridView', array(
				'id'=>'extension-grid',
				'dataProvider'=>$dataProvider,
			'itemsCssClass'=>'table table-striped mb30',
				'afterAjaxUpdate' => 'initGrid',
				'columns'=>array(
					array(
						//'value'=>'CHtml::image($data[\'path\'].$data[\'icon\'])',
						'value'=>'CHtml::image(
								Yii::app()->createUrl(\'appadmin/extensions/renderImage\',array(\'mod\'=>$data[\'id\'],\'icon\'=>$data[\'icon\'])),
								$data[\'name\'],
								array(\'height\'=>\'40px\')
							)',
						'type'=>'raw',
						'htmlOptions'=>array('style'=>'text-align:center;'),
					),
					array(
						'header'=>Yii::t('AppadminModule.extension','Name'),
						'value'=>'CHtml::link($data[\'name\'],array(\'/\'.$data[\'id\']))."<br/>by ".CHtml::link($data[\'author\'],$data[\'author_url\'])',
						'type'=>'raw',
					),
					array(
						'header'=>Yii::t('AppadminModule.extension','Description'),
						'value'=>'$data[\'description\']',
						'type'=>'raw',
					),
					array(
						'header'=>Yii::t('AppadminModule.extension','Action'),
						'value'=>'Extension::getActionButton($data)',
						'type'=>'raw',
					),
				),
			)); ?>
		</div>
	</div>
</div>
<script type="text/javascript">
function initGrid(){
$(function(){
$('a[id="btn-action"]').click(function(){
	if(confirm('Anda yakin ingin melakukan action ini?')){
		var id = $(this).attr('attr-id');
		$.ajax({
			'beforeSend': function() { Loading.show(); },
			'complete': function() { Loading.hide(); },
			'url': $(this).attr('href'),
			'dataType': 'json',
			'type':'post',
			'data':{'id':id},
			'success': function(data) {
				if(data.status=='success'){
					$.fn.yiiGridView.update('extension-grid');
				}
			}
		});
	}
	return false;
});
});
}
</script>
