<?php if($model->type == 'revolution'): ?>
	<?php if($model->items_count>0): ?>
	<div class="slider">
		<div class="fullwidthbanner-container">
			<div class="fullwidthbanner2">
				<ul>
					<?php foreach(ModSlideShowItem::getItemsProvider($model->id)->data as $item): ?>
					<li data-transition="random" data-slotamount="7" data-masterspeed="1000">
						<?php echo CHtml::image(Yii::app()->request->baseUrl.'/'.$item->image_path,$item->title,array('alt'=>$item->title));?>
						<!--<div class="tp-caption large_black sfr" data-x="140" data-y="100" data-speed="1100" data-start="1500" data-easing="easeInOutBack" style="font-size: 18px; font-weight: 400; text-transform: normal; color: #ffaa31;font-family: Playfair Display;font-style:italic">
							Sale up to 50% off
						</div>
						<div class="tp-caption large_black sfr" data-x="170" data-y="120" data-speed="1100" data-start="1500" data-easing="easeInOutBack" style="font-size: 18px; font-weight: bold; text-transform: uppercase; color: #FFF;font-family: Montserrat;">
							<img src="{{ App.request.baseUrl~'/css/'~App.theme.name~'/img/slider/slider-border.jpg' }}" alt="">
						</div>
						<div class="tp-caption large_black sfr" data-x="70" data-y="140" data-speed="1100" data-start="2000" data-easing="easeInOutBack" style="font-family: Open Sans; font-size: 54px; font-weight: 700;text-transform: uppercase; color: #fff;text-align:center;line-height:60px">
							handBags 
							<br>
							For men
						</div>
						<div class="tp-caption large_black sfr" data-x="30" data-y="300" data-speed="1100" data-start="2700" data-easing="easeInOutBack" style="font-family: Open Sans; font-size: 14px; font-weight: 300; color: #FFF;line-height:25px;text-transform: normal;text-align:center">
							Fusce ac consectetur neque, nec pharetra dolor. Aenean metus 
							<br>
							mauris, facilisis vel leo non, ornare pretium eros.
						</div>
						<div class="tp-caption lfb carousel-caption-inner" data-x="150" data-y="370" data-speed="1300" data-start="3000" data-easing="easeInOutBack" style="font-family: Montserrat; font-size: 12px; font-weight: bold; text-transform: uppercase; color: #F3F3F3;">
							<a href="#" class="s-btn" style="background: transparent none repeat scroll 0% 0%; color: rgb(255, 255, 255); display: block; padding: 12px 28px; border: 2px solid rgb(255, 255, 255);">shop now</a>
						</div>-->
						<?php echo $item->caption;?>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
	<!-- RS Lib -->
	<link href="<?php echo $this->getAssetsUrl().'/revolution/css/settings.css';?>" rel="stylesheet" />
	<script src="<?php echo $this->getAssetsUrl().'/revolution/js/jquery.themepunch.plugins.min.js';?>"></script>
	<script src="<?php echo $this->getAssetsUrl().'/revolution/js/jquery.themepunch.revolution.min.js';?>"></script>
	<script src="<?php echo $this->getAssetsUrl().'/revolution/main.js';?>"></script>
	<?php endif;?>
<?php endif; ?>
