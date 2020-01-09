<?
/**
 * Import settings page
 * @var CMain $APPLICATION
 */
use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Loader,
    Bitrix\Main\Application,
    Studio7spb\Marketplace\ImportSettingsTable;
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
Loc::loadLanguageFile(__FILE__);
Loader::includeModule("iblock");
$request = Application::getInstance()->getContext()->getRequest();

// <editor-fold defaultstate="collapsed" desc="Set CONSTANTS">

$arParams = array(
    "CONSTANTS" => array(),
    "SELECT" => array(
        "ID",
        "NAME",
        "IBLOCK_ID"
    ),
    "FILTER" => array(
        "IBLOCK_ID" => 2
    )
);

$constants = ImportSettingsTable::getList();
while ($constant = $constants->fetch())
{
    $arParams["CONSTANTS"][$constant["CODE"]] = $constant["VALUE"];
}

// </editor-fold>

$arResult = array(
    "ELEMENTS",
    "PROPERTIES",
    "RESULT"
);

// <editor-fold defaultstate="collapsed" desc="Get properies">
$properties = CIBlockProperty::GetList(array(), $arParams["FILTER"]);
while ($property = $properties->Fetch())
{
    if(
        $property["CODE"] !== "MORE_PHOTO" &&
        $property["CODE"] !== "DISCOUNT" &&
        $property["CODE"] !== "VIDEO"
    ){
        $arParams["SELECT"][] = "PROPERTY_" . $property["CODE"];
        $arResult["PROPERTIES"][$property["ID"]] = $property;
    }
}

// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="Get Elements">

$sTableID = "studio7spb_market_grupper_groups";
$oSort = new CAdminSorting($sTableID, "ID", "desc");
$lAdmin = new CAdminList($sTableID, $oSort);

$rsData = CIBlockElement::GetList(array($by=>$order), $arParams["FILTER"], false, false, $arParams["SELECT"]);
$rsData = new CAdminResult($rsData, $sTableID);
$rsData->NavStart();
$lAdmin->NavText( $rsData->GetNavPrint( GetMessage("GRUPPER_PAGE_NAVI") ) );

$headers = array(
    array("id" => "ID", "content" => Loc::getMessage("LIST_HEADER_ID"), "sort" => "id", "default" => true),
    array("id" => "NAME", "content" => Loc::getMessage("LIST_HEADER_NAME"), "sort" => "name", "default" => true),
    array("id" => "FOB_RMB", "content" => Loc::getMessage("LIST_HEADER_FOB_RMB"), "sort" => "name", "default" => true),
    array("id" => "AQ", "content" => "AQ доставка срочная", "sort" => "code", "default" => true),
    array("id" => "AR", "content" => "AR Дост стнд.", "sort" => "code", "default" => true),
    array("id" => "AS", "content" => "AS таможня срчн.", "sort" => "code", "default" => true),
    array("id" => "AT", "content" => "AT таможня стнд.", "sort" => "code", "default" => true),
    array("id" => "AU", "content" => "AU НДС срчн.", "sort" => "code", "default" => true),
    array("id" => "AV", "content" => "AV НДС стнд.", "sort" => "code", "default" => true),
    array("id" => "AW", "content" => "AW cost срочн.", "sort" => "code", "default" => true),
    array("id" => "AX", "content" => "AX продажа врублях, срочн.", "sort" => "code", "default" => true),
    array("id" => "AY", "content" => "AY cost стнд.", "sort" => "sort", "default" => true),
    array("id" => "AZ", "content" => "AZ продажа врублях, стнд.", "sort" => "code", "default" => true),
    array("id" => "AN", "content" => "AN FOB $", "sort" => "sort", "default" => true)
);

$lAdmin->AddHeaders($headers);

$dl = null;



