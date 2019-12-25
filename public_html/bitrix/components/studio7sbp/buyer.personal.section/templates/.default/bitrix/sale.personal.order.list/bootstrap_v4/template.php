<?
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogProductsViewedComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Application;
$request = Application::getInstance()->getContext()->getRequest();
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>

<div id="personal-orders" class="ml-3 mr-3">

    <div class="row">
        <h1><?=Loc::getMessage("SPOL_TPL_ORDER_TITLE")?></h1>
    </div>

    <?if(empty($arResult["ORDERS"])):?>

        <?if(!empty($arResult["INFO"]["STATUS"])):?>
            <div class="row border-secondary border-top border-bottom text-center align-items-center pt-2 pb-2"
                 id="personal-order-statuses">

                <a class="col <?=($request->get("filter_status") ? "text-dark" : "text-primary")?>"
                   href="<?=$arResult["CURRENT_PAGE"]?>">
                    <?=Loc::getMessage("SPOL_TPL_STATUS_ALL")?> (<?=$arResult["INFO"]["ORDERS_COUNT"]?>)
                </a>

                <?foreach ($arResult["INFO"]["STATUS"] as $arStatus):?>
                    <a class="col border-secondary border-left <?=($request->get("filter_status") == $arStatus["ID"] ? "text-primary" : "text-dark")?>"
                       href="<?=$arResult["CURRENT_PAGE"]?>?filter_status=<?=$arStatus["ID"]?>">
                        <?=$arStatus["NAME"]?> (<?=($arStatus["COUNT"] ? $arStatus["COUNT"] : 0)?>)
                    </a>
                <?endforeach;?>
            </div>
        <?endif;?>

        <?if(empty($arResult["ERRORS"])):?>
            <div class="alert alert-info m-4"><?=Loc::getMessage("SPOL_TPL_EMPTY_ORDER_LIST")?></div>
        <?endif;?>
    <?else:?>

        <?if(!empty($arResult["INFO"]["STATUS"])):?>
            <div class="row border-secondary border-top border-bottom text-center align-items-center pt-2 pb-2"
                 id="personal-order-statuses">

                <a class="col <?=($request->get("filter_status") ? "text-dark" : "text-primary")?>"
                   href="<?=$arResult["CURRENT_PAGE"]?>">
                    <?=Loc::getMessage("SPOL_TPL_STATUS_ALL")?> (<?=$arResult["INFO"]["ORDERS_COUNT"]?>)
                </a>

                <?foreach ($arResult["INFO"]["STATUS"] as $arStatus):?>
                    <a class="col border-secondary border-left <?=($request->get("filter_status") == $arStatus["ID"] ? "text-primary" : "text-dark")?>"
                       href="<?=$arResult["CURRENT_PAGE"]?>?filter_status=<?=$arStatus["ID"]?>">
                        <?=$arStatus["NAME"]?> (<?=($arStatus["COUNT"] ? $arStatus["COUNT"] : 0)?>)
                    </a>
                <?endforeach;?>
            </div>
        <?endif;?>

        <form class="row align-items-center"
              id="personal-order-filter"
              method="post"
              action="<?=$arParams["PATH"]?>">
            <div class="form-group col-sm-auto mb-3 mt-3">
                <label><?=Loc::getMessage("SPOL_TPL_ORDER_SEARCH")?></label>
            </div>
            <div class="form-group col-sm col-sm-auto mb-3 mt-3">
                <label for="order-search-id" class="sr-only"><?=Loc::getMessage("SPOL_TPL_ORDER_NUMBER")?></label>
                <select name="search-order-id"
                       class="form-control">
                    <option value=""><?=Loc::getMessage("SPOL_TPL_ORDER_NUMBER")?></option>
                    <?foreach ($arResult["ORDERS_FILTER"]["ORDERS"] as $arOrder):?>
                        <option value="<?=$arOrder["ID"]?>"
                            <?if($arResult["ORDERS_FILTER"]["ID"] == $arOrder["ID"]){
                                echo "selected";
                            }?>>
                            <?=$arOrder["ID"]?>
                        </option>
                    <?endforeach;?>
                </select>
            </div>
            <div class="form-group col-sm col-md-5 mb-3 mt-3">
                <label for="order-search-id" class="sr-only"><?=Loc::getMessage("SPOL_TPL_BASKET_ITEM_NAME")?></label>
                <select name="search-order-item"
                       class="form-control">
                    <option value=""><?=Loc::getMessage("SPOL_TPL_BASKET_ITEM_NAME")?></option>
                    <?foreach ($arResult["ORDERS_FILTER"]["ITEMS"] as $arBasketItem):?>
                        <option value="<?=$arBasketItem["ID"]?>"
                            <?if($arResult["ORDERS_FILTER"]["ITEM"] == $arBasketItem["ID"]){
                                echo "selected";
                            }?>><?=TruncateText($arBasketItem["NAME"], 36)?></option>
                    <?endforeach;?>
                </select>
            </div>
            <div class="form-group col-sm-auto col-md-3 mb-3 mt-3 text-center text-sm-eft">
                <button type="submit" class="d-none d-sm-inline-block search-form-button align-top"></button>
                <input type="submit"
                       class="d-inline-block d-sm-none btn btn-primary"
                       value="<?=Loc::getMessage("SPOL_TPL_BASKET_ITEM_SUBMIT")?>">
                <?if($arResult["ORDERS_FILTER"]["ID"] > 0 || $arResult["ORDERS_FILTER"]["ITEM"] > 0):?>
                    <input type="submit"
                           name="clear-filter"
                           class="btn btn-outline-danger d-inline-block align-top"
                           value="<?=Loc::getMessage("SPOL_TPL_BASKET_ITEM_CLEAR")?>">
                <?endif;?>
            </div>
        </form>

        <div class="row" id="personal-order-list">
                <table class="table">
                    <tbody>
                    <tr class="bg-light d-none d-md-table-row">
                        <?if(count($arOrder["BASKET_ITEMS"]) > 1):?>
                            <th><?=Loc::getMessage("SPOL_TPL_ORDER_LIST_ITEMS")?></th>
                        <?else:?>
                            <th><?=Loc::getMessage("SPOL_TPL_ORDER_LIST_ITEM")?></th>
                        <?endif;?>
                        <th class="text-center" width="20%"><?=Loc::getMessage("SPOL_TPL_ORDER_LIST_STATUS")?></th>
                        <th class="text-center" width="20%"><?=Loc::getMessage("SPOL_TPL_ORDER_LIST_ACTION")?></th>
                    </tr>
                    <?
                    $i = 0;
                    foreach ($arResult["ORDERS"] as $arOrder):
                        $i++;
                    ?>
                        <tr class="bg-light d-block d-md-table-row cursor"
                            onclick="personalOrders.toggleItems(this, <?=$arOrder["ORDER"]["ID"]?>)">
                            <td colspan="2">
                                <div class="mb-1">
                                    <span class="mr-5">
                                    <span class="text-secondary mr-1"><?=Loc::getMessage("SPOL_TPL_ORDER") . " " . Loc::getMessage("SPOL_TPL_NUMBER_SIGN")?>:</span><b><?=$arOrder["ORDER"]["ID"]?></b>
                                </span>
                                    <a href="<?=$arOrder["ORDER"]["URL_TO_DETAIL"]?>">
                                        <?=Loc::getMessage("SPOL_TPL_MORE_ON_ORDER")?>
                                    </a>
                                </div>
                                <span class="text-secondary mr-1"><?=Loc::getMessage("SPOL_TPL_ORDER_DATE_INSERT")?>:</span><b><?=$arOrder["ORDER"]["DATE_INSERT"]?></b>
                            </td>
                            <td colspan="2" class="text-right position-relative pr-5">
                                <b><?=$arOrder["ORDER"]["FORMATED_PRICE"]?></b>
                                <div class="<?=$i===1 ? "switcher switcher-close" : "switcher"?>"></div>
                            </td>
                        </tr>
                        <?foreach ($arOrder["BASKET_ITEMS"] as $arBasketItem):?>
                            <tr class="order-<?=$arOrder["ORDER"]["ID"]?> <?=$i===1 ? "d-block d-md-table-row" : "d-none"?>">
                                <td class="d-block d-md-table-cell">
                                    <div class="row">
                                        <?if(is_array($arBasketItem["PREVIEW_PICTURE"])):?>
                                            <div class="col col-auto">
                                                <img src="<?=$arBasketItem["PREVIEW_PICTURE"]["src"]?>">
                                            </div>
                                        <?endif;?>
                                        <div class="col">
                                            <a href="<?=$arBasketItem["DETAIL_PAGE_URL"]?>" class="h5"><?=$arBasketItem["NAME"]?></a>
                                            <br>
                                            <?=CurrencyFormat($arBasketItem["PRICE"], $arBasketItem["CURRENCY"])?>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center d-block d-md-table-cell">
                                    <p><?=$arResult["INFO"]["STATUS"][$arOrder["ORDER"]["STATUS_ID"]]["NAME"]?></p>
                                </td>
                                <td class="text-center text-md-right d-block d-md-table-cell">
                                    <a href="<?=$arOrder["ORDER"]["URL_TO_DETAIL"]?>" class="btn btn-primary"><?=Loc::getMessage("SPOL_TPL_PAY")?></a>
                                    <!----<a href="<?=$arOrder["ORDER"]["URL_TO_CANCEL"]?>" class="btn btn-outline"><?=Loc::getMessage("SPOL_TPL_CANCEL_ORDER")?></a>--->
                                </td>
                            </tr>

                        <?endforeach;?>
                    <?endforeach;?>
                    </tbody>
                </table>
        </div>

    <?endif;?>

</div>