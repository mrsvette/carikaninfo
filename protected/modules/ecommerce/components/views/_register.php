<?php if($this->position=='main'):?>
<?php $this->controller->renderPartial('/site/widget/_register',array('model'=>$model,'model2'=>$model2));?>
<?php else:?>
<?php $this->controller->renderPartial('/site/widget/_register_widget',array('model'=>$model,'model2'=>$model2));?>
<?php endif;?>
