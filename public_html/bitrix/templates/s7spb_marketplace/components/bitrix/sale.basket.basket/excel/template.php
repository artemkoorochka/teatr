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
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

$string = "Vasja.xlsx";
//$string = mb_convert_encoding($string, "UTF-8", "Windows-1251");

$objPHPExcel->getActiveSheet()
    //    ->setCellValue('A1', 'Hello')
    ->setCellValueExplicit("B1", $string, PHPExcel_Cell_DataType::TYPE_STRING);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

?>