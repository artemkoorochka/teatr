<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

use Bitrix\Main\Localization\Loc,
    Studio7spb\Marketplace\RequisitsTable;

Loc::loadMessages(__FILE__);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title><?=Loc::getMessage('SALE_HPS_BILL_TITLE')?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?=LANG_CHARSET?>">
<style type="text/css">
	table { border-collapse: collapse; }
	table.acc td { border: 1pt solid #000000; padding: 0pt 3pt; line-height: 21pt; }
	table.it td { border: 1pt solid #000000; padding: 0pt 3pt; }
	table.sign td { font-weight: bold; vertical-align: bottom; }
	table.header td { padding: 0pt; vertical-align: top; }
</style>
</head>

<?

if ($_REQUEST['BLANK'] == 'Y')
	$blank = true;

$pageWidth  = 595.28;
$pageHeight = 841.89;

$background = '#ffffff';
if ($params['BILL_BACKGROUND'])
{
	$path = $params['BILL_BACKGROUND'];
	if (intval($path) > 0)
	{
		if ($arFile = CFile::GetFileArray($path))
			$path = $arFile['SRC'];
	}

	$backgroundStyle = $params['BILL_BACKGROUND_STYLE'];
	if (!in_array($backgroundStyle, array('none', 'tile', 'stretch')))
		$backgroundStyle = 'none';

	if ($path)
	{
		switch ($backgroundStyle)
		{
			case 'none':
				$background = "url('" . $path . "') 0 0 no-repeat";
				break;
			case 'tile':
				$background = "url('" . $path . "') 0 0 repeat";
				break;
			case 'stretch':
				$background = sprintf(
					"url('%s') 0 0 repeat-y; background-size: %.02fpt %.02fpt",
					$path, $pageWidth, $pageHeight
				);
				break;
		}
	}
}

$margin = array(
	'top' => intval($params['BILL_MARGIN_TOP'] ?: 15) * 72/25.4,
	'right' => intval($params['BILL_MARGIN_RIGHT'] ?: 15) * 72/25.4,
	'bottom' => intval($params['BILL_MARGIN_BOTTOM'] ?: 15) * 72/25.4,
	'left' => intval($params['BILL_MARGIN_LEFT'] ?: 20) * 72/25.4
);

$width = $pageWidth - $margin['left'] - $margin['right'];

?>

<body style="margin: 0pt; padding: 0pt; background: <?=$background; ?>"<? if ($_REQUEST['PRINT'] == 'Y') { ?> onload="setTimeout(window.print, 0);"<? } ?>>

<div style="margin: 0pt; padding: 5pt 10pt; background: <?=$background; ?>">

    <table style="margin-bottom: 24px;">
        <tr style="font-size: 16px; font-weight: bold;">
            <td width="30%">Поставщик:</td>
            <td>
                <div style="text-decoration: underline;">
                    ООО "ЛАНСИ ЧАЙНА"
                    <br>
                    111250, город Москва, улица Лефортовский Вал,
                    <br>
                    дом 24, пом/ком/оф IV/5/74, подвал
                    <br>
                    тел. (964) 722 29 29
                </div>
            </td>
        </tr>
    </table>

    <table class="acc"
           style="margin-bottom: 40px;"
           width="100%">
        <tr>
            <td>ИИН</td>
            <td>7722480743</td>
            <td>КПП</td>
            <td>772201001</td>
            <td rowspan="2" style="vertical-align: bottom">Счет No</td>
            <td rowspan="2" style="vertical-align: bottom">40702810002490003513</td>
        </tr>
        <tr>
            <td colspan="4" style="line-height: 1.3">
                Получатель
                <br>
                ООО "ЛАНСИ ЧАЙНА"
            </td>

        </tr>
        <tr>
            <td colspan="4" rowspan="2" style="line-height: 1.3">
                Банк получателя
                <br>
                АО «АЛЬФА-БАНК»
            </td>
            <td>БИК</td>
            <td>44525593</td>
        </tr>
        <tr>
            <td>Счет No</td>
            <td>30101810200000000593</td>
        </tr>
    </table>

    <table class="acc"
           style="margin: 0 auto 24px auto; font-size: 18px; font-weight: bold;">
        <tr>
            <td style="padding: 2px;">СЧЕТ №</td>
            <td style="padding: 2px 15px; text-align: center;"><?=$params["ACCOUNT_NUMBER"]?></td>
        </tr>
        <tr>
            <td style="padding: 2px;">ОТ</td>
            <td style="padding: 2px 15px; text-align: center;"><?=$params["PAYMENT_DATE_INSERT"]?></td>
        </tr>
    </table>

    <table style="margin-bottom: 24px; width: 100%;">
        <tr style="font-size: 16px; font-weight: bold;">
            <td width="30%">Плательщик:</td>
            <td>
                <div style="text-decoration: underline;">
                    <?
                    $requisits = RequisitsTable::getList(array(
                        "filter" => array(
                            "USER_ID" => $USER->GetID()
                        )
                    ));
                    if($requisits = $requisits->fetch()):
                    ?>
                        <?=$requisits["OWNERSHIP"]?> "<?=$requisits["NAME"]?>"
                    <?endif;?>
                </div>
            </td>
        </tr>
    </table>

    <div>При оформлении платежного поручения ссылка на номер счета и дату обязательны!</div>
    <div style="font-weight: bold">
        При оплате счета третьим лицом обязательна ссылка на покупателя товара. Просьба в платежных документах обязательно выделять НДС (ставку и сумму)
    </div>


<?

if ($params['BILL_PAYER_SHOW'] == 'Y'):
	if ($params["BUYER_PERSON_COMPANY_NAME"]) {
		echo Loc::getMessage('SALE_HPS_BILL_BUYER_NAME', array('#BUYER_NAME#' => $params["BUYER_PERSON_COMPANY_NAME"]));
		if ($params["BUYER_PERSON_COMPANY_INN"])
			echo Loc::getMessage('SALE_HPS_BILL_BUYER_INN', array('#INN#' => $params["BUYER_PERSON_COMPANY_INN"]));
		if ($params["BUYER_PERSON_COMPANY_ADDRESS"])
		{
			$buyerAddr = $params["BUYER_PERSON_COMPANY_ADDRESS"];
			if (is_array($buyerAddr))
				$buyerAddr = implode(', ', $buyerAddr);
			else
				$buyerAddr = str_replace(array("\r\n", "\n", "\r"), ', ', strval($buyerAddr));
			echo sprintf(", %s", $buyerAddr);
		}
		if ($params["BUYER_PERSON_COMPANY_PHONE"])
			echo sprintf(", %s", $params["BUYER_PERSON_COMPANY_PHONE"]);
		if ($params["BUYER_PERSON_COMPANY_FAX"])
			echo sprintf(", %s", $params["BUYER_PERSON_COMPANY_FAX"]);
		if ($params["BUYER_PERSON_COMPANY_NAME_CONTACT"])
			echo sprintf(", %s", $params["BUYER_PERSON_COMPANY_NAME_CONTACT"]);
	}
endif;
?>



<?
$arCurFormat = CCurrencyLang::GetCurrencyFormat($params['CURRENCY']);
$currency = preg_replace('/(^|[^&])#/', '${1}', $arCurFormat['FORMAT_STRING']);

$cells = array();
$props = array();

$n = 0;
$sum = 0.00;
$vat = 0;
$cntBasketItem = 0;

// Code injection
$params['BILL_COLUMN_CODE_SHOW'] = "Y";
$params['BILL_COLUMN_CODE_TITLE'] = Loc::getMessage("SALE_HPS_BILL_BASKET_ITEM_CODE");
$params['BILL_COLUMN_CODE_SORT'] = "190";
// Article injection
$params['BILL_COLUMN_ARTICLE_SHOW'] = "Y";
$params['BILL_COLUMN_ARTICLE_TITLE'] = Loc::getMessage("SALE_HPS_BILL_BASKET_ITEM_ARTICLE");
$params['BILL_COLUMN_ARTICLE_SORT'] = "210";


$columnList = array('NUMBER', "CODE", 'NAME', "ARTICLE", 'QUANTITY', 'PRICE', 'VAT_RATE', 'SUM'); // 'MEASURE'
$arCols = array();
$vatRateColumn = 0;
foreach ($columnList as $column)
{
	if ($params['BILL_COLUMN_'.$column.'_SHOW'] == 'Y')
	{
		$caption = $params['BILL_COLUMN_'.$column.'_TITLE'];
		if (in_array($column, array('PRICE', 'SUM'))){
		    // SALE_HPS_BILL_TOTAL_VAT_RATE_NO

            switch ($column){
                case "PRICE":
                    $caption .=  " " . Loc::getMessage("SALE_HPS_BILL_TOTAL_VAT_RATE_NO");
                    break;
                case "SUM":
                    $caption .=  " " . Loc::getMessage("SALE_HPS_BILL_BASKET_ITEM_VAT_RATE");
                    break;
            }

            $caption .= ', '.$currency . " " . Loc::getMessage("SALE_HPS_BILL_CHENTO");
        }

		$arCols[$column] = array(
			'NAME' => $caption,
			'SORT' => $params['BILL_COLUMN_'.$column.'_SORT']
		);
	}
}
if ($params['USER_COLUMNS'])
{
	$columnList = array_merge($columnList, array_keys($params['USER_COLUMNS']));
	foreach ($params['USER_COLUMNS'] as $id => $val)
	{
		$arCols[$id] = array(
			'NAME' => $val['NAME'],
			'SORT' => $val['SORT']
		);
	}
}

uasort($arCols, function ($a, $b) {return ($a['SORT'] < $b['SORT']) ? -1 : 1;});

$arColumnKeys = array_keys($arCols);
$columnCount = count($arColumnKeys);

if ($params['BASKET_ITEMS'])
{
	foreach ($params['BASKET_ITEMS'] as $basketItem)
	{
		$productName = $basketItem["NAME"];
		if ($productName == "OrderDelivery")
			$productName = Loc::getMessage('SALE_HPS_BILL_DELIVERY');
		else if ($productName == "OrderDiscount")
			$productName = Loc::getMessage('SALE_HPS_BILL_DISCOUNT');

		if ($basketItem['IS_VAT_IN_PRICE'])
			$basketItemPrice = $basketItem['PRICE'];
		else
			$basketItemPrice = $basketItem['PRICE']*(1 + $basketItem['VAT_RATE']);

		$cells[++$n] = array();
		foreach ($arCols as $columnId => $caption)
		{
			$data = null;

			switch ($columnId)
			{
				case 'NUMBER':
					$data = $n;
					break;
				case 'CODE':
				case 'ARTICLE':

					$element = CIBlockElement::GetList(
                        array(),
                        array("ID" => $basketItem["PRODUCT_ID"]),
                        false,
                        false,
                        array("ID", "IBLOCK_ID", "PROPERTY_ITEM_NO")
                    );
					if($element = $element->Fetch()){
                        $data = $element["PROPERTY_ITEM_NO_VALUE"];
                    }

					break;
				case 'NAME':
					$data = htmlspecialcharsbx($productName);
					break;
				case 'QUANTITY':
					$data = roundEx($basketItem['QUANTITY'], SALE_VALUE_PRECISION);
					break;
				case 'MEASURE':
					$data = $basketItem["MEASURE_NAME"] ? htmlspecialcharsbx($basketItem["MEASURE_NAME"]) : Loc::getMessage('SALE_HPS_BILL_BASKET_MEASURE_DEFAULT');
					break;
				case 'PRICE':
					$data = SaleFormatCurrency($basketItem['PRICE'], $basketItem['CURRENCY'], true);
					break;
				case 'VAT_RATE':
					$data = roundEx($basketItem['VAT_RATE'] * 100, SALE_VALUE_PRECISION)."%";
					break;
				case 'SUM':
					$data = SaleFormatCurrency($basketItemPrice * $basketItem['QUANTITY'], $basketItem['CURRENCY'], true);
					break;
				default :
					$data = ($basketItem[$columnId]) ?: '';
			}
			if ($data !== null)
				$cells[$n][$columnId] = $data;
		}
		$props[$n] = array();
		/** @var \Bitrix\Sale\BasketPropertyItem $basketPropertyItem */
		if ($basketItem['PROPS'])
		{
			foreach ($basketItem['PROPS'] as $basketPropertyItem)
			{
				if ($basketPropertyItem['CODE'] == 'CATALOG.XML_ID' || $basketPropertyItem['CODE'] == 'PRODUCT.XML_ID')
					continue;
				$props[$n][] = htmlspecialcharsbx(sprintf("%s: %s", $basketPropertyItem["NAME"], $basketPropertyItem["VALUE"]));
			}
		}
		$sum += doubleval($basketItem['PRICE'] * $basketItem['QUANTITY']);
		$vat = max($vat, $basketItem['VAT_RATE']);
	}
}

if ($vat <= 0)
{
	unset($arCols['VAT_RATE']);
	$columnCount = count($arCols);
	$arColumnKeys = array_keys($arCols);
	foreach ($cells as $i => $cell)
		unset($cells[$i]['VAT_RATE']);
}

if ($params['DELIVERY_PRICE'] > 0)
{
	$deliveryItem = Loc::getMessage('SALE_HPS_BILL_DELIVERY');

	if ($params['DELIVERY_NAME'])
		$deliveryItem .= sprintf(" (%s)", $params['DELIVERY_NAME']);
	$cells[++$n] = array();
	foreach ($arCols as $columnId => $caption)
	{
		$data = null;

		switch ($columnId)
		{
			case 'NUMBER':
				$data = $n;
				break;
			case 'NAME':
				$data = htmlspecialcharsbx($deliveryItem);
				break;
			case 'QUANTITY':
				$data = 1;
				break;
			case 'MEASURE':
				$data = '';
				break;
			case 'PRICE':
				$data = SaleFormatCurrency($params['DELIVERY_PRICE'], $params['CURRENCY'], true);
				break;
			case 'VAT_RATE':
				$data = roundEx($params['DELIVERY_VAT_RATE'] * 100, SALE_VALUE_PRECISION)."%";
				break;
			case 'SUM':
				$data = SaleFormatCurrency($params['DELIVERY_PRICE'], $params['CURRENCY'], true);
				break;
		}
		if ($data !== null)
			$cells[$n][$columnId] = $data;
	}
	$sum += doubleval($params['DELIVERY_PRICE']);
}

if ($params['BILL_TOTAL_SHOW'] == 'Y')
{
	$cntBasketItem = $n;
	$eps = 0.0001;
	if ($params['SUM'] - $sum > $eps)
	{
		$cells[++$n] = array();
		for ($i = 0; $i < $columnCount; $i++)
			$cells[$n][$arColumnKeys[$i]] = null;

		$cells[$n][$arColumnKeys[$columnCount-2]] = Loc::getMessage('SALE_HPS_BILL_SUBTOTAL');
		$cells[$n][$arColumnKeys[$columnCount-1]] = SaleFormatCurrency($sum, $params['CURRENCY'], true);
	}

	if ($params['TAXES'])
	{
		foreach ($params['TAXES'] as $tax)
		{
			$cells[++$n] = array();
			for ($i = 0; $i < $columnCount; $i++)
				$cells[$n][$arColumnKeys[$i]] = null;

			$cells[$n][$arColumnKeys[$columnCount-2]] = htmlspecialcharsbx(sprintf(
					"%s%s%s:",
					($tax["IS_IN_PRICE"] == "Y") ? Loc::getMessage('SALE_HPS_BILL_INCLUDING') : "",
					$tax["TAX_NAME"],
					($vat <= 0 && $tax["IS_PERCENT"] == "Y")
							? sprintf(' (%s%%)', roundEx($tax["VALUE"], SALE_VALUE_PRECISION))
							: ""
			));
			$cells[$n][$arColumnKeys[$columnCount-1]] = SaleFormatCurrency($tax["VALUE_MONEY"], $params['CURRENCY'], true);
		}
	}

	/*
	if (!$params['TAXES'])
	{
		$cells[++$n] = array();
		for ($i = 0; $i < $columnCount; $i++)
			$cells[$n][$i] = null;

		$cells[$n][$arColumnKeys[$columnCount-2]] = htmlspecialcharsbx(Loc::getMessage('SALE_HPS_BILL_TOTAL_VAT_RATE'));
		$cells[$n][$arColumnKeys[$columnCount-1]] = htmlspecialcharsbx(Loc::getMessage('SALE_HPS_BILL_TOTAL_VAT_RATE_NO'));
	}
	*/

	if ($params['SUM_PAID'] > 0)
	{
		$cells[++$n] = array();
		for ($i = 0; $i < $columnCount; $i++)
			$cells[$n][$arColumnKeys[$i]] = null;

		$cells[$n][$arColumnKeys[$columnCount-2]] = Loc::getMessage('SALE_HPS_BILL_TOTAL_PAID');
		$cells[$n][$arColumnKeys[$columnCount-1]] = SaleFormatCurrency($params['SUM_PAID'], $params['CURRENCY'], true);
	}
	if ($params['DISCOUNT_PRICE'] > 0)
	{
		$cells[++$n] = array();
		for ($i = 0; $i < $columnCount; $i++)
			$cells[$n][$arColumnKeys[$i]] = null;

		$cells[$n][$arColumnKeys[$columnCount-2]] = Loc::getMessage('SALE_HPS_BILL_TOTAL_DISCOUNT');
		$cells[$n][$arColumnKeys[$columnCount-1]] = SaleFormatCurrency($params['DISCOUNT_PRICE'], $params['CURRENCY'], true);
	}

	$cells[++$n] = array();
	for ($i = 0; $i < $columnCount; $i++)
		$cells[$n][$arColumnKeys[$i]] = null;

	$cells[$n][$arColumnKeys[$columnCount-2]] = Loc::getMessage('SALE_HPS_BILL_TOTAL_SUM');
	$cells[$n][$arColumnKeys[$columnCount-1]] = SaleFormatCurrency($params['SUM'], $params['CURRENCY'], true);

    $n++;
    $vatPercent = $params['SUM'] * 0.1;
    $vatPercent = SaleFormatCurrency($vatPercent, $params['CURRENCY'], true);
    $cells[$n][$arColumnKeys[$columnCount-2]] = Loc::getMessage('SALE_HPS_BILL_TOTAL_VAT_10');
    $cells[$n][$arColumnKeys[$columnCount-1]] = $vatPercent;

    $n++;
    $vatPercent = $params['SUM'] * 0.2;
    $vatPercent = SaleFormatCurrency($vatPercent, $params['CURRENCY'], true);
    $cells[$n][$arColumnKeys[$columnCount-2]] = Loc::getMessage('SALE_HPS_BILL_TOTAL_VAT_20');
    $cells[$n][$arColumnKeys[$columnCount-1]] = $vatPercent;

}
?>

<table class="it" width="100%">
	<tr>
	<?foreach ($arCols as $columnId => $col):?>
		<td><?=$col['NAME'];?></td>
	<?endforeach;?>
	</tr>
<?

$rowsCnt = count($cells);
for ($n = 1; $n <= $rowsCnt; $n++):

	$accumulated = 0;
?>
	<tr valign="top">
	<?foreach ($arCols as $columnId => $col):?>
		<?
			if (!is_null($cells[$n][$columnId]))
			{
				if ($columnId === 'NUMBER')
				{?>
					<td align="center"><?=$cells[$n][$columnId];?></td>
				<?}
				elseif ($columnId === 'NAME')
				{
				?>
					<td align="<?=($n > $cntBasketItem) ? 'right' : 'left';?>"
						style="word-break: break-word; word-wrap: break-word; <? if ($accumulated) {?>border-width: 0pt 1pt 0pt 0pt; <? } ?>"
						<? if ($accumulated) { ?>colspan="<?=($accumulated+1); ?>"<? $accumulated = 0; } ?>>
						<?=$cells[$n][$columnId]; ?>
						<? if (isset($props[$n]) && is_array($props[$n])) { ?>
						<? foreach ($props[$n] as $property) { ?>
						<br>
						<small><?=$property; ?></small>
						<? } ?>
						<? } ?>
					</td>
				<?}
				else
				{
					if (!is_null($cells[$n][$columnId]))
					{
						if ($columnId != 'VAT_RATE' || $vat > 0 || is_null($cells[$n][$columnId]) || $n > $cntBasketItem)
						{ ?>
							<td align="right"
								<? if ($accumulated) { ?>
								style="border-width: 0pt 1pt 0pt 0pt"
								colspan="<?=(($columnId == 'VAT_RATE' && $vat <= 0) ? $accumulated : $accumulated+1); ?>"
								<? $accumulated = 0; } ?>>
								<?if ($columnId == 'SUM' || $columnId == 'PRICE'):?>
									<nobr><?=$cells[$n][$columnId];?></nobr>
								<?else:?>
									<?=$cells[$n][$columnId]; ?>
								<?endif;?>
							</td>
						<? }
					}
					else
					{
						$accumulated++;
					}
				}
			}
			else
			{
				$accumulated++;
			}
		?>
	<?endforeach;?>
	</tr>

<?endfor;?>
</table>
<br>

<?if ($params['BILL_TOTAL_SHOW'] == 'Y'):?>
	<?=Loc::getMessage(
			'SALE_HPS_BILL_BASKET_TOTAL',
			array(
					'#BASKET_COUNT#' => $cntBasketItem,
					'#BASKET_PRICE#' => SaleFormatCurrency($params['SUM'], $params['CURRENCY'], false)
			)
	);?>

    <div style="margin-top: 15px; margin-bottom: 15px;">
        <b style="margin-right: 15px; font-weight: bold;">Сумма:</b>
        <b style="font-weight: bold;">
            <?
            if (in_array($params['CURRENCY'], array("RUR", "RUB")))
            {
                echo Number2Word_Rus($params['SUM']);
            }
            else
            {
                echo SaleFormatCurrency(
                    $params['SUM'],
                    $params['CURRENCY'],
                    false
                );
            }

            ?>
        </b>
    </div>
<?endif;?>

<?
$params['SUM30'] = $params['SUM'] * 0.3;
$params['SUM70'] = $params['SUM'] - $params['SUM30'];
?>

<p>
    Оплата счета осуществляется в следующем порядке:
</p>

<p>
    <b style="font-weight: bold;"><?=SaleFormatCurrency($params['SUM30'], $params['CURRENCY'], false)?> (30 % от суммы, указанной в счёте)</b>
    должны быть оплачены в течение 3 (трех) банковских дней с даты получения счета Покупателем
</p>

<p>
    <b style="font-weight: bold;"><?=SaleFormatCurrency($params['SUM70'], $params['CURRENCY'], false)?> (остаток суммы по счёту)</b>
    должны быть оплачены за 7 (семь) банковских дней до даты предполагаемого прибытия товара  на таможню.
</p>
<p>
    Обязательства покупателя перед продавцом по оплате товаров считаются исполненными с момента поступления  100% предоплаты на расчетный счет поставщика.
</p>
<p>
    Отгрузка товара осуществляется самовывозом со склада Поставщика в МосквеДоставка силами и за счет Поставщика осуществляется в пределах г. Москвы и Московской области(до склада Покупателя или до терминала ТК)
</p>

<div style="padding: 0 24px; margin-bottom: 24px; font-weight: bold;">Внимание! Счет действителен в течение 3-х рабочих дней!!! При непоступлении денежных средств на расчетный счет компании заказ будет расформирован</div>


<?if ($params['BILL_SIGN_SHOW'] == 'Y'):?>
	<? if (!$blank) { ?>
	<div style="position: relative; "><?=CFile::ShowImage(
			$params["BILL_PATH_TO_STAMP"],
		160, 160,
		'style="position: absolute; left: 40pt; "'
	); ?></div>
	<? } ?>

	<div style="position: relative">
		<table class="sign">
			<? if ($params["SELLER_COMPANY_DIRECTOR_POSITION"]) { ?>
			<tr>
				<td style="width: 150pt; ">РУКОВОДИТЕЛЬ</td>
				<td style="width: 160pt; border: 1pt solid #000000; border-width: 0pt 0pt 1pt 0pt; text-align: center; ">
					<? if (!$blank) { ?>
					<?=CFile::ShowImage($params["SELLER_COMPANY_DIR_SIGN"], 200, 50); ?>
					<? } ?>
				</td>
				<td>
					<? if ($params["SELLER_COMPANY_DIRECTOR_NAME"]) { ?>
					(<?=$params["SELLER_COMPANY_DIRECTOR_NAME"]; ?>)
					<? } ?>
				</td>
			</tr>
			<tr><td colspan="3">&nbsp;</td></tr>
			<? } ?>
			<? if ($params["SELLER_COMPANY_ACCOUNTANT_POSITION"]) { ?>
			<tr>
				<td style="width: 150pt; ">ГЛАВНЫЙ БУХГАЛТЕР</td>
				<td style="width: 160pt; border: 1pt solid #000000; border-width: 0pt 0pt 1pt 0pt; text-align: center; ">
					<? if (!$blank) { ?>
					<?=CFile::ShowImage($params["SELLER_COMPANY_ACC_SIGN"], 200, 50); ?>
					<? } ?>
				</td>
				<td>
					<? if ($params["SELLER_COMPANY_ACCOUNTANT_NAME"]) { ?>
					(<?=$params["SELLER_COMPANY_ACCOUNTANT_NAME"]; ?>)
					<? } ?>
				</td>
			</tr>
			<? } ?>
		</table>
	</div>
<?endif;?>

</div>

</body>
</html>