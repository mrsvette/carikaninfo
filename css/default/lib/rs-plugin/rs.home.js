(function() {

	"use strict";


			// Revolution Slider Initialize
			if($(".fullwidthbanner2").get(0)) {
				var rev = $(".fullwidthbanner2").revolution({
					delay:9000,
					startheight:492,
					startwidth:850,

					hideThumbs:10,

					thumbWidth:100,
					thumbHeight:50,
					thumbAmount:5,

					navigationType:"verticalcentered",
					navigationArrows:"verticalcentered",

					touchenabled:"on",
					onHoverStop:"on",

					navOffsetHorizontal:0,
					navOffsetVertical:20,

					stopAtSlide:-1,
					stopAfterLoops:-1,

					shadow:0,
					fullWidth:"on"
				});

				$("#revolutionSlider .tp-caption").on("mousedown", function(e) {
					e.preventDefault();
					rev.revpause();
					return false;
				});

			}
			
			// Revolution Slider Initialize
			if($(".fullwidthbanner3").get(0)) {
				var rev = $(".fullwidthbanner3").revolution({
					delay:9000,
					startwidth:1350,
					startheight:503,
					

					hideThumbs:10,

					thumbWidth:100,
					thumbHeight:50,
					thumbAmount:5,

					navigationType:"verticalcentered",
					navigationArrows:"verticalcentered",

					touchenabled:"on",
					onHoverStop:"on",

					navOffsetHorizontal:0,
					navOffsetVertical:20,

					stopAtSlide:-1,
					stopAfterLoops:-1,

					shadow:0,
					fullWidth:"on"
				});

				$("#revolutionSlider .tp-caption").on("mousedown", function(e) {
					e.preventDefault();
					rev.revpause();
					return false;
				});

			}

})();