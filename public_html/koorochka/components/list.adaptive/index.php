<?
/**
 * Addaptive list
 */

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
Asset::getInstance()->addCss("/koorochka/components/list/style.css");
Asset::getInstance()->addJs("/koorochka/components/list/js/carousel.bx.js");
Asset::getInstance()->addJs("/koorochka/components/list/js/list.adaptive.js");
Asset::getInstance()->addJs("/koorochka/components/list/js/component.js");
Asset::getInstance()->addJs("/koorochka/components/list/js/script.js");
$arResult = array();
$arResult['ITEMS'] = array(
    array(
        "NAME" => "NAME",
        "DETAIL_PAGE_URL" => "#",
        "PREVIEW_PICTURE" => array(
            "SRC" => "/upload/iblock/0e0/0e0e640d1b5e1554f6dee89c3e7f148a.png"
        )
    ),
    array(
        "NAME" => "NAME",
        "DETAIL_PAGE_URL" => "#",
        "PREVIEW_PICTURE" => array(
            "SRC" => "/upload/iblock/0e0/0e0e640d1b5e1554f6dee89c3e7f148a.png"
        )
    ),
    array(
        "NAME" => "NAME",
        "DETAIL_PAGE_URL" => "#",
        "PREVIEW_PICTURE" => array(
            "SRC" => "/upload/iblock/0e0/0e0e640d1b5e1554f6dee89c3e7f148a.png"
        )
    ),
    array(
        "NAME" => "NAME",
        "DETAIL_PAGE_URL" => "#",
        "PREVIEW_PICTURE" => array(
            "SRC" => "/upload/iblock/0e0/0e0e640d1b5e1554f6dee89c3e7f148a.png"
        )
    ),
    array(
        "NAME" => "NAME",
        "DETAIL_PAGE_URL" => "#",
        "PREVIEW_PICTURE" => array(
            "SRC" => "/upload/iblock/0e0/0e0e640d1b5e1554f6dee89c3e7f148a.png"
        )
    ),
    array(
        "NAME" => "NAME",
        "DETAIL_PAGE_URL" => "#",
        "PREVIEW_PICTURE" => array(
            "SRC" => "/upload/iblock/0e0/0e0e640d1b5e1554f6dee89c3e7f148a.png"
        )
    ),
    array(
        "NAME" => "NAME",
        "DETAIL_PAGE_URL" => "#",
        "PREVIEW_PICTURE" => array(
            "SRC" => "/upload/iblock/0e0/0e0e640d1b5e1554f6dee89c3e7f148a.png"
        )
    ),
    array(
        "NAME" => "NAME",
        "DETAIL_PAGE_URL" => "#",
        "PREVIEW_PICTURE" => array(
            "SRC" => "/upload/iblock/0e0/0e0e640d1b5e1554f6dee89c3e7f148a.png"
        )
    ),
    array(
        "NAME" => "NAME",
        "DETAIL_PAGE_URL" => "#",
        "PREVIEW_PICTURE" => array(
            "SRC" => "/upload/iblock/0e0/0e0e640d1b5e1554f6dee89c3e7f148a.png"
        )
    ),
);

?>


<div class="bg-white p5 mt1 mb4">

    <div class="margin-bottom-60" id="catalog-section-news">
        <div class="adaptive-wrapper koorochka-carousel-wrapper">
            <div class="adaptive-viewport koorochka-carousel-viewport">
                <?foreach ($arResult['ITEMS'] as $item):

                    ?>
                    <div data-sticker="new"
                         class="adaptive-item koorochka-carusel-item catalog-item">

                        <a class="catalog-item-img"
                           href="<?=$item["DETAIL_PAGE_URL"]?>">
                            <img src="<?=$item["PREVIEW_PICTURE"]["SRC"]?>"
                            />
                        </a>



                    </div>
                <?endforeach;?>
            </div>
        </div>

        <div id="catalog-section-news-prev" class="d-block d-lg-none"></div>
        <div id="catalog-section-news-next" class="d-block d-lg-none"></div>

    </div>

</div>

<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');?>