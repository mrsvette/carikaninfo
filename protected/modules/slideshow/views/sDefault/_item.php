<div class="col-sm-6" id="item-form">
	<?php $this->renderPartial('_item_form',array('model'=>$model));?>
</div><!-- col-sm-6 -->
<div class="col-sm-6">
<?php
$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$model->search(),
    'itemView'=>'_image',
	'id'=>'image-list-view',
	'itemsTagName'=>'ol',
));
?>
</div>
<style>
.form-horizontal .form-group{border-top: 1px solid #e7e7e7;clear: both;padding: 20px 16px;position: relative;margin:0;}
</style>
<script type="text/javascript">
function updateItem(data){
	$.ajax({
		beforeSend: function() { Loading.show(); },
		complete: function() { Loading.hide(); },
		url: $(data).attr('href'),
		type: 'POST',
		dataType: 'json',
		success: function (r) {
			if(r.status=="success"){
				$("#item-form").html(r.div);
				setTimeout(function(){
					$('#item-cmessage').parent().addClass('hide');
				},2000);
			}else{
				$("form[id='slideshow-item-form']").parent().parent().html(r.div);
			}
		},
	});
	return false;
}
function deleteItem(data){
	if(confirm('Anda yakin ingin menghapus item ini?')){
		$.ajax({
			beforeSend: function() { Loading.show(); },
			complete: function() { Loading.hide(); },
			url: $(data).attr('href'),
			type: 'POST',
			dataType: 'json',
			success: function (r) {
				if(r.status=="success"){
					$.fn.yiiListView.update('image-list-view');
				}
			},
		});
	}
	return false;
}
</script>
