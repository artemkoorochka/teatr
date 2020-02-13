<?php
namespace Studio7spb\Marketplace;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);

class CMarketplaceTools {	

	public static function getIblockByTagName($sTagName) {

	}

	static function getCurrentDir() {
		static $result;

		if(!isset($result)){
			global $APPLICATION;
			$result = $APPLICATION->GetCurDir();
		}
		return $result;
	}

	static function isOneColumnWithBreadcrums(){

		$aOneComunLink = array("/auth/", "/basket/", "/partner/", "/help/", 
			"/return/", "/pay/", "/about/", "/order/",
			"/feedback/", "/sale/", "info", "info.help", "info.more");
		$sCurrentDis = self::getCurrentDir();

		foreach ($aOneComunLink as $aOneComunLinkItem) {
			if(strpos($sCurrentDis, $aOneComunLinkItem) !== false) {
				return true;
			}
		}
		return false;
	}

	static function IsMainPage(){
		static $result;

		if(!isset($result)){
			$result = \CSite::InDir(SITE_DIR.'index.php');
		}
		return $result;
	}

	static function IsCatalogPage($page = ''){
		static $result;

		if(!isset($result)) {
			if(!$page) {
				$page = CMarketplaceOptions::getInstance()->getOption("catalog_page_url");
				if(!$page) {
					$page = SITE_DIR.'catalog/';
				}
				$page = str_replace('#SITE_DIR#', SITE_DIR, $page);
			}
			$result = \CSite::InDir($page);
		}
		return $result;
	}
	
	static function getSkipCatalogProperty() {
		return array("rating", "vote_count", "vote_sum", "FORUM_TOPIC_ID", "FORUM_MESSAGE_CNT", "IS_NEW", "IS_RECOMMEND", "IS_BESTSELLER");
	}

	/**
	 * $aProperty = ['id' => 'iblockPropertyId', 'value' => 'elementId', 'name' => 'elementName']
	 * 
	 */

	public static function setIblockUiFilter($iblockType, $iblockId, $aProperty) {

		$sFilterName = "tbl_iblock_element_".md5($iblockType.".".$iblockId);

		$aParams = array(
			"FILTER_ID" => $sFilterName,
			"GRID_ID" => $sFilterName,
			"action" => "setFilter",
			"forAll" => false,
			"commonPresetsId" => "",
			"apply_filter" => "Y",
			"clear_filter" => "N",
			"with_preset" => "N",
			"save" => "Y"
		);
		$aData = array(
			'fields' => array (
				'FIND' => '',
				'PROPERTY_'.$aProperty['id'] => '{"'.$aProperty['value'].'":["'.$aProperty['value'].'","['.$aProperty['value'].'] '.$aProperty['name'].'"]}',
				'PROPERTY_'.$aProperty['id'].'_label' => '['.$aProperty['value'].'] '.$aProperty['name'],
			),
			'rows' => 'PROPERTY_'.$aProperty['id'],
			'preset_id' => 'tmp_filter',
			'name' => '',
		);

		$oOptions = self::getUiFilterOptions($aParams);
		$oOptions->setFilterSettings($aData['preset_id'], $aData);
		$oOptions->save();
	}

	private static function getUiFilterOptions($aParams) {

		$oOptions = new \Bitrix\Main\UI\Filter\Options(
			$aParams['FILTER_ID'], null, ""
		);

		return $oOptions;
	}

	public static function getBasketItems($iblockId, $field = "PRODUCT_ID"){
		$basket_items = $delay_items = $subscribe_items = $not_available_items = array();
		$arItems = array();
		$sum = 0;
		$currency = "RUB";
		if(\CModule::IncludeModule("sale")){
			$arBasketItems = array();
			$dbRes = \CSaleBasket::GetList(
			    array("NAME" => "ASC", "ID" => "ASC"),
                array("FUSER_ID" => \CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => "NULL"),
                false,
                false,
                array("ID", "PRODUCT_ID", "DELAY", "SUBSCRIBE", "CAN_BUY", "TYPE", "SET_PARENT_ID", "QUANTITY", "CURRENCY", "PRICE")
            );
			while($item = $dbRes->Fetch()){
				$arBasketItems[] = $item;
			}

			global $compare_items;
			if(!is_array($compare_items)){
				$compare_items = array();
				if($iblockId && isset($_SESSION["CATALOG_COMPARE_LIST"][$iblockId]["ITEMS"])){
					$compare_items = array_keys($_SESSION["CATALOG_COMPARE_LIST"][$iblockId]["ITEMS"]);
				}
			}
			if($arBasketItems){
				foreach($arBasketItems as $arBasketItem){
					if(\CSaleBasketHelper::isSetItem($arBasketItem)) // set item
						continue;
					if($arBasketItem["DELAY"]=="N" && $arBasketItem["CAN_BUY"] == "Y" && $arBasketItem["SUBSCRIBE"] == "N"){
						$basket_items[] = $arBasketItem[$field];
						$currency = $arBasketItem["CURRENCY"];
                        $arBasketItem["SUM"] = $arBasketItem["PRICE"] * $arBasketItem["QUANTITY"];
						$sum += $arBasketItem["SUM"];
					}
					elseif($arBasketItem["DELAY"]=="Y" && $arBasketItem["CAN_BUY"] == "Y" && $arBasketItem["SUBSCRIBE"] == "N"){
						$delay_items[] = $arBasketItem[$field];
					}
					elseif($arBasketItem["SUBSCRIBE"]=="Y"){
						$subscribe_items[] = $arBasketItem[$field];
					}else{
						$not_available_items[] = $arBasketItem[$field];
					}
				}
			}
			$arItems["basket"] = array_combine($basket_items, $basket_items);
			$arItems["delay"] = array_combine($delay_items, $delay_items);
			$arItems["subscribe"] = array_combine($subscribe_items, $subscribe_items);
			$arItems["not_available"] = array_combine($not_available_items, $not_available_items);
			$arItems["compare"] = array_combine($compare_items, $compare_items);

            $count = count($arItems["basket"]);
            $countReminder = ($count > 10 && $count < 20) ? 0 : $count % 10;

            if($count > 0){
                if ($countReminder === 1)
                {
                    $arItems["sum"] = "<b>" . count($arItems["basket"]) . "</b> товар на <b>" . CurrencyFormat($sum, $currency) . "</b>";
                }
                else if ($countReminder >= 2 && $countReminder <= 4)
                {
                    $arItems["sum"] = "<b>" . count($arItems["basket"]) . "</b> товара на <b>" . CurrencyFormat($sum, $currency)  . "</b>";
                }
                else
                {
                    $arItems["sum"] = "<b>" . count($arItems["basket"]) . "</b> товаров на <b>" . CurrencyFormat($sum, $currency) . "</b>";
                }
            }
            else{
                $arItems["sum"] = "<b>" . count($arItems["basket"]) . "</b>";
            }

		}

		return $arItems;
	}

	public static function getBasketCounters(){
		Loader::includeModule("sale");

		$fUserId = \Bitrix\Sale\Fuser::getId();
		return (int)\Bitrix\Sale\BasketComponentHelper::getFUserBasketQuantity($fUserId);
	}

} 