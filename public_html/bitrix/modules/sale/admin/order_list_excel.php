<?
use \Bitrix\Main\Localization\Loc,
    Studio7spb\Marketplace\StaticTable;

// <editor-fold defaultstate="collapsed" desc=" # Preparato">
Loc::loadLanguageFile(__FILE__);
if (ini_get('mbstring.func_overload') & 2) {
    ini_set("mbstring.func_overload", 0);
}
require $_SERVER["DOCUMENT_ROOT"] . '/system/7studio/excel/git/PHPExcel/Classes/PHPExcel.php';
header("Content-Type: application/vnd.ms-excel");
header('Cache-Control: max-age=0'); //no cache
header("Content-Disposition: filename=" . Loc::getMessage("ORDER_LIST_EXCEL_TITLE") .  ".xlsx");
$objPHPExcel = new PHPExcel();

$arParams = [
    "START" => [
        "COL" => 0,
        "LINE" => 2
    ]
];

$arResult = [
    "ELEMENTS" => [],
    "LANSY_STATIC" => [
        "DATA" => []
    ],
    "TOTAL" => [
        "QUANTITY" => 0
    ]
];

$arFields = [
    [
        "CODE" => "A",
        "WIDTH" => 34
    ],
    [
        "CODE" => "B",
        "WIDTH" => 16
    ],
    [
        "CODE" => "C",
        "WIDTH" => 24
    ],
    [
        "CODE" => "D",
        "WIDTH" => 18
    ],
    [
        "CODE" => "E",
        "WIDTH" => 18
    ],
    [
        "CODE" => "F",
        "WIDTH" => 18
    ],
    [
        "CODE" => "G",
        "WIDTH" => 18
    ],
    [
        "CODE" => "H",
        "WIDTH" => 18
    ],
    [
        "CODE" => "I",
        "WIDTH" => 18
    ],
    [
        "CODE" => "J",
        "WIDTH" => 18
    ],
    [
        "CODE" => "K",
        "WIDTH" => 18
    ],
    [
        "CODE" => "L",
        "WIDTH" => 18
    ],
    [
        "CODE" => "M",
        "WIDTH" => 18
    ],
    [
        "CODE" => "N",
        "WIDTH" => 18
    ],
    [
        "CODE" => "O",
        "WIDTH" => 18
    ],
    [
        "CODE" => "P",
        "WIDTH" => 18
    ],
    [
        "CODE" => "Q",
        "WIDTH" => 18
    ],
    [
        "CODE" => "R",
        "WIDTH" => 18
    ],
];

// </editor-fold>

// <editor-fold defaultstate="collapsed" desc=" # Excel Output Global settings">
foreach ($arFields as $arField){
    $objPHPExcel->getActiveSheet()->getColumnDimension($arField["CODE"])->setWidth($arField["WIDTH"]);
}
// </editor-fold>

$i = $arParams["START"]["LINE"];

