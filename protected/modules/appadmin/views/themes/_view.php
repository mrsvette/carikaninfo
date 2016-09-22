<div class="col-sm-4">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<?php echo $data['name'];?>
				<?php if(Yii::app()->config->get('theme')==$data['id']):?>
				<?php echo CHtml::link('Nonaktifkan','javascript:void(0);',array('class'=>'btn btn-default pull-right','onclick'=>'install(this);','id'=>$data['id'],'install'=>0));?>
				<?php else:?>
				<?php echo CHtml::link('Aktifkan','javascript:void(0);',array('class'=>'btn btn-default pull-right','onclick'=>'install(this);','id'=>$data['id'],'install'=>1));?>
				<?php endif;?>
			</h4>
		</div>
		<div class="panel-body">
			<div class="col-sm-12 col-xs-12">
			<?php echo CHtml::image($data['img_path'],$data['name'],array('class'=>'img-responsive'));?>
			</div>
			<div class="col-sm-12 col-xs-12 mt10"><?php echo $data['description'];?></div>
		</div>
	</div>
</div>
<script type="text/javascript">

function install(data){
	var $this = $(data);
	if($this.attr('install')>0)
		var act = 'Aktifkan';
	else
		var act = 'Nonaktifkan';
	if(confirm('Anda yakin ingin meng-'+act+' tema ini?')){
	$.ajax({
		'beforeSend': function() { Loading.show(); },
		'complete': function() { Loading.hide(); },
		'url': "<?php echo Yii::app()->createUrl('/appadmin/themes/install');?>",
		'type':'post',
		'data':{'id':$this.attr('id'),'install':$this.attr('install')},
		'success': function(data){
			$.fn.yiiListView.update("themes-list");
		  },
	});
	}
	return false;
} 
</script>
