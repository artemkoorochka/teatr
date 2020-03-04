<?
use Bitrix\Main\Loader;

// <editor-fold defaultstate="collapsed" desc=" # Preparato">

if (ini_get('mbstring.func_overload') & 2) {
    ini_set("mbstring.func_overload", 0);
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: filename=master.file.xlsx");
header('Cache-Control: max-age=0'); //no cache

require $_SERVER["DOCUMENT_ROOT"] . '/system/7studio/excel/git/PHPExcel/Classes/PHPExcel.php';
$request = Application::getInstance()->getContext()->getRequest();
$arParams = [
    "MODULES" => ["iblock"]
];
$arResult = [

];

if(!empty($arParams["MODULES"])){
    foreach ($arParams["MODULES"] as $module){
        Loader::includeModule($module);
    }
}

$objPHPExcel = new PHPExcel();
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc=" # Output">
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
// </editor-fold>
?>