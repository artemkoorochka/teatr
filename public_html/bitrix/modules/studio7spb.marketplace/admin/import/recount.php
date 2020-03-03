<?
/**
 * Import settings page
 * @var CMain $APPLICATION
 */
use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Application,
    Studio7spb\Marketplace\CMarketplaceOptions,
    Studio7spb\Marketplace\ImportSettingsTable,
    Bitrix\Main\Loader,
    Bitrix\Sale\Basket,
    Bitrix\Iblock\ElementTable;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
Loc::loadLanguageFile(__FILE__);

$CHECK_RIGHT = $APPLICATION->GetGroupRight("main");
if ($CHECK_RIGHT == "D")
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));

$request = Application::getInstance()->getContext()->getRequest();

// <editor-fold defaultstate="collapsed" desc="# Prepare properties">

$arParams = array(
    "MODULES" => ["sale", "iblock"],
    "CATALOG_IBLOCK_ID" => CMarketplaceOptions::getInstance()->getOption("catalog_iblock_id"),
    "PAGE" => $APPLICATION->GetCurPage(),
    "LIMIT" => 1
);

if(!empty($arParams["MODULES"])){
    foreach ($arParams["MODULES"] as $module){
        Loader::includeModule($module);
    }
}
// </editor-fold>



$arResult = [
    "CONSTANTS" => [],
    "ORDER_BASKET_ITEMS" => [], // Ордернутые товары
    "CATALOG_ITEMS" => []
];

// <editor-fold defaultstate="collapsed" desc="# Get settings">
$constants = ImportSettingsTable::getList();
while ($constant = $constants->fetch())
{
    $arResult["CONSTANTS"][$constant["CODE"]] = $constant["VALUE"];
}
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc=# "Work process">
if($request->get("work_start") && check_bitrix_sessid())
{
    $rsEl = CIBlockElement::GetList(array("ID" => "ASC"), array("IBLOCK_ID" => $arParams["CATALOG_IBLOCK_ID"], ">ID" => $request->get("lastid")), false, array("nTopCount" => $arParams["LIMIT"]));
    while ($arEl = $rsEl->Fetch())
    {
        /*
         * do something
         */
        $lastID = intval($arEl["ID"]);
    }

    $rsLeftBorder = CIBlockElement::GetList(array("ID" => "ASC"), array("IBLOCK_ID" => $arParams["CATALOG_IBLOCK_ID"], "<=ID" => $lastID));
    $leftBorderCnt = $rsLeftBorder->SelectedRowsCount();

    $rsAll = CIBlockElement::GetList(array("ID" => "ASC"), array("IBLOCK_ID" => $arParams["CATALOG_IBLOCK_ID"]));
    $allCnt = $rsAll->SelectedRowsCount();

    $p = round(100*$leftBorderCnt/$allCnt, 2);

    echo 'CurrentStatus = Array('.$p.',"'.($p < 100 ? '&lastid='.$lastID : '').'","Обрабатывается товар ID #'.$lastID.' new curse: ' . $request->get("curse") . '");';

    die();
}
// </editor-fold>

$clean_test_table = '<table id="result_table" cellpadding="0" cellspacing="0" border="0" width="100%" class="internal">'.
    '<tr class="heading">'.
    '<td>' . Loc::getMessage("RECOUNT_WORK_STATUS") . '</td>'.
    '</tr>'.
    '<tr>'.
    '<td id="result_table_status"></td>'.
    '</tr>'.
    '</table>';


$aTabs = array(array("DIV" => "edit1", "TAB" => Loc::getMessage("RECOUNT_WORK_TAB")));
$tabControl = new CAdminTabControl("tabControl", $aTabs);

