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
    this.bWorkFinished = false;
    this.bSubmit = null;
}

/**
 * Prototipe methods
 * каждый прототип функции имеет свойство constructor по умолчанию,
 * поэтому нам нет необходимости его создавать
 */
lancyImport.prototype.showStatus = function() {
    console.info(this.arParams);
    console.info(this.bWorkFinished);
    console.info(this.bSubmit);
};

lancyImport.prototype.setStart = function (val) {
    console.info(val);
};

lancyImport.prototype.workOnload = function (result) {
    console.info(result);
};





/**
 * Using
 * @type {lancyImport}
 */
var lancyImport = new lancyImport({
    arParams: {param1: 1, param2: 2}
});


lancyImport.showStatus();

lancyImport.setStart("set start");
lancyImport.workOnload("result to eval");
