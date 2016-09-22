<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- bootstrap framework -->
	<link href="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/tisa/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<!-- custom icons -->
	<!-- font awesome icons -->
	<link href="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/tisa/icons/font-awesome/css/font-awesome.min.css" rel="stylesheet" media="screen">
	<!-- ionicons -->	
	<link href="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/tisa/icons/ionicons/css/ionicons.min.css" rel="stylesheet" media="screen">
	<!-- nvd3 charts -->
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/tisa/lib/novus-nvd3/nv.d3.min.css">
	<!-- owl carousel -->
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/tisa/lib/owl-carousel/owl.carousel.css">
				
	<!-- main stylesheet -->
	<link href="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/tisa/css/style.css" rel="stylesheet" media="screen">

	<link rel="shortcut icon" type="image/x-icon" href="<?php echo Yii::app()->request->baseUrl.'/uploads/images/'.Yii::app()->config->get('favicon'); ?>">
	
	<!-- jQuery -->
	<script src="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/tisa/js/jquery.min.js"></script>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
	<!-- top bar -->
	<?php if(!Yii::app()->user->isGuest):?>
	<header class="navbar navbar-fixed-top" role="banner">
		<div class="container-fluid">
			<div class="navbar-header pull-right">
				<?php echo CHtml::link(CHtml::image(''),array('/appadmin/default'),array('class'=>'navbar-brand'));?>
			</div>
			<ul class="top_links">
				<?php $this->widget('application.modules.ecommerce.components.ECounterWidget'); ?>
			</ul>
			<ul class="nav navbar-nav navbar-left">
					<li><a href="javascript:void(0)" class="slidebar-toggle"><span class="navbar_el_icon ion-navicon-round"></span></a></li>
					<li class="user_menu">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<span class="navbar_el_icon ion-person"></span> <span class="caret"></span>
						</a>
						<?php
							$this->widget('zii.widgets.CMenu', array(
								'items'=>array(
									array('label'=>Yii::t('menu','Change Password'), 'url'=>array('/appadmin/profile/changePassword')),
									array('label'=>'Logout', 'url'=>array('/appadmin/default/logout')),
								),
								'htmlOptions'=>array('class'=>'dropdown-menu dropdown-menu-left'),
								'encodeLabel'=>false,
							));
						?>
					</li>
			</ul>
		</div>
	</header>
	<?php endif;?>
	<section>
		<!-- main content -->
		<div id="main_wrapper">
		<?php echo $content; ?>	
		</div>
		<!-- side navigation -->
		<?php if(!Yii::app()->user->isGuest):?>
		<nav id="side_nav">
			<?php $this->widget('adminMainMenu');?>
		</nav>
		<?php endif;?>
	</section>
	<!-- easing -->
	<script src="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/tisa/js/jquery.easing.1.3.min.js"></script>
	<!-- bootstrap js plugins -->
	<script src="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/tisa/bootstrap/js/bootstrap.min.js"></script>
	<!-- top dropdown navigation -->
	<script src="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/tisa/js/tinynav.js"></script>
	<!-- perfect scrollbar -->
	<script src="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/tisa/lib/perfect-scrollbar/min/perfect-scrollbar-0.4.8.with-mousewheel.min.js"></script>
		
	<!-- common functions -->
	<script src="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/tisa/js/tisa_common.js"></script>
		
	<!-- page specific plugins -->

	<!-- nvd3 charts -->
	<script src="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/tisa/lib/d3/d3.min.js"></script>
	<script src="<?php echo Yii::app()->request->baseUrl.'/css'; ?>/tisa/lib/novus-nvd3/nv.d3.min.js"></script>
<?php $this->widget('ext.widgets.loading.LoadingWidget');?>
</body>
