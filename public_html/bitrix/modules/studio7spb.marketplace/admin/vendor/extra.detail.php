<?
/**
 * Extra detail page
 * @var CMain $APPLICATION
 */
use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Application,
    Studio7spb\Marketplace\CMarketplaceOptions;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
Loc::loadLanguageFile(__FILE__);

$request = Application::getInstance()->getContext()->getRequest();

// <editor-fold defaultstate="collapsed" desc="Set CONSTANTS">

$arParams = [
    "FILTER" => [
        "IBLOCK_ID" => CMarketplaceOptions::getInstance()->getOption("company_iblock_id")
    ],
    "SELECT" => [
        "ID",
        "NAME"
    ]
];


// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="Fill static archive">
CModule::AddAutoloadClasses(
    "studio7spb.marketplace",
    array(
        "\\Studio7spb\\Marketplace\\StaticTable" => "lib/lansy/static.php"
    )
);

// </editor-fold>



$APPLICATION->SetTitle( Loc::getMessage("TITLE") );
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>

    <form method="POST" name="frm" id="frm">
        <?
        echo bitrix_sessid_post();
        $aTabs = array(
            array(
                "DIV" => "edit1",
                "TAB" => GetMessage("TITLE"),
                "ICON" => "iblock",
                "TITLE" => GetMessage("TITLE"),
            )
        );

        $tabControl = new CAdminTabControl("tabControl", $aTabs);
        $tabControl->Begin();

        $tabControl->BeginNextTab();
        ?>
        <?foreach ($arParams["CONSTANTS"] as $key=>$value):?>
            <tr>
                <td width="240"><?
                    echo "title";
                    ?>
                </td>
                <td>
                    <input type="text"
                           name="CONSTANTS[<?=$key?>]"
                           value="<?=$value?>">
                </td>
            </tr>
        <?endforeach;?>
        <?
        $tabControl->Buttons(array());
        $tabControl->End();
        ?>
    </form>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>