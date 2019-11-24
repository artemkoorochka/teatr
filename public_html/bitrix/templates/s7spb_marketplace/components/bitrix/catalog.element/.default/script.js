$(function() {

	var inputQuantity = $('.s7sbp--marketplace--catalog-element-detail-product input[name="quantity"]');

	$('body').on('click', '.s7sbp--marketplace--catalog-element-detail-product .product-item-amount-field-btn-minus, .s7sbp--marketplace--catalog-element-detail-product .product-item-amount-field-btn-plus', function(e) {
		e.preventDefault();

		var inputQuantityValue = parseInt(inputQuantity.val());
		var newInputValue = inputQuantityValue;
		if($(this).hasClass('product-item-amount-field-btn-minus')) {
			if(inputQuantityValue <= 1) {
				$(this).addClass('product-item-amount-field-btn-disabled');
			} else {
				newInputValue -= 1;
				if(newInputValue == 1) {
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
			itemId = _this.attr('data-item-id');

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