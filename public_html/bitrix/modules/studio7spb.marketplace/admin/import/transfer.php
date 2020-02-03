<?
use Bitrix\Main\Loader,
    Bitrix\Main\Config\Option,
    Bitrix\Main\Localization\Loc;

/**
 * https://dev.1c-bitrix.ru/community/webdev/user/11948/blog/2047/
 */

// <editor-fold defaultstate="collapsed" desc=" # Prepare">

$arParams = array(
    "MODULE" => "studio7spb.marketplace",
    "FILE" => "IMPORT_DATA_FILE",
    "START_IMPORT" => "work_start",
    "STOP_IMPORT" => "work_stop",
    "PROGRESS_IMPORT" => "work_progress",
    "START_ANALIZE" => "analize_start",
    "STOP_ANALIZE" => "analize_stop",
    "PROGRESS_ANALIZE" => "analize_progress"
);

$arResult = array(
    "FILE" => null
);

if (isset($_REQUEST[$arParams["START_IMPORT"]]))
{
    define("NO_AGENT_STATISTIC", true);
    define("NO_KEEP_STATISTIC", true);
}
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
Loader::includeModule("iblock");
Loc::loadLanguageFile(__FILE__);

$POST_RIGHT = $APPLICATION->GetGroupRight("main");
if ($POST_RIGHT == "D")
    $APPLICATION->AuthForm("Доступ запрещен");

$BID = 2;
$limit = 10;

// </editor-fold>

// <editor-fold defaultstate="collapsed" desc=" # One sep process">

if($_REQUEST[$arParams["START_ANALIZE"]] == "Y" && check_bitrix_sessid())
{
    $rsEl = CIBlockElement::GetList(array("ID" => "ASC"), array("IBLOCK_ID" => $BID, ">ID" => $_REQUEST["lastid"]), false, array("nTopCount" => $limit));
    while ($arEl = $rsEl->Fetch())
    {
        /*
         * do something
         */
        $lastID = intval($arEl["ID"]);
    }

    $rsLeftBorder = CIBlockElement::GetList(array("ID" => "ASC"), array("IBLOCK_ID" => $BID, "<=ID" => $lastID));
    $leftBorderCnt = $rsLeftBorder->SelectedRowsCount();

    $rsAll = CIBlockElement::GetList(array("ID" => "ASC"), array("IBLOCK_ID" => $BID));
    $allCnt = $rsAll->SelectedRowsCount();

    $p = round(100*$leftBorderCnt/$allCnt, 2);

    echo 'CurrentStatus = Array('.$p.',"'.($p < 100 ? '&lastid='.$lastID : '').'","Проверен товар №: '.$lastID.'");';

    die();
}

if($_REQUEST[$arParams["START_IMPORT"]] && check_bitrix_sessid())
{
    $rsEl = CIBlockElement::GetList(array("ID" => "ASC"), array("IBLOCK_ID" => $BID, ">ID" => $_REQUEST["lastid"]), false, array("nTopCount" => $limit));
    while ($arEl = $rsEl->Fetch())
    {
        /*
         * do something
         */
        $lastID = intval($arEl["ID"]);
    }

    $rsLeftBorder = CIBlockElement::GetList(array("ID" => "ASC"), array("IBLOCK_ID" => $BID, "<=ID" => $lastID));
    $leftBorderCnt = $rsLeftBorder->SelectedRowsCount();

    $rsAll = CIBlockElement::GetList(array("ID" => "ASC"), array("IBLOCK_ID" => $BID));
    $allCnt = $rsAll->SelectedRowsCount();

    $p = round(100*$leftBorderCnt/$allCnt, 2);

    echo 'CurrentStatus = Array('.$p.',"'.($p < 100 ? '&lastid='.$lastID : '').'","Обрабаботан товар №: '.$lastID.'");';

    die();
}

// </editor-fold>

