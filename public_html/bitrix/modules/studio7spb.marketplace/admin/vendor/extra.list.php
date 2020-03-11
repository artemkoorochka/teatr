<?
/**
 * Import settings page
 * @var CMain $APPLICATION
 */
use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Loader,
    Studio7spb\Marketplace\CMarketplaceOptions;
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
Loc::loadLanguageFile(__FILE__);
Loader::includeModule("iblock");

$arParams = [
    "FILTER" => [
        "IBLOCK_ID" => CMarketplaceOptions::getInstance()->getOption("company_iblock_id")
    ],
    "SELECT" => [
        "ID",
        "NAME"
    ]
];

// <editor-fold defaultstate="collapsed" desc="Get vendor List">



// </editor-fold>




// <editor-fold defaultstate="collapsed" desc="Get Elements">

$sTableID = "import_calculate";
$oSort = new CAdminSorting($sTableID, "ID", "desc");
$lAdmin = new CAdminList($sTableID, $oSort);
$rsData = CIBlockElement::GetList(array($by=>$order), $arParams["FILTER"], false, false, $arParams["SELECT"]);
$rsData = new CAdminResult($rsData, $sTableID);
$rsData->NavStart();
$lAdmin->NavText( $rsData->GetNavPrint( GetMessage("GRUPPER_PAGE_NAVI") ) );

$headers = array(
    array("id" => "ID", "content" => Loc::getMessage("VENDOR_ID"), "sort" => "id", "default" => true),
    array("id" => "NAME", "content" => Loc::getMessage("VENDOR_NAME"), "sort" => "name", "default" => true),

);

$lAdmin->AddHeaders($headers);

$dl = null;



while ($element = $rsData->Fetch())
{


    $row =& $lAdmin->AddRow($element["ID"], $element);
    $row->AddViewField("ID", $element["ID"]);
    $row->AddViewField("NAME", '<a href="' . "iblock_element_edit.php?IBLOCK_ID=" . $element["IBLOCK_ID"] . "&type=marketplace&ID=".$element["ID"]."&lang=".LANG . '" target="_blank">' . $element["NAME"] . '</a>');
    $row->AddViewField("FOB_RMB", $element["PROPERTY_FOB_RMB_VALUE"]);




    $row->AddActions(array(
        array(
            "ICON" => "edit",
            "DEFAULT" => true,
            "TEXT" => Loc::getMessage("VENDOR_DETAIL_PAGE"),
            "ACTION" => $lAdmin->ActionRedirect("studio7spb.marketplace_vendor_extra_detail.php?vendor=" . $element["ID"] . "&lang=".LANG)
        )
    ));

}
// </editor-fold>


// ******************************************************************** //
//                ВЫВОД                                                 //
// ******************************************************************** //

// альтернативный вывод
$lAdmin->CheckListMode();

// установим заголовок страницы
$APPLICATION->SetTitle( Loc::getMessage("VENDOR_TITLE") );

// не забудем разделить подготовку данных и вывод
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>

<?
// выведем таблицу списка элементов
$lAdmin->DisplayList();
?>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>