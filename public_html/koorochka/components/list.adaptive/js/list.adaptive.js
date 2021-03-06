/**
 * list.adaptive
 */

(function (window) {

    if (!!window.KoorochkaListAdaptive)
    {
        return;
    }

    window.KoorochkaListAdaptive = function (arParams)
    {
        // init fields
        this.id = null;
        this.wrapper = null;
        this.viewport = null;
        this.items = [];
        this.maxWidth = 750;
        this.currentWidth = null;
        this.width = null;
        this.caruselObj = null;
        this.carusel = false;
        this.restyle = true;
        this.stickered = false;
        this.sticker = "";
        // set params to object
        if (typeof arParams === 'object')
        {
            this.id = arParams.id;
            this.wrapper = arParams.wrapper;
            this.viewport = arParams.viewport;
            if(arParams.maxWidth > 0){
                this.maxWidth = arParams.maxWidth;
            }
            if(arParams.sticker){
                this.sticker = arParams.sticker;
            }
        }
        // init object
        this.Init();
    };
    window.KoorochkaListAdaptive.prototype.Init = function()
    {
        this.wrapper = BX.findChild(BX(this.id), {className:'adaptive-wrapper'}, true, false);
        this.viewport = BX.findChild(BX(this.id), {className:'adaptive-viewport'}, true, false);
        this.items = BX.findChild(BX(this.id), {className:'adaptive-item'}, true, true);
        this.caruselCheck();
        //this.styleCarusel();
        //this.setItemsSticker(this.sticker);
    };
    window.KoorochkaListAdaptive.prototype.setWidth = function () {
        var width = BX.width(BX(this.id));
        if(this.currentWidth == width){
            this.restyle = false;
        }else{
            this.currentWidth = width;
            this.width = width;
            this.restyle = true;
        }
    };
    window.KoorochkaListAdaptive.prototype.getWidth = function () {
        return this.width;
    };
    window.KoorochkaListAdaptive.prototype.caruselOn = function () {
        this.carusel = true;
    };
    window.KoorochkaListAdaptive.prototype.caruselOff = function () {
        this.carusel = false;
    };
    window.KoorochkaListAdaptive.prototype.caruselCheck = function () {
        this.setWidth();
        if(this.restyle){
            if(this.maxWidth >= this.getWidth()){
                this.caruselOn();
            }
            else{
                this.caruselOff();
            }
        }
    };
    window.KoorochkaListAdaptive.prototype.styleCarusel = function () {

        if(this.restyle){
            if(this.carusel){
                BX.addClass(BX(this.id), 'row');
                BX.removeClass(this.viewport, 'row');
                BX.addClass(this.wrapper, 'koorochka-carousel-wrapper');
                BX.addClass(this.viewport, 'koorochka-carousel-viewport');
                if (!!this.items && 0 < this.items.length)
                {
                    for (var i = 0; i < this.items.length; i++)
                    {
                        BX.addClass(this.items[i], 'koorochka-carusel-item');
                        BX.removeClass(this.items[i], 'col-md-3');
                    }
                }
                this.setCarusel();
            }else{
                BX.removeClass(BX(this.id), 'row');
                BX(this.id).setAttribute("style", "");
                BX(this.viewport).setAttribute("style", "");
                BX.addClass(this.viewport, 'row');
                BX.removeClass(this.wrapper, 'koorochka-carousel-wrapper');
                BX.removeClass(this.viewport, 'koorochka-carousel-viewport');
                if (!!this.items && 0 < this.items.length)
                {
                    for (var i = 0; i < this.items.length; i++)
                    {
                        BX.removeClass(this.items[i], 'koorochka-carusel-item');
                        BX.addClass(this.items[i], 'col-md-3');
                    }
                }
                this.removeCarusel();
            }
        }
    };
    window.KoorochkaListAdaptive.prototype.setCarusel = function () {
        this.caruselObj = new KoorochkaCarousel({
            id: this.id,
            prev: this.id + "-next",
            next: this.id + "-prev",
            duration: 100,
            step: 262
        });
    };
    window.KoorochkaListAdaptive.prototype.removeCarusel = function () {
        this.caruselObj = null;
    };
    window.KoorochkaListAdaptive.prototype.setItemsSticker = function (type) {
        var sticker = '';
        if(type == "discont"){
            sticker = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M20,18a2,2,0,0,1-2,2H2a2,2,0,0,1-2-2V2A2,2,0,0,1,2,0H18a2,2,0,0,1,2,2V18Z" style="fill: #ff5046"></path><path d="M6.43,10.66a2.35,2.35,0,0,1-2-1,4,4,0,0,1-.71-2.43A4.09,4.09,0,0,1,4.4,4.8a2.36,2.36,0,0,1,2-1,2.38,2.38,0,0,1,2,1,4,4,0,0,1,.72,2.43,4,4,0,0,1-.72,2.46A2.4,2.4,0,0,1,6.43,10.66Zm7.14,6.67a2.36,2.36,0,0,1-2-1,4.06,4.06,0,0,1-.71-2.44,4.07,4.07,0,0,1,.71-2.44,2.37,2.37,0,0,1,2.05-1,2.37,2.37,0,0,1,2,1,4,4,0,0,1,.72,2.43,4,4,0,0,1-.72,2.45A2.39,2.39,0,0,1,13.57,17.33Z" style="fill: none;stroke: #fff;stroke-miterlimit: 10"></path><line x1="5" y1="17.33" x2="15.14" y2="4.18" style="fill: none;stroke: #fff;stroke-linecap: round;stroke-miterlimit: 10"></line></svg>';
        }
        else if(type == "new"){
            sticker = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M20,18a2,2,0,0,1-2,2H2a2,2,0,0,1-2-2V2A2,2,0,0,1,2,0H18a2,2,0,0,1,2,2V18Z" style="fill: #ffb838"></path><g><path d="M3.6,12.42V7.58H4.28L5.83,10A13.62,13.62,0,0,1,6.7,11.6h0c-0.06-.65-0.07-1.24-0.07-2v-2H7.23v4.84H6.6L5.06,10a15.71,15.71,0,0,1-.91-1.62h0c0,0.61.05,1.19,0.05,2v2.07H3.6Z" style="fill: #fff"></path><path d="M10.83,10.15H8.94V11.9H11v0.52H8.32V7.58h2.62V8.1h-2V9.63h1.88v0.52Z" style="fill: #fff"></path><path d="M12.71,12.42L11.48,7.58h0.66L12.71,10c0.14,0.6.27,1.21,0.36,1.68h0c0.08-.48.23-1.06,0.4-1.68l0.65-2.44h0.65L15.37,10c0.14,0.57.27,1.15,0.34,1.66h0c0.1-.53.24-1.07,0.39-1.68l0.64-2.44h0.64L16,12.42H15.37L14.76,9.9a14.43,14.43,0,0,1-.32-1.58h0a15.55,15.55,0,0,1-.38,1.58l-0.69,2.52H12.71Z" style="fill: #fff"></path></g></svg>';
        }
        if (!!this.items && 0 < this.items.length && this.stickered == false)
        {
            for (var i = 0; i < this.items.length; i++)
            {
                this.items[i].appendChild(BX.create(
                    'DIV',
                    {
                        props: {
                            className: 'catalog-item-sticker'

                        },
                        html: sticker
                    }
                ));
            }
            this.stickered = true;
        }
    };
})(window);