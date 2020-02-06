<?
/**
 * @var array $arParams
 */

use Bitrix\Highloadblock;
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

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
 * Format display properties
 */
$arResult["DISPLAY_PROPERTIES"]["Master_CTN_SIZE"]["DISPLAY_VALUE"] = str_replace("см", "", $arResult["DISPLAY_PROPERTIES"]["Master_CTN_SIZE"]["DISPLAY_VALUE"]);
$arResult["DISPLAY_PROPERTIES"]["LHW_ctn"]["DISPLAY_VALUE"] = str_replace("см", "", $arResult["DISPLAY_PROPERTIES"]["LHW_ctn"]["DISPLAY_VALUE"]);

/**
 * Английские названия нескольких полей в карточке товара (перевод должен быть как заголовка поля так и значения поля (если необходимо))
 */
if($arParams["LANGUAGE_MODE"] == "rus"){
    if(is_set($arResult["DISPLAY_PROPERTIES"]["MOQ"])){
        $arResult["DISPLAY_PROPERTIES"]["MOQ"]["NAME"] = $arResult["DISPLAY_PROPERTIES"]["MOQ"]["XML_ID"];
    }
    if(is_set($arResult["DISPLAY_PROPERTIES"]["Master_CTN_PCS"])){
        $arResult["DISPLAY_PROPERTIES"]["Master_CTN_PCS"]["NAME"] = $arResult["DISPLAY_PROPERTIES"]["Master_CTN_PCS"]["XML_ID"];
    }
    if(is_set($arResult["DISPLAY_PROPERTIES"]["Master_CTN_CBM"])){
        $arResult["DISPLAY_PROPERTIES"]["Master_CTN_CBM"]["NAME"] = $arResult["DISPLAY_PROPERTIES"]["Master_CTN_CBM"]["XML_ID"];
    }
    if(is_set($arResult["DISPLAY_PROPERTIES"]["WEIGHT"])){
        $arResult["DISPLAY_PROPERTIES"]["WEIGHT"]["NAME"] = $arResult["DISPLAY_PROPERTIES"]["WEIGHT"]["XML_ID"];
    }
    if(is_set($arResult["DISPLAY_PROPERTIES"]["FACTORY"])){
        $arResult["DISPLAY_PROPERTIES"]["FACTORY"]["NAME"] = $arResult["DISPLAY_PROPERTIES"]["FACTORY"]["XML_ID"];
    }
    if(is_set($arResult["DISPLAY_PROPERTIES"]["PACKAGE"])){
        $arResult["DISPLAY_PROPERTIES"]["PACKAGE"]["NAME"] = $arResult["DISPLAY_PROPERTIES"]["PACKAGE"]["XML_ID"];
    }
    if(is_set($arResult["DISPLAY_PROPERTIES"]["INNER_BOX"])){
        $arResult["DISPLAY_PROPERTIES"]["INNER_BOX"]["NAME"] = $arResult["DISPLAY_PROPERTIES"]["INNER_BOX"]["XML_ID"];
    }
    if(is_set($arResult["DISPLAY_PROPERTIES"]["CATEGORY"])){

        $ENTITY_ID = 11;
        $hlblock = Highloadblock\HighloadBlockTable::getById($ENTITY_ID)->fetch();
        $hlEntity = Highloadblock\HighloadBlockTable::compileEntity($hlblock);
        $entDataClass = $hlEntity->getDataClass();
        $sTableID = 'tbl_'.$hlblock['TABLE_NAME'];

        $rsData = $entDataClass::getList(array(
            "select" => array('ID', 'UF_CATEGORY'),
            "filter" => array("UF_NAME" => $arResult["DISPLAY_PROPERTIES"]["CATEGORY"]["VALUE"]),
        ));
        $rsData = new CDBResult($rsData, $sTableID);
        if($arRes = $rsData->Fetch()){
            $arResult["DISPLAY_PROPERTIES"]["CATEGORY"]["DISPLAY_VALUE"] = $arRes["UF_CATEGORY"];
        }
    }
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
