<?
use Bitrix\Main\EventManager,
    Bitrix\Main\Loader,
    Bitrix\Sale\Location\LocationTable,
    Studio7spb\Marketplace\SaleAddressTable;

$eventManager = EventManager::getInstance();

/**
 * order events
 */
$eventManager->AddEventHandler("sale", "OnSaleComponentOrderOneStepOrderProps", "OnSaleComponentOrderOneStepOrderPropsHandler");
$eventManager->AddEventHandler("iblock", "OnAfterIBlockElementAdd", "OnAfterIBlockElementUpdateHandler");
$eventManager->AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "OnAfterIBlockElementUpdateHandler");


// <editor-fold defaultstate="Component one step order. TODO Check and delete that">
/**
 * При смене профиля меняем и адресс в свойстве заказа
 * @param $arResult
 * @param $arUserResult
 * @param $arParams
 * @throws \Bitrix\Main\ArgumentException
 * @throws \Bitrix\Main\LoaderException
 * @throws \Bitrix\Main\ObjectPropertyException
 * @throws \Bitrix\Main\SystemException
 */
function OnSaleComponentOrderOneStepOrderPropsHandler(&$arResult, &$arUserResult, $arParams) {
    global $USER;

    if($arUserResult["PROFILE_CHANGE"] == "Y"){
        $collect = array();

        foreach ($arResult["ORDER_PROP"]["USER_PROPS_Y"] as $key=>$property){
            if($property["TYPE"] == "LOCATION"){
                $collect[] = $key;
            }
        }

        foreach ($arResult["ORDER_PROP"]["USER_PROPS_N"] as $key=>$property){
            if($property["TYPE"] == "LOCATION"){
                $collect[] = $key;
            }
        }

        if(
            $USER->GetID() &&
            !empty($collect) &&
            Loader::includeModule("sale")
        ){
            $adress = SaleAddressTable::getList(array(
                "filter" => array(
                    "PROFILE_ID" => $arUserResult["PROFILE_ID"],
                    "USER_ID" => $USER->GetID()
                )
            ));
            if($adress = $adress->fetch())
            {
                // set location property
                $location = LocationTable::getList(array(
                    "filter" => array(
                        "ID" => $adress["LOCATION"]
                    ),
                    "select" => array("ID", "CODE")
                ));
                if($location = $location->fetch()){
                    foreach ($collect as $item) {
                        $arUserResult["ORDER_PROP"][$item] = $location["CODE"];
                    }
                }
                // set props from ORDER_PROPS field
                $adress["ORDER_PROPS"] = unserialize($adress["ORDER_PROPS"]);
                foreach ($arResult["ORDER_PROP"]["USER_PROPS_Y"] as $property){
                    if(!empty($adress["ORDER_PROPS"][$property["CODE"]])){
                        $arUserResult["ORDER_PROP"][$property["ID"]] = $adress["ORDER_PROPS"][$property["CODE"]];
                    }
                }
                foreach ($arResult["ORDER_PROP"]["USER_PROPS_N"] as $key=>$property){
                    if(!empty($adress["ORDER_PROPS"][$property["CODE"]])){
                        $arUserResult["ORDER_PROP"][$property["ID"]] = $adress["ORDER_PROPS"][$property["CODE"]];
                    }
                }
            }
        }
    }



}
// </editor-fold>

// <editor-fold defaultstate="After iblock element add or Update function">

/**
 * @param $arFields
 */
function OnAfterIBlockElementUpdateHandler(&$arFields)
{
    if($arFields["IBLOCK_ID"] == 2){
        if($arFields["RESULT"]){

            $groups = array(
                2, // FOB LC
                3, // стандарт
                4, // срочная доставка
                5, // ExWork,RMB
                6 // FOB
            );

            if($arFields["ID"] == 2529){

                $prices = CPrice::GetList(array(), array("PRODUCT_ID" => $arFields["ID"]));

                while($price = $prices->Fetch())
                {
                    if(in_array($price["CATALOG_GROUP_ID"], $groups)){
                        $arFields = Array(
                            "PRODUCT_ID" => $price["ID"],
                            "CATALOG_GROUP_ID" => $price["CATALOG_GROUP_ID"],
                            "PRICE" => 29.95,
                            "CURRENCY" => "USD"
                        );

                        //$result = CPrice::Update($price["ID"], array("PRICE" => $arFields));

                    }else{
                        //CPrice::Add(array());
                    }

                }

            }



        }
    }

}
// </editor-fold>


/**
 * Developer tools
 */
if (!function_exists("d") )
{
    function d($value, $type="pre")
    {
        if ( is_array( $value ) || is_object( $value ) )
        {
            echo "<" . $type . " class=\"prettyprint\">".htmlspecialcharsbx( print_r($value, true) )."</" . $type . ">";
        }
        else
        {
            echo "<" . $type . " class=\"prettyprint\">".htmlspecialcharsbx($value)."</" . $type . ">";
        }
    }
}