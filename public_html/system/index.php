<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');


//CIBlockElement::SetPropertyValues(2615, 2, "KIDIAN", "FACTORY");


CModule::IncludeModule("highloadblock");

use Bitrix\Highloadblock;
use Bitrix\Main\Entity;
$hlblock = Highloadblock\HighloadBlockTable::getById(6)->fetch();
$hlEntity = Highloadblock\HighloadBlockTable::compileEntity($hlblock);
$entDataClass = $hlEntity->getDataClass();
$sTableID = 'tbl_'.$hlblock['TABLE_NAME'];
$rsData = $entDataClass::getList(array(
    "select" => array('*'), //выбираем все поля
    "filter" => array("UF_NAME" => "KIDIAN"),
    "order" => array("UF_NAME"=>"ASC") // сортировка по полю UF_SORT, будет работать только, если вы завели такое поле в hl'блоке
));
$rsData = new CDBResult($rsData, $sTableID);
if($arRes = $rsData->Fetch()){
    d($arRes);
}

?>

<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');?>