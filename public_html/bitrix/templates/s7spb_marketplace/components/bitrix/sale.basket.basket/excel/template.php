<?
/**
 * @var array $arParams
 * @var array $arResult
 * @var string $templateFolder
 * @var string $templateName
 * @var CMain $APPLICATION
 * @var CBitrixBasketComponent $component
 * @var CBitrixComponentTemplate $this
 * @var array $giftParameters
 */

use Bitrix\Main\Localization\Loc;
Loc::loadLanguageFile(__FILE__);

$arParams["ALFAVITE"] = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q");

$arParams["MATRIX"] = array(
    "START" => array(
        "X" => "B",
        "Y" => 3,
        "ADRESS" => "B3"
    ),
    "CELL" => array(
        "X" => "B",
        "Y" => 3,
        "ADRESS" => "B3"
    )
);


$objPHPExcel = new PHPExcel();

include "basket.total.php";
include "basket.head.php";

foreach ($arResult["ITEMS"]["AnDelCanBuy"] as $key => $arItem){
    include "basket.item.php";
}
include "basket.total.php";

$i = 3;
//$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(24)->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], Loc::getMessage("BASKET_TOTAL"), PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet()->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], Loc::getMessage("BASKET_TOTAL"), PHPExcel_Cell_DataType::TYPE_STRING);
$i++;
$objPHPExcel->getActiveSheet()
    ->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], number_format($arResult["allSum"], 0, '.', ' '), PHPExcel_Cell_DataType::TYPE_STRING)
    ->getDefaultStyle()->getFont()->setSize(24);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

?>