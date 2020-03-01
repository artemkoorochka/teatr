<?php
/**
 * @status: dev
 */
$_SERVER["DOCUMENT_ROOT"] = "/home/c/ca01826813/teatr-msk.ru/public_html/";
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
\Bitrix\Main\UI\Extension::load("ui.bootstrap4");
$APPLICATION->ShowHead();


$arParams = [
    "COMMANDS" => [
        "catalog::getList"
    ]
];

d($arParams);

?>

