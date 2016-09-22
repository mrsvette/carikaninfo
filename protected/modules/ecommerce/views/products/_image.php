<div class="row">
	<div class="col-sm-6">
		<?php
			$this->widget('zii.widgets.grid.CGridView', array(
				'dataProvider'=>$imageProvider,
				'itemsCssClass'=>'table table-striped mb30',
				'id'=>'image-grid-view',
				'afterAjaxUpdate' => 'reloadGrid',
				'columns'=>array(
				array(
					'value'=>'$this->grid->dataProvider->getPagination()->getOffset()+$row+1',
				),
				array(
					'name'=>'image',
					'type'=>'raw',
					'value'=>'$data->image'
				),
				array(
					'name'=>'type',
					'type'=>'raw',
					'value'=>'CHtml::dropDownList(\'type\',$data->type,Lookup::items(\'ProductImageType\'),array(\'id\'=>$data->id))'
				),
				array(
					'class'=>'CButtonColumn',
					'template'=>'{delete}',
					'buttons'=>array(
						'delete'=>array(
							'imageUrl'=>false,
							'label'=>'<span class="glyphicon glyphicon-trash"></span>',
							'options'=>array('title'=>'Delete'),
							'url'=>'Yii::app()->createUrl("/ecommerce/products/deleteImage2", array("id"=>$data->id))',
							//'visible'=>'Rbac::ruleAccess(\'delete_p\')',
						),	
					),
					//'visible'=>Rbac::ruleAccess('update_p'),
					'htmlOptions'=>array('style'=>'width:10%;','class'=>'table-action'),
				),
			),
			));
		?>
		<div class="col-sm-12">
		<?php
			$this->widget('ext.EAjaxUpload.EAjaxUpload',
				array(
					'id'=>'uploadFile',
					'config'=>array(
						'action'=>Yii::app()->createUrl('/ecommerce/products/createImage',array('id'=>$model->id)),
						'allowedExtensions'=>array("jpg","jpeg","gif","png"),
						'sizeLimit'=>Yii::app()->params['sizeLimit'],// maximum file size in bytes
						'minSizeLimit'=>1,// minimum file size in bytes
						'onComplete'=>"js:function(id, fileName, responseJSON){ 
							if(responseJSON.success){
								$.fn.yiiListView.update('image-list-view', {
									data: $(this).serialize()
								});
								$.fn.yiiGridView.update('image-grid-view', {
									data: $(this).serialize()
								});
							}
						}",
						'messages'=>array(
							'typeError'=>"{file} has invalid extension. Only {extensions} are allowed.",
							'sizeError'=>"{file} is too large, maximum file size is {sizeLimit}.",
							'minSizeError'=>"{file} is too small, minimum file size is {minSizeLimit}.",
							'emptyError'=>"{file} is empty, please select files again without it.",
							'onLeave'=>"The files are being uploaded, if you leave now the upload will be cancelled."
						),
						'showMessage'=>"js:function(message){ alert(message); }",
					)
			));
		?>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="row filemanager">
			<?php
			$this->widget('zii.widgets.CListView', array(
				'dataProvider'=>$imageProvider,
				'itemView'=>'__image',
				'id'=>'image-list-view',
			));
			?>
		</div>
	</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('.thmb').hover(function(){
      var t = jQuery(this);
      t.find('.ckbox').show();
      t.find('.fm-group').show();
    }, function() {
      var t = jQuery(this);
      if(!t.closest('.thmb').hasClass('checked')) {
        t.find('.ckbox').hide();
        t.find('.fm-group').hide();
      }
    });
    
    jQuery('.ckbox').each(function(){
      var t = jQuery(this);
      var parent = t.parent();
      if(t.find('input').is(':checked')) {
        t.show();
        parent.find('.fm-group').show();
        parent.addClass('checked');
      }
    });
    
    
    jQuery('.ckbox').click(function(){
      var t = jQuery(this);
      if(!t.find('input').is(':checked')) {
        t.closest('.thmb').removeClass('checked');
        enable_itemopt(false);
      } else {
        t.closest('.thmb').addClass('checked');
        enable_itemopt(true);
      }
    });
    
    jQuery('#selectall').click(function(){
      if(jQuery(this).is(':checked')) {
        jQuery('.thmb').each(function(){
          jQuery(this).find('input').attr('checked',true);
          jQuery(this).addClass('checked');
          jQuery(this).find('.ckbox, .fm-group').show();
        });
        enable_itemopt(true);
      } else {
        jQuery('.thmb').each(function(){
          jQuery(this).find('input').attr('checked',false);
          jQuery(this).removeClass('checked');
          jQuery(this).find('.ckbox, .fm-group').hide();
        });
        enable_itemopt(false);
      }
    });
    
    function enable_itemopt(enable) {
      if(enable) {
        jQuery('.itemopt').removeClass('disabled');
      } else {
        
        // check all thumbs if no remaining checks
        // before we can disabled the options
        var ch = false;
        jQuery('.thmb').each(function(){
          if(jQuery(this).hasClass('checked'))
            ch = true;
        });
        
        if(!ch)
          jQuery('.itemopt').addClass('disabled');
      }
    }
    
    //Replaces data-rel attribute to rel.
    //We use data-rel because of w3c validation issue
    /*jQuery('a[data-rel]').each(function() {
      jQuery(this).attr('rel', jQuery(this).data('rel'));
    });
    
    jQuery("a[rel^='prettyPhoto']").prettyPhoto();*/
    
});
function deleteImage(id){
	if(confirm('Anda yakin ingin mengapus item ini?')){
		$.ajax({
			'beforeSend': function() { Loading.show(); },
			'complete': function() { Loading.hide(); },
			'url': '<?php echo Yii::app()->createUrl('/'.Yii::app()->controller->module->id.'/products/deleteImage');?>',
			'dataType': 'json',
			'type':'post',
			'data':{'id':id},
			'success': function(data) {
				if(data.status=='success'){
					$.fn.yiiListView.update('image-list-view', {
						data: $(this).serialize()
					});
					$.fn.yiiGridView.update('image-grid-view', {
						data: $(this).serialize()
					});
				}
			}
		});
	}
	return false;
}
$('#grid-view').find('a.delete').click(function(){
	if(confirm('Anda yakin ingin mengapus item ini?')){
		$.ajax({
			'beforeSend': function() { Loading.show(); },
			'complete': function() { Loading.hide(); },
			'url': $(this).attr('href'),
			'dataType': 'json',
			'type':'post',
			'success': function(data) {
				if(data.status=='success'){
					$.fn.yiiGridView.update('image-grid-view', {
						data: $(this).serialize()
					});
					$.fn.yiiListView.update('image-list-view', {
						data: $(this).serialize()
					});
				}
			}
		});
	}
	return false;
});
$('#image-grid-view').find('select[name="type"]').change(function(){
	var $this=$(this);
	$.ajax({
		'beforeSend': function() { Loading.show(); },
		'complete': function() { Loading.hide(); },
		'url': '<?php echo Yii::app()->createUrl('/'.Yii::app()->controller->module->id.'/products/updateImageType');?>',
		'dataType': 'json',
		'type':'post',
		'data':{'id':$this.attr('id'),'value':$this.val()},
		'success': function(data) {
			if(data.status=='success'){
				$.fn.yiiGridView.update('image-grid-view', {
					data: $(this).serialize()
				});
			}
		}
	});
	return false;
});
</script>
