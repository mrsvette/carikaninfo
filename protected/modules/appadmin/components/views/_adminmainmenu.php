<?php
	$this->widget('zii.widgets.CMenu', array(
		'items'=>$items,
		//'htmlOptions'=>array('class'=>'nav nav-pills nav-stacked nav-bracket'),
		'encodeLabel'=>false,
		'submenuHtmlOptions'=>array('class'=>'children')
	));
?>
<script type="text/javascript">
$(function(){
	$('.children').each(function(){
		var child = $(this);
		var title = child.parent().find('.nav_title').text();
		child.parent().append('<div class="sub_panel"><div class="side_inner"><h4 class="panel_heading panel_heading_first">'+title+'</h4><ul>'+child.html()+'</ul></div>');
		child.hide();
	});
});
</script>
