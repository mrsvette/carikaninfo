<?php
$this->breadcrumbs=array(
	Yii::t('post','Comment')=>array('view'),
	Yii::t('global','Manage'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' '.Yii::t('post','Comment'), 'url'=>array('view')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('comment-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
	$('#datepicker_for_create_time').datepicker();
}
");
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-btns">
			<a class="panel-close" href="#">×</a>
			<a class="minimize" href="#">−</a>
		</div>
		<h4 class="panel-title"><?php echo Yii::t('global','List').' '.Yii::t('post','Comment');?></h4>
	</div>
	<div class="panel-body">
		<p>
		<?php echo Yii::t('global','You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.');?>
		</p>

		<?php echo CHtml::link(Yii::t('global','Advanced Search'),'#',array('class'=>'search-button')); ?>
		<div class="search-form" style="display:none">
		<?php $this->renderPartial('_search',array(
			'model'=>$model,
		)); ?>
		</div><!-- search-form -->
		<div class="table-responsive">
		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'comment-grid',
			'dataProvider'=>$model->search(),
			'itemsCssClass'=>'table table-striped mb30',
			'filter'=>$model,
			'afterAjaxUpdate' => 'reinstallDatePicker',
			'columns'=>array(
				array(
					'value'=>'$this->grid->dataProvider->getPagination()->getOffset()+$row+1',
				),
				'author',
				'email',
				'content',
				array(
					'name'=>'status',
					'value'=>'CHtml::activeDropDownList($data,\'status\',Lookup::items(\'CommentStatus\'),array(\'style\'=>\'width:100px;\',\'id\'=>$data->id,\'class\'=>\'status-change\'))',
					'filter'=>Lookup::items('CommentStatus'),
					'type'=>'raw',
				),
				array(
					'header'=>'Post Title',
					'value'=>'CHtml::link($data->post->title,array(\'/appadmin/posts/view\',\'id\'=>$data->post->id,\'title\'=>$data->post->title))',
					'type'=>'raw',
					'filter'=>CHtml::activeTextField($model,'post_cr'),
				),
				array(
					'name'=>'create_time',
					'value'=>'date("d-m-Y H:i:s",$data->create_time)',
					'htmlOptions'=>array('style'=>'text-align:center;'),
					'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'model'=>$model, 
						'attribute'=>'create_time', 
						'language' => 'id',
						'i18nScriptFile' => 'jquery.ui.datepicker-ja.js', // (#2)
						'htmlOptions' => array(
							'id' => 'datepicker_for_create_time',
							'size' => '10',
						),
						'id'=>'filter-create-time',
						'defaultOptions' => array(  // (#3)
							'showOn' => 'focus', 
							'dateFormat' => 'yy-mm-dd',
							'showOtherMonths' => true,
							'selectOtherMonths' => true,
							'changeMonth' => true,
							'changeYear' => true,
							'showButtonPanel' => true,
						)),true), // (#4)
				),
				array(
					'class'=>'CButtonColumn',
					'template'=>'{view}{update}{delete}',
					'viewButtonUrl'=>'Yii::app()->createUrl(\'/appadmin/comments/detail\',array(\'id\'=>$data->id))',
				),
			),
		)); ?>
		</div>
	</div>
</div>

<script type="text/javascript">
$('.status-change').each(function(){
	$(this).change(function(){
		$.ajax({
			'beforeSend':function() { Loading.show(); },
			'complete': function() { Loading.hide(); },
			'url':"<?php echo Yii::app()->createUrl('/appadmin/comments/updateStatus');?>",
			'type':'post',
			'dataType':'json',
			'data':{'id':this.id,'value':this.value},
			'success':function(data){
				if(data.status=='success'){
					$.fn.yiiGridView.update('comment-grid', {
						data: $(this).serialize()
					});
				}
			},
		});
		return false;
	});
});
</script>
