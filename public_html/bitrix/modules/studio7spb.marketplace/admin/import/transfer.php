<?
use Bitrix\Main\Loader,
    Bitrix\Main\Config\Option,
    Bitrix\Main\Localization\Loc,
    Studio7spb\Marketplace\ImportTable,
    Bitrix\Iblock\ElementTable,
    Bitrix\Catalog\Model\Price,
    Bitrix\Catalog\Model\Product,
    Bitrix\Iblock\SectionTable,
    Bitrix\Highloadblock,
    Studio7spb\Marketplace\ImportSettingsTable;

/**
 * https://dev.1c-bitrix.ru/community/webdev/user/11948/blog/2047/
 */

// <editor-fold defaultstate="collapsed" desc=" # Prepare">

if (ini_get('mbstring.func_overload') & 2) {
    ini_set("mbstring.func_overload", 0);
}

$arParams = array(
    "MODULE" => "studio7spb.marketplace",
    "FILE" => "IMPORT_DATA_FILE",
    "START_IMPORT" => "work_start",
    "STOP_IMPORT" => "work_stop",
    "PROGRESS_IMPORT" => "work_progress",
    "START_ANALIZE" => "analize_start",
    "STOP_ANALIZE" => "analize_stop",
    "PROGRESS_ANALIZE" => "analize_progress",
    "IBLOCK_ID" => 2,
    "LIMIT" => 1
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
CJSCore::Init(array("jquery"));
Loader::includeModule("iblock");
Loc::loadLanguageFile(__FILE__);

$POST_RIGHT = $APPLICATION->GetGroupRight("main");
if ($POST_RIGHT == "D")
    $APPLICATION->AuthForm("Доступ запрещен");

// </editor-fold>

// <editor-fold defaultstate="collapsed" desc=" # Save import file">
// get file
$arResult["FILE"] = Option::get($arParams["MODULE"], $arParams["FILE"]);
// if send new file
if(!empty($_POST["import_file"]) && !empty($_FILES[$arParams["FILE"]])){
    // delete old if exist
    if($arResult["FILE"] > 0){
        CFile::Delete($arResult["FILE"]);
    }
    $arResult["FILE"] = CFile::SaveFile($_FILES[$arParams["FILE"]], "excel");
    // save new file
    Option::set($arParams["MODULE"], $arParams["FILE"], $arResult["FILE"]);
    // and save to settings
    $importSettings = ImportSettingsTable::getList(array(
        "filter" => array("CODE" => "IMPORT_DATA_FILE"),
        "select" => array("ID")
    ));
    if($importSettings = $importSettings->fetch()){
        ImportSettingsTable::update($importSettings["ID"], array("VALUE" => $arResult["FILE"]));
    }

    LocalRedirect($APPLICATION->GetCurPageParam("file=upload", array("file")));
}

if($arResult["FILE"] > 0){
    $arResult["FILE"] = CFile::GetFileArray($arResult["FILE"]);
}
else{
    $arResult["FILE"] = null;
}

// </editor-fold>

// <editor-fold defaultstate="collapsed" desc=" # One-sep Analize process">

if($_REQUEST[$arParams["START_ANALIZE"]] == "Y" && check_bitrix_sessid())
{

    // Первый вызов - тут чтение файла
    // и запись во временную таблицу
    if(!isset($_REQUEST["lastid"])) {
        // записать во временную таблицу
        echo shell_exec('php ' . $_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/studio7spb.marketplace/admin/import/db.fill.php');
    }
    else{
        // TODO работа со статусами во временной таблице
        // TODO проверка обязательных полей
        // TODO http://teatr-msk.ru/bitrix/admin/iblock_edit.php?type=marketplace&tabControl_active_tab=edit2&lang=ru&ID=2&admin=Y
        $elements = ImportTable::getList(array(
            "order" => array("ID" => "ASC"),
            "filter" => array(
                ">ID" => $_REQUEST["lastid"]
            ),
            "select" => array(
                "ID",
                "DATA"
            ),
            "limit" => $arParams["LIMIT"]
        ));
        $error = array();
        $notNeed = array("A", "B", "F", "I", "N", "O", "Q", "R", "S", "U", "V", "W", "X", "Y", "Z", "AA", "AB");
        while ($element = $elements->fetch())
        {
            /*
             * TODO работа с полем статуса
             * TODO ImportTable::update($element["ID"], array("STATUS" => "Y"))
             */

            $element["DATA"] = unserialize($element["DATA"]);

            foreach ($element["DATA"] as $key=>$value)
            {
                if(empty($value) && !in_array($key, $notNeed)){
                    $error[] = "Не заполнено обязательное поле" . $key;
                }
            }

            $lastID = $element["ID"];
        }

        $rsLeftBorder = ImportTable::getList(array(
            "order" => array("ID" => "ASC"),
            "filter" => array(
                "<=ID" => $lastID
            ),
            "select" => array(
                "ID"
            )
        ));
        $leftBorderCnt = $rsLeftBorder->getSelectedRowsCount();

        $rsAll = ImportTable::getList(array(
            "order" => array("ID" => "ASC"),
            "select" => array(
                "ID"
            )
        ));
        $allCnt = $rsAll->getSelectedRowsCount();

        $p = round(100*$leftBorderCnt/$allCnt, 2);

        if(empty($error)){
            echo 'CurrentStatus = Array('.$p.',"'.($p < 100 ? '&lastid='.$lastID : '').'","Статус проверки. Последний проверенный товар №: '.$lastID.'. Ошибок не обнаружено.", "success");';
        }else{
            $error = implode(", ", $error);
            echo 'CurrentStatus = Array('.$p.',"'.($p < 100 ? '&lastid='.$lastID : '').'","Товар №: '.$lastID.'. Ошибка: ' . $error . '", "error");';
        }
    }

    die();
}
else{
    if($_REQUEST["transfer"] === "worksheet"){

        if($_POST["worksheet_catalog"] !== "0"){
            echo shell_exec('php ' . $_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/studio7spb.marketplace/admin/import/worksheet.catalog.php');
        }
        else{
            echo shell_exec('php ' . $_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/studio7spb.marketplace/admin/import/worksheet.php');
        }

        die;
    }
}
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc=" # One-sep Import process">
if($_REQUEST[$arParams["START_IMPORT"]] && check_bitrix_sessid())
{
    // импорт из временной таблицы в инфоблок
    $elements = ImportTable::getList(array(
        "order" => array("ID" => "ASC"),
        "filter" => array(
            ">ID" => $_REQUEST["lastid"]
        ),
        "limit" => $arParams["LIMIT"]
    ));
    while ($element = $elements->fetch())
    {
        Loader::includeModule("highloadblock");
        Loader::includeModule("iblock");
        Loader::includeModule("catalog");
        // get tmp data
        $element["DATA"] = unserialize($element["DATA"]);
        //check iblock element add or update
        $products = ElementTable::getList(array(
            "filter" => array(
                "NAME" => $element["DATA"]["D"],
                "IBLOCK_ID" => $arParams["IBLOCK_ID"]
            ),
            "select" => array(
                "ID",
                "NAME"
            )
        ));
        // Оновить или добавить и проверить на дули, если есть дубли - то первый обновляется а остальные удаляются
        $productIsUpdate = false;
        while ($product = $products->fetch())
        {
            if($productIsUpdate){
                # Продукт уже обновлён. Последовательно удаляем цену позицию в каталоге и элемент
                # delete prices
                $prices = Price::getList(array(
                    "filter" => array(
                        "PRODUCT_ID" => $product["ID"]
                    )
                ));
                while ($price = $prices->fetch()){
                    Price::delete($price["ID"]);
                }
                # Check position in catalog
                $existProduct = Product::getCacheItem($product["ID"],true);
                if(!empty($existProduct)){
                    Product::delete($product["ID"]);
                }
                # delete iblock element
                CIBlockElement::Delete($product["ID"]);
            }else{
                # Check position in catalog
                // https://dev.1c-bitrix.ru/api_help/catalog/classes/ccatalogproduct/add.php
                $existProduct = Product::getCacheItem($product["ID"],true);
                if(empty($existProduct)){
                    Product::add(array(
                        "ID" => $product["ID"],
                        "QUANTITY" => $element["DATA"]["O"],
                        "WEIGHT" => $element["DATA"]["V"], //  * 1000 TODO разделить на #CELL20#
                        "WIDTH" => $element["DATA"]["R"] * 10,
                        "LENGTH" => $element["DATA"]["P"] * 10,
                        "HEIGHT" => $element["DATA"]["Q"] * 10
                        //                         "MEASURE" => 4,
                    ));
                }else{
                    Product::update($product["ID"], array(
                        "QUANTITY" => $element["DATA"]["O"],
                        "WEIGHT" => $element["DATA"]["V"], //  * 1000 TODO разделить на #CELL20# = ["DATA"]["T"]
                        "WIDTH" => $element["DATA"]["R"] * 10,
                        "LENGTH" => $element["DATA"]["P"] * 10,
                        "HEIGHT" => $element["DATA"]["Q"] * 10
                        // "MEASURE" => 4,
                    ));
                }

                /**
                 * Обновляем элемент инфоблока
                 * array (
                'A' => NULL,
                'B' => 'EC031609',
                'C' => 'Toys: musical instruments and devices',
                'D' => 'Music Bedside Bell',
                'E' => 'Игрушки: инструменты и устройства музыкальные',
                'F' => NULL,
                'G' => 907.0,
                'H' => 'MinLe',
                'I' => NULL,
                'J' => 1149.0,
                'K' => 'window box',
                'L' => 40.54,
                'M' => 42.27,
                'N' => 'Shantou',
                'O' => NULL,
                'P' => 48.0, //
                'Q' => 9.0,
                'R' => 34.0,
                'S' => 9.0,
                'T' => 18.0,
                'U' => 0.316,
                'V' => 21.0,
                'W' => 18.0,
                'X' => 85.0,
                'Y' => 51.0,
                'Z' => 73.0,
                'AA' => 4.0,
                'AB' => 10.0,
                'AC' => 'plastic',
                'AD' => 'not limited',
                )
                 */
                $parentSection = SectionTable::getList(array(
                    "filter" => array(
                        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                        "XML_ID" => $element["DATA"]["G"]
                    ),
                    "select" => array("ID")
                ));
                if($parentSection = $parentSection->fetch()){
                    $parentSection = $parentSection["ID"];
                }
                if($parentSection < 1){
                    $parentSection = 0;
                }
                $neo = new CIBlockElement();
                $neo->Update($product["ID"], array(
                    "PREVIEW_TEXT" => $element["DATA"]["D"],
                    "PREVIEW_PICTURE" => CFile::MakeFileArray($element["DATA"]["A"]),
                    "DETAIL_PICTURE" => CFile::MakeFileArray($element["DATA"]["A"]),
                    "IBLOCK_SECTION_ID" => $parentSection
                ));

                # update iblock-element properties
                CIBlockElement::SetPropertyValuesEx($product["ID"], $product["IBLOCK_ID"], array(
                    "ITEM_NO" => $element["DATA"]["J"],
                    "FACTORY" => $element["DATA"]["H"], // that value form TRADE_MARK
                    "PORT" => $element["DATA"]["N"],
                    "LHW_ctn" => trim($element["DATA"]["P"]) . "см х " . trim($element["DATA"]["Q"]) . "см х " . trim($element["DATA"]["R"]) . "см",
                    "INNER_BOX" => $element["DATA"]["O"],
                    "Master_CTN_PCS" => $element["DATA"]["T"],
                    "Master_CTN_CBM" => $element["DATA"]["U"],
                    "WEIGHT" => $element["DATA"]["U"],
                    "WEIGHT_NETTO" => $element["DATA"]["V"],
                    "Master_CTN_SIZE" => trim($element["DATA"]["X"]) . "см х " . trim($element["DATA"]["Y"]) . "см х " . trim($element["DATA"]["Z"]) . "см",
                    "L_ctn" => $element["DATA"]["X"],
                    "H_ctn" => $element["DATA"]["Y"],
                    "W_ctn" => $element["DATA"]["Z"],
                    "MOQ" => $element["DATA"]["AA"],
                    "Production_time_days" => $element["DATA"]["AB"],
                    "MOQ" => $element["DATA"]["AA"],
                    "Expire_time_from_production_date" => $element["DATA"]["AD"],
                    // set technical prices
                    "ExWork_RMB" => $element["DATA"]["L"],
                    "FOB_RMB" => $element["DATA"]["M"],
                    // highloadblock properties
                    "PACKAGE" => $element["DATA"]["K"],
                    "MATERIALS" => $element["DATA"]["AC"],
                    "CATEGORY" => $element["DATA"]["C"]
                ));
            }

            // первый обновляем остальные удаляем
            $productIsUpdate = true;
        }
        if($productIsUpdate == false){
            $parentSection = SectionTable::getList(array(
                "filter" => array(
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "XML_ID" => $element["DATA"]["G"]
                ),
                "select" => array("ID")
            ));
            if($parentSection = $parentSection->fetch()){
                $parentSection = $parentSection["ID"];
            }
            if($parentSection < 1){
                $parentSection = 0;
            }
            $neo = new CIBlockElement();
            $ID = $neo->Add(array(
                "NAME" => $element["DATA"]["D"],
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "IBLOCK_SECTION_ID" => $parentSection,
                "PREVIEW_PICTURE" => CFile::MakeFileArray($element["DATA"]["A"]),
                "DETAIL_PICTURE" => CFile::MakeFileArray($element["DATA"]["A"]),
                "PROPERTY_VALUES" => array(
                    "ITEM_NO" => $element["DATA"]["J"],
                    "FACTORY" => $element["DATA"]["H"], // that value form TRADE_MARK
                    "PORT" => $element["DATA"]["N"],
                    "LHW_ctn" => trim($element["DATA"]["P"]) . "см х " . trim($element["DATA"]["Q"]) . "см х " . trim($element["DATA"]["R"]) . "см",
                    "INNER_BOX" => $element["DATA"]["O"],
                    "Master_CTN_PCS" => $element["DATA"]["T"],
                    "Master_CTN_CBM" => $element["DATA"]["U"],
                    "WEIGHT" => $element["DATA"]["U"],
                    "WEIGHT_NETTO" => $element["DATA"]["V"],
                    "Master_CTN_SIZE" => trim($element["DATA"]["X"]) . "см х " . trim($element["DATA"]["Y"]) . "см х " . trim($element["DATA"]["Z"]) . "см",
                    "L_ctn" => $element["DATA"]["X"],
                    "H_ctn" => $element["DATA"]["Y"],
                    "W_ctn" => $element["DATA"]["Z"],
                    "MOQ" => $element["DATA"]["AA"],
                    "Production_time_days" => $element["DATA"]["AB"],
                    "MOQ" => $element["DATA"]["AA"],
                    "Expire_time_from_production_date" => $element["DATA"]["AD"],
                    // set technical prices
                    "ExWork_RMB" => $element["DATA"]["L"],
                    "FOB_RMB" => $element["DATA"]["M"],
                    // highloadblock properties
                    "PACKAGE" => $element["DATA"]["K"],
                    "MATERIALS" => $element["DATA"]["AC"],
                    "CATEGORY" => $element["DATA"]["C"]
                )
            ));
            if($ID > 0){
                # update iblock-element properties
                Product::add(array(
                    "ID" => $ID,
                    "QUANTITY" => $element["DATA"]["O"],
                    "WEIGHT" => $element["DATA"]["V"], //  * 1000 TODO разделить на #CELL20#
                    "WIDTH" => $element["DATA"]["R"] * 10,
                    "LENGTH" => $element["DATA"]["P"] * 10,
                    "HEIGHT" => $element["DATA"]["Q"] * 10
                    //                         "MEASURE" => 4,
                ));
            }
        }

        // GOTO nexst step
        $lastID = $element["ID"];
    }

    $rsLeftBorder = ImportTable::getList(array(
        "order" => array("ID" => "ASC"),
        "filter" => array(
            "<=ID" => $lastID
        ),
        "select" => array(
            "ID"
        )
    ));
    $leftBorderCnt = $rsLeftBorder->getSelectedRowsCount();

    $rsAll = ImportTable::getList(array(
        "order" => array("ID" => "ASC"),
        "select" => array(
            "ID"
        )
    ));
    $allCnt = $rsAll->getSelectedRowsCount();

    $p = round(100*$leftBorderCnt/$allCnt, 2);

    echo 'CurrentStatus = Array('.$p.',"'.($p < 100 ? '&lastid='.$lastID : '').'","В каталог выгружен товар №: '.$lastID.'");';

    die();
}
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc=" # Formating Analize script">
$arResult["CLEAN_ANALIZE_TABLE"] = '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="internal" id="' . $arParams["START_ANALIZE"] . '-info-table">'.
    '<tr class="heading">'.
    '<td>Текущий процесс</td>'.
    '</tr>'.
    '<tr>'.
    '<td id="' . $arParams["START_ANALIZE"] . '-info">&nbsp;</td>'.
    '</tr>'.
    '</table>';
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc=" # Formating Import script">
$arResult["CLEAN_IMPORT_TABLE"] = '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="internal">'.
    '<tr class="heading">'.
    '<td>Текущий процесс</td>'.
    '</tr>'.
    '<tr>'.
    '<td id="' . $arParams["START_IMPORT"] . '-info">&nbsp;</td>'.
    '</tr>'.
    '</table>';
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc=" # Admin and tabs controls">

$aTabs = array(
    array("DIV" => $arParams["FILE"], "TAB" => "Загрузка файла импорта", "ICON"=>"main_user_edit", "TITLE" => "Загрузка файла импорта"),
    array("DIV" => $arParams["FILE"] . "_SETTINGS", "TAB" => "Настройки импорта", "ICON"=>"main_user_edit", "TITLE" => "Настройки импорта"),
    array("DIV" => $arParams["FILE"] . "_ANALIZE", "TAB" => "Проверка файла импорта", "ICON"=>"main_user_edit", "TITLE" => "Проверка файла импорта"),
    array("DIV" => $arParams["FILE"] . "_IMPORT", "TAB" => "Импорт товаров", "ICON"=>"main_user_edit", "TITLE" => "Импорт товаров")
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);

$APPLICATION->SetTitle("Импорт товаров");

// </editor-fold>

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");



// <editor-fold defaultstate="collapsed" desc=" # Form and javascript">
?>

    <form method="post"
          action="<?echo $APPLICATION->GetCurPage()?>"
          enctype="multipart/form-data"
          name="post_form"
          onsubmit="return marketplaceTransfer.submit()"
          id="post_form">
        <?
        echo bitrix_sessid_post();

        $tabControl->Begin();
        $tabControl->BeginNextTab();
        ?>
            <tr>
                <td width="40%" class="adm-detail-content-cell-l">
                    Файл для загрузки:
                </td>
                <td class="adm-detail-content-cell-r">
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

            <?if(empty($arResult["FILE"])):?>
                <tr>
                    <td colspan="2" class="adm-detail-content-cell-l">После загрузки файла во вкладке <b>Настройки импорта</b> появится перечень настроек для импорта.</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit"
                               name="import_file"
                               value="Загрузить">
                    </td>
                </tr>
            <?else:?>
                <tr>
                    <td colspan="2" class="adm-detail-content-cell-l">Обновление файла - это загрузка нового файла импорта и удаление старого файла импорта. Файл импорта в этом разделе всегда один.</td>
                </tr>
                <tr>
                    <td colspan="2" class="adm-detail-content-cell-l">
                        <input type="submit"
                               name="import_file"
                               value="Обновить файл">
                    </td>
                </tr>
            <?endif;?>


        <?
        $tabControl->BeginNextTab();
        ?>

        <tr class="heading"><td colspan="2">Настройки каталога:</td></tr>

        <tr>
            <td class="adm-detail-content-cell-l">Задать лист в котором содержится каталог</td>
            <td class="adm-detail-content-cell-r">
                <select id="worksheet-catalog"
                        name="worksheet_catalog">
                    <option value="0">Loading ...</option>
                </select>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <table id="worksheet-preview"></table>
            </td>
        </tr>
        <tr>
            <td class="adm-detail-content-cell-l">Задать строку, с которой начинать парсинг данных:</td>
            <td class="adm-detail-content-cell-r">
                <select>
                    <?for($i = 1; $i <= 100; $i++):?>
                        <option value="<?=$i?>"><?=$i?></option>
                    <?endfor;?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="hidden"
                       name="transfer"
                       value="worksheet" />
                <input type="submit"
                       value="Перейти к проверке">




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
                    resultTableInfo = document.getElementById('<?=$arParams["START_ANALIZE"]?>-info');
                    resultTableInfo.innerHTML = strCurrentAction;

                    if(CurrentStatus[3] == "error"){
                        resultTableInfo = document.getElementById('<?=$arParams["START_ANALIZE"]?>-info-table');
                        oRow = resultTableInfo.insertRow(-1);
                        oCell = oRow.insertCell(-1);
                        oCell.innerHTML = strCurrentAction;
                    }

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


        var marketplaceTransfer = {

            worksheet: {
                catalog: "#worksheet-catalog",
                preview: "#worksheet-preview"
            },

            preview: function(catalog)
            {


                var i = 0,
                    n = 0,
                    key,
                    table = [],
                    tr;

                for(i = 0; i < catalog.length; i++){
                    n++;
                    // set headders
                    if(i === 0){
                        tr = "<tr>";
                        tr += "<th>№</th>";
                        for(key in catalog[i]){
                            tr += "<th>" + key + "</th>";
                        }
                        tr += "</tr>";
                        table.push(tr);
                    }

                    // set rows
                    tr = "<tr>";
                    tr += "<th>" + n +  "</th>";
                    for(key in catalog[i]){
                        tr += "<td>" + catalog[i][key] + "</td>";
                    }
                    tr += "</tr>";
                    table.push(tr);

                }

                //table.append(tr);

                $(marketplaceTransfer.worksheet.preview).fadeIn(300)
                    .html(table.join(''));

            },

            submit: function () {
                ShowWaitWindow();

                var form = $("#post_form"),
                    action = form.attr("action"),
                    data = form.serializeArray(),
                    notValidate = true,
                    i;

                if(data.length > 0){
                    for(i = 0; i < data.length; i++){
                        if(data[i]["name"] === "transfer" && data[i]["value"] === "worksheet"){
                            notValidate = false;
                        }
                    }
                }

                console.info(data);

                // если файл загружен
                // делаем ajax запрос для превью и списка листов
                if(notValidate === false){

                    alert("notValidate");

                    $.post(action, data, function (result) {
                        // fill catalog
                        if(!!result.WORKSHEET && result.WORKSHEET.length > 0){
                            $(marketplaceTransfer.worksheet.catalog).empty();
                            for(i = 0; i < result.WORKSHEET.length; i++){
                                $(marketplaceTransfer.worksheet.catalog).append($("<option>", {
                                    text: result.WORKSHEET[i],
                                    value: result.WORKSHEET[i]
                                }));
                            }
                        }
                        // fill preview
                        if(!!result.CATALOG && result.CATALOG.length > 0){
                            marketplaceTransfer.preview(result.CATALOG);
                        }

                        CloseWaitWindow();
                    }, "json");
                }else{
                    alert("Validate");
                }

                return notValidate;
            }

        };



            //tabControl.EnableTab('edit2');
            tabControl.DisableTab('<?=$arParams["FILE"] . "_ANALIZE"?>');
            tabControl.DisableTab('<?=$arParams["FILE"] . "_IMPORT"?>');
    </script>

    <style>
        #worksheet-preview{

        }
        #worksheet-preview th,
        #worksheet-preview td{
            border: 1px solid #333;
            text-align: center;
        }
        #worksheet-preview th{
            background-color: #cecece;
        }
    </style>
<?
// </editor-fold>



require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>