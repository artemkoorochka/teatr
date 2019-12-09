/**
 * @param to
 * @param type
 * @param fn
 */
function addEvent(to, type, fn){
    if(document.addEventListener){
        to.addEventListener(type, fn, false);
    } else if(document.attachEvent){
        to.attachEvent('on'+type, fn);
    } else {
        to['on'+type] = fn;
    }
}

/**
 * Начальное состояние
 */
BX.ready(function(){
    window.catalogAdaptiveNews = new KoorochkaListAdaptive({
        id: "catalog-section-news"
    });
    window.catalogAdaptiveNews.styleCarusel();
    window.catalogAdaptiveNews.setItemsSticker("new");
});

/**
 * При изменении ширины окна
 */
addEvent(window, "resize", function(event) {
    window.catalogAdaptiveNews.caruselCheck();
    window.catalogAdaptiveNews.styleCarusel();
});
