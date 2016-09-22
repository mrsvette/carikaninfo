<?php
$this->breadcrumbs=array(
	ucfirst(Yii::app()->controller->module->id)=>array('/'.Yii::app()->controller->module->id.'/'),
	'Menu'=>array('view'),
	Yii::t('global','Manage'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' Menu', 'url'=>array('view')),
	array('label'=>Yii::t('global','Create').' Menu', 'url'=>array('create')),
	array('label'=>'Group Menu', 'url'=>array('/'.Yii::app()->controller->module->id.'/menugroup/view')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('menu-grid', {
		data: $(this).serialize()
	});
	return false;
});
$('input.urutan').change(function(){
	$.ajax({
		'beforeSend': function() { Loading.show(); },
		'complete': function() { Loading.hide(); },
		'url': '".Yii::app()->createUrl('/appadmin/menu/updateorder')."',
		'type':'post',
		'dataType': 'json',
		'data':{'id':$(this).attr('id'),'urutan':$(this).val()},
		  	'success': function(data){
				if(data.status=='success'){
					$.fn.yiiGridView.update(\"menu-grid\", {
						data: $(this).serialize()
					});
				}
		  	},
		});
		return false;
	});
$('.dd-item').find('a.delete').click(function(){
	var id = $(this).attr('id');
	var ini = $(this);
	if(confirm('Anda yakin ingin menghapus menu ini?')){
	$.ajax({
		'beforeSend': function() { Loading.show(); },
		'complete': function() { Loading.hide(); },
		'url': $(this).attr('href')+'?ajax=true',
		'type':'post',
		'data':{'id':id},
		'success': function(data){
			$.fn.yiiGridView.update(\"menu-grid\", {
					data: $(this).serialize()
			});
			ini.parent().parent().parent().remove();
		  },
	});
	}
	return false;
});
");
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title"><?php echo Yii::t('global','Manage');?> Menu</h4>
		
	</div>
	<div class="panel-body">
		<ul id="tabs_a" class="nav nav-tabs">
			<li class="active">
				<a href="#list-view" data-toggle="tab"><span class="fa fa-list"></span> List View</a>
			</li>
			<li class="">
				<a href="#grid-view" data-toggle="tab"><span class="fa fa-th-large"></span> Grid View</a>
			</li>
		</ul>
		<div id="tabs_content_a" class="tab-content">
			<?php $this->widget(
					'ext.nestable.Nestable',
					array(
						'element'=>'#nestable',
						'options'=>array('rootClass'=>'dd')
					)
			);?>
			<div id="list-view" class="tab-pane fade in active">
				<?php foreach(MenuGroup::model()->findAll() as $menu_group):?>
				<div class="dd" id="nestable">
					<div class="heading_a mt10 mb10"><b><?php echo $menu_group->nama_group;?></b></div>
					<?php echo Menu::getNestableMenu($menu_group->key); ?>
				</div>
				<?php endforeach;?>
			</div>
			<div id="grid-view" class="tab-pane fade">
				<p><?php echo Yii::t('global','You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.');?></p>

				<?php echo CHtml::link(Yii::t('global','Advanced Search'),'#',array('class'=>'search-button')); ?>
				<div class="search-form" style="display:none">
				<?php $this->renderPartial('_search',array(
					'model'=>$model,
				)); ?>
				</div><!-- search-form -->
				<div class="table-responsive">
				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'id'=>'menu-grid',
					'dataProvider'=>$model->search(),
					'filter'=>$model,
					'itemsCssClass'=>'table table-striped mb30',
					'afterAjaxUpdate' => 'reloadGrid',
					'columns'=>array(
						array(
							'value'=>'$this->grid->dataProvider->getPagination()->getOffset()+$row+1',
						),
						array(
							'name'=>'content_rel.nama_menu',
							'value'=>'$data->content_rel->getIonTitle(0,false)',
							'type'=>'raw',
						),
						array(
							'name'=>'urutan',
							'value'=>'CHtml::textField(\'urutan\',$data->urutan,array(\'id\'=>$data->id,\'class\'=>\'urutan\'))',
							'type'=>'raw',
							'htmlOptions'=>array('width'=>'5%'),
						),
						/*array(
							'name'=>'parent_id',
							'value'=>'$data->parentid->content_rel->nama_menu',
							'filter'=>Menu::listData(),
						),*/
						array(
							'name'=>'content_rel.link_action',
							'value'=>'$data->content_rel->link_action',
							'type'=>'raw',
						),
						array(
							'name'=>'group_id',
							'value'=>'$data->group->nama_group',
							'filter'=>MenuGroup::listData(''),
						),
						array(
							'name'=>'notaktif',
							'value'=>'Lookup::item(\'MenuStatus\',$data->notaktif)',
							'htmlOptions'=>array('style'=>'text-align:center;'),
							'filter'=>Lookup::items('MenuStatus'),
						),
						array(
							'class'=>'CButtonColumn',
							'template'=>'{update}{delete}',
							'buttons'=>array(
								'update'=>array(
									'imageUrl'=>false,
									'label'=>'<span class="glyphicon glyphicon-pencil"></span>',
									'options'=>array('title'=>'Update'),
									'visible'=>'Rbac::ruleAccess(\'update_p\')',
								),
								'delete'=>array(
									'imageUrl'=>false,
									'label'=>'<span class="glyphicon glyphicon-trash"></span>',
									'options'=>array('title'=>'Delete'),
									'visible'=>'Rbac::ruleAccess(\'delete_p\')',
								),	
							),
							'visible'=>Rbac::ruleAccess('update_p'),
							'htmlOptions'=>array('style'=>'width:10%;','class'=>'table-action'),
						),
					),
				)); ?>
				</div>
			</div> <!-- #list-view -->
		</div> <!-- .tab-content -->
	</div>
</div>
