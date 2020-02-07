var s7market = {};

s7market.updateBasket = function() {
	var request = BX.ajax.runAction('studio7spb:marketplace.api.tools.getBasketInfo');
	request.then(function(response){
		var count = 0;
		if(response.status == 'success') {

			var countBasket = Object.keys(response.data.basket).length,
				countFavorite = Object.keys(response.data.delay).length;

			$('.s7sbp--marketplace--header--search-line--buttons--basket--counter').text(countBasket);
			$('.s7sbp--marketplace--full--basket--counter').text(response.data.sum);
			$('.s7sbp--marketplace--header--search-line--buttons--favorite--counter').text(countFavorite);

			if(countBasket > 0) {
				$('.s7sbp--marketplace--header--search-line--buttons--basket--counter').addClass('active');
			}
			if(countFavorite > 0) {
				$('.s7sbp--marketplace--header--search-line--buttons--favorite--counter').addClass('active');
			}

		}
	});
}
s7market.setHeightCatalogItems = function() {
	var maxheight = 0;
	$('.product-item-container.card').removeAttr('style');
	$('.product-item-container.card').each(function(index, el) {
		if(maxheight < $(this).height()) {
			maxheight = $(this).height();
		}
	});
	$('.product-item-container.card').height(maxheight);
}


$(function() {

	var config = {};

	if($('.s7sbp--marketplace--index--catalog--slider--wrapper').length) {
		config = {"controlNav": true, "animationLoop": true, "pauseOnHover" : true};
		$(".s7sbp--marketplace--index--catalog--slider--wrapper .flexslider").flexslider(config);
	}

	$(".brands_slider_wrapp.flexslider").each(function(){
		var slider = $(this);
		var options;
		var defaults = {
			animationLoop: false,
			controlNav: false,
			directionNav: true,
			animation: "slide"
		}
		var config = $.extend({}, defaults, options, slider.data('plugin-options'));

		if(typeof(config.counts) != 'undefined' && config.direction !== 'vertical'){
			config.maxItems =  getGridSize(config.counts);
			config.minItems = getGridSize(config.counts);
			config.move = getGridSize(config.counts);
			config.itemWidth = 200;
		}

		config.after = function(slider){
			var eventdata = {slider: slider};
			BX.onCustomEvent('onSlide', [eventdata]);
		}
		config.start = function(slider){
			var eventdata = {slider: slider};
			BX.onCustomEvent('onSlideInit', [eventdata]);
		}

		config.end = function(slider){
			var eventdata = {slider: slider};
			BX.onCustomEvent('onSlideEnd', [eventdata]);
		}

		slider.flexslider(config).addClass('flexslider-init');
		if(config.controlNav)
			slider.addClass('flexslider-control-nav');
		if(config.directionNav)
			slider.addClass('flexslider-direction-nav');

	});


	s7market.updateBasket();

	BX.addCustomEvent('OnBasketChange', BX.delegate(function () {
		s7market.updateBasket();
	}));


	if($('.s7sbp--marketplace--lk--menu--wrapp').length) {
		$('.s7sbp--marketplace--lk--menu--wrapp .s7sbp--marketplace--lk--menu--text').on('click', function() {
			$(this).parent().find('.s7sbp--marketplace--lk--menu').fadeToggle();
		});
	}
	$('.share_wrapp .text').on('click', function(){
		$(this).parent().find('.shares').fadeToggle();
	})
	$('html, body').on('mousedown', function(e) {
		e.stopPropagation();
		$('.shares').fadeOut();
		$('.s7sbp--marketplace--lk--menu').fadeOut();
		$('.search_middle_block').removeClass('active_wide');
	});
	$('.share_wrapp, .s7sbp--marketplace--lk--menu--wrapp').find('*').on('mousedown', function(e) {
		e.stopPropagation();
	});



	if($('.s7sbp--marketplace--catalog-section--items').length > 0) {
		$(window).on('resize', function(){
			s7market.setHeightCatalogItems();
		});
		s7market.setHeightCatalogItems();
	}
	$('body').on('click', '.hamburger--squeeze', function(e) {
		e.preventDefault();
		var mobileMenuContainer = $('.s7sbp--marketplace--index--catalog--menu--mobile');
		if($(this).hasClass('active')) {
			// hide menu
			$(this).removeClass('active');
			mobileMenuContainer.fadeOut();
		} else {
			// show menu
			mobileMenuContainer.fadeIn();
			$(this).addClass('active');
		}
	});

	if($(document).width() > 980){

		$(document).on('click', function (e) {
			if ($(e.target).closest(".s7sbp--marketplace--index--catalog--menu--mobile").length === 0) {
				var mobileMenuContainer = $('.s7sbp--marketplace--index--catalog--menu--mobile');
				mobileMenuContainer.hide();
			}
		});

		$('body').on('mouseenter', '.hamburger--squeeze', function(e) {
			e.preventDefault();
			var mobileMenuContainer = $('.s7sbp--marketplace--index--catalog--menu--mobile');
			// show menu
			mobileMenuContainer.fadeIn();
			$(this).addClass('active');
		});
	}

	$('.s7sbp--marketplace--index--catalog--menu--mobile .has-child>a .toggle_block, .s7sbp--marketplace--index--catalog--menu--mobile .has-childs>a .toggle_block').on('click', function(e) {
		e.preventDefault();

		var dropdownMenu = $(this).closest('li').children('.dropdown');
		if(dropdownMenu.hasClass('active')) {
			$(this).removeClass('active');
			$(this).parent().parent().removeClass('active');
			dropdownMenu.fadeOut('400', function() {
				dropdownMenu.removeClass('active');
			});
		} else {
			$(this).addClass('active');
			$(this).parent().parent().addClass('active');
			dropdownMenu.fadeIn('400', function() {
				dropdownMenu.addClass('active');
			});
		}
	});

	$('.s7sbp--marketplace--index--catalog--menu--mobile .has-child>a, .s7sbp--marketplace--index--catalog--menu--mobile .has-childs>a').mouseenter(function () {

		if($(document).width() > 1150){
			var li = $(this).parent(),
				dropdownMenu = $(this).next(),
				all = li.parent();

			if(dropdownMenu.hasClass('active') == false) {
				all.find(".dropdown").hide();
				dropdownMenu.show();
				dropdownMenu.addClass('active');
			}
			all.find('.dropdown').removeClass('active');
		}

	});

	$('body').on('click', '.ajax_load_btn', function(){
		var url = $(this).closest('.module-pagination-wrapper').find('.module-pagination .flex-direction-nav .flex-next').attr('href'),
			th = $(this).find('.more_text_ajax');
		th.addClass('loading');

		$.ajax({
			url: url,
			data: {"ajax_get": "Y"},
			success: function(html){
				var new_html = $.parseHTML(html);
				th.removeClass('loading');
				if($('.s7sbp--marketplace--catalog-section--items').length){
					$('.s7sbp--marketplace--catalog-section--items').append(html);
				}
				$('.module-pagination-wrapper').html($(html).filter('.module-pagination-wrapper').html());
			}
		})
	})


	$('.fancy').fancybox({
		openEffect  : 'fade',
		closeEffect : 'fade',
		nextEffect : 'fade',
		prevEffect : 'fade',
		tpl:{
			closeBtn : '<a title="'+BX.message('FANCY_CLOSE')+'" class="fancybox-item fancybox-close" href="javascript:;"></a>',
			next     : '<a title="'+BX.message('FANCY_NEXT')+'" class="fancybox-nav fancybox-next" href="javascript:;"><span></span></a>',
			prev     : '<a title="'+BX.message('FANCY_PREV')+'" class="fancybox-nav fancybox-prev" href="javascript:;"><span></span></a>'
		},
	});

	/**
	 * fade in #scroll-to-top
	 */
	$(window).scroll(function () {
		if ($(this).scrollTop() > 100) {
			$('#scroll-to-top').fadeIn();
		} else {
			$('#scroll-to-top').fadeOut();
		}
	});
	$('#scroll-to-top').click(function () {
		$('body,html').animate({
			scrollTop: 0
		}, 800);
		return false;
	});


});


