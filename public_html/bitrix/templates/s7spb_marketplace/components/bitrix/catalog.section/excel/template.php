<?
// <editor-fold defaultstate="collapsed" desc=" # Preparacia">
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
// </editor-fold>

$objPHPExcel = new PHPExcel();

// <editor-fold defaultstate="collapsed" desc=" # Stiliziren">
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(80);
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc=" # Cicle items">
if(!empty($arResult['ITEMS'])){
    $i = 1;
    $i++;
    $i++;
    foreach ($arResult['ITEMS'] as $arItem){
        $i++;
        $objPHPExcel->getActiveSheet()->getRowDimension($arParams["MATRIX"]["CELL"]["Y"])->setRowHeight(22);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit("B" . $i, $arItem["NAME"], PHPExcel_Cell_DataType::TYPE_STRING);

        $arParams["MATRIX"]["CELL"]["Y"]++;
        $arParams["MATRIX"]["CELL"]["X"] = $arParams["MATRIX"]["START"]["X"];
        $arParams["MATRIX"]["CELL"]["ADRESS"] = $arParams["MATRIX"]["CELL"]["X"] . $arParams["MATRIX"]["CELL"]["Y"];
    }
}
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc=" # Output">
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
// </editor-fold>