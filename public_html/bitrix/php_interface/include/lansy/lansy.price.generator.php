<?


use Studio7spb\Marketplace\ImportSettingsTable,
    Bitrix\Highloadblock;

class lansyPriceGenerator
{

    function isCompanyProduction($arFields){
        $result = false;
        // IBLOCK_ID
        // ID

        // CModule::IncludeModule('iblock');
        $company = CIBlockElement::GetList(
            array(),
            array(
                "IBLOCK_ID" => $arFields["IBLOCK_ID"],
                "ID" => $arFields["ID"],
                "!PROPERTY_H_COMPANY" => false
            ),
            false,
            false,
            array(
                "ID"
            )
        );
        if($company->SelectedRowsCount() > 0)
        {
            $result = true;
        }

        return $result;
    }

    /**
     * After iblock element add or Update function
     * @param $arFields
     */
    function OnAfterIBlockElementUpdateHandler($arFields)
    {
        // generate prices
        if($arFields["IBLOCK_ID"] == 2 && !self::isCompanyProduction($arFields)){
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
        #Конвертировать эту же цену и записать сюда $PRICE_TYPE_ID = 1; с доллара в рубль
        $curs = Studio7spb\Marketplace\ImportSettingsTable::getList(array(
            "filter" => array("CODE" => "AZ1")
        ));
        if($curs = $curs->fetch()){
            if($curs["VALUE"] > 0){
                $PRICE_TYPE_ID = 7;
                $arFields = Array(
                    "PRODUCT_ID" => $PRODUCT_ID,
                    "CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
                    "PRICE" => $price["AN"]["RESULT"] * $curs["VALUE"],
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

    /**
     * Fill FACTORY && TRADE_MARK
     * @param $element
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    function setFactory($element){
        CModule::IncludeModule("iblock");
        $factory = \Bitrix\Iblock\ElementTable::getList(array(
            "filter" => array(
                "IBLOCK_ID" => 1,
                "NAME" => $element["ELEMENT"]["PROPERTY_FACTORY_VALUE"]
            ),
            "select" => array("ID", "NAME")
        ));
        if($factory = $factory->fetch()){
            CIBlockElement::SetPropertyValuesEx($element["ELEMENT"]["ID"], $element["ELEMENT"]["IBLOCK_ID"], array(
                "TRADE_MARK" => $factory["ID"],
                "FACTORY" => $factory["NAME"]
            ));
        }else{
            CIBlockElement::SetPropertyValuesEx($element["ELEMENT"]["ID"], $element["ELEMENT"]["IBLOCK_ID"], array(
                "TRADE_MARK" => 2534,
                "FACTORY" => "Other"
            ));
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
        if($arRes = $rsData->Fetch()){

            CIBlockElement::SetPropertyValues($element["ELEMENT"]["ID"], $element["ELEMENT"]["IBLOCK_ID"], $arRes["UF_POSHLINA"], "POSLINA");
            CIBlockElement::SetPropertyValues($element["ELEMENT"]["ID"], $element["ELEMENT"]["IBLOCK_ID"], $arRes["UF_NDS"], "NDS");
            CIBlockElement::SetPropertyValues($element["ELEMENT"]["ID"], $element["ELEMENT"]["IBLOCK_ID"], $arRes["UF_NDS"], "CODE_TNVED");

        }

    }

    /**
     * https://github.com/sidigi/bitrix-info/wiki/Добавление-товара-в-корзину-с-произвольной-ценой-(D7)
     * @param $productID
     * @param int $quantity
     * @param array $arUserGroups
     * @param string $renewal
     * @param array $arPrices
     * @param bool $siteID
     * @param bool $arDiscountCoupons
     * @return array
     */
    function OnGetOptimalPriceHandler($productID, $quantity = 1, $arUserGroups = array(), $renewal = "N", $arPrices = array(), $siteID = false, $arDiscountCoupons = false)
    {
        // Идентификатор цены
        $price = intval($_SESSION["USER_CATALOG_GROUP"]);
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


    /**
     * @param $arFields
     */
    function OnAfterUserUpdateHandler($arFields){

        AddMessage2Log($arFields, "OnAfterUserUpdateHandler");

        if($arFields["UF_DISCONT_VALUE"] > 0){

            CModule::IncludeModule('sale');

            $discount = CSaleDiscount::GetList(
                array("ID" => "DESC"),
                array("XML_ID" => $arFields["ID"]),
                false,
                false,
                array("ID", "XML_ID")
            );
            if($discount = $discount->Fetch()){
                $discount = $discount["ID"];
            }

            switch ($arFields["UF_DISCONT_TYPE"]){
                case 1:
                    // Скидка
                    $discountFields = array(
                        "LID" => "s1",
                        "ACTIVE" => "Y",
                        "NAME" => "Personal discont for " . $arFields["LOGIN"],
                        "XML_ID" => $arFields["ID"],
                        'USER_GROUPS' => array(2),
                        'CONDITIONS' => Array(
                            'CLASS_ID' => 'CondGroup',
                            'DATA' => Array(
                                'All' => 'OR',
                                'True' => 'True'
                            ),
                            'CHILDREN' => Array(
                                Array(
                                    'CLASS_ID' => 'CondMainUserId',
                                    'DATA' =>
                                        array (
                                            'logic' => 'Equal',
                                            'value' =>
                                                array (
                                                    0 => $arFields["ID"]
                                                )
                                        )
                                ),
                            )
                        ),

                        'ACTIONS' => array(
                            'CLASS_ID' => 'CondGroup',
                            'DATA' => Array(
                                'All' => 'AND',
                            ),
                            'CHILDREN' => Array(
                                Array(
                                    'CLASS_ID' => 'ActSaleBsktGrp',
                                    'DATA' => array (
                                        'Type' => 'Discount',
                                        'Value' => $arFields["UF_DISCONT_VALUE"],
                                        'Unit' => 'Perc',
                                        'Max' => 0,
                                        'All' => 'AND',
                                        'True' => 'True',
                                    ),
                                    'CHILDREN' => Array()
                                ),
                            )
                        )
                    );

                    if($discount > 0){
                        CSaleDiscount::Update($discount, $discountFields);
                    }else{
                        CSaleDiscount::Add($discountFields);
                    }

                    break;
                case 2:
                    // Наценка
                    $discountFields = array(
                        "LID" => "s1",
                        "ACTIVE" => "Y",
                        "NAME" => "Personal discont for " . $arFields["LOGIN"],
                        "XML_ID" => $arFields["ID"],
                        'USER_GROUPS' => array(2),
                        'CONDITIONS' => Array(
                            'CLASS_ID' => 'CondGroup',
                            'DATA' => Array(
                                'All' => 'OR',
                                'True' => 'True'
                            ),
                            'CHILDREN' => Array(
                                Array(
                                    'CLASS_ID' => 'CondMainUserId',
                                    'DATA' =>
                                        array (
                                            'logic' => 'Equal',
                                            'value' =>
                                                array (
                                                    0 => $arFields["ID"]
                                                )
                                        )
                                ),
                            )
                        ),

                        'ACTIONS' => array(
                            'CLASS_ID' => 'CondGroup',
                            'DATA' => Array(
                                'All' => 'AND',
                            ),
                            'CHILDREN' => Array(
                                Array(
                                    'CLASS_ID' => 'ActSaleBsktGrp',
                                    'DATA' => array (
                                        'Type' => 'Extra',
                                        'Value' => $arFields["UF_DISCONT_VALUE"],
                                        'Unit' => 'Perc',
                                        'Max' => 0,
                                        'All' => 'AND',
                                        'True' => 'True',
                                    ),
                                    'CHILDREN' => Array()
                                ),
                            )
                        )
                    );

                    if($discount > 0){
                        CSaleDiscount::Update($discount, $discountFields);
                    }else{
                        CSaleDiscount::Add($discountFields);
                    }

                    break;
            }
        }

    }
}