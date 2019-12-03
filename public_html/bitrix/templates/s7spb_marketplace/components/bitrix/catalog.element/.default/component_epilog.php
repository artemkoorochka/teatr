<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
__IncludeLang($_SERVER["DOCUMENT_ROOT"].$templateFolder."/lang/".LANGUAGE_ID."/template.php");

global $brandInfo;
$brandInfo = $arResult["FACTORY"];

if($arResult["ID"])
	$GLOBALS['addChainItemElementName'] = $arResult["NAME"];
?>