while ($element = $rsData->Fetch())
{
    /**
     * AQ доставка срочная
     * =U7*AP7/T7
     * =CBM MasterCTN * на Закуб срочная /на PCS MasterCTN=0,217*100/48
     */
    $arResult["RESULT"][$element["ID"]]["AQ"] = array(
        "PROPERTY_NAME" => "AQ доставка срочная",
        "FORMULA" => 'Excel: U7*AP7/T7 comment: CBM MasterCTN * на Закуб срочная /на PCS MasterCTN=0,217*100/48',
        "CONDITION" => $element["PROPERTY_MASTER_CTN_CBM_VALUE"] . " * " . $arParams["CONSTANTS"]["AP"] . " / " . $element["PROPERTY_MASTER_CTN_PCS_VALUE"],
        "RESULT" => $element["PROPERTY_MASTER_CTN_CBM_VALUE"] * $arParams["CONSTANTS"]["AP"] / $element["PROPERTY_MASTER_CTN_PCS_VALUE"]
    );

    /**
     * AR Дост стнд.
     * =U7*AO7/T7
     */
    $arResult["RESULT"][$element["ID"]]["AR"] = array(
        "PROPERTY_NAME" => "AR Дост стнд.",
        "FORMULA" => "Excel: U7*AO7/T7 comment: CBM MasterCTN * на За кубстнд / на PCSMaster CTN=0,217*70/48",
        "CONDITION" => $element["PROPERTY_MASTER_CTN_CBM_VALUE"] . " * " . $arParams["CONSTANTS"]["AO"] . " / " . $element["PROPERTY_MASTER_CTN_PCS_VALUE"],
        "RESULT" => $element["PROPERTY_MASTER_CTN_CBM_VALUE"] * $arParams["CONSTANTS"]["AO"] / $element["PROPERTY_MASTER_CTN_PCS_VALUE"]
    );

    /**
     * AS таможня срчн.
     * =(AQ7+L7/$AV$1)*AI7
     */
    $arResult["RESULT"][$element["ID"]]["AS"] = array(
        "PROPERTY_NAME" => "AS таможня срчн.",
        "FORMULA" => '=(AQ7+L7/$AV$1)*AI7',
        "CONDITION" => "(" . $arResult["RESULT"][$element["ID"]]["AQ"]["RESULT"] . " + " . $element["PROPERTY_EXWORK_RMB_VALUE"] . " / " . $arParams["CONSTANTS"]["AV1"] . ") * " . $arParams["CONSTANTS"]["AI"] . "%",
        "RESULT" => ($arResult["RESULT"][$element["ID"]]["AQ"]["RESULT"] + $element["PROPERTY_EXWORK_RMB_VALUE"] / $arParams["CONSTANTS"]["AV1"]) * ($arParams["CONSTANTS"]["AI"] / 100)
    );

    /**
     * AT таможня стнд.
     * =(AR7+М7/$AV$1)*AI7
     */
    $arResult["RESULT"][$element["ID"]]["AT"] = array(
        "PROPERTY_NAME" => "AT таможня стнд.",
        "FORMULA" => '=(AR7+М7/$AV$1)*AI7',
        "CONDITION" => "(" . $arResult["RESULT"][$element["ID"]]["AR"]["RESULT"] . " + " . $element["PROPERTY_FOB_RMB_VALUE"] . " / " . $arParams["CONSTANTS"]["AV1"] . ") * " . $arParams["CONSTANTS"]["AI"] . "%",
        "RESULT" => ($arResult["RESULT"][$element["ID"]]["AR"]["RESULT"] + $element["PROPERTY_FOB_RMB_VALUE"] / $arParams["CONSTANTS"]["AV1"]) * ($arParams["CONSTANTS"]["AI"] / 100)
    );

    /**
     * AU НДС срчн.
     * =(L7/$AV$1+AQ7+AS7)*AJ7
     */
    $arResult["RESULT"][$element["ID"]]["AU"] = array(
        "PROPERTY_NAME" => "AU НДС срчн.",
        "FORMULA" => '=(L7/$AV$1+AQ7+AS7)*AJ7',
        "CONDITION" => "(" . $element["PROPERTY_EXWORK_RMB_VALUE"] . " / " . $arParams["CONSTANTS"]["AV1"] . " + " . $arResult["RESULT"][$element["ID"]]["AQ"]["RESULT"] . " + " . $arResult["RESULT"][$element["ID"]]["AS"]["RESULT"] . ") * " . $arParams["CONSTANTS"]["AJ"] . "%",
        "RESULT" => ($element["PROPERTY_EXWORK_RMB_VALUE"] / $arParams["CONSTANTS"]["AV1"] + $arResult["RESULT"][$element["ID"]]["AQ"]["RESULT"] + $arResult["RESULT"][$element["ID"]]["AS"]["RESULT"]) * ($arParams["CONSTANTS"]["AJ"] / 100)
    );

    /**
     * AV НДС стнд.
     * =(М7/$AV$1+AR7+AT7)*AJ7
     */
    $arResult["RESULT"][$element["ID"]]["AV"] = array(
        "PROPERTY_NAME" => "AV НДС стнд.",
        "FORMULA" => '=(М7/$AV$1+AR7+AT7)*AJ7',
        "CONDITION" => "(" . $element["PROPERTY_FOB_RMB_VALUE"] . " / " . $arParams["CONSTANTS"]["AV1"] . " + " . $arResult["RESULT"][$element["ID"]]["AR"]["RESULT"] . " + " . $arResult["RESULT"][$element["ID"]]["AT"]["RESULT"] . ") * " . $arParams["CONSTANTS"]["AJ"] . "%",
        "RESULT" => ($element["PROPERTY_FOB_RMB_VALUE"] / $arParams["CONSTANTS"]["AV1"] + $arResult["RESULT"][$element["ID"]]["AR"]["RESULT"] + $arResult["RESULT"][$element["ID"]]["AT"]["RESULT"]) * ($arParams["CONSTANTS"]["AJ"] / 100)
    );

    /**
     * AW cost срочн.
     * =L7/$AV$1+AS7+AQ7+AU7
     */
    $arResult["RESULT"][$element["ID"]]["AW"] = array(
        "PROPERTY_NAME" => "AW cost срочн.",
        "FORMULA" => '=L7/$AV$1+AS7+AQ7+AU7',
        "CONDITION" => $element["PROPERTY_EXWORK_RMB_VALUE"] . " / " . $arParams["CONSTANTS"]["AV1"] . " + " . $arResult["RESULT"][$element["ID"]]["AS"]["RESULT"] . " + " . $arResult["RESULT"][$element["ID"]]["AQ"]["RESULT"] . " + " . $arResult["RESULT"][$element["ID"]]["AU"]["RESULT"],
        "RESULT" => $element["PROPERTY_EXWORK_RMB_VALUE"] / $arParams["CONSTANTS"]["AV1"] + $arResult["RESULT"][$element["ID"]]["AS"]["RESULT"] + $arResult["RESULT"][$element["ID"]]["AQ"]["RESULT"] + $arResult["RESULT"][$element["ID"]]["AU"]["RESULT"]
    );

    /**
     * AX продажа врублях, срочн.
     * AW7*(1+AM7)*$AZ$1
     */
    $arResult["RESULT"][$element["ID"]]["AX"] = array(
        "PROPERTY_NAME" => "AX продажа врублях, срочн.",
        "FORMULA" => 'AW7*(1+AM7)*$AZ$1',
        "CONDITION" => $arResult["RESULT"][$element["ID"]]["AW"]["RESULT"] . " * (1+" . ($element["PROPERTY_DISCOUNT_DDP_FAST_VALUE"] ? intval($element["PROPERTY_DISCOUNT_DDP_FAST_VALUE"]) : $arParams["CONSTANTS"]["AM"]) . " / 100) * " . $arParams["CONSTANTS"]["AZ1"],
        "RESULT" => $arResult["RESULT"][$element["ID"]]["AW"]["RESULT"] * (1 + ($element["PROPERTY_DISCOUNT_DDP_FAST_VALUE"] ? intval($element["PROPERTY_DISCOUNT_DDP_FAST_VALUE"]) : $arParams["CONSTANTS"]["AM"]) / 100) * $arParams["CONSTANTS"]["AZ1"]
    );

    /**
     * AY cost стнд.
     * =М7/$AV$1+AT7+AR7+AV7
     */
    $arResult["RESULT"][$element["ID"]]["AY"] = array(
        "PROPERTY_NAME" => "AY cost стнд.",
        "FORMULA" => '=М7/$AV$1+AT7+AR7+AV7',
        "CONDITION" => $element["PROPERTY_FOB_RMB_VALUE"] . " / " . $arParams["CONSTANTS"]["AV1"] . " + " . $arResult["RESULT"][$element["ID"]]["AT"]["RESULT"] . " + " . $arResult["RESULT"][$element["ID"]]["AR"]["RESULT"] . " + " . $arResult["RESULT"][$element["ID"]]["AV"]["RESULT"],
        "RESULT" => $element["PROPERTY_FOB_RMB_VALUE"] / $arParams["CONSTANTS"]["AV1"] + $arResult["RESULT"][$element["ID"]]["AT"]["RESULT"] + $arResult["RESULT"][$element["ID"]]["AR"]["RESULT"] + $arResult["RESULT"][$element["ID"]]["AV"]["RESULT"]
    );

    /**
     * AZ продажа врублях, стнд.
     * AY7*(1+AL7)*$AZ$1
     */
    $arResult["RESULT"][$element["ID"]]["AZ"] = array(
        "PROPERTY_NAME" => "AZ продажа врублях, стнд.",
        "FORMULA" => 'AY7*(1+AL7)*$AZ$1',
        "CONDITION" => $arResult["RESULT"][$element["ID"]]["AY"]["RESULT"] . " * (1+" . ($element["PROPERTY_DISCOUNT_DDP_VALUE"] ? intval($element["PROPERTY_DISCOUNT_DDP_VALUE"]) : $arParams["CONSTANTS"]["AL"]) . " / 100) * " . $arParams["CONSTANTS"]["AZ1"],
        "RESULT" => $arResult["RESULT"][$element["ID"]]["AY"]["RESULT"] * (1 + ($element["PROPERTY_DISCOUNT_DDP_VALUE"] ? intval($element["PROPERTY_DISCOUNT_DDP_VALUE"]) : $arParams["CONSTANTS"]["AL"]) / 100) * $arParams["CONSTANTS"]["AZ1"]
    );

    /**
     * AN FOB $
     * =M7*(100%+AK7)/$AO$1
     */
    $arResult["RESULT"][$element["ID"]]["AN"] = array(
        "PROPERTY_NAME" => "AN FOB $",
        "FORMULA" => 'M7/$AO$1*(100%+AK7)  comment: = [FOB, RMB]/7* (100% + [Нацна FOB])= 13,42/7*(100%+5%)',
        "CONDITION" => $element["PROPERTY_FOB_RMB_VALUE"] . " / " . $arParams["CONSTANTS"]["AV1"] . " * ((100+" . ($element["PROPERTY_DISCOUNT_FOB_VALUE"] ? intval($element["PROPERTY_DISCOUNT_FOB_VALUE"]) : $arParams["CONSTANTS"]["AK"]) .") / 100)",
        "RESULT" => $element["PROPERTY_FOB_RMB_VALUE"] / $arParams["CONSTANTS"]["AV1"] * ((100 + ($element["PROPERTY_DISCOUNT_FOB_VALUE"] ? intval($element["PROPERTY_DISCOUNT_FOB_VALUE"]) : $arParams["CONSTANTS"]["AK"])) / 100)
    );


    $row =& $lAdmin->AddRow($element["ID"], $element);
    $row->AddViewField("ID", $element["ID"]);
    $row->AddViewField("NAME", '<a href="' . "iblock_element_edit.php?IBLOCK_ID=" . $element["IBLOCK_ID"] . "&type=marketplace&ID=".$element["ID"]."&lang=".LANG . '" target="_blank">' . $element["NAME"] . '</a>');
    $row->AddViewField("FOB_RMB", $element["PROPERTY_FOB_RMB_VALUE"]);





    foreach ($headers as $header){

        if(
            $header["id"] == "ID" ||
            $header["id"] == "NAME" ||
            $header["id"] == "FOB_RMB"
        )
            continue;

        $dl = '<dl style="white-space: nowrap;">';
        $dl .= '<dt>' . Loc::getMessage("FORMULA") . ":<dt><dd>" . $arResult["RESULT"][$element["ID"]][$header["id"]]["FORMULA"] . '</dd>';
        $dl .= '<dt>' . Loc::getMessage("CONDITION") . ":<dt><dd>" . $arResult["RESULT"][$element["ID"]][$header["id"]]["CONDITION"] . '</dd>';
        $dl .= '<dt>' . Loc::getMessage("RESULT") . ":<dt><dd>" . $arResult["RESULT"][$element["ID"]][$header["id"]]["RESULT"] . '</dd>';
        $dl .= '</dl>';

        $row->AddViewField($header["id"], $dl);
    }

    $row->AddActions(array(
        array(
            "ICON" => "edit",
            "DEFAULT" => true,
            "TEXT" => Loc::getMessage("GOTO_ELEMENT"),
            "ACTION" => $lAdmin->ActionRedirect("iblock_element_edit.php?IBLOCK_ID=" . $element["IBLOCK_ID"] . "&type=marketplace&ID=".$element["ID"]."&lang=".LANG)
        )
    ));

}
// </editor-fold>


$APPLICATION->SetTitle( Loc::getMessage("TITLE") );
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
$lAdmin->DisplayList();
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>