$APPLICATION->SetTitle( Loc::getMessage("RECOUNT_TITLE") );
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>
    <script type="text/javascript">

        var bWorkFinished = false,
            bSubmit,
            newCurse,
            newCurseVal = 0;

        function set_start(val)
        {
            // validate
            newCurse = document.getElementById("new-curse-val"),
            newCurse.disabled = true;
            newCurseVal = newCurse.value;
            newCurseVal = parseFloat(newCurseVal);

            if(newCurseVal > 0){
                // start process
                document.getElementById('work_start').disabled = val ? 'disabled' : '';
                document.getElementById('work_stop').disabled = val ? '' : 'disabled';
                document.getElementById('progress').style.display = val ? 'block' : 'none';

                if (val)
                {
                    ShowWaitWindow();
                    document.getElementById('result').innerHTML = '<?=$clean_test_table?>';
                    document.getElementById('status').innerHTML = '<?=Loc::getMessage("RECOUNT_WORK_PROGRESS")?>';

                    document.getElementById('percent').innerHTML = '0%';
                    document.getElementById('indicator').style.width = '0%';

                    CHttpRequest.Action = work_onload;
                    CHttpRequest.Send('<?= $_SERVER["PHP_SELF"]?>?work_start=Y&lang=<?=LANGUAGE_ID?>&<?=bitrix_sessid_get()?>&curse=' + newCurseVal);
                }
                else{
                    CloseWaitWindow();
                    newCurse.disabled = false;
                }
            }else{
                alert('<?=Loc::getMessage("RECOUNT_VALIDATE_ERROR")?>');
                newCurse.disabled = false;
            }
        }

        function work_onload(result)
        {
            try
            {
                eval(result);

                iPercent = CurrentStatus[0];
                strNextRequest = CurrentStatus[1];
                strCurrentAction = CurrentStatus[2];

                document.getElementById('percent').innerHTML = iPercent + '%';
                document.getElementById('indicator').style.width = iPercent + '%';

                document.getElementById('status').innerHTML = '<?=Loc::getMessage("RECOUNT_WORK_PROGRESS")?>';

                if (strCurrentAction != 'null')
                {
                    oTable = document.getElementById('result_table_status');
                    oTable.innerHTML = strCurrentAction;
                }

                if (strNextRequest && document.getElementById('work_start').disabled)
                    CHttpRequest.Send('<?= $_SERVER["PHP_SELF"]?>?work_start=Y&lang=<?=LANGUAGE_ID?>&<?=bitrix_sessid_get()?>' + strNextRequest + '&curse=' + newCurseVal);
                else
                {
                    set_start(0);
                    bWorkFinished = true;
                }

            }
            catch(e)
            {
                CloseWaitWindow();
                document.getElementById('work_start').disabled = '';
                alert('<?=Loc::getMessage("RECOUNT_WORK_DATA_ERROR")?>');
            }
        }

    </script>

    <form method="post" action="<?=$arParams["PAGE"]?>"
          name="post_form"
          id="post_form">
        <?
        echo bitrix_sessid_post();

        $tabControl->Begin();
        $tabControl->BeginNextTab();
        ?>
        <tr>
            <td width="40%" align="right">
                <?=Loc::getMessage("RECOUNT_WORK_NEW_VALUE")?>
            </td>
            <td>
                <input type="text"
                       value="2"
                       id="new-curse-val">
            </td>
        </tr>
        <tr>
            <td colspan="2">

                <input type=button value="<?=Loc::getMessage("RECOUNT_START")?>" id="work_start" onclick="set_start(1)" />
                <input type=button value="<?=Loc::getMessage("RECOUNT_STOP")?>" disabled id="work_stop" onclick="bSubmit=false;set_start(0)" />
                <div id="progress" style="display:none;" width="100%">
                    <br />
                    <div id="status"></div>
                    <table border="0" cellspacing="0" cellpadding="2" width="100%">
                        <tr>
                            <td height="10">
                                <div style="border:1px solid #B9CBDF">
                                    <div id="indicator" style="height:10px; width:0%; background-color:#B9CBDF"></div>
                                </div>
                            </td>
                            <td width=30>&nbsp;<span id="percent">0%</span></td>
                        </tr>
                    </table>
                </div>
                <div id="result" style="padding-top:10px"></div>

            </td>
        </tr>
        <?
        $tabControl->End();
        ?>
    </form>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>