

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

/**
 * При смене ориентации с портретной на пейзажную или обратно
 */
addEvent(window, "onorientationchange", function(event) {
    window.catalogAdaptiveNews.caruselCheck();
    window.catalogAdaptiveNews.styleCarusel();
});