<?php $this->beginContent('application.modules.appadmin.views.layouts.main'); ?>
	<div class="page_bar clearfix">
		<div class="row">
			<div class="col-md-8">
				<h1 class="page_title"><?php echo strtoupper(Yii::app()->config->get('site_name'));?></h1>
			</div>
			<?php if(is_array($this->menu)):?>
			<div class="col-md-4 text-right">
				<?php
					$this->widget('zii.widgets.CMenu', array(
						'items'=>$this->menu,
						'htmlOptions'=>array('class'=>'nav navbar-nav navbar-right'),
						'id'=>'manage-menu',
					));
				?>
				<?php /*<div class="btn-group btn-group-sm">
					<?php foreach($this->menu as $i=>$m):?>
					<a class="btn btn-default" href="<?php echo Yii::app()->createUrl('/'.Yii::app()->controller->module->id.'/'.Yii::app()->controller->id.'/'.$m['url'][0]);?>">
						<?php echo $m['label'];?>
					</a>
					<?php endforeach;?>
				</div>*/?>
			</div>
			<?php endif;?>
		</div>
	</div>
	<div class="page_content">
		<div class="container-fluid">
			<?php echo $content; ?>
		</div>
	</div>	
<?php $this->endContent(); ?>

<script type="text/javascript">
$(function(){
	dataReload();
});
function reloadGrid(id, data) {
	dataReload();
	return false;
}
function dataReload(){
	$('input[type=text]').addClass('form-control');
	$('textarea').addClass('form-control');
	$('select').addClass('form-control');
	$('input[type=password]').addClass('form-control');
	$('input[type=submit]').addClass('btn btn-primary');
	$('input[type=button]').addClass('btn btn-primary');
	$('.yiiPager').addClass('dataTables_paginate paging_full_numbers');
	$('.dataTables_paginate').removeClass('yiiPager');
	$('.panel-btns').addClass('hide');
	$('#manage-menu').find('a').addClass('btn btn-default no-padding');
	return false;
}
</script>
