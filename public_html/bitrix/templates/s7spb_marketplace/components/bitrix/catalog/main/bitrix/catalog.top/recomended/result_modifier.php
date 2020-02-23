<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogTopComponent $component
 */


foreach ($arResult['ITEMS'] as &$item) {

	if((int)$item["PROPERTIES"]["H_PRICE_DISCOUNT"]["VALUE"] > 0) {

		$discountPrice = $item["PROPERTIES"]["H_PRICE_DISCOUNT"]["VALUE"];

		if($discountPrice >= $item["MIN_PRICE"]["VALUE"]) continue;

		$discountPercent = (100 - round(($discountPrice * 100)/$item["MIN_PRICE"]["VALUE"], 0));
		$discountDiff = $item["MIN_PRICE"]["VALUE"] - $discountPrice;

		$discountDiffFormatted = CurrencyFormat($discountDiff, $item["MIN_PRICE"]["CURRENCY"]);
		$discountPriceFormatted = CurrencyFormat($discountPrice, $item["MIN_PRICE"]["CURRENCY"]);

		foreach ($item["PRICES"] as $keyPriceCode => $aPriceItem) {
			$item["PRICES"][$keyPriceCode]["DISCOUNT_VALUE_VAT"] = $discountPrice;
			$item["PRICES"][$keyPriceCode]["DISCOUNT_VALUE_NOVAT"] = $discountPrice;
			$item["PRICES"][$keyPriceCode]["ROUND_VALUE_VAT"] = $discountPrice;
			$item["PRICES"][$keyPriceCode]["ROUND_VALUE_NOVAT"] = $discountPrice;
			$item["PRICES"][$keyPriceCode]["UNROUND_DISCOUNT_VALUE"] = $discountPrice;
			$item["PRICES"][$keyPriceCode]["DISCOUNT_VALUE"] = $discountPrice;
			$item["PRICES"][$keyPriceCode]["DISCOUNT_DIFF"] = $discountDiff;
			$item["PRICES"][$keyPriceCode]["DISCOUNT_DIFF_PERCENT"] = $discountPercent;

			$item["PRICES"][$keyPriceCode]["PRINT_DISCOUNT_VALUE_NOVAT"] = $discountPriceFormatted;
			$item["PRICES"][$keyPriceCode]["PRINT_DISCOUNT_VALUE_VAT"] = $discountPriceFormatted;
			$item["PRICES"][$keyPriceCode]["PRINT_DISCOUNT_VALUE"] = $discountPriceFormatted;
			$item["PRICES"][$keyPriceCode]["PRINT_DISCOUNT_DIFF"] = $discountDiffFormatted;
		}

		$item["MIN_PRICE"] = $item["PRICES"]["BASE"];

		foreach ($item["ITEM_PRICES"] as $keyPriceId => $aPriceItem) {
			$item["ITEM_PRICES"][$keyPriceId]["UNROUND_PRICE"] = $discountPrice;
			$item["ITEM_PRICES"][$keyPriceId]["PRICE"] = $discountPrice;
			$item["ITEM_PRICES"][$keyPriceId]["DISCOUNT"] = $discountDiff;
			$item["ITEM_PRICES"][$keyPriceId]["PERCENT"] = $discountPercent;

			$item["ITEM_PRICES"][$keyPriceId]["RATIO_PRICE"] = $discountPrice;
			$item["ITEM_PRICES"][$keyPriceId]["PRINT_PRICE"] = $discountPriceFormatted;
			$item["ITEM_PRICES"][$keyPriceId]["PRINT_RATIO_PRICE"] = $discountPriceFormatted;
			$item["ITEM_PRICES"][$keyPriceId]["PRINT_DISCOUNT"] = $discountDiffFormatted;
			$item["ITEM_PRICES"][$keyPriceId]["RATIO_DISCOUNT"] = $discountDiff;
			$item["ITEM_PRICES"][$keyPriceId]["PRINT_RATIO_DISCOUNT"] = $discountDiffFormatted;
		}
	}
}


$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();