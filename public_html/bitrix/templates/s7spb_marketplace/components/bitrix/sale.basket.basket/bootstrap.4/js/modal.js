;(function() {
    'use strict';

    window.saleModal = {

        id: "#modal-basket",
        back: "#modal-basket-backdrop",
        title: {
            class: ".modal-title",
            content: null
        },
        body: {
            class: ".modal-body",
            content: null
        },
        footer: {
            class: ".modal-footer",
            content: null
        },
        show: function(){
            this.fill();
            $(this.id)
                .addClass("show")
                .addClass("d-block")
                .removeClass("d-none");
            $(this.back)
                .addClass("show")
                .addClass("d-block")
                .removeClass("d-none");
        },
        hide: function (t) {
            $(this.id)
                .removeClass("show")
                .removeClass("d-block")
                .addClass("d-none");
            $(this.back)
                .removeClass("show")
                .removeClass("d-block")
                .addClass("d-none");
            this.clear();
        },
        fill: function () {
            $(this.id).find(this.title.class).text(this.title.content);
            $(this.id).find(this.body.class).html(this.body.content);
            $(this.id).find(this.footer.class).html(this.footer.content);
        },
        clear: function () {
            $(this.id).find(this.title.class).text("");
            $(this.id).find(this.body.class).text("");
            $(this.id).find(this.footer.class).text("");
        }
    };

})();