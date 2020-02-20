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
                d($drawing->getName());
                d($drawing->getPath());
                d($drawing->getImageIndex());
                d($drawing->getDescription());
                d($drawing->getCoordinates());
                d($drawing->getHashCode());

                /////////
                if ($drawing instanceof PHPExcel_Worksheet_MemoryDrawing) {
                    ob_start();
                    call_user_func(
                        $drawing->getRenderingFunction(),
                        $drawing->getImageResource()
                    );

                    $imageContents = ob_get_contents();
                    ob_end_clean();
                    $extension = 'png';
                } else {
                    $zipReader = fopen($drawing->getPath(),'r');
                    $imageContents = '';

                    while (!feof($zipReader)) {
                        $imageContents .= fread($zipReader,1024);
                    }
                    fclose($zipReader);
                    $extension = $drawing->getExtension();
                }

                d($imageContents);
                d($extension);
                /////////
            }


            break;
    }

}