// <editor-fold defaultstate="collapsed" desc=" # Formating script">
$arResult["CLEAN_IMPORT_TABLE"] = '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="internal">'.
    '<tr class="heading">'.
    '<td>Текущий процесс</td>'.
    '</tr>'.
    '<tr>'.
    '<td id="' . $arParams["START_IMPORT"] . '-info">&nbsp;</td>'.
    '</tr>'.
    '</table>';

$arResult["CLEAN_ANALIZE_TABLE"] = '<table cellpadding="0" id="' . $arParams["START_ANALIZE"] . '-info" cellspacing="0" border="0" width="100%" class="internal">'.
    '<tr class="heading">'.
    '<td>Текущее действие</td>'.
    '<td width="1%">&nbsp;</td>'.
    '</tr>'.
    '</table>';
// </editor-fold>


$aTabs = array(
    array("DIV" => $arParams["FILE"], "TAB" => "Загрузка файла импорта", "ICON"=>"main_user_edit", "TITLE" => "Загрузка файла импорта"),
    array("DIV" => $arParams["FILE"] . "_ANALIZE", "TAB" => "Проверка файла импорта", "ICON"=>"main_user_edit", "TITLE" => "Проверка файла импорта"),
    array("DIV" => $arParams["FILE"] . "_IMPORT", "TAB" => "Импорт товаров", "ICON"=>"main_user_edit", "TITLE" => "Импорт товаров")
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);

$APPLICATION->SetTitle("Импорт товаров");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

// <editor-fold defaultstate="collapsed" desc=" # Save import file">
$arResult["FILE"] = Option::get($arParams["MODULE"], $arParams["FILE"]);
if(!empty($_FILES[$arParams["FILE"]])){
    if($arResult["FILE"] > 0){
        CFile::Delete($arResult["FILE"]);
    }
    $arResult["FILE"] = CFile::SaveFile($_FILES[$arParams["FILE"]], "excel");
    Option::set($arParams["MODULE"], $arParams["FILE"], $arResult["FILE"]);
    LocalRedirect($APPLICATION->GetCurPageParam("file=upload", array("file")));
}

if($arResult["FILE"] > 0){
    $arResult["FILE"] = CFile::GetFileArray($arResult["FILE"]);
}
else{
    $arResult["FILE"] = null;
}
// </editor-fold>

