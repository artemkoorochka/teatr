<?
/**
 * @var CMain $APPLICATION
 */

use Bitrix\Main\Loader,
    Bitrix\Iblock\ElementTable;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->RestartBuffer();

$arParams = array(
    "MODULE" => "iblock"
);

Loader::includeModule($arParams["MODULE"]);

