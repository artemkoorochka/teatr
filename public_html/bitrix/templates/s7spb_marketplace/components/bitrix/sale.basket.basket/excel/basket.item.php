<?
/**
 * @var array $arItem
 */

use Bitrix\Main\Localization\Loc;
?>

<tr class="basket-item"
    data-product="<?=$arItem["ID"]?>">
    <td>
        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="basket-item-info d-none d-lg-block">
            <?if(!empty($arItem["PREVIEW_PICTURE_SRC"])):?>
                <img src="<?=$arItem["PREVIEW_PICTURE_SRC"]?>"
                     width="100"
                     class="img-responsive">
            <?else:?>
                <img src="<?=$this->GetFolder()?>/images/no_photo.png"
                     width="100"
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
    <td class="text-nowrap align-middle text-center">
        <?=number_format($arItem["PRICE"], 2, '.', ' ')?>
    </td>

    <?
    $i = 0;
    $value = 0;
    $data_value = 0;
    foreach ($arParams["COLUMNS_HEADER"] as $code):
        $i++;

        switch ($code){
            case "PROPERTY_LHW_ctn":
                $value = $arItem["PROPERTY_Master_CTN_CBM_VALUE"];
                $value = round($value, 2);
                break;
            case "PROPERTY_DISPLAY_COUNT":
                $value = $arItem["PROPERTY_Master_CTN_PCS_VALUE"];
                $arItem["QUANTITY"] = $arItem["QUANTITY"] / $value;
                $arItem["PRICE"] = $arItem["PRICE"] * $value;
                //$arItem["SUM_VALUE"] = $arItem["SUM_VALUE"] * $value;
                break;
            case "PROPERTY_Master_CTN_PCS":
                $value = $arItem["QUANTITY"] * $arItem["PROPERTY_Master_CTN_PCS_VALUE"];
                $value = round($value, 1);
                break;
            case "PROPERTY_Master_CTN_CBM":
                $data_value = $arItem["PROPERTY_Master_CTN_CBM_VALUE"];
                $value = $arItem["QUANTITY"] * $data_value;
                $value = round($value, 2);
                break;
            case "PROPERTY_WEIGHT":
                $value = 0;
                $data_value = $arItem["PROPERTY_WEIGHT_VALUE"];
                if($data_value > 0){
                    $value = $arItem["QUANTITY"] * $data_value;
                    $value = round($value, 0);
                }
                break;
        }
        ?>
        <?if($i === 3):?>
        <td class="align-middle">
            <div class="input-group"
                 data-currency="<?=$arResult["CURRENCIES_FORMAT"][$arItem["CURRENCY"]]?>"
                 data-sum="<?=$arItem["SUM_VALUE"]?>"
                 data-price="<?=$arItem["PRICE"]?>"
                 data-space="<?=$arItem["PROPERTY_Master_CTN_CBM_VALUE"]?>"
                 data-pcs="<?=$arItem["PROPERTY_Master_CTN_PCS_VALUE"]?>"
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

        <td class="text-nowrap align-middle <?=$code=="PROPERTY_WEIGHT"? "text-right" : "text-center"?> d-none d-lg-table-cell cell-<?=$code?>"
            data-value="<?=$data_value ? $data_value : $value?>">
            <?=$value;?>
        </td>
    <?endforeach;?>

    <td class="text-nowrap align-middle text-16 ceil-sum text-right">
        <b class="item-sum">
            <?=number_format($arItem["SUM_VALUE"], 0, '.', ' ')?>
        </b>
    </td>
    <td class="align-middle">

        <div onclick="saleBasket.toggleFavorite(this, <?=$arItem["PRODUCT_ID"]?>)"
            <?if(in_array($arItem["PRODUCT_ID"], $arResult["USER_FAVORITE"])):?>
                class="item-favorite-fill"
            <?else:?>
                class="item-favorite-icon"
            <?endif;?>
             title="<?=Loc::getMessage("BASKET_ITEM_FAVORITE")?>"></div>

        <a onclick="saleBasket.delete(this)"
           data-title="<?=Loc::getMessage("BASKET_ITEM_DELETE_CONFIRM_DELETE")?>"
           data-delete="<?=Loc::getMessage("BASKET_ITEM_DELETE_CONFIRM")?>"
           data-cancel="<?=Loc::getMessage("BASKET_ITEM_DELETE_CANCEL")?>"
           title="<?=Loc::getMessage("BASKET_ITEM_DELETE")?>"
           class="item-trash-icon text-primary"></a>
    </td>
</tr>