?>

    <form method="post" action="<?echo $APPLICATION->GetCurPage()?>" enctype="multipart/form-data" name="post_form" id="post_form">
        <?
        echo bitrix_sessid_post();

        $tabControl->Begin();
        $tabControl->BeginNextTab();
        ?>
            <tr>
                <td width="40%">
                    Файл для загрузки
                </td>
                <td>
                    <?
                    echo \CFileInput::Show(
                        $arParams["FILE"], // name
                        $arResult["FILE"]["ID"], // value
                        array(
                            "IMAGE" => "N",
                            "PATH" => "Y",
                            "FILE_SIZE" => "Y",
                            "DIMENSIONS" => "N"
                        ),
                        array(
                            'upload' => true,
                            'medialib' => false,
                            'file_dialog' => true,
                            'cloud' => true,
                            'email' => true,
                            'linkauth' => true,
                            'del' => false,
                            'description' => false
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit"
                           value="Загрузить">
                </td>
            </tr>
        <?
        $tabControl->BeginNextTab();
        ?>

            <tr>
                <td colspan="2">

                    <input type=button value="Запустить" id="<?=$arParams["START_ANALIZE"]?>" onclick="analizeStart(1)" />
                    <input type=button value="Остановить" disabled id="<?=$arParams["STOP_ANALIZE"]?>" onclick="bSubmit=false;analizeStart(0)" />
                    <div id="<?=$arParams["PROGRESS_ANALIZE"]?>" style="display:none;" width="100%">
                        <br />
                        <div id="<?=$arParams["START_ANALIZE"]?>-status"></div>
                        <table border="0" cellspacing="0" cellpadding="2" width="100%">
                            <tr>
                                <td height="10">
                                    <div style="border:1px solid #B9CBDF">
                                        <div id="<?=$arParams["START_ANALIZE"]?>-indicator" style="height:10px; width:0%; background-color:#B9CBDF"></div>
                                    </div>
                                </td>
                                <td width=30>&nbsp;<span id="<?=$arParams["START_ANALIZE"]?>-percent">0%</span></td>
                            </tr>
                        </table>
                    </div>
                    <div id="<?=$arParams["START_ANALIZE"]?>-result" style="padding-top:10px"></div>

                </td>
            </tr>

        <?
        $tabControl->BeginNextTab();
        ?>

        <tr>
            <td colspan="2">

                <input type=button value="Запустить" id="<?=$arParams["START_IMPORT"]?>" onclick="import_start(1)" />
                <input type=button value="Остановить" disabled id="<?=$arParams["STOP_IMPORT"]?>" onclick="bSubmit=false;import_start(0)" />
                <div id="<?=$arParams["PROGRESS_IMPORT"]?>" style="display:none;" width="100%">
                    <br />
                    <div id="<?=$arParams["START_IMPORT"]?>-status"></div>
                    <table border="0" cellspacing="0" cellpadding="2" width="100%">
                        <tr>
                            <td height="10">
                                <div style="border:1px solid #B9CBDF">
                                    <div id="<?=$arParams["START_IMPORT"]?>-indicator" style="height:10px; width:0%; background-color:#B9CBDF"></div>
                                </div>
                            </td>
                            <td width=30>&nbsp;<span id="<?=$arParams["START_IMPORT"]?>-percent">0%</span></td>
                        </tr>
                    </table>
                </div>
                <div id="<?=$arParams["START_IMPORT"]?>-result" style="padding-top:10px"></div>

            </td>
        </tr>
        <?
        $tabControl->End();
        ?>
    </form>

    <script type="text/javascript">

        var bWorkFinished = false;
        var bSubmit;

        /**
         * import start
         * @param val
         */
        function import_start(val)
        {
            document.getElementById('<?=$arParams["START_IMPORT"]?>').disabled = val ? 'disabled' : '';
            document.getElementById('<?=$arParams["STOP_IMPORT"]?>').disabled = val ? '' : 'disabled';
            document.getElementById('<?=$arParams["PROGRESS_IMPORT"]?>').style.display = val ? 'block' : 'none';

            if (val)
            {
                ShowWaitWindow();
                document.getElementById('<?=$arParams["START_IMPORT"]?>-result').innerHTML = '<?=$arResult["CLEAN_IMPORT_TABLE"]?>';
                document.getElementById('<?=$arParams["START_IMPORT"]?>-status').innerHTML = 'Идёт прогресс импорта...';

                document.getElementById('<?=$arParams["START_IMPORT"]?>-percent').innerHTML = '0%';
                document.getElementById('<?=$arParams["START_IMPORT"]?>-indicator').style.width = '0%';

                CHttpRequest.Action = import_onload;
                CHttpRequest.Send('<?= $_SERVER["PHP_SELF"]?>?<?=$arParams["START_IMPORT"]?>=Y&lang=<?=LANGUAGE_ID?>&<?=bitrix_sessid_get()?>');
            }
            else
                CloseWaitWindow();
        }

        function import_onload(result)
        {
            try
            {
                eval(result);

                iPercent = CurrentStatus[0];
                strNextRequest = CurrentStatus[1];
                strCurrentAction = CurrentStatus[2];

                document.getElementById('<?=$arParams["START_IMPORT"]?>-percent').innerHTML = iPercent + '%';
                document.getElementById('<?=$arParams["START_IMPORT"]?>-indicator').style.width = iPercent + '%';

                document.getElementById('<?=$arParams["START_IMPORT"]?>-status').innerHTML = 'Идёт прогресс импорта...';

                if (strCurrentAction != 'null')
                {
                    resultTableInfo = document.getElementById('<?=$arParams["START_IMPORT"]?>-info');
                    resultTableInfo.innerHTML = strCurrentAction;
                }

                if (strNextRequest && document.getElementById('<?=$arParams["START_IMPORT"]?>').disabled)
                    CHttpRequest.Send('<?= $_SERVER["PHP_SELF"]?>?<?=$arParams["START_IMPORT"]?>=Y&lang=<?=LANGUAGE_ID?>&<?=bitrix_sessid_get()?>' + strNextRequest);
                else
                {
                    import_start(0);
                    bWorkFinished = true;
                }

            }
            catch(e)
            {
                CloseWaitWindow();
                document.getElementById('<?=$arParams["START_IMPORT"]?>').disabled = '';
                alert('Сбой в получении данных');
            }
        }

        /**
         * import start
         * @param val
         */
        function analizeStart(val)
        {
            document.getElementById('<?=$arParams["START_ANALIZE"]?>').disabled = val ? 'disabled' : '';
            document.getElementById('<?=$arParams["STOP_ANALIZE"]?>').disabled = val ? '' : 'disabled';
            document.getElementById('<?=$arParams["PROGRESS_ANALIZE"]?>').style.display = val ? 'block' : 'none';

            if (val)
            {
                ShowWaitWindow();
                document.getElementById('<?=$arParams["START_ANALIZE"]?>-result').innerHTML = '<?=$arResult["CLEAN_ANALIZE_TABLE"]?>';
                document.getElementById('<?=$arParams["START_ANALIZE"]?>-status').innerHTML = 'Идёт процесс проверки...';

                document.getElementById('<?=$arParams["START_ANALIZE"]?>-percent').innerHTML = '0%';
                document.getElementById('<?=$arParams["START_ANALIZE"]?>-indicator').style.width = '0%';

                CHttpRequest.Action = analize_onload;
                CHttpRequest.Send('<?= $_SERVER["PHP_SELF"]?>?<?=$arParams["START_ANALIZE"]?>=Y&lang=<?=LANGUAGE_ID?>&<?=bitrix_sessid_get()?>');
            }
            else
                CloseWaitWindow();
        }

        function analize_onload(result)
        {
            try
            {
                eval(result);

                iPercent = CurrentStatus[0];
                strNextRequest = CurrentStatus[1];
                strCurrentAction = CurrentStatus[2];

                document.getElementById('<?=$arParams["START_ANALIZE"]?>-percent').innerHTML = iPercent + '%';
                document.getElementById('<?=$arParams["START_ANALIZE"]?>-indicator').style.width = iPercent + '%';
                document.getElementById('<?=$arParams["START_ANALIZE"]?>-status').innerHTML = 'дождитесь окончания прогресса...';

                if (strCurrentAction != 'null')
                {
                    oTable = document.getElementById('<?=$arParams["START_ANALIZE"]?>-info');
                    oRow = oTable.insertRow(-1);
                    oCell = oRow.insertCell(-1);
                    oCell.innerHTML = strCurrentAction;
                    oCell = oRow.insertCell(-1);
                    oCell.innerHTML = '';
                }

                if (strNextRequest && document.getElementById('<?=$arParams["START_ANALIZE"]?>').disabled)
                    CHttpRequest.Send('<?= $_SERVER["PHP_SELF"]?>?<?=$arParams["START_ANALIZE"]?>=Y&lang=<?=LANGUAGE_ID?>&<?=bitrix_sessid_get()?>' + strNextRequest);
                else
                {
                    analizeStart(0);
                    bWorkFinished = true;
                }

            }
            catch(e)
            {
                CloseWaitWindow();
                document.getElementById('<?=$arParams["START_ANALIZE"]?>').disabled = '';
                alert('Сбой в получении данных');
            }
        }


    </script>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>