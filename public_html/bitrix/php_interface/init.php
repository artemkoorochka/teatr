<?
use Bitrix\Main\EventManager,
    Bitrix\Main\Loader,
    Studio7spb\Marketplace\SaleAddressTable,
    Studio7spb\Marketplace\RequisitsTable;

$eventManager = EventManager::getInstance();

/**
 * order events
 */
$eventManager->AddEventHandler("sale", "OnSaleComponentOrderOneStepOrderProps", "OnSaleComponentOrderOneStepOrderPropsHandler");
$eventManager->AddEventHandler("sale", "OnBeforeOrderAdd", "OnBeforeOrderAddHandler");

/**
 * Iblock element events
 */
$eventManager->AddEventHandler("iblock", "OnAfterIBlockElementAdd", array("lansyPriceGenerator", "OnAfterIBlockElementUpdateHandler"));
$eventManager->AddEventHandler("iblock", "OnAfterIBlockElementUpdate", array("lansyPriceGenerator", "OnAfterIBlockElementUpdateHandler"));

/**
 * callbacks
 */
include "include/lansy/lansy.price.generator.php";

// <editor-fold defaultstate="Component one step order.">
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

    /**
     * Location injection
     */
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

        if($USER->GetID() && !empty($collect)){
            $adress = SaleAddressTable::getList(array(
                "filter" => array(
                    "PROFILE_ID" => $arUserResult["PROFILE_ID"],
                    "USER_ID" => $USER->GetID()
                )
            ));
            if($adress = $adress->fetch())
            {
                //$arUserResult["USER_VALS"]["ORDER_PROP"][3] = $adress["LOCATION"];
                foreach ($arResult["ORDER_PROP"]["USER_PROPS_Y"] as $property){
                    $arUserResult["ORDER_PROP"][$property["ID"]] = $adress["LOCATION"];
                }
                foreach ($arResult["ORDER_PROP"]["USER_PROPS_N"] as $key=>$property){
                    $arUserResult["ORDER_PROP"][$property["ID"]] = $adress["LOCATION"];
                }
            }
        }

    }

    /**
     * User requisits feature
     */
    if($USER->IsAuthorized()){
        // get user requisits
        $userRequisits = RequisitsTable::getList(array(
            "filter" => array(
                "USER_ID" => $USER->GetID()
            )
        ))->fetch();

        foreach ($arResult["ORDER_PROP"]["USER_PROPS_N"] as $key=>$property){
            if(empty($property["VALUE"])){
                $arUserResult["ORDER_PROP"][$property["ID"]] = $userRequisits[str_replace("COMPANY_", "", $property["CODE"])];
            }
        }
    }

}
// </editor-fold>

// <editor-fold defaultstate="OnOrderAdd после добавления заказа">

function OnBeforeOrderAddHandler(&$arFields){

    $countProps = count($arFields["ORDER_PROP"]);
    $countPropsFill = 0;
    foreach ($arFields["ORDER_PROP"] as $value)
    {
        if(!empty($value)){
            $countPropsFill++;
        }
    }
    if($countProps > $countPropsFill){
        $arFields["USER_DESCRIPTION"] = "Покупатель запросил обратный звонок. Номер " . $arFields["ORDER_PROP"][22];
    }

}

// </editor-fold>

// <editor-fold defaultstate="Developer tools">

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

// </editor-fold>