getGridSize = function(counts) {
	var counts_item=1;
	//wide
	if(window.matchMedia('(min-width: 1200px)').matches){
		counts_item=counts[0];
	}

	//large
	if(window.matchMedia('(max-width: 1200px)').matches){
		counts_item=counts[1];
	}

	//middle
	if(window.matchMedia('(max-width: 992px)').matches){
		counts_item=counts[2];
	}

	//small
	if(counts[3]){
		if(window.matchMedia('(max-width: 600px)').matches){
			counts_item=counts[3];
		}
	}

	//exsmall
	if(counts[4]){
		if(window.matchMedia('(max-width: 400px)').matches){
			counts_item=counts[4];
		}
	}
	return counts_item;
}

wechat = {

	obPopupWin: null,

	initPopupWindow: function()
	{
		if (this.obPopupWin)
			return;

		this.obPopupWin = BX.PopupWindowManager.create('wechat_header', null, {
			autoHide: true,
			offsetLeft: 0,
			offsetTop: 0,
			overlay : true,
			closeByEsc: true,
			titleBar: true,
			closeIcon: true,
			contentColor: 'white',
			className: ""
		});
	},

	toggle: function (t) {

		if($(document).width() > 960){
			var popupContent = $(t).data("img");

			this.initPopupWindow();
			popupContent = '<div style="width: 291px; height: 291px;"><img src="'
				+ popupContent
				+ '" /></div>';

			this.obPopupWin.setTitleBar("WeChat ID: wxid_unzq1khzmp3b12");
			this.obPopupWin.setContent(popupContent);
			this.obPopupWin.show();
		}
		else{
			location.href = "https://u.wechat.com/ICtFqZOM8bu5oTVvfrdILqQ";
		}

	}
};