/**
 * https://learn.javascript.ru/class
 * Может нужно использовать и класс
 * Это не просто синтаксический сахар
 * @param arParams
 */
// 1. Создаём функцию constructor
function lancyImport(arParams) {
    this.arParams = arParams;
}
// каждый прототип функции имеет свойство constructor по умолчанию,
// поэтому нам нет необходимости его создавать

// 2. Добавляем метод в прототип
lancyImport.prototype.sayHi = function() {
    console.info(this.arParams);
};

var lancyImport = new lancyImport({name: "Artem", age: 38});


lancyImport.sayHi();
