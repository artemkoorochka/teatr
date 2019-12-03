$(function() {

	$('.s7sbp--marketplace--catalog-element-detail-product--slides .wrapp_thumbs').find('.thumbs').flexslider({
		animation: "slide",
		selector: ".slides_block > li",
		slideshow: false,
		animationSpeed: 600,
		directionNav: true,
		controlNav: false,
		pauseOnHover: true,
		itemWidth: 54,
		itemMargin: 10,
		animationLoop: true,
		controlsContainer: ".thumbs_navigation",
	});
	$('.s7sbp--marketplace--catalog-element-detail-product--slides.flex.flexslider').flexslider({
		animation: "slide",
		selector: ".slides > li",
		slideshow: false,
		slideshowSpeed: 10000,
		animationSpeed: 600,
		directionNav: false,
		pauseOnHover: true,
		animationLoop: false,
	});

	var inputQuantity = $('.s7sbp--marketplace--catalog-element-detail-product input[name="quantity"]');
	var obPopupWin = null;

	$('body').on('click', '.s7sbp--marketplace--catalog-element-detail-product .product-item-amount-field-btn-minus, .s7sbp--marketplace--catalog-element-detail-product .product-item-amount-field-btn-plus', function(e) {
		e.preventDefault();

		var inputQuantityValue = parseInt(inputQuantity.val()),
			inputQuantityMoq = inputQuantity.data("moq");
			newInputValue = inputQuantityValue;

		if($(this).hasClass('product-item-amount-field-btn-minus')) {
			if(inputQuantityValue <= inputQuantityMoq) {
				$(this).addClass('product-item-amount-field-btn-disabled');
			} else {
				newInputValue -= 1;
				if(newInputValue <= inputQuantityMoq) {
					$(this).addClass('product-item-amount-field-btn-disabled');
				}
			}
		} else {
			newInputValue += 1;
			if(newInputValue > 1) {
				$('.s7sbp--marketplace--catalog-element-detail-product .product-item-amount-field-btn-minus').removeClass('product-item-amount-field-btn-disabled');
			}
		}
		inputQuantity.val(newInputValue).trigger('change');

		// calculate properties
		$("#calculator-Master_CTN_PCS").text(parseInt($("#property-Master_CTN_PCS").text()) * newInputValue);
		$("#calculator-Master_CTN_CBM").text((parseFloat($("#property-Master_CTN_CBM").text()) * newInputValue).toFixed(3));
		// calculate prices
		$("#price-FOB").text((parseFloat($("#price-FOB").data("price")) * newInputValue).toFixed(2) + " " + $("#price-FOB").data("currency"));
		$("#price-normal_price").text((parseFloat($("#price-normal_price").data("price")) * newInputValue).toFixed(2)  + " " + $("#price-normal_price").data("currency"));
		$("#price-quickly_price").text((parseFloat($("#price-quickly_price").data("price")) * newInputValue).toFixed(2)  + " " + $("#price-quickly_price").data("currency"));

	})
	.on('click', '[data-action="showTab"]', function(e) {
		e.preventDefault();
		var tabName = $(this).attr('data-tabname'),
			containerToMove = $('.s7sbp--marketplace--catalog-element-detail-product--tabs--header--item[data-tabname="'+tabName+'"]');

		$('html, body').animate({
			scrollTop: containerToMove.offset().top
		}, 300);
		containerToMove.trigger('click');
	})
	.on('click', '.s7sbp--marketplace--catalog-element-detail-product--tabs--header--item', function(e) {
		e.preventDefault();
		if($(this).hasClass('active')) return false;
		var tabName = $(this).attr('data-tabname');
		$('.s7sbp--marketplace--catalog-element-detail-product--tabs--header--item').removeClass('active');
		$(this).addClass('active');
		$('.s7sbp--marketplace--catalog-element-detail-product--tabs--body--item').removeClass('active');
		$('.s7sbp--marketplace--catalog-element-detail-product--tabs--body--item[data-tabname="'+tabName+'"]').addClass('active');
	}).on('click', '.s7sbp--marketplace--catalog-element-detail-product--controls--add-to-basket .btn', function(e) {
		e.preventDefault();
		var _this = $(this),
			quantity = inputQuantity.val(),
			inputQuantityMoq = inputQuantity.data("moq");
			itemId = _this.attr('data-item-id');

			if(inputQuantityMoq > quantity){
				if(obPopupWin){
					return;
				}

				obPopupWin = BX.PopupWindowManager.create('CatalogElemetnPopup', null, {
					autoHide: true,
					offsetLeft: 0,
					offsetTop: 0,
					overlay : true,
					closeByEsc: true,
					titleBar: true,
					closeIcon: true,
					contentColor: 'white',
					className: ''
				});
				obPopupWin.setTitleBar(inputQuantity.data("title"));
				obPopupWin.setContent('<div style="width: 100%; margin: 0; text-align: center;"><p>'
					+ inputQuantity.data("hint")
					+ '</p></div>');
				obPopupWin.show();

			}else{
				_this.prop("disabled", true);

				var request = BX.ajax.runAction('studio7spb:marketplace.api.tools.addToBasket', {
					data: {
						id: itemId,
						quantity: quantity
					}
				});
				request.then(function(response){
					if(response.status == 'success') {
						_this
							.addClass('in-basket')
							.text(BX.message('PRODUCT_ADD_TO_BASKET_IN_BASKET'));
						s7market.updateBasket();
					}

				});
			}

	}).on('click', '.s7sbp--marketplace--catalog-element-detail-product--header-line--item--wish-list', function(e) {
		e.preventDefault();
		var _this = $(this),
			itemId = _this.attr('data-item-id');

		//_this.prop("disabled", true);

		var request = BX.ajax.runAction('studio7spb:marketplace.api.tools.addToWish', {
			data: {
				id: itemId,
			}
		});

		request.then(function(response){
			if(response.status == 'success') {
				// toggle text and class
				if(_this.hasClass("active")){
					_this.text(_this.attr('data-title'));
					_this.removeClass('active');
				}else{
					_this.text(_this.attr('data-title-in'));
					_this.addClass('active');
				}

				s7market.updateBasket();
			}
		});
	}).on('click', '.reviews-collapse-link', function(){
		$('.reviews-reply-form').slideToggle();
	});
});