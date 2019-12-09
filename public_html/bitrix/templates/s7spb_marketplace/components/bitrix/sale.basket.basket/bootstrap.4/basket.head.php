<?php

use Bitrix\Main\Localization\Loc;


?>
<tr class="table-bordered border-bottom-0 text-center">
    <th colspan="2" class="align-middle"><?=Loc::getMessage("BASKET_ITEMS_HEAD_NAME")?></th>
    <th class="align-middle"><?=Loc::getMessage("BASKET_ITEMS_HEAD_PRICE")?></th>
    <?
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
    <th colspan="2" class="align-middle"><?=Loc::getMessage("BASKET_ITEMS_HEAD_SUM")?></th>
</tr>