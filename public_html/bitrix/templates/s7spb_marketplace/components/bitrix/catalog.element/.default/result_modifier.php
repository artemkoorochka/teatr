<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();


$arEmptyPreview = false;
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
unset($strEmptyPreview);

if(empty($arResult["MORE_PHOTO"]) && !empty($arResult['PROPERTIES'][$arParams['ADD_PICT_PROP']]['VALUE'])) {
	foreach ($arResult['PROPERTIES'][$arParams['ADD_PICT_PROP']]['VALUE'] as $file) {
		$file = \CFile::GetFileArray($file);
		if (is_array($file)){
			$arResult['MORE_PHOTO'][] = array_merge(
				$file, array(
					"BIG" => array('src' => CFile::GetPath($file["ID"])),
					"SMALL" => CFile::ResizeImageGet($file["ID"], array("width" => 340, "height" => 340), BX_RESIZE_IMAGE_PROPORTIONAL, true, array()),
					"THUMB" => CFile::ResizeImageGet($file["ID"], array("width" => 50, "height" => 50), BX_RESIZE_IMAGE_PROPORTIONAL, true, array()),
				)
			);
		}
	}
}
if(empty($arResult["MORE_PHOTO"])) {
	$arResult['MORE_PHOTO'][] = $arEmptyPreview;
}

if((int)$arResult["PROPERTIES"]["H_PRICE_DISCOUNT"]["VALUE"] > 0 && $arResult["PROPERTIES"]["H_PRICE_DISCOUNT"]["VALUE"] < $arResult["MIN_PRICE"]["VALUE"]) {

	$discountPrice = $arResult["PROPERTIES"]["H_PRICE_DISCOUNT"]["VALUE"];
	$discountPercent = (100 - round(($discountPrice * 100)/$arResult["MIN_PRICE"]["VALUE"], 0));
	$discountDiff = $arResult["MIN_PRICE"]["VALUE"] - $discountPrice;
	
	$discountDiffFormatted = CurrencyFormat($discountDiff, $arResult["MIN_PRICE"]["CURRENCY"]);
	$discountPriceFormatted = CurrencyFormat($discountPrice, $arResult["MIN_PRICE"]["CURRENCY"]);

	$arResult["MIN_PRICE"]["DISCOUNT_VALUE_VAT"] = $discountPrice;
	$arResult["MIN_PRICE"]["DISCOUNT_VALUE_NOVAT"] = $discountPrice;
	$arResult["MIN_PRICE"]["ROUND_VALUE_VAT"] = $discountPrice;
	$arResult["MIN_PRICE"]["ROUND_VALUE_NOVAT"] = $discountPrice;
	$arResult["MIN_PRICE"]["UNROUND_DISCOUNT_VALUE"] = $discountPrice;
	$arResult["MIN_PRICE"]["DISCOUNT_VALUE"] = $discountPrice;
	$arResult["MIN_PRICE"]["DISCOUNT_DIFF"] = $discountDiff;
	$arResult["MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"] = $discountPercent;

	$arResult["MIN_PRICE"]["PRINT_DISCOUNT_VALUE_NOVAT"] = $discountPriceFormatted;
	$arResult["MIN_PRICE"]["PRINT_DISCOUNT_VALUE_VAT"] = $discountPriceFormatted;
	$arResult["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"] = $discountPriceFormatted;
	$arResult["MIN_PRICE"]["PRINT_DISCOUNT_DIFF"] = $discountDiffFormatted;
}


$arResult["DELIVERY_TEXT"] = \Studio7spb\Marketplace\CMarketplaceOptions::getInstance()->getOption("settings_product_delivery_text");

if($arResult["PROPERTIES"]["H_COMPANY"]["VALUE"]) {
	$arResult["COMPANY_INFO"] = \Studio7spb\Marketplace\CMarketplaceSeller::getCompanyById($arResult["PROPERTIES"]["H_COMPANY"]["VALUE"]);
}

/**
 * Send out brand property
 */
$cp = $this->__component;
if (is_object($cp))
{
    if($arResult["DISPLAY_PROPERTIES"]["H_COMPANY"]["VALUE"] > 0){
        $cp->arResult["H_COMPANY"] = $arResult["DISPLAY_PROPERTIES"]["H_COMPANY"];
    }
    elseif($arResult["PROPERTIES"]["H_COMPANY"]["VALUE"] > 0){
        $cp->arResult["H_COMPANY"] = $arResult["PROPERTIES"]["H_COMPANY"];
    }




    $cp->SetResultCacheKeys(array("H_COMPANY")); //cache keys in $arResult array
}