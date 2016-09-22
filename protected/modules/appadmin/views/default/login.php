<?php
$this->pageTitle=Yii::app()->config->get('site_name') . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
Yii::app()->clientScript->registerScript('recovery', "
$('.request-password').click(function(){
	passrequest();
	$('#jdl').text('Request Password');
	return false;
});
");
?>
<div class="row">
	<div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-12">
		<div class="mt50"></div>
		<div class="panel panel-default">
			<div class="panel-heading"><span class="glyphicon glyphicon-log-in"></span> Login</div>
			<div class="panel-body">
		    <?php if(Yii::app()->user->hasFlash('login')): ?>
			<div class="alert alert-success alert-block">
				<?php 
					header('refresh: 2; url='.Yii::app()->user->returnUrl);
					echo Yii::app()->user->getFlash('login'); 
				?>
			</div>
			<?php endif; ?>	
			<?php $form=$this->beginWidget('CActiveForm', array(
					'id'=>'login-form',
					'enableClientValidation'=>true,
					'clientOptions'=>array(
						'validateOnSubmit'=>true,
					),
				)); ?>
		
			<?php echo $form->errorSummary($model,null,null,array('class'=>'alert alert-warning alert-block alert-dismissable fade in')); ?>
			<div class="form-group">
				<label for="login_username">Username</label>
				<?php echo $form->textField($model,'username',array('class'=>'form-control input-lg')); ?>
			</div>
			<div class="form-group">
				<label for="login_password">Password</label>
				<?php echo $form->passwordField($model,'password',array('class'=>'form-control input-lg')); ?>
				<span class="help-block">
				<?php echo CHtml::link("Bermasalah dengan akun login?",'#',array('class'=>'request-password'));?>
				</span>
			</div>
			<div class="submit_section">
				<?php echo CHtml::submitButton(Yii::t('global','Login'),array('style'=>'min-width:100px;','id'=>'tombol','class'=>'btn btn-info btn-block btn-lg')); ?>
			</div>
		    <?php $this->endWidget(); ?>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
function passrequest()
{	
	$.ajax({
	'beforeSend': function() { Loading.show(); },
	'complete': function() { Loading.hide(); },
      	'url': '<?php echo Yii::app()->createUrl('/'.Yii::app()->controller->module->id.'/password/request');?>',
      	'dataType': 'json',
      	'success': function(data){
			if(data.status=='success'){
				$('#div-for-form').html(data.div);
			}
      		},
    	});
    return false;
 
}
$(function(){
	$('#AdminLoginForm_username').focus();
});
</script>
