<?/** * Import settings page * @var CMain $APPLICATION */use Bitrix\Main\Localization\Loc,    \Studio7spb\Marketplace\ImportTable;// Чтение данных из Excel// http://swblog.ru/articles/programming/sozdaem-otchety-v-excel-na-php.htmlrequire_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");/** KDAPHPExcel root directory */if (!defined('KOOROCHKA_EXCEL_ROOT')) {    /**     * @ignore     */    define('KOOROCHKA_EXCEL_ROOT', $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/studio7spb.marketplace/lib/import/PHPExcel/");    require(KOOROCHKA_EXCEL_ROOT . 'PHPExcel/Autoloader.php');    require(KOOROCHKA_EXCEL_ROOT . 'PHPExcel.php');    require(KOOROCHKA_EXCEL_ROOT . 'PHPExcel/IOFactory.php');}Loc::loadLanguageFile(__FILE__);$APPLICATION->SetTitle( Loc::getMessage("TITLE") );require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");$arParams = array(    "FILE" => $_SERVER["DOCUMENT_ROOT"] . "/upload/kda.importexcel/538/Import в Битрикс5.xls");$arResult = array();$arResult["MAP"] = ImportTable::getMap();// Открываем файл$xls = KDAPHPExcel_IOFactory::load($arParams["FILE"]);// Устанавливаем индекс активного листа$xls->setActiveSheetIndex(0);// Получаем активный лист$sheet = $xls->getActiveSheet();echo "<table>";// Получили строки и обойдем их в цикле$rowIterator = $sheet->getRowIterator();foreach ($rowIterator as $row) {    // Получили ячейки текущей строки и обойдем их в цикле    $cellIterator = $row->getCellIterator();    echo "<tr>";    foreach ($cellIterator as $cell) {        echo "<td>" . $cell->getCalculatedValue() . "</td>";    }    echo "</tr>";}echo "</table>";d($arResult);require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>