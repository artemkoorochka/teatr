<?
/**
 * @var array $arParams
 * @var array $arResult
 * @var string $templateFolder
 * @var string $templateName
 * @var CMain $APPLICATION
 * @var CBitrixBasketComponent $component
 * @var CBitrixComponentTemplate $this
 * @var array $giftParameters
 */
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->addExternalCss("/bitrix/css/main/bootstrap_v4/bootstrap.css");
$this->addExternalJs($this->GetFolder() . "/js/modal.js");
use Bitrix\Main\Localization\Loc;
Loc::loadLanguageFile(__FILE__);
?>

<div id="basket-empty" class="<?=empty($arResult["ITEMS"]["AnDelCanBuy"]) ? "p-5 text-center" : "p-5 text-center d-none"?>">
    <img src="<?=$this->GetFolder()?>/images/empty_cart.svg" class="img-responsive">
    <div class="alert alert-danger mt-3"><?=Loc::getMessage("BASKET_EMPTY")?></div>
</div>

<div id="basket"
     data-warning="<?=Loc::getMessage("BASKET_WARNING")?>"
     data-moq-hint="<?=Loc::getMessage("BASKET_MOQ_HINT")?>">

    <?if(count($arResult["ITEMS"]["AnDelCanBuy"]) > 0):?>

        <div class="container pt-4 pb-4">

            <div class="row align-items-center mb-2">
                <div class="col-12 col-sm"><h1 class="h1 text-uppercase"><?=Loc::getMessage("BASKET_TITLE")?></h1></div>
                <div class="col-12 col-sm-auto"><a href="<?=$arParams["PATH_TO_ORDER"]?>" class="text-info text-14"><?=Loc::getMessage("BASKET_SUBMIT_LINK")?></a></div>
            </div>

            <div class="row p-2 mb-3 text-white bg-primary">
                <?include "basket.total.php";?>
            </div>

            <?foreach ($arResult["MARKET_LIST"] as $market):?>
                <div class="market-item">
                    <!--- Market head --->
                    <div class="row mt-4 bg-light p-1 mb-xl-3 justify-content-between align-items-center">
                        <div class="store-secton col-12 col-lg-6 py-3">
                            <span class="pr-3 pt-2 pb-2 mr-2 border-right border-dark"><?=Loc::getMessage("BASKET_MARKET_TITLE")?></span><span class="text-primary"><?=$market["NAME"]?></span><?=$market["PREVIEW_TEXT"]?>
                        </div>
                    </div>

                    <?
                    // <editor-fold defaultstate="collapsed" desc=" # Basket head">
                    include "basket.head.php";
                    // </editor-fold>

                    // <editor-fold defaultstate="collapsed" desc=" # Basket items list">
                    foreach ($arResult["ITEMS"]["AnDelCanBuy"] as $key => $arItem){
                        if(in_array($arItem["PRODUCT_ID"], $market["ITEMS"])){
                            include "basket.item.php";
                            unset($arResult["GRID"]["ROWS"][$key]);
                        }
                    }
                    // </editor-fold>
                    ?>
                </div>
            <?
            endforeach;
            if(count($arResult["GRID"]["ROWS"]) > 0):
            ?>
                <table class="table">
                    <?
                    // <editor-fold defaultstate="collapsed" desc=" # Basket head">
                    include "basket.head.php";
                    // </editor-fold>
                    ?>
                    <tbody>
                    <?
                    // <editor-fold defaultstate="collapsed" desc=" # Basket items list">
                    foreach ($arResult["ITEMS"]["AnDelCanBuy"] as $key => $arItem){
                        include "basket.item.php";
                    }
                    // </editor-fold>
                    ?>
                    </tbody>
                </table>
            <?endif;?>

            <div class="row p-3 border-bottom border-primary mb-2">
                <?include "basket.total.footer.php";?>
            </div>

            <div class="row bg-light align-items-center p-4">

                <div class="col">
                    <b class="h3"><?=Loc::getMessage("BASKET_TOTAL")?>:</b>
                    <b class="h1 basket-total-sum"><?=number_format($arResult["allSum"], 2, '.', ' ')?></b>
                    <b class="h1"><?=str_replace("#", "", $arResult["CURRENCIES_FORMAT"][$arResult["CURRENCY"]])?></b>
                </div>

                <div class="col-auto">
                    <a onclick="saleBasket.deleteAll(this)"
                       data-pic="<?=$this->GetFolder()?>/images/empty_cart.svg"
                       data-title="<?=Loc::getMessage("BASKET_ITEM_CONFIRM_DELETE_ALL")?>"
                       data-delete="<?=Loc::getMessage("BASKET_ITEM_DELETE_CONFIRM")?>"
                       data-cancel="<?=Loc::getMessage("BASKET_ITEM_DELETE_CANCEL")?>"
                       class="trash-link text-primary"><?=Loc::getMessage("BASKET_DELETE_ALL")?></a>
                </div>

                <div class="col-auto">
                    <a href="<?=$arParams["PATH_TO_ORDER"]?>" class="btn text-uppercase btn-primary h4 py-3 px-4 b"><?=Loc::getMessage("BASKET_SUBMIT")?></a>
                </div>

            </div>

        </div>

        <div class="modal fade d-none" id="modal-basket">
            <div class="modal-dialog m-0" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="close" onclick="saleModal.hide();">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body text-center"></div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>

        <div class="modal-backdrop fade d-none"
             id="modal-basket-backdrop"
             onclick="saleModal.hide();"></div>

    <?endif;?>
</div>
