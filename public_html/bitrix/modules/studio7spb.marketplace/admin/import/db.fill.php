<?
use Bitrix\Main\Config\Option,
    Studio7spb\Marketplace\ImportTable;

$_SERVER["DOCUMENT_ROOT"] = "/home/c/ca01826813/teatr-msk.ru/public_html/";
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
require $_SERVER["DOCUMENT_ROOT"] . '/system/7studio/excel/git/PHPExcel/Classes/PHPExcel.php';
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

// <editor-fold defaultstate="collapsed" desc=" # Clear b_studio7spb_import">
$DB->Query("DROP TABLE IF EXISTS b_studio7spb_import;");
$DB->Query("CREATE TABLE IF NOT EXISTS b_studio7spb_import
(
    ID INT NOT NULL AUTO_INCREMENT,
    STATUS CHAR(1),
    DATA TEXT,
    NOTE VARCHAR(255),
    PRIMARY KEY (ID)
);");
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc=" # arParams">
$arParams = array(
    "MODULE" => "studio7spb.marketplace",
    "FILE" => "IMPORT_DATA_FILE"
);
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc=" # Load">
$arResult = array(
    "FILE" => Option::get($arParams["MODULE"], $arParams["FILE"])
);

if($arResult["FILE"] > 0){
    $arResult["FILE"] = CFile::GetFileArray($arResult["FILE"]);
    $arResult["FILE"] = $_SERVER["DOCUMENT_ROOT"] . $arResult["FILE"]["SRC"];
}
else{
    $arResult["FILE"] = null;
}

$objReader = PHPExcel_IOFactory::createReaderForFile($arResult["FILE"]);
$objPHPExcel = $objReader->load($arResult["FILE"]);
// </editor-fold>



foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
    // get shett data

    switch ($worksheet->getTitle()){

        case "Лист1":
            $rows = array();
            // TODO Лимит на чтение можно вставить тут
            foreach ($worksheet->getRowIterator() as $row) {
                if($row->getRowIndex() > 3){
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
                    $i = 0;
                    foreach ($cellIterator as $cell) {
                        $i++;
                        if (!is_null($cell) && $i < 31) {
                            //$rows[$row->getRowIndex()][$cell->getCoordinate()] = $cell->getValue();
                            $rows[$row->getRowIndex()][$cell->getColumn()] = $cell->getValue();
                        }
                    }
                }
            }
            foreach ($rows as $row){
                ImportTable::add(array(
                    "STATUS" => "Y",
                    "DATA" => serialize($row),
                    "NOTE" => ""
                ));
            }
            break;

        case "Materials":
            // TODO Пополнить справочник Materials

            $objPHPExcel->setActiveSheetIndex(2);
            $sheet = $objPHPExcel->getActiveSheet();
            foreach ($sheet->getRowIterator() as $row) {
                if($row->getRowIndex() >= 3){
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
                    $i = 0;
                    foreach ($cellIterator as $cell) {
                        $i++;
                        if (!is_null($cell) && $i < 31) {
                            //$rows[$row->getRowIndex()][$cell->getCoordinate()] = $cell->getValue();
                            $rows[$row->getRowIndex()][$cell->getColumn()] = $cell->getValue();
                        }
                    }
                }
            }

            AddMessage2Log($worksheet->getTitle(), "Materials");
            AddMessage2Log($worksheet->getTitle(), "Materials");

            /*
            $ENTITY_ID = 4;
            $hlblock = Highloadblock\HighloadBlockTable::getById($ENTITY_ID)->fetch();
            $hlEntity = Highloadblock\HighloadBlockTable::compileEntity($hlblock);
            $entDataClass = $hlEntity->getDataClass();
            $sTableID = 'tbl_'.$hlblock['TABLE_NAME'];

            $rsData = $entDataClass::getList(array(
                "select" => array('ID', 'UF_NAME'),
                "filter" => array("UF_NAME" => $element["ELEMENT"]["PROPERTY_FACTORY_VALUE"]),
            ));
            $rsData = new CDBResult($rsData, $sTableID);
            if($arRes = $rsData->Fetch()){
                AddMessage2Log($arRes, "Is exisiting");
            }
            */


            break;
    }

}







$p = 5;
//$p = round(100*$leftBorderCnt/$allCnt, 2);
$lastID = 0;

echo 'CurrentStatus = Array('.$p.',"'.($p < 100 ? '&lastid='.$lastID : '').'","Данные получены.");';