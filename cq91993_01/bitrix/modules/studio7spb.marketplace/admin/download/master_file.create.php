<?
use Bitrix\Main\Localization\Loc,
    Bitrix\Main\IO\File,
    Bitrix\Main\Web\Json;

$_SERVER["DOCUMENT_ROOT"] = $argv[1];
$p  = $argv[2];
$lastID = $argv[3];
$leftBorderCnt = $argv[4];

$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
require $_SERVER["DOCUMENT_ROOT"] . '/system/7studio/excel/git/PHPExcel/Classes/PHPExcel.php';
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
Loc::loadLanguageFile(__FILE__);

// <editor-fold defaultstate="collapsed" desc=" # arParams">
$arParams = array(
    "MODULE" => "studio7spb.marketplace",
    "UPLOAD_PATH" => $_SERVER["DOCUMENT_ROOT"] . "/upload/excel/download/catalog/",
    "UPLOAD_FILE_NAME" => "2.xlsx"
);
$arParams["UPLOAD_PATH_TMP"] = $arParams["UPLOAD_PATH"] . "tmp/";
@mkdir($arParams["UPLOAD_PATH"], 0777);
@mkdir($arParams["UPLOAD_PATH_TMP"], 0777);

// </editor-fold>

// <editor-fold defaultstate="collapsed" desc=" # arResult">
$arResult = [
    "FILES" => []
];

$arResult["FILES"] = array_diff(
    scandir($arParams["UPLOAD_PATH_TMP"]),
    array('..', '.')
);

if(!empty($arResult["FILES"]))
{
    $objPHPExcel = new PHPExcel();

    if (File::isFileExists($arParams["UPLOAD_PATH"] . $arParams["UPLOAD_FILE_NAME"])) {
        $objPHPExcel = PHPExcel_IOFactory::load($arParams["UPLOAD_PATH"] . $arParams["UPLOAD_FILE_NAME"]);
        $objPHPExcel->setActiveSheetIndex(0);
        $row = $objPHPExcel->getActiveSheet()->getHighestRow() + 2;
    }else{
        $row = 1;
        $objPHPExcel = new PHPExcel();
    }

    foreach ($arResult["FILES"] as $file){
        $jsonObjects = file($arParams["UPLOAD_PATH_TMP"] . $file);
        if(!empty($jsonObjects)){
            foreach ($jsonObjects as $arElements){
                $arElements = Json::decode($arElements);
                if(!empty($arElements)){
                    foreach ($arElements as $arEl){
                        $row++;
                        //$objPHPExcel->getActiveSheet()->setCellValueExplicit("C" . $i, $arEl["NAME"], PHPExcel_Cell_DataType::TYPE_STRING);
                        //$objPHPExcel->getActiveSheet()->setCellValueExplicit("D" . $i, $arEl["ID"], PHPExcel_Cell_DataType::TYPE_NUMERIC);

                        $objPHPExcel->getActiveSheet()->SetCellValue('C'.$row, $arEl["NAME"]);
                        $objPHPExcel->getActiveSheet()->SetCellValue('D'.$row, $arEl["ID"]);
                        $objPHPExcel->getActiveSheet()->SetCellValue('E'.$row, "append");
                        $objPHPExcel->getActiveSheet()->SetCellValue('F'.$row, "append new");


                    }
                }

            }
        }
    }


    ############################

    /*
    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$row, $_POST['name']);
    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$row, $_POST['email']);
    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$row, $_POST['phone']);
    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$row, $_POST['city']);
    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$row, $_POST['kid1']);
    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$row, $_POST['kid2']);
     */

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $objWriter->save($arParams["UPLOAD_PATH"] . $arParams["UPLOAD_FILE_NAME"]);

    # $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    # $objWriter->save($arParams["UPLOAD_PATH"] . $arParams["UPLOAD_FILE_NAME"]);

    ###########################




}

// </editor-fold>

# Output
echo 'CurrentStatus = Array('.$p.',"'.($p < 100 ? '&lastid='.$lastID : '').'","' . Loc::getMessage("MASTER_PROCESS_STATUS", ["ID" => $lastID, "COUNT" => $leftBorderCnt]) .'");';

#d("####### Start System execute #######");
#d($arResult);
#d("####### End System execute #######");