<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if(!empty($arResult["MORE_PHOTO"])) {
    foreach ($arResult["MORE_PHOTO"] as $key=>$file){
        $arResult['MORE_PHOTO'][$key] = array_merge(
            $file, array(
                "BIG" => array('src' => $file["SRC"]),
                "SMALL" => CFile::ResizeImageGet($file["ID"], array("width" => 340, "height" => 340), BX_RESIZE_IMAGE_PROPORTIONAL, true),
                "THUMB" => CFile::ResizeImageGet($file["ID"], array("width" => 50, "height" => 50), BX_RESIZE_IMAGE_EXACT, true),
            )
        );
    }
}

if(is_array($arResult["DETAIL_PICTURE"])){
    $arResult["MORE_PHOTO"][] = array_merge(
        $arResult["DETAIL_PICTURE"], array(
            "BIG" => array('src' => $arResult["DETAIL_PICTURE"]["SRC"]),
            "SMALL" => CFile::ResizeImageGet($arResult["DETAIL_PICTURE"]["ID"], array("width" => 340, "height" => 340), BX_RESIZE_IMAGE_PROPORTIONAL, true),
            "THUMB" => CFile::ResizeImageGet($arResult["DETAIL_PICTURE"]["ID"], array("width" => 50, "height" => 50), BX_RESIZE_IMAGE_EXACT, true),
        )
    );
}

if(empty($arResult["MORE_PHOTO"])) {
    $strEmptyPreview = SITE_TEMPLATE_PATH.'/images/no_photo_medium.png';
    if (file_exists($_SERVER['DOCUMENT_ROOT'].$strEmptyPreview))
    {
        $arSizes = getimagesize($_SERVER['DOCUMENT_ROOT'].$strEmptyPreview);
        if (!empty($arSizes))
        {
            $arEmptyPreview = array(
                'SRC' => $strEmptyPreview,
                'WIDTH' => (int)$arSizes[0],
                'HEIGHT' => (int)$arSizes[1]
            );
        }
        unset($arSizes);
    }
    $arResult['MORE_PHOTO'][] = $arEmptyPreview;
}

/**
 * Send out brand property
 */
$cp = $this->__component;
if (is_object($cp))
{
    if(!empty($arResult["DISPLAY_PROPERTIES"]["FACTORY"]["VALUE"])){
        $cp->arResult["FACTORY"] = $arResult["DISPLAY_PROPERTIES"]["FACTORY"];
    }
    elseif(!empty($arResult["PROPERTIES"]["FACTORY"]["VALUE"])){
        $cp->arResult["FACTORY"] = $arResult["PROPERTIES"]["FACTORY"];
    }

    $cp->SetResultCacheKeys(array("FACTORY")); //cache keys in $arResult array
}