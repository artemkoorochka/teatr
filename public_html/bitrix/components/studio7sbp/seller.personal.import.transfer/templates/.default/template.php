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


<div id="progress-import-bar"
     class="progress d-none">
    <div class="progress-bar bg-success" style="width: 100%;"></div>
</div>

<div id="progress-import-status" class="alert alert-light">
    Status
</div>


<div class="m-5">
    <button class="btn btn-success" onclick="lancyImport.setStart(1)">Начать обработку </button>
</div>

<script type="text/javascript">
    /**
     * Using
     * @type {lancyImport}
     */
    var lancyImport = new lancyImport({
        bar: "#progress-import-bar",
        status: "#progress-import-status",
        messages: {
            status: "Статус",
            work: "Исполняется процесс импорта"
        }
    });



</script>