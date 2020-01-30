<?


use Studio7spb\Marketplace\ImportSettingsTable,
    Bitrix\Highloadblock;

class lansyPriceGenerator
{

    /**
     * After iblock element add or Update function
     * @param $arFields
     */
    function OnAfterIBlockElementUpdateHandler(&$arFields)
    {
        // generate prices
        if($arFields["IBLOCK_ID"] == 2){
            $price = self::calculate($arFields["ID"], $arFields["IBLOCK_ID"]);
            self::setFOB($arFields["ID"], $price);
            self::setNormalPrice($arFields["ID"], $price);
            self::setQuicklyPrice($arFields["ID"], $price);

            // set properties
            self::setFactory($price);

            // set data from tamojnja
            self::Tamojnja($price);
        }
    }

    /**
     * FOB
     * @param $arFields
     */
    function setFOB($PRODUCT_ID, $price){
        $PRICE_TYPE_ID = 6;

        $arFields = Array(
            "PRODUCT_ID" => $PRODUCT_ID,
            "CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
            "PRICE" => $price["AN"]["RESULT"],
            "CURRENCY" => "USD"
        );

        $res = CPrice::GetList(
            array(),
            array(
                "PRODUCT_ID" => $PRODUCT_ID,
                "CATALOG_GROUP_ID" => $PRICE_TYPE_ID
            )
        );

        if ($arr = $res->Fetch())
        {
            CPrice::Update($arr["ID"], $arFields);
        }
        else
        {
            CPrice::Add($arFields);
        }

    }

    /**
     * quickly_price
     * @param $arFields
     */
    function setQuicklyPrice($PRODUCT_ID, $price){
        $PRICE_TYPE_ID = 4;

        $arFields = Array(
            "PRODUCT_ID" => $PRODUCT_ID,
            "CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
            "PRICE" => $price["AX"]["RESULT"],
            "CURRENCY" => "RUB"
        );

        $res = CPrice::GetList(
            array(),
            array(
                "PRODUCT_ID" => $PRODUCT_ID,
                "CATALOG_GROUP_ID" => $PRICE_TYPE_ID
            )
        );

        if ($arr = $res->Fetch())
        {
            CPrice::Update($arr["ID"], $arFields);
        }
        else
        {
            CPrice::Add($arFields);
        }

    }

    /**
     * normal_price
     * @param $arFields
     */
    function setNormalPrice($PRODUCT_ID, $price){
        $PRICE_TYPE_ID = 3;

        $arFields = Array(
            "PRODUCT_ID" => $PRODUCT_ID,
            "CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
            "PRICE" => $price["AZ"]["RESULT"],
            "CURRENCY" => "RUB"
        );

        $res = CPrice::GetList(
            array(),
            array(
                "PRODUCT_ID" => $PRODUCT_ID,
                "CATALOG_GROUP_ID" => $PRICE_TYPE_ID
            )
        );

        if ($arr = $res->Fetch())
        {
            CPrice::Update($arr["ID"], $arFields);
        }
        else
        {
            CPrice::Add($arFields);
        }

    }

