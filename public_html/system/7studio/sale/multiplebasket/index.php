<?
/**
 * @var CMain $APPLICATION
 */

use Bitrix\Sale\Basket,
    Bitrix\Main\Loader,
    Bitrix\Main\Page\Asset,
    Studio7spb\Marketplace\MultipleBasketTable;

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
Asset::getInstance()->addCss("/bitrix/css/main/bootstrap_v4/bootstrap.css");
Asset::getInstance()->addJs("/system/7studio/sale/multiplebasket/js/multiplebasket.js");
$APPLICATION->SetTitle("Мои корзины");

Loader::includeModule("sale");

$baskets = MultipleBasketTable::getList(array(
    "order" => array("ID" => "DESC"),
    "filter" => array(
        "FUSER_ID" => \CSaleBasket::GetBasketUserID()
    )
));
$i = 0;
while ($basket = $baskets->fetch())
{
    $i++;
    $basket["NUM"] = $i;
    $basket["PARAMS"] = unserialize($basket["PARAMS"]);
    $arResult["BASKETS"][] = $basket;
}
?>

<div class="row">
    <div class="col">
        <h1 class="h1"><?=$APPLICATION->GetTitle()?></h1>
        <?foreach ($arResult["BASKETS"] as $basket):?>

            <h3 class="h3">Моя корзина <?=$basket["NUM"]?></h3>

            <table class="table table-striped mb-3">
                <tr>
                    <th>Наименование</th>
                    <th>Количество</th>
                    <th>Цена</th>
                </tr>
                <?foreach ($basket["PARAMS"] as $basketItem):?>
                    <tr>
                        <th><?=$basketItem["NAME"]?></th>
                        <th><?=$basketItem["QUANTITY"]?></th>
                        <th><?=CurrencyFormat($basketItem["PRICE"], $basketItem["CURRENCY"])?></th>
                    </tr>
                <?endforeach;?>
            </table>

            <p><a href="/system/7studio/sale/multiplebasket/procedures/use.php?basket=<?=$basket["ID"]?>" class="btn btn-success mb-2">Использовать эту корзину как текущую</a></p>
            <p><a href="/system/7studio/sale/multiplebasket/procedures/compare.php?basket=<?=$basket["ID"]?>" class="btn btn-success mb-2">Добавить товары с этой корзины в текущую текущую</a></p>
            <p><a href="/system/7studio/sale/multiplebasket/procedures/delete.php?basket=<?=$basket["ID"]?>" class="btn btn-success mb-3">Удалить эту корзину из списка</a></p>
        <?endforeach;?>
    </div>
    <div class="col-4">
        <a href="/system/7studio/sale/multiplebasket/procedures/add.php"
           class="btn btn-success d-block">Добавить текущую корзину в Мои корзины</a>

        <?if(!empty($_REQUEST["type"]) && !empty($_REQUEST["text"])):?>
            <div class="d-block mt-3 alert alert-<?=$_REQUEST["type"]?>"><?=$_REQUEST["text"]?></div>
        <?endif;?>

    </div>
</div>

<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');?>