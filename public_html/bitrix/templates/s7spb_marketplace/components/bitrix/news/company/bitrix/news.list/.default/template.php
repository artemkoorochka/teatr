<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$this->setFrameMode(true);
?>


<?foreach ($arResult["ALPHAVITE"] as $alphavite):?>
    <?foreach ($alphavite as $letter):?>
        <?if(is_set($letter["ITEMS"])):?>
            <a class="btn mt-1" href="#brand_<?=$letter["LETTER"]?>"><?=$letter["LETTER"]?></a>
        <?else:?>
            <a class="btn mt-1 disabled"><?=$letter["LETTER"]?></a>
        <?endif;?>
    <?endforeach;?>
    <br>
<?endforeach;?>
<br>


<?foreach ($arResult["ALPHAVITE"] as $alphavite):?>
    <?foreach ($alphavite as $letter):?>
        <?if(!empty($letter["ITEMS"])):?>

            <div class="s7sbp--marketplace--section-title" id="brand_<?=$letter["LETTER"]?>">
                <span><?=$letter["LETTER"]?></span>
            </div>

            <?foreach($letter["ITEMS"] as $arItem):?>
                <a href="<?echo $arItem["DETAIL_PAGE_URL"]?>" class="text-18"><b><?echo $arItem["NAME"]?></b></a><br />
            <?endforeach;?>
            <br>

        <?endif;?>
    <?endforeach;?>

<?endforeach;?>




