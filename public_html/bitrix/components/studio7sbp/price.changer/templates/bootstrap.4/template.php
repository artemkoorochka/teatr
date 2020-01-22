<?
/**
 * @var CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */
use Bitrix\Main\Localization\Loc;
Loc::loadLanguageFile(__FILE__);
?>
<i class="pl-5 pr-2"><?=Loc::getMessage("PRICE_CHANGER")?></i>
<div class="btn-group price-changer">
    <?foreach ($arResult["PRICES"] as $price):?>
        <a href="<?=$arParams["PATH"]?>?price=<?=$price["ID"]?>"
           class="btn <?=$arParams["CURRENT"]==$price["ID"] ? "btn-primary" : "btn-secondary"?>">
            <?=Loc::getMessage("PRICE_NAME_" . $price["NAME"]) ? Loc::getMessage("PRICE_NAME_" . $price["NAME"]) : $price["NAME"]?>
        </a>
    <?endforeach;?>
</div>