    /**
     * @param $element
     * @param $IBLOCK_ID
     * @return array
     */
    function calculate($element, $IBLOCK_ID){
        $arResult = array();
        $arParams = self::calculateParams($IBLOCK_ID);

        $arParams["FILTER"] = array("ID" => $element, "IBLOCK_ID" => $IBLOCK_ID);
        $elements = CIBlockElement::GetList(array(), $arParams["FILTER"], false, false, $arParams["SELECT"]);
        if($element = $elements->Fetch())
        {

            /**
             * AQ доставка срочная
             * =U7*AP7/T7
             * =CBM MasterCTN * на Закуб срочная /на PCS MasterCTN=0,217*100/48
             */
            $arResult["AQ"] = array(
                "RESULT" => $element["PROPERTY_MASTER_CTN_CBM_VALUE"] * $arParams["CONSTANTS"]["AP"] / $element["PROPERTY_MASTER_CTN_PCS_VALUE"]
            );

            /**
             * AR Дост стнд.
             * =U7*AO7/T7
             */
            $arResult["AR"] = array(
                "RESULT" => $element["PROPERTY_MASTER_CTN_CBM_VALUE"] * $arParams["CONSTANTS"]["AO"] / $element["PROPERTY_MASTER_CTN_PCS_VALUE"]
            );

            /**
             * AS таможня срчн.
             * =(AQ7+L7/$AV$1)*AI7
             */
            $arResult["AS"] = array(
                "RESULT" => ($arResult["AQ"]["RESULT"] + $element["PROPERTY_EXWORK_RMB_VALUE"] / $arParams["CONSTANTS"]["AV1"]) * ($arParams["CONSTANTS"]["AI"] / 100)
            );

            /**
             * AT таможня стнд.
             * =(AR7+М7/$AV$1)*AI7
             */
            $arResult["AT"] = array(
                "RESULT" => ($arResult["AR"]["RESULT"] + $element["PROPERTY_FOB_RMB_VALUE"] / $arParams["CONSTANTS"]["AV1"]) * ($arParams["CONSTANTS"]["AI"] / 100)
            );

            /**
             * AU НДС срчн.
             * =(L7/$AV$1+AQ7+AS7)*AJ7
             */
            $arResult["AU"] = array(
                "RESULT" => ($element["PROPERTY_EXWORK_RMB_VALUE"] / $arParams["CONSTANTS"]["AV1"] + $arResult["AQ"]["RESULT"] + $arResult["AS"]["RESULT"]) * ($arParams["CONSTANTS"]["AJ"] / 100)
            );

            /**
             * AV НДС стнд.
             * =(М7/$AV$1+AR7+AT7)*AJ7
             */
            $arResult["AV"] = array(
                "RESULT" => ($element["PROPERTY_FOB_RMB_VALUE"] / $arParams["CONSTANTS"]["AV1"] + $arResult["AR"]["RESULT"] + $arResult["AT"]["RESULT"]) * ($arParams["CONSTANTS"]["AJ"] / 100)
            );

            /**
             * AW cost срочн.
             * =L7/$AV$1+AS7+AQ7+AU7
             */
            $arResult["AW"] = array(
                "RESULT" => $element["PROPERTY_EXWORK_RMB_VALUE"] / $arParams["CONSTANTS"]["AV1"] + $arResult["AS"]["RESULT"] + $arResult["AQ"]["RESULT"] + $arResult["AU"]["RESULT"]
            );

            /**
             * AX продажа врублях, срочн.
             * AW7*(1+AM7)*$AZ$1
             */
            $arResult["AX"] = array(
                "RESULT" => $arResult["AW"]["RESULT"] * (1 + ($element["PROPERTY_DISCOUNT_DDP_FAST_VALUE"] ? intval($element["PROPERTY_DISCOUNT_DDP_FAST_VALUE"]) : $arParams["CONSTANTS"]["AM"]) / 100) * $arParams["CONSTANTS"]["AZ1"]
            );

            /**
             * AY cost стнд.
             * =М7/$AV$1+AT7+AR7+AV7
             */
            $arResult["AY"] = array(
                "RESULT" => $element["PROPERTY_FOB_RMB_VALUE"] / $arParams["CONSTANTS"]["AV1"] + $arResult["AT"]["RESULT"] + $arResult["AR"]["RESULT"] + $arResult["AV"]["RESULT"]
            );

            /**
             * AZ продажа врублях, срочн.
             * AY7*(1+AL7)*$AZ$1
             */
            $arResult["AZ"] = array(
                "RESULT" => $arResult["AY"]["RESULT"] * (1 + ($element["PROPERTY_DISCOUNT_DDP_VALUE"] ? intval($element["PROPERTY_DISCOUNT_DDP_VALUE"]) : $arParams["CONSTANTS"]["AL"]) / 100) * $arParams["CONSTANTS"]["AZ1"]
            );

            /**
             * AN FOB $
             * =M7*(100%+AK7)/$AO$1
             */
            $arResult["AN"] = array(
                "RESULT" => $element["PROPERTY_FOB_RMB_VALUE"] / $arParams["CONSTANTS"]["AV1"] * ((100 + ($element["PROPERTY_DISCOUNT_FOB_VALUE"] ? intval($element["PROPERTY_DISCOUNT_FOB_VALUE"]) : $arParams["CONSTANTS"]["AK"])) / 100)
            );

            /**
             * element
             */
            $arResult["ELEMENT"] = $element;

        }

        return $arResult;
    }

    /**
     * @return array
     */
    function calculateParams($IBLOCK_ID){
        $arResult = array(
            "CONSTANTS" => array(),
            "SELECT" => array(
                "ID",
                "NAME",
                "IBLOCK_ID"
            ),
            "FILTER" => array(
                "IBLOCK_ID" => $IBLOCK_ID
            )
        );

        $constants = ImportSettingsTable::getList();
        while ($constant = $constants->fetch())
        {
            $arResult["CONSTANTS"][$constant["CODE"]] = $constant["VALUE"];
        }

        $properties = CIBlockProperty::GetList(array(), $arResult["FILTER"]);
        while ($property = $properties->Fetch())
        {
            if(
                $property["CODE"] !== "MORE_PHOTO" &&
                $property["CODE"] !== "DISCOUNT" &&
                $property["CODE"] !== "VIDEO"
            ){
                $arResult["SELECT"][] = "PROPERTY_" . $property["CODE"];
            }
        }

        return $arResult;
    }


