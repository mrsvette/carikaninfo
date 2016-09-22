<?php
$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>
<div class="row">
	<div class="col-sm-12 col-md-10 col-md-offset-1">
		<div class="panel panel-default mt90">
			<div class="panel-heading"><h1 class="error_heading">Error <?php echo $code; ?></h1></div>
            <div class="panel-body">
              	<div class="row">
                	<div class="col-sm-12">
						<h2 class="error_subheading"><?php echo CHtml::encode($message); ?></h2>
						<p><a href="javascript: history.go(-1)">Go Back</a></p>
                	</div><!-- col-sm-12 -->
              	</div><!-- row -->
            </div><!-- panel-body -->
        </div><!-- panel -->
 	</div><!-- col-sm-12 -->
</div><!-- row -->
