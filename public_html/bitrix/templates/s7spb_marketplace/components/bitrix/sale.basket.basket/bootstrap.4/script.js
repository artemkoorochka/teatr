;(function() {
    'use strict';

    window.saleBasket = {
        basket: {
            id: "#basket"
        },
        market: {
            class: ".market-item"
        },
        item: {
            class: ".basket-item",
            current: null
        },
        popup: null,
        format: {
            ceil: 0,
            duration: 500
        },

        deleteAll: function (t) {
            this.item.current = t;

            // header title
            saleModal.title.content = $(t).data("title");
            // body
            saleModal.body.content = '<img class="img-thumbnail" src="'+  $(t).data("pic") + '">';
            // footer
            saleModal.footer.content = '<button class="btn btn-danger" onclick="saleBasket.removeAll();">' + $(t).data("delete") + '</button>';
            saleModal.footer.content += '<button class="btn btn-primary" onclick="saleModal.hide();">' + $(t).data("cancel") + '</button>';

            saleModal.show();
        },

        delete: function (t) {
            this.item.current = t;

            // header title
            saleModal.title.content = $(t).data("title");
            saleModal.body.content = $(this.item.current).closest(this.item.class).find(".basket-item-info");
            saleModal.body.content = saleModal.body.content.clone();
            $(saleModal.body.content).find("img")
                .addClass("img-thumbnail")
                .addClass("mb-3")
                .parent()
                .removeAttr("class")
                .addClass("col-12");
            saleModal.body.content = saleModal.body.content.html();
            // footer
            saleModal.footer.content = '<button class="btn btn-danger" onclick="saleBasket.remove();">' + $(t).data("delete") + '</button>';
            saleModal.footer.content += '<button class="btn btn-primary" onclick="saleModal.hide();">' + $(t).data("cancel") + '</button>';

            saleModal.show();
        },

        remove: function() {

            var market = $(this.item.current).closest(this.market.class),
                item = $(this.item.current).closest(this.item.class),
                request = BX.ajax.runAction('studio7spb:marketplace.api.tools.deleteBasket', {
                    data: {
                        id: item.data("product")
                    }
                });

            request.then(function(response){
                s7market.updateBasket();
            });

            item.remove();

            if(market.find(this.item.class).length <= 0){
                market.remove();
            }

            this.item.current = null;
            saleModal.hide();
            this.calculate();
        },

        removeAll: function() {
            var request = BX.ajax.runAction('studio7spb:marketplace.api.tools.deleteBasket');
            request.then(function(response){
                location.reload();
            });
        },

        count: function(t, type){

            var counter = $(t).parent(),
                input = counter.find("input"),
                value = parseInt(input.val()),
                price = counter.data("price"),
                item = $(t).closest(this.item.class),
                itemSum = item.find(".item-sum"),
                itemMoq = item.find(".element-PROPERTY_MOQ").text();

            if(type === true){
                value++;
            }
            if(type === false){
                value--;
            }

            if(itemMoq > value){
                this.dialog({
                    title: $(this.basket.id).data("warning"),
                    desc: $(this.basket.id).data("moq-hint")
                });
                value = itemMoq;
            }
            if(value < 1){
                value = 1;
            }

            // output price
            this.animateInput(input, value);
            price = price.toFixed(saleBasket.format.ceil);
            price = parseFloat(price);
            price = price * value;
            this.animateNumber(itemSum, price);
            this.calculateProperty(value, item);


            // calculate basket
            this.calculate();

            // save data
            BX.ajax.runAction('studio7spb:marketplace.api.tools.counterBasket', {
                data: {
                    id: item.data("product"),
                    quantity: value
                }
            });

        },

        calculate: function(){

            var basket = $("#basket"),
                space = 0,
                price = 0,
                weight = 0,
                count = 0;

            if(basket.find(".input-group").length > 0){
                basket.find(".input-group").each(function () {
                    count = $(this).find("input").val();
                    count = parseInt(count);
                    // calculate
                    price += parseFloat($(this).data("price")) * count;
                    weight += parseFloat($(this).data("weight")) * count;
                    space += parseFloat($(this).data("space")) * count;
                });

                $(".basket-total-sum").text(price.toFixed(saleBasket.format.ceil));
                this.animateNumber($(".basket-total-wight"), weight);
                this.animateNumber($(".basket-total-space"), space);
            }
            else{
                basket.html(null);
                $("#basket-empty").removeClass("d-none");
            }

        },
        calculateProperty: function(count, item){
            // PROPERTY_LHW_ctn
            // PROPERTY_DISPLAY_COUNT
            // PROPERTY_Master_CTN_PCS
            // PROPERTY_Master_CTN_SIZE
            // PROPERTY_WEIGHT

            var property = item.find(".cell-PROPERTY_Master_CTN_PCS"),
                value = parseFloat(item.find(".cell-PROPERTY_DISPLAY_COUNT").data("value"));

            if(value > 0){
                value = count / value;
                value = value.toFixed(1);
                property.text(value);
            }

            property = item.find(".cell-PROPERTY_Master_CTN_CBM");
            value = parseFloat(property.data("value"));
            if(value > 0){
                value = value * count;
                value = value.toFixed(1);
                property.text(value);
            }

            property = item.find(".cell-PROPERTY_WEIGHT");
            value = parseFloat(property.data("value"));
            if(value > 0){
                value = value * count;
                value = value.toFixed(1);
                property.text(value);
            }

        },

        pressBtn: function(e, t, action){
            switch (action) {
                case "calculate":
                    if(e.keyCode == 13){
                        this.count(t, 'calculate');
                    }
                    break;
            }
        },

        blur: function(t, action){
            switch (action) {
                case "calculate":
                    this.count(t, 'calculate');
                    break;
            }
        },

        /**
         * Animate number count without format
         * @param element
         * @param number
         */
        animateNumber: function(element, number){

            if(number ){
                number = number.toFixed(saleBasket.format.ceil);
                element.text(number);
            }
        },

        /**
         * Basic animate fof this project
         * @param element
         * @param number
         */
        animateInput: function(element, number){
            element.val(number);
        },

        // basket
        cuponUse: function(t){
            $(t).find(".btn").addClass("disabled");
            $(t).find(".form-control").addClass("disabled");
            $.post($(t).attr("action"), $(t).serializeArray(), (result) => {
                location.reload();
            });

            return false;
        },

        dialog: function(properties){
            if(this.popup)
                this.popup = null;

            this.popup = BX.PopupWindowManager.create('BasketPopup', null, {
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
            this.popup.setTitleBar(properties.title);
            this.popup.setContent('<div style="width: 100%; margin: 0; text-align: center;"><p>'
                + properties.desc
                + '</p></div>');
            this.popup.show();
        },

        toggleFavorite: function (t, id) {

            this.item.current = t;
            this.remove();

            // sent data
            var request = BX.ajax.runAction('studio7spb:marketplace.api.tools.addToWish', {
                data: {
                    id: id,
                }
            });
            request.then(function(response){
                if(response.status == 'success') {
                    s7market.updateBasket();
                }
            });
        },

        d: function (value) {
            console.log(value);
        }
    };

})();