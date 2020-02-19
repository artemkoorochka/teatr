<?
/**
 * @var array $arResult
 * @var array $arParams
 * @var $this CBitrixComponentTemplate
 */
$this->addExternalCss("/bitrix/css/main/bootstrap_v4/bootstrap.css");
?>

<form method="post"
      action="<?echo $APPLICATION->GetCurPage()?>"
      enctype="multipart/form-data"
      name="post_form">

    <?=bitrix_sessid_post();?>

    <a href="<?=SITE_DIR?>upload/excel/sample/import.xlsx" class="text-success text-14 excell-download mr-3">Excel - образец файла загрузки</a>
        <br>
        <br>


    <label>Файл</label>
    <input type="file" name="file">
<br>
<br>

    <input type="submit"
           name="import_file"
           value="Загрузить">
</form>

<div class="m-5">
    <button class="btn btn-success" onclick="lancyImport.setStart(1)">Start</button>
    <button class="btn btn-danger" onclick="lancyImport.setStart(0)">Stop</button>
</div>

<!--  -->
<?



$clean_test_table = '<table id="result_table" cellpadding="0" cellspacing="0" border="0" width="100%" class="internal">'.
    '<tr class="heading">'.
    '<td>Текущее действие</td>'.
    '<td width="1%">&nbsp;</td>'.
    '</tr>'.
    '</table>';
?>


<script type="text/javascript">
    /**
     * Using
     * @type {lancyImport}
     */
    var lancyImport = new lancyImport({
        arParams: {param1: 1, param2: 2}
    });


    lancyImport.showStatus();

    lancyImport.setStart("set start");
    lancyImport.workOnload("result to eval");


    /**
     * old
     * @type {boolean}
     */
    var bWorkFinished = false;
    var bSubmit;

    function set_start(val)
    {
        document.getElementById('work_start').disabled = val ? 'disabled' : '';
        document.getElementById('work_stop').disabled = val ? '' : 'disabled';
        document.getElementById('progress').style.display = val ? 'block' : 'none';

        if (val)
        {
            ShowWaitWindow();
            document.getElementById('result').innerHTML = '<?=$clean_test_table?>';
            document.getElementById('status').innerHTML = 'Работаю...';

            document.getElementById('percent').innerHTML = '0%';
            document.getElementById('indicator').style.width = '0%';

            CHttpRequest.Action = work_onload;
            CHttpRequest.Send('/system/seller/products/procedures/transfer.php?work_start=Y&<?=bitrix_sessid_get()?>');
        }
        else
            CloseWaitWindow();
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

            document.getElementById('status').innerHTML = 'Работаю...';

            if (strCurrentAction != 'null')
            {
                oTable = document.getElementById('result_table');
                oRow = oTable.insertRow(-1);
                oCell = oRow.insertCell(-1);
                oCell.innerHTML = strCurrentAction;
                oCell = oRow.insertCell(-1);
                oCell.innerHTML = '';
            }

            if (strNextRequest && document.getElementById('work_start').disabled)
                CHttpRequest.Send('/system/seller/products/procedures/transfer.php?work_start=Y&<?=bitrix_sessid_get()?>' + strNextRequest);
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
            alert('Сбой в получении данных');
        }
    }

</script>

<form method="post" action="/system/seller/products/procedures/transfer.php" enctype="multipart/form-data" name="post_form" id="post_form">
<?=bitrix_sessid_post()?>

    <input type=button value="Старт" id="work_start" onclick="set_start(1)" />
    <input type=button value="Стоп" disabled id="work_stop" onclick="bSubmit=false;set_start(0)" />
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

</form>