<?
/**
 * @var array $arItem
 */

use Bitrix\Main\Localization\Loc;
?>

<tr class="basket-item"
    data-product="<?=$arItem["ID"]?>">
    <td>
        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="basket-item-info">
            <?if(!empty($arItem["PREVIEW_PICTURE_SRC"])):?>
                <img src="<?=$arItem["PREVIEW_PICTURE_SRC"]?>"
                     width="100%"
                     class="img-responsive">
            <?else:?>
                <img src="<?=$this->GetFolder()?>/images/no_photo.png"
                     width="100%"
                     class="img-responsive">
            <?endif;?>
        </a>
    </td>
    <td>
        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a>
        <div class="d-block d-lg-none">
            <?foreach ($arParams["COLUMNS_LIST"] as $value):?>
                <?if(
                        is_set($arItem[$value . "_VALUE"]) && $arItem[$value . "_VALUE_ID"] > 0 &&
                        is_set(Loc::getMessage("BASKET_ITEM_" . $value))
                ):?>
                    <p class="text-nowrap">
                        <?=Loc::getMessage("BASKET_ITEM_" . $value) . ": "?>
                        <span class="element-<?=$value?>"><?
                        if(!empty($arResult["SPACE"][$arItem["ID"]][$value])){
                            echo $arResult["SPACE"][$arItem["ID"]][$value];
                        }else{
                            echo $arItem[$value . "_VALUE"];
                        }
                        ?></span>
                    </p>
                <?endif;?>
            <?endforeach;?>
        </div>
    </td>
    <td class="text-nowrap align-middle">
            <?=$arItem["PRICE"]?>
            <?=str_replace("#", "", $arResult["CURRENCIES_FORMAT"][$arItem["CURRENCY"]])?>
    </td>

    <?
    $i = 0;
    foreach ($arParams["COLUMNS_HEADER"] as $code):
        $i++;

        if(!empty($arResult["SPACE"][$arItem["ID"]][$code])){
            $value = $arResult["SPACE"][$arItem["ID"]][$code];
        }else{
            $value = round($arItem[$code . "_VALUE"], 1);
        }
        if($value <= 0){
            $value = 1;
        }
    ?>
        <?if($i === 2):?>
            <td class="align-middle">
                <div class="input-group"
                     data-currency="<?=$arResult["CURRENCIES_FORMAT"][$arItem["CURRENCY"]]?>"
                     data-sum="<?=$arItem["SUM_VALUE"]?>"
                     data-price="<?=$arItem["PRICE"]?>"
                     data-space="<?=$arResult["SPACE"][$arItem["ID"]]["DIMENSIONS"]?>"
                     data-weight="<?=$arItem["PROPERTY_WEIGHT_VALUE"]?>">
                    <a class="input-group-prepend" onclick="saleBasket.count(this, false)">
                        <span class="input-group-text">-</span>
                    </a>
                    <input type="text"
                           class="form-control text-center"
                           onkeypress="saleBasket.pressBtn(event, this, 'calculate')"
                           onblur="saleBasket.blur(this, 'calculate')"
                           value="<?=$arItem["QUANTITY"]?>"
                           name="count">
                    <a class="input-group-append" onclick="saleBasket.count(this, true)">
                        <span class="input-group-text">+</span>
                    </a>
                </div>
            </td>
        <?endif;?>

        <td class="text-nowrap align-middle text-center d-none d-lg-table-cell cell-<?=$code?>"
            data-value="<?=$value?>">
            [<?=$code?>]
            <?
            if(in_array($code, array("PROPERTY_Master_CTN_PCS", "PROPERTY_Master_CTN_CBM", "PROPERTY_WEIGHT"))) // "PROPERTY_Master_CTN_CBM"
            $value = $value * $arItem["QUANTITY"];
            echo $value;
            ?>
        </td>
    <?endforeach;?>

    <td class="text-nowrap align-middle text-16 ceil-sum">
        <b class="item-sum"><?=round($arItem["SUM_VALUE"], 0)?></b>
        <b><?=str_replace("#", "", $arResult["CURRENCIES_FORMAT"][$arItem["CURRENCY"]])?></b>
    </td>
    <td class="align-middle">

        <div onclick="saleBasket.toggleFavorite(this, <?=$arItem["PRODUCT_ID"]?>)"
             class="item-favorite-icon"
             title="<?=Loc::getMessage("BASKET_ITEM_FAVORITE")?>"></div>

        <a onclick="saleBasket.delete(this)"
           data-title="<?=Loc::getMessage("BASKET_ITEM_DELETE_CONFIRM_DELETE")?>"
           data-delete="<?=Loc::getMessage("BASKET_ITEM_DELETE_CONFIRM")?>"
           data-cancel="<?=Loc::getMessage("BASKET_ITEM_DELETE_CANCEL")?>"
           title="<?=Loc::getMessage("BASKET_ITEM_DELETE")?>"
           class="item-trash-icon text-primary"></a>
    </td>
</tr>
