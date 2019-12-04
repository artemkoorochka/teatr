<?
/**
 * @var array $arResult
 */

use Bitrix\Main\Localization\Loc;
?>
<div class="col text-right"><div class="h3"><?=Loc::getMessage("BASKET_TOTAL_PARAMS")?></div></div>
<div class="col-auto total-col text-center">
    <b class="h3 basket-total-space"><?=round($arResult["SPACE"]["TOTAL"]["LHW_ctn"])?></b>
    <b><?=Loc::getMessage("BASKET_MEASURE_SPACE")?></b>
</div>
<div class="col-auto total-col text-center">
    <b class="h3 basket-total-wight"><?=round($arResult["SPACE"]["TOTAL"]["WEIGHT"])?></b>
    <b><?=Loc::getMessage("BASKET_MEASURE_WEIGHT")?></b>
</div>
<div class="col-auto total-col text-center">
    <b class="h3 basket-total-sum"><?=$arResult["allSum"]?></b>
    <b class="h3"><?=str_replace("#", "", $arResult["CURRENCIES_FORMAT"][$arResult["CURRENCY"]])?></b>
</div>