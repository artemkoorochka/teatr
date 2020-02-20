<?
/**
 * https://github.com/PHPOffice/PHPExcel/blob/develop/Documentation/markdown/Overview/04-Configuration-Settings.md
 * system/7studio/excel/git/PHPExcel/Examples/24readfilter.php
 */
if (ini_get('mbstring.func_overload') & 2) {
    ini_set("mbstring.func_overload", 0);
}

global $objReader;

$arParams = array();
$arResult = array();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once $_SERVER["DOCUMENT_ROOT"] . '/system/7studio/excel/git/PHPExcel/Classes/PHPExcel.php';
$arParams = array(
    "FILE" => $_SERVER["DOCUMENT_ROOT"] . "/system/7studio/excel/img/test.xlsx",
    "IMPORT_DATA_VENDOR1_START" => 3
);

$objReader = PHPExcel_IOFactory::createReaderForFile($arParams["FILE"]);
$objPHPExcel = $objReader->load($arParams["FILE"]);
$objDrawing = new PHPExcel_Worksheet_Drawing();


foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {

    $rows = array();
    switch ($worksheet->getTitle()){
        case "Загрузка":

            foreach ($worksheet->getDrawingCollection() as $drawing) {


                ////////////
                //for XLSX format
                $string = $drawing->getCoordinates();
                $coordinate = PHPExcel_Cell::coordinateFromString($string);
                if ($drawing instanceof PHPExcel_Worksheet_Drawing) {
                    $filename = $drawing->getPath();
                    $drawing->getDescription();

                    d($filename);

                    d(filetype($drawing->getDescription()));

                    // copy($filename, $drawing->getDescription());

                }
                /////////

            }


            break;
    }

}