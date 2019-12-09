// koorochka.carousel.js

(function (window) {

    if (!!window.KoorochkaCarousel)
    {
        return;
    }

    window.KoorochkaCarousel = function (arParams)
    {
        this.duration = 500;
        this.viewport = null;
        this.wrapper = null;
        this.slider = {
            start: 1106,
            //end: -3853, // -3853
            step: 150,
            current: 1106,
            next: null,
            prev:null,
            space: null
        };
        this.items = {
            count: null,
            itemWidth: null,
            width: null
        };

        if (typeof arParams === 'object')
        {
            this.id = arParams.id;
            this.nextButton = arParams.next;
            this.prevButton = arParams.prev;
            if(arParams.duration > 0){
                this.duration = arParams.duration;
            }
            if(arParams.step > 0){
                this.slider.step = arParams.step;
            }

        }

        if(!this.viewport && this.id){
            this.setViewport();
        }

        if(!this.wrapper && this.id){
            this.setWrapper();
        }

        this.Init();

        /**
         * Events
         */
        BX.bind(BX(this.nextButton), 'click', BX.proxy(this.next, this));
        BX.bind(BX(this.prevButton), 'click', BX.proxy(this.prev, this));
        //BX.addCustomEvent('onSaleProductIsNotGift', BX.delegate(this.onSaleProductIsNotGift, this));
    };

    window.KoorochkaCarousel.prototype.Init = function()
    {
        // koorochka-carusel-item
        var items = BX.findChild(
            BX(this.id),
            {
                className:'koorochka-carusel-item'
            },
            true,
            true
        );
        this.items.count = items.length;
        this.items.itemWidth = items[0].clientWidth;
        this.items.width = this.items.count * this.items.itemWidth;
        BX.style(this.viewport, 'width', this.items.width + 'px');
    };

    window.KoorochkaCarousel.prototype.setViewport = function()
    {
        this.viewport = BX.findChild(
            BX(this.id),
            {
                className:'koorochka-carousel-viewport'
            },
            true,
            false
        );
    };

    window.KoorochkaCarousel.prototype.setWrapper = function()
    {
        this.wrapper = BX.findChild(
            BX(this.id),
            {
                className:'koorochka-carousel-wrapper'
            },
            true,
            false
        );
    };

    window.KoorochkaCarousel.prototype.next = function()
    {
        // calculate slider space
        this.slider.space = this.slider.start - this.viewport.clientWidth + this.wrapper.clientWidth;
        this.slider.next = this.slider.current - this.slider.step;

        if(this.slider.next > this.slider.space){
            this.animate(this.slider.next);
            this.slider.current = this.slider.next;
            this.slider.next = this.slider.current - this.slider.step;
        }
    };

    window.KoorochkaCarousel.prototype.prev = function()
    {

        this.slider.prev = this.slider.current + this.slider.step;
        if(this.slider.current < this.slider.start){
            this.animate(this.slider.prev);
            this.slider.current = this.slider.prev;
            this.slider.prev = this.slider.current + this.slider.step;
        }
    };

    window.KoorochkaCarousel.prototype.animate = function(position)
    {
        var viewport = BX(this.viewport);
        var easing = new BX.easing({
            duration : this.duration,
            start : { left : this.slider.current},
            finish : { left : position},
            transition : BX.easing.transitions.back,
            step : function(state){
                viewport.style.left = state.left + "px";
            }
        });
        easing.animate();
    };




})(window);