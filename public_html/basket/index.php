<?
/**
 * @var CMain $APPLICATION
 */

# mode switcher
$excelMode = $_REQUEST["mode"] == "ex";
$template = "bootstrap.4";
if($excelMode){
	$template = "excel";
	// check mbstring.func_overload
	if (ini_get('mbstring.func_overload') & 2) {
		ini_set("mbstring.func_overload", 0);
	}
	// formating prolog
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: filename=".basename($APPLICATION->GetCurPage(), ".php").".xls");
	header('Cache-Control: max-age=0'); //no cache
	require $_SERVER["DOCUMENT_ROOT"] . '/system/7studio/excel/git/PHPExcel/Classes/PHPExcel.php';
}
else{
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
	$APPLICATION->SetTitle("Ваша корзина");
	$APPLICATION->SetPageProperty('SectionClassRight', "s7sbp--marketplace--basket");
}

# price switcher
$price = $_REQUEST["price"];
if (isset($_SESSION["SALE_USER_ID"]) && $price > 0)
{
	$_SESSION["USER_CATALOG_GROUP"] = $price;

	if($price == 6){
		$_SESSION["SALE_USER_CURRENCY"] = "USD";
	}else{
		unset($_SESSION["SALE_USER_CURRENCY"]);
	}
}
?>

<?$APPLICATION->IncludeComponent(
	"bitrix:sale.basket.basket",
	$template,
	array(
		"COLUMNS_HEADER" => array(
			"PROPERTY_LHW_ctn",
			"PROPERTY_DISPLAY_COUNT",
			"PROPERTY_Master_CTN_PCS",
			"PROPERTY_Master_CTN_CBM",
			"PROPERTY_WEIGHT"
		),
		"COLUMNS_LIST" => array(
			"NAME",
			"PREVIEW_PICTURE",
			"DELETE",
			"PRICE",
			"QUANTITY",
			"SUM",
		),
		"COLUMNS_LIST_EXT" => array(
			"PROPERTY_DISPLAY_COUNT",
			"PROPERTY_Master_CTN_PCS",
			"PROPERTY_LHW_ctn",
			"PROPERTY_Master_CTN_CBM",
			//"PROPERTY_Master_CTN_SIZE",
			"PROPERTY_WEIGHT",
			"PROPERTY_MOQ",
			//"PROPERTY_L_ctn",
			//"PROPERTY_H_ctn",
			//"PROPERTY_W_ctn"
		),
		"OFFERS_PROPS" => array(
		),
		"PATH_TO_ORDER" => SITE_DIR."order/",
		"PATH_TO_BASKET" => SITE_DIR."basket/",
		"HIDE_COUPON" => "N",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
		"USE_PREPAYMENT" => "N",
		"SET_TITLE" => "N",
		"AJAX_MODE_CUSTOM" => "Y",
		"SHOW_MEASURE" => "Y",
		"PICTURE_WIDTH" => "100",
		"PICTURE_HEIGHT" => "100",
		"SHOW_FULL_ORDER_BUTTON" => "Y",
		"SHOW_FAST_ORDER_BUTTON" => "Y",
		"COMPONENT_TEMPLATE" => "bootstrap_v4",
		"QUANTITY_FLOAT" => "N",
		"ACTION_VARIABLE" => "action",
		"TEMPLATE_THEME" => "blue",
		"AUTO_CALCULATION" => "Y",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"USE_GIFTS" => "Y",
		"GIFTS_PLACE" => "BOTTOM",
		"GIFTS_BLOCK_TITLE" => "Выберите один из подарков",
		"GIFTS_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"GIFTS_PRODUCT_PROPS_VARIABLE" => "prop",
		"GIFTS_SHOW_OLD_PRICE" => "Y",
		"GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
		"GIFTS_SHOW_NAME" => "Y",
		"GIFTS_SHOW_IMAGE" => "Y",
		"GIFTS_MESS_BTN_BUY" => "Выбрать",
		"GIFTS_MESS_BTN_DETAIL" => "Подробнее",
		"GIFTS_PAGE_ELEMENT_COUNT" => "10",
		"GIFTS_CONVERT_CURRENCY" => "N",
		"GIFTS_HIDE_NOT_AVAILABLE" => "N",
		"DEFERRED_REFRESH" => "N",
		"USE_DYNAMIC_SCROLL" => "Y",
		"SHOW_FILTER" => "N",
		"SHOW_RESTORE" => "Y",

		"COLUMNS_LIST_MOBILE" => array(
			0 => "PREVIEW_PICTURE",
			1 => "DISCOUNT",
			2 => "DELETE",
			3 => "DELAY",
			4 => "SUM",
		),
		"TOTAL_BLOCK_DISPLAY" => array(
			0 => "bottom",
		),
		"DISPLAY_MODE" => "extended",
		"PRICE_DISPLAY_MODE" => "N",
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"DISCOUNT_PERCENT_POSITION" => "bottom-right",
		"PRODUCT_BLOCKS_ORDER" => "props,sku,columns",
		"USE_PRICE_ANIMATION" => "Y",
		"LABEL_PROP" => array(
		),
		"CORRECT_RATIO" => "Y",
		"COMPATIBLE_MODE" => "Y",
		"EMPTY_BASKET_HINT_PATH" => "/",
		"ADDITIONAL_PICT_PROP_34" => "-",
		"ADDITIONAL_PICT_PROP_35" => "-",
		"BASKET_IMAGES_SCALING" => "adaptive",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"ADDITIONAL_PICT_PROP_2" => "-",
		"ADDITIONAL_PICT_PROP_3" => "-",
		"ADDITIONAL_PICT_PROP_4" => "-"
	),
	false
);?>

<?
if($excelMode){
	die;
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>