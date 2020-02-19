/**
 * https://learn.javascript.ru/class
 * Может нужно использовать и класс
 * Это не просто синтаксический сахар
 * @param arParams
 */

/**
 * Create constructor function
 * @param arParams
 */
function lancyImport(arParams) {
    this.arParams = arParams;
}

/**
 * Prototipe methods
 * каждый прототип функции имеет свойство constructor по умолчанию,
 * поэтому нам нет необходимости его создавать
 */
lancyImport.prototype.showStatus = function() {
    console.info(this.arParams);
};

lancyImport.prototype.setStart = function (val) {

    var status = "<strong>" + this.arParams.messages.status + ":</strong> " + this.arParams.messages.work;
    $(this.arParams.status).html(status);
    if(val){
        // Preparato set status bar and set progress to null
        this.showWaitWindow();
        $(this.arParams.bar)
            .removeClass("d-none")
            .find(".progress-bar")
            .css({width:"0%"})
            .text("0%")
            .addClass("progress-bar-animated")
            .addClass("progress-bar-striped");

        // Ajax

        BX.ajax.runComponentAction(
            'studio7sbp:seller.personal.import.transfer',
            'getElements',
            {
                mode:'class',
                data: {
                    work_start: "Y",
                    sessid: BX.bitrix_sessid()
                }
            }).then(BX.delegate(this.workOnload, this));

    }
    else{
        this.closeWaitWindow();
    }

};

lancyImport.prototype.workOnload = function (response) {
    if (response.status === 'success') {

        var status = "<strong>" + this.arParams.messages.status + ":</strong> " + response.data.status;
        $(this.arParams.status).html(status);

        // Preparato set status bar and set progress to null
        $(this.arParams.bar)
            .removeClass("d-none")
            .find(".progress-bar")
            .animate({width:response.data.p + "%"}, 50)
            .text(response.data.p + "%")
            .addClass("progress-bar-animated")
            .addClass("progress-bar-striped");

        // Ajax
        if(response.data.lastid > 0){
            // send
            BX.ajax.runComponentAction(
                'studio7sbp:seller.personal.import.transfer',
                'getElements',
                {
                    mode:'class',
                    data: {
                        work_start: "Y",
                        lastid: response.data.lastid,
                        sessid: BX.bitrix_sessid()
                    }
                }).then(BX.delegate(this.workOnload, this));
        }else{
            // stop
            lancyImport.setStart(0);
        }

    }
};

lancyImport.prototype.showWaitWindow = function () {
    var html = '<div class="product-add-loading">';
        html += '<div class="main-ui-loader main-ui-loader-custom main-ui-show" data-is-shown="true" style="width: 130px; height: 130px;">';
        html += '<svg class="main-ui-loader-svg" viewBox="25 25 50 50">';
        html += '<circle class="main-ui-loader-svg-circle" cx="50" cy="50" r="20" fill="none" stroke-miterlimit="10" style="stroke: rgb(102, 58, 242);">';
        html += '</circle>';
        html += '</svg>';
        html += '</div>';
        html += '</div>';
        $("body").append(html);
};

lancyImport.prototype.closeWaitWindow = function () {
    $(".product-add-loading").remove();
};