    function setFactory($element){
        $factory = "Other";

        // check in spravochnik
        CModule::IncludeModule("highloadblock");

        // prepare
        $hlblock = Highloadblock\HighloadBlockTable::getById(6)->fetch();
        $hlEntity = Highloadblock\HighloadBlockTable::compileEntity($hlblock);
        $entDataClass = $hlEntity->getDataClass();
        $sTableID = 'tbl_'.$hlblock['TABLE_NAME'];

        $rsData = $entDataClass::getList(array(
            "select" => array('UF_NAME'),
            "filter" => array("UF_NAME" => $element["ELEMENT"]["PROPERTY_FACTORY_VALUE"]),
            "order" => array("UF_NAME"=>"ASC")
        ));
        $rsData = new CDBResult($rsData, $sTableID);
        if($arRes = $rsData->Fetch()){
            $factory = $arRes["UF_NAME"];
        }

        CIBlockElement::SetPropertyValues($element["ELEMENT"]["ID"], $element["ELEMENT"]["IBLOCK_ID"], $factory, "FACTORY");

        // TRADE_MARK
        // 2534
        if(!$element["ELEMENT"]["PROPERTY_TRADE_MARK_VALUE"]){
            CIBlockElement::SetPropertyValues($element["ELEMENT"]["ID"], $element["ELEMENT"]["IBLOCK_ID"], 2534, "TRADE_MARK");
        }


    }

    /**
     * Справочник таможенных платежей
     * @param $element
     */
    function Tamojnja($element){
        CModule::IncludeModule("highloadblock");

        // prepare
        $hlblock = Highloadblock\HighloadBlockTable::getById(11)->fetch();
        $hlEntity = Highloadblock\HighloadBlockTable::compileEntity($hlblock);
        $entDataClass = $hlEntity->getDataClass();
        $sTableID = 'tbl_'.$hlblock['TABLE_NAME'];

        // get data
        $rsData = $entDataClass::getList(array(
            "filter" => array("UF_NAME" => $element["ELEMENT"]["PROPERTY_CATEGORY_VALUE"]),
            "order" => array("UF_NAME"=>"ASC")
        ));
        $rsData = new CDBResult($rsData, $sTableID);
        AddMessage2Log($rsData->SelectedRowsCount());
        if($arRes = $rsData->Fetch()){

            CIBlockElement::SetPropertyValues($element["ELEMENT"]["ID"], $element["ELEMENT"]["IBLOCK_ID"], $arRes["UF_POSHLINA"], "POSLINA");
            CIBlockElement::SetPropertyValues($element["ELEMENT"]["ID"], $element["ELEMENT"]["IBLOCK_ID"], $arRes["UF_NDS"], "NDS");
            CIBlockElement::SetPropertyValues($element["ELEMENT"]["ID"], $element["ELEMENT"]["IBLOCK_ID"], $arRes["UF_NDS"], "CODE_TNVED");

        }

    }


    function OnGetOptimalPriceHandler($productID, $quantity = 1, $arUserGroups = array(), $renewal = "N", $arPrices = array(), $siteID = false, $arDiscountCoupons = false){
        // Проверить права на
        $price = intval($_SESSION["USER_CATALOG_GROUP"]);
        if($price > 0){
            $arOptPrices = CCatalogProduct::GetByIDEx($productID);

            return array(
                'PRICE' => array(
                    "ID" => $productID,
                    'ELEMENT_IBLOCK_ID' => $arOptPrices["IBLOCK_ID"],
                    'CATALOG_GROUP_ID' => $price,
                    'PRICE' => $arOptPrices['PRICES'][$price]['PRICE'],
                    'CURRENCY' => $arOptPrices['PRICES'][$price]['CURRENCY']
                    // Умножить на коефициент НДС
                    // Включать ли НДС
                    //"VAT_RATE" => 7,
                    //"VAT_INCLUDED" => "N",

                )
            );

        }
    }

    function OnGetOptimalPriceResultHandler(&$arResult){
        // OnGetOptimalPriceResult
        // https://dev.1c-bitrix.ru/community/blogs/vws/work-in-pairs.php

        $arResult["PRICE"]["PRICE"] == 666;
        $arResult["PRICE"]["CURRENCY"] == "RUB";
        $arResult["RESULT_PRICE"]["PRICE_TYPE_ID"] == 3;
        $arResult["RESULT_PRICE"]["BASE_PRICE"] == 666;
        $arResult["RESULT_PRICE"]["DISCOUNT_PRICE"] == 666;
        $arResult["RESULT_PRICE"]["CURRENCY"] == "RUB";

        AddMessage2Log($arResult, "OnGetOptimalPriceResult");



    }



}