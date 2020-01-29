<?php

use Bitrix\Main\Localization\Loc;


?>
<tr class="table-bordered border-bottom-0 text-center">
    <th colspan="2" class="align-middle"><?=Loc::getMessage("BASKET_ITEMS_HEAD_NAME")?></th>
    <?if($arResult["CURRENCY"] == "USD"):?>
        <th class="align-middle"><?=Loc::getMessage("BASKET_ITEMS_HEAD_PRICE", array("CURRENCY" => str_replace("#", "", $arResult["CURRENCIES_FORMAT"][$arResult["CURRENCY"]])))?></th>
    <?else:?>
        <th class="align-middle"><?=Loc::getMessage("BASKET_ITEMS_HEAD_PRICE_NDS", array("CURRENCY" => str_replace("#", "", $arResult["CURRENCIES_FORMAT"][$arResult["CURRENCY"]])))?></th>
    <?
    endif;
    $i = 0;
    foreach ($arParams["COLUMNS_HEADER"] as $value):
        $i++;
    ?>
        <?if($i===3):?>
            <th class="align-middle"><?=Loc::getMessage("BASKET_ITEMS_HEAD_QUANTITY")?></th>
        <?endif;?>
        <?if(strpos($value, 'PROPERTY') !== false):?>
            <td class="align-middle d-none d-lg-table-cell"><?=Loc::getMessage("BASKET_ITEM_" . $value)?></td>
        <?endif;?>
    <?endforeach;?>

    <?if($arResult["CURRENCY"] == "USD"):?>
        <th colspan="2" class="align-middle"><?=Loc::getMessage("BASKET_ITEMS_HEAD_SUM", array("CURRENCY" => str_replace("#", "", $arResult["CURRENCIES_FORMAT"][$arResult["CURRENCY"]])))?></th>
    <?else:?>
        <th colspan="2" class="align-middle"><?=Loc::getMessage("BASKET_ITEMS_HEAD_SUM_NDS", array("CURRENCY" => str_replace("#", "", $arResult["CURRENCIES_FORMAT"][$arResult["CURRENCY"]])))?></th>
    <?endif;?>
</tr>