<?
/**
 * https://dev.1c-bitrix.ru/api_help/currency/developer/ccurrencyrates/ccurrencyrates__convertcurrency.930a5544.php
 * @var CMain $APPLICATION
 */
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->RestartBuffer();

if (CModule::IncludeModule('currency')) {

        $val = 100; // сумма в USD
        $newval = CCurrencyRates::ConvertCurrency($val, "USD", "RUB");
        d($val." USD = ".$newval." EUR");

}