if(empty($orderList)){

}else{
    foreach ($orderList as $arOrder){

        $char = $arParams["START"]["COL"];

        // <editor-fold defaultstate="collapsed" desc=" # Заказ №63 от 21.04.2020 14:11:47">
        $objPHPExcel->getActiveSheet()->mergeCells($arFields[$char]["CODE"] . $i . ':' . $arFields[1]["CODE"] . $i);
        $objPHPExcel->getActiveSheet()->getStyle($arFields[$char]["CODE"] . $i)->getFont()->setSize(18);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char]["CODE"] . $i, Loc::getMessage("ORDER_LIST_EXCEL_DATE_INSERT", ["ID" => $arOrder["ID"], "DATE_INSERT" => $arOrder["DATE_INSERT"]->format("d.m.Y H:i:s")]), PHPExcel_Cell_DataType::TYPE_STRING);
        $i++;
        // </editor-fold>

        // <editor-fold defaultstate="collapsed" desc=" # Lancy static data">
        # актуальный курс на время заказа
        CModule::AddAutoloadClasses(
            "studio7spb.marketplace",
            array(
                "\\Studio7spb\\Marketplace\\StaticTable" => "lib/lansy/static.php"
            )
        );
        $lansyStatics = StaticTable::getList([
            'order' => array('ID' => 'DESC'),
            "limit" => 1,
            "filter" => [
                "<=DATE" => $arOrder["DATE_INSERT"]
            ]
        ]);
        if($lansyStatics = $lansyStatics->fetch()){
            $arResult["LANSY_STATIC"]["DATA"] = unserialize($lansyStatics["DATA"]);
            unset($lansyStatics);
        }
        // </editor-fold>

        // <editor-fold defaultstate="collapsed" desc=" # User data">
        if($arOrder["USER_ID"] > 0){
            $arUser = \Bitrix\Main\UserTable::getList([
                "filter" => [
                    "ID" => $arOrder["USER_ID"]
                ],
                "select" => [
                    "ID",
                    "NAME",
                    "LAST_NAME",
                    "SECOND_NAME"
                ]
            ]);
            if($arUser = $arUser->fetch()){
                # work with data
                $arUser["FIO"] = [$arUser["NAME"], $arUser["SECOND_NAME"], $arUser["LAST_NAME"]];
                $arUser["FIO"] = implode(" ", $arUser["FIO"]);

                # fell excel
                $objPHPExcel->getActiveSheet()->getStyle($arFields[$char]["CODE"] . ++$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C2D69B');
                $objPHPExcel->getActiveSheet()->getStyle($i)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] .   $i, Loc::getMessage("ORDER_LIST_EXCEL_USER_ID"), PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->getStyle($arFields[$char]["CODE"] . $i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C2D69B');
                $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char--]["CODE"] . $i, $arUser["ID"], PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->getStyle($i)->getFont()->setBold(true);

                $objPHPExcel->getActiveSheet()->getStyle($arFields[$char]["CODE"] . ++$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C2D69B');
                $objPHPExcel->getActiveSheet()->getStyle($i)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, Loc::getMessage("ORDER_LIST_EXCEL_USER_NAME"), PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->getStyle($arFields[$char]["CODE"] . $i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C2D69B');
                $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char--]["CODE"] . $i, $arUser["FIO"], PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->getStyle($i)->getFont()->setBold(true);

                // <курс $ по которому рассчитаны рублевые цены>
                $objPHPExcel->getActiveSheet()->getStyle($arFields[$char]["CODE"] . ++$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C2D69B');
                $objPHPExcel->getActiveSheet()->getStyle($i)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, Loc::getMessage("ORDER_LIST_EXCEL_ORDER_DATE_CURSE"), PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->getStyle($arFields[$char]["CODE"] . $i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C2D69B');
                $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char--]["CODE"] . $i, $arResult["LANSY_STATIC"]["DATA"]["AZ1"], PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->getStyle($i)->getFont()->setBold(true);

                $i++;
            }
        }
        // </editor-fold>

        // <editor-fold defaultstate="collapsed" desc=" # Basket data">
        if(empty($basketList[$arOrder["ID"]])){
            // some thing wrong with it
        }
        else{

            # элементы инфоблока
            $arResult["ELEMENTS"] = [];
            foreach ($basketList[$arOrder["ID"]] as $BASKET_ITEM){
                $arResult["ELEMENTS"][] = $BASKET_ITEM["PRODUCT_ID"];
            }

            $items = CIBlockElement::GetList(
                [],
                [
                    "ID" => $arResult["ELEMENTS"]
                ],
                false,
                false,
                [
                    "ID",
                    "IBLOCK_ID",
                    "NAME",
                    "PREVIEW_PICTURE",
                    "PROPERTY_ITEM_NO",
                    "PROPERTY_FACTORY",
                    "PROPERTY_ExWork_RMB",
                    "PROPERTY_FOB_RMB",
                    "PROPERTY_AGENT_PRIMARY",
                    "PROPERTY_AGENT_CODE",
                    "PROPERTY_LHW_CTN",
                    "PROPERTY_DISPLAY_COUNT",
                    "PROPERTY_Master_CTN_CBM",
                    "PROPERTY_FACTORY",
                    "PROPERTY_POSLINA",
                ]
            );
            while ($item = $items->Fetch()){
                if($item["PREVIEW_PICTURE"] > 0)
                    $item["PREVIEW_PICTURE"] = CFile::GetFileArray($item["PREVIEW_PICTURE"]);
                $arResult["ELEMENTS"][$item["ID"]] = $item;
            }

            # fell basket header
            $char = $arParams["START"]["COL"];
            $i++;
            $objPHPExcel->getActiveSheet()->getStyle($i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ff6f00');
            $objPHPExcel->getActiveSheet()->getStyle($i)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
            $objPHPExcel->getActiveSheet()->getStyle($i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle($i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle($arFields[$char]["CODE"] . $i)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(22);

            $objPHPExcel->getActiveSheet()->getStyle($arFields[$char]["CODE"] . $i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C2D69B');
            $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, Loc::getMessage("ORDER_LIST_EXCEL_BASKET_IMAGE"), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, Loc::getMessage("ORDER_LIST_EXCEL_BASKET_PRODUCT_ID"), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, Loc::getMessage("ORDER_LIST_EXCEL_BASKET_NAME"), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, Loc::getMessage("ORDER_LIST_EXCEL_BASKET_FACTORY_ARTICLE"), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, Loc::getMessage("ORDER_LIST_EXCEL_BASKET_PRICE"), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, Loc::getMessage("ORDER_LIST_EXCEL_BASKET_PRICE_USD"), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, Loc::getMessage("ORDER_LIST_EXCEL_BASKET_QUANTITY"), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, Loc::getMessage("ORDER_LIST_EXCEL_BASKET_SUM"), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->getStyle($arFields[$char]["CODE"] . $i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C2D69B');
            $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, Loc::getMessage("ORDER_LIST_EXCEL_BASKET_VAT"), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->getStyle($arFields[$char]["CODE"] . $i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C2D69B');
            $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, Loc::getMessage("ORDER_LIST_EXCEL_BASKET_VAT2"), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, Loc::getMessage("ORDER_LIST_EXCEL_BASKET_FACTORY"), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->getStyle($arFields[$char]["CODE"] . $i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C2D69B');
            $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, Loc::getMessage("ORDER_LIST_EXCEL_BASKET_PRICE_FOB_CNY"), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->getStyle($arFields[$char]["CODE"] . $i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C2D69B');
            $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, Loc::getMessage("ORDER_LIST_EXCEL_BASKET_PRICE_EXWRK_CNY"), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->getStyle($arFields[$char]["CODE"] . $i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C2D69B');
            $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, Loc::getMessage("ORDER_LIST_EXCEL_BASKET_MASTER_CTN_CBM"), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->getStyle($arFields[$char]["CODE"] . $i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C2D69B');
            $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, Loc::getMessage("ORDER_LIST_EXCEL_BASKET_DISPLAY_COUNT"), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->getStyle($arFields[$char]["CODE"] . $i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C2D69B');
            $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, Loc::getMessage("ORDER_LIST_EXCEL_BASKET_LHW_CTN"), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->getStyle($arFields[$char]["CODE"] . $i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C2D69B');
            $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, Loc::getMessage("ORDER_LIST_EXCEL_BASKET_AGENT_PRIMARY"), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->getStyle($arFields[$char]["CODE"] . $i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C2D69B');
            $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, Loc::getMessage("ORDER_LIST_EXCEL_BASKET_AGENT_CODE"), PHPExcel_Cell_DataType::TYPE_STRING);
            $i++;

            # fill busket items
            foreach ($basketList[$arOrder["ID"]] as $BASKET_ITEM){
                $arResult["TOTAL"]["QUANTITY"] = 0;
                $char = $arParams["START"]["COL"];
                $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(100);
                $objPHPExcel->getActiveSheet()->getStyle($i)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle($i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle($i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

                # picture
                $excelType = "light";
                if($_REQUEST["type"] == "full")
                    $excelType = "full";

                if($excelType == "light"){
                    $char++;
                }else{
                    if(!empty($arResult["ELEMENTS"][$BASKET_ITEM["PRODUCT_ID"]]["PREVIEW_PICTURE"])){
                        $BASKET_ITEM["PREVIEW_PICTURE_SRC"] = $arResult["ELEMENTS"][$BASKET_ITEM["PRODUCT_ID"]]["PREVIEW_PICTURE"]["SRC"];
                        $objDrawing = new PHPExcel_Worksheet_Drawing();
                        $objDrawing->setName($BASKET_ITEM["NAME"]);
                        $objDrawing->setDescription($BASKET_ITEM["NAME"]);
                        $objDrawing->setPath($_SERVER["DOCUMENT_ROOT"] . $BASKET_ITEM["PREVIEW_PICTURE_SRC"]);
                        $objDrawing->setCoordinates($arFields[$char++]["CODE"] . $i);
                        //setOffsetX works properly
                        $objDrawing->setOffsetX(5);
                        $objDrawing->setOffsetY(5);
                        //set width, height
                        //$objDrawing->setWidth(100);
                        $objDrawing->setHeight(100);
                        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
                    }
                }

                # fill busket item calculating and formating
                $BASKET_ITEM["SUM"] = round(($BASKET_ITEM["PRICE"] * $BASKET_ITEM["QUANTITY"]), 2);
                $BASKET_ITEM["VAT_RATE"] = $BASKET_ITEM["VAT_RATE"] * 100;
                $BASKET_ITEM["PRICE"] = round($BASKET_ITEM["PRICE"], 2);
                $BASKET_ITEM["PRICE_USD"] = round($BASKET_ITEM["PRICE"] / $arResult["LANSY_STATIC"]["DATA"]["AZ1"], 2);
                $BASKET_ITEM["QUANTITY"] = intval($BASKET_ITEM["QUANTITY"]);

                $arResult["TOTAL"]["QUANTITY"] += $BASKET_ITEM["QUANTITY"];

                # fill busket item
                $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, $BASKET_ITEM["PRODUCT_ID"], PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, $BASKET_ITEM["NAME"], PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, "YB1769G", PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, $BASKET_ITEM["PRICE"], PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, $BASKET_ITEM["PRICE_USD"], PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, $BASKET_ITEM["QUANTITY"], PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, $BASKET_ITEM["SUM"], PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, $BASKET_ITEM["VAT_RATE"], PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, $arResult["ELEMENTS"][$BASKET_ITEM["PRODUCT_ID"]]["PROPERTY_POSLINA_VALUE"], PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, $arResult["ELEMENTS"][$BASKET_ITEM["PRODUCT_ID"]]["PROPERTY_FACTORY_VALUE"], PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, round($arResult["ELEMENTS"][$BASKET_ITEM["PRODUCT_ID"]]["PROPERTY_FOB_RMB_VALUE"], 2), PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, round($arResult["ELEMENTS"][$BASKET_ITEM["PRODUCT_ID"]]["PROPERTY_EXWORK_RMB_VALUE"], 2), PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, $arResult["ELEMENTS"][$BASKET_ITEM["PRODUCT_ID"]]["PROPERTY_MASTER_CTN_CBM_VALUE"], PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, $arResult["ELEMENTS"][$BASKET_ITEM["PRODUCT_ID"]]["PROPERTY_DISPLAY_COUNT_VALUE"], PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, $arResult["ELEMENTS"][$BASKET_ITEM["PRODUCT_ID"]]["PROPERTY_LHW_CTN_VALUE"], PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, $arResult["ELEMENTS"][$BASKET_ITEM["PRODUCT_ID"]]["PROPERTY_LHW_CTN_VALUE"], PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, $arResult["ELEMENTS"][$BASKET_ITEM["PRODUCT_ID"]]["PROPERTY_AGENT_CODE_VALUE"], PHPExcel_Cell_DataType::TYPE_STRING);

                $i++;
            }


        }



        // </editor-fold>

        $i++;

        // <editor-fold defaultstate="collapsed" desc=" # Order total">
        $char = $arParams["START"]["COL"];

        $objPHPExcel->getActiveSheet()->getStyle($i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ff6f00');
        $objPHPExcel->getActiveSheet()->getStyle($i)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
        $objPHPExcel->getActiveSheet()->getStyle($i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle($i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle($arFields[$char]["CODE"] . $i)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(22);

        $objPHPExcel->getActiveSheet()->getStyle($arFields[$char]["CODE"] . $i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C2D69B');
        $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char]["CODE"] . $i, Loc::getMessage("ORDER_LIST_EXCEL_TOTAL"), PHPExcel_Cell_DataType::TYPE_STRING);

        $char = 6;
        $objPHPExcel->getActiveSheet()->getStyle($arFields[$char]["CODE"] . $i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C2D69B');
        $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char++]["CODE"] . $i, $arResult["TOTAL"]["QUANTITY"], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->getStyle($arFields[$char]["CODE"] . $i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C2D69B');
        $objPHPExcel->getActiveSheet()->setCellValueExplicit($arFields[$char]["CODE"] . $i, round($arOrder["PRICE"], 2), PHPExcel_Cell_DataType::TYPE_NUMERIC);
        // </editor-fold>

        $i++;
    }
}

// <editor-fold defaultstate="collapsed" desc=" # Output">
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
// </editor-fold>

die;
