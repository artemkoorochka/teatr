<?
use Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
Loc::loadLanguageFile(__FILE__);
Loader::includeModule("iblock");

/**
 *
 * This file modifies result for every request (including AJAX).
 * Use it to edit output result for "{{ mustache }}" templates.
 *
 * @var array $result
 */

$result["MARKET_LIST"] = array();
$result["SPACE"] = array(
    "TOTAL" => array(
        "LHW_ctn" => 0,
        "WEIGHT" => 0
    ),
    "NUMBER_ROUND" => 2
);


foreach ($this->basketItems as $arItem)
{
    $element = CIBlockElement::GetList(array(), array("ID" => $arItem["PRODUCT_ID"]), false, false, array(
        "ID",
        "IBLOCK_ID",
        "PROPERTY_H_COMPANY.ID",
        "PROPERTY_H_COMPANY.NAME",
        "PROPERTY_H_COMPANY.PREVIEW_TEXT"
    ));
    if($element = $element->GetNext()){

        if($element["PROPERTY_H_COMPANY_ID"] > 0){
            if(empty($result["MARKET_LIST"][$element["PROPERTY_H_COMPANY_ID"]])){
                $result["MARKET_LIST"][$element["PROPERTY_H_COMPANY_ID"]] = array(
                    "NAME" => $element["PROPERTY_H_COMPANY_NAME"],
                    "ITEMS" => array()
                );
            }

            $result["MARKET_LIST"][$element["PROPERTY_H_COMPANY_ID"]]["ITEMS"][] = $element["ID"];

        }

    }


    // total space
    $result["SPACE"]["TOTAL"]["LHW_ctn"] += round($arItem["PROPERTY_Master_CTN_CBM_VALUE"] * $arItem["QUANTITY"], $result["SPACE"]["NUMBER_ROUND"]);;
    $result["SPACE"]["TOTAL"]["WEIGHT"] += round($arItem["PROPERTY_WEIGHT_VALUE"] * $arItem["QUANTITY"], $result["SPACE"]["NUMBER_ROUND"]);


}

// CURRENCIES FORMAT
$result["CURRENCIES_FORMAT"] = array();
foreach ($result["CURRENCIES"] as $currency){
    $result["CURRENCIES_FORMAT"][$currency["CURRENCY"]] = $currency["FORMAT"]["FORMAT_STRING"];
}

/**
 * @param $num
 * @param $vars
 * @param string $before
 * @param string $after
 * @return string|void
 */
function declension( $num, $vars, $before = '', $after = '' )
{
    if( $num == 0 ) // если число равно нулю
        return; // ничего не возвращаем
    $normal_num = $num; // сохраняем число в исходном виде
    $num = $num % 10; // определяем цифру, стоящую после десятка
    if( $num == 1 ) // если это единица
    {
        $num = $normal_num . ' ' . $vars[0];
    }else if( $num > 1 && $num < 5 ) // если это 2, 3, 4
    {
        $num = $normal_num . ' ' . $vars[1];
    }else
    {
        $num = $normal_num . ' ' . $vars[2];
    }
    return $before . $num . $after; // возвращаем строку
}