<li>
	<a href="<?php echo Yii::app()->createUrl('/ecommerce/clients/view');?>">
		<span><?php echo ModClient::getCount('active');?></span>Client
	</a>
</li>
<li>
	<a href="<?php echo Yii::app()->createUrl('/ecommerce/invoices/view',array('status'=>'unpaid'));?>">
		<span><?php echo ModInvoice::getCount('unpaid');?></span>Invoice
	</a>
</li>
<li>
	<a href="<?php echo Yii::app()->createUrl('/ecommerce/orders/view',array('status'=>'pending_setup'));?>">
		<span><?php echo ModClientOrder::getCountOrder('pending_setup');?></span>Orders
	</a>
</li>
