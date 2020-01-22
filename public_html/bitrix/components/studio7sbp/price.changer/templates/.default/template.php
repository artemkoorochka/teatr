<?

/**
 * @var CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */
?>

<div class="btn-group price-changer">
    <?foreach ($arResult["PRICES"] as $price):?>
        <a href="<?=$arParams["PATH"]?>?price=<?=$price["ID"]?>"
           class="btn <?=$arParams["CURRENT"]==$price["ID"] ? "btn-danger" : "btn-secondary"?>"><?=$price["NAME_LANG"]?></a>
    <?endforeach;?>
</div>