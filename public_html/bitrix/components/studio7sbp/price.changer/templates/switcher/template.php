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
<div class="price-switcher">

    <?foreach ($arResult["PRICES"] as $key=>$price):?>

        <?if($arParams["CURRENT"] == $price["ID"]):?>
            <div class="active-flag active-flag-<?=$key?>"></div>
        <?endif;?>


        <a href="<?=$arParams["PATH"]?>?price=<?=$price["ID"]?>"
           onclick="return priceSwitcher.a(this, <?=$key?>)">
            <?=Loc::getMessage("PRICE_NAME_" . $price["NAME"]) ? Loc::getMessage("PRICE_NAME_" . $price["NAME"]) : $price["NAME"]?>
        </a>

    <?endforeach;?>

</div>