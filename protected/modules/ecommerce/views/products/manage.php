<?php
$this->breadcrumbs=array(
	ucfirst(Yii::app()->controller->module->id)=>array('/'.Yii::app()->controller->module->id.'/'),
	Yii::t('global','Manage').' '.Yii::t('EcommerceModule.product','Product'),
);

$this->menu=array(
	array('label'=>Yii::t('global','List').' '.Yii::t('EcommerceModule.product','Product'), 'url'=>array('view')),
	array('label'=>Yii::t('global','Create').' '.Yii::t('EcommerceModule.product','Product'), 'url'=>'#new', 'linkOptions'=>array('data-toggle'=>'tab')),
);
?>
<style>
.form-horizontal .form-group{border-top: 1px solid #e7e7e7;clear: both;padding: 20px 16px;position: relative;margin:0;}
#ModProduct_status input, #ModProduct_hidden input, #ModProduct_setup input, #ModProductPayment_type input{float:left;margin-right:10px;}
#ModProduct_status label, #ModProduct_hidden label{width:20%;float:left;}
#ModProduct_setup label{width:30%;float:left;}
#ModProductPayment_type label{width:20%;float:left;}
</style>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">#<?php echo $model->id;?> - <b><?php echo $model->title;?></b></h4>
	</div>
	<div class="panel-body">
		<ul class="nav nav-tabs">
			<li class="active">
				<a data-toggle="tab" href="#general">
					<strong><?php echo Yii::t('EcommerceModule.product','General Setting');?></strong>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#image">
					<strong><?php echo Yii::t('EcommerceModule.product','Product Images');?></strong>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#discount">
					<strong><?php echo Yii::t('EcommerceModule.product','Product Discount');?></strong>
				</a>
			</li>
			<li class="">
				<a data-toggle="tab" href="#links">
					<strong><?php echo Yii::t('EcommerceModule.product','Product Links');?></strong>
				</a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="general" class="tab-pane active">
				<div class="mt20"></div>
				<?php echo $this->renderPartial('_form_manage',array('model'=>$model,'model2'=>$model2));?>
			</div>
			<div id="image" class="tab-pane">
				<?php echo $this->renderPartial('_image',array('model'=>$model,'imageProvider'=>$imageProvider));?>
			</div>
			<div id="links" class="tab-pane">
				<?php echo $this->renderPartial('_link',array('model'=>$model));?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
function hapus(data){
	if(confirm('Are you sure to delete this?')){
		$.ajax({
			'beforeSend': function() { Loading.show(); },
			'complete': function() { Loading.hide(); },
			'url': $(data).attr('href'),
			'type':'post',
		   	'dataType': 'json',
		  	'success': function(data){
				if(data.status=='success'){
					
				}
			},
		});
	}
	return false;
}
/*$(function(){
	$('#ModProductPayment_type').change(function(){
		var type = $(this).find('input[type="radio"]:checked').val();
		if(type=='free'){
			$('#once-price').addClass('hide');
			$('div[id="recurrent-price"]').addClass('hide');
		}else if(type=='once'){
			$('#once-price').removeClass('hide');
			$('div[id="recurrent-price"]').addClass('hide');
		}else if(type=='requrrent'){
			$('#once-price').addClass('hide');
			$('div[id="recurrent-price"]').removeClass('hide');
		}
	});
});*/
</script>
