<?

use Bitrix\Main\Localization\Loc;
Loc::loadLanguageFile(__FILE__);

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->SetViewTarget('company_left_area');
?>

    <?if(is_array($arResult["PREVIEW_PICTURE"])):?>
        <div class="bordered">
            <img src="<?=$arResult["PREVIEW_PICTURE"]["SRC"]?>"
                 width="<?=$arResult["PREVIEW_PICTURE"]["WIDTH"]?>"
                 height="<?=$arResult["PREVIEW_PICTURE"]["HEIGHT"]?>"
                 title="<?=$arResult["PREVIEW_PICTURE"]["TITLE"]?>"
                 alt="..." />
        </div>
    <?endif;?>

    <span class="p-3"><?=$arResult["NAME"]?></span>

    <div class="s7sbp--marketplace--catalog-element-detail-product--header-line--item--wish-list d-inline-block
        <?if(in_array($arResult["ID"], $arParams["USER_FAVORITES"])):?>
            active
        <?endif;?>
        cursor"
         data-title="<?=Loc::getMessage("COMPANY_WISH_IN")?>"
         data-title-in="<?=Loc::getMessage("COMPANY_WISH_OUT")?>"
         onclick="tradeMark.wish(this)"
         data-item-id="<?=$arResult["ID"]?>">
        <?
        if(in_array($arResult["ID"], $arParams["USER_FAVORITES"])){
            echo Loc::getMessage("COMPANY_WISH_OUT");
        }else{
            echo Loc::getMessage("COMPANY_WISH_IN");
        }
        ?>
    </div>

<?$this->EndViewTarget(); ?>





<div class="s7sbp--marketplace--section-title">
    <span><?=Loc::getMessage("COMPANY_TITLE", array("NAME" => $arResult["NAME"]))?></span>
    <span class="s7sbp--marketplace--section-item-type">
				<!--noindex-->
				<a class="s7sbp--marketplace--section-item-type--icon card<?=$arParams["DISPLAY_TYPE"] == "card" ? " active" : ""?>" href="<?=$APPLICATION->GetCurPageParam('display=card', array('display'));?>"></a>
				<a class="s7sbp--marketplace--section-item-type--icon list<?=$arParams["DISPLAY_TYPE"] == "line" ? " active" : ""?>" href="<?=$APPLICATION->GetCurPageParam('display=line', array('display'));?>"></a>
        <!--/noindex-->
			</span>
</div>
