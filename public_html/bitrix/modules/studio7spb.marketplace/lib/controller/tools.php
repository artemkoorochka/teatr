<?php
namespace Studio7spb\Marketplace\Controller;
 

class Tools extends \Bitrix\Main\Engine\Controller {

	public function configureActions() {
		return [
			'getBasketInfo' => [
				'-prefilters' => [
					\Bitrix\Main\Engine\ActionFilter\Authentication::class,
				],
			],
			'addToBasket' => [
				'-prefilters' => [
					\Bitrix\Main\Engine\ActionFilter\Authentication::class,
				],
			],
			'deleteBasket' => [
				'-prefilters' => [
					\Bitrix\Main\Engine\ActionFilter\Authentication::class,
				],
			],
			'counterBasket' => [
				'-prefilters' => [
					\Bitrix\Main\Engine\ActionFilter\Authentication::class,
				],
			],
			'addToWish' => [
				'-prefilters' => [
					\Bitrix\Main\Engine\ActionFilter\Authentication::class,
				],
			],
		];
	}
	
	private function loadModules2Basket() {
		\CModule::IncludeModule("sale");
		\CModule::IncludeModule("catalog");
		\CModule::IncludeModule("iblock");
	}

	public function getBasketInfoAction() {

		self::loadModules2Basket();

		\Studio7spb\Marketplace\CMarketplaceCache::ClearCacheByTag('sale_basket');
		$iblockId = \Studio7spb\Marketplace\CMarketplaceOptions::getInstance()->getOption('catalog_iblock_id');

		$arItems = \Studio7spb\Marketplace\CMarketplaceTools::getBasketItems($iblockId);

		// Подменить delay на favorite
        global $USER;
        \CBitrixComponent::includeComponentClass("studio7sbp:favorite");
        $favorite = new \studio7sbpFavorite();
        if($USER->IsAuthorized()){
            $favorite->onPrepareComponentParams(array("USER_ID" => $USER->GetID()));
        }
        $arItems["delay"] = $favorite->getUserFavorite();

		return $arItems;
	}

	public function addToBasketAction($id, $quantity) {

		if((int)$id <= 0) {
			$this->errorCollection[] = new \Bitrix\Main\Error("Do not id params", "PARAMETR_ERROR");
			return null;
		}

		self::loadModules2Basket();

		$dbBasketItems = \CSaleBasket::GetList(
			array("NAME" => "ASC", "ID" => "ASC"),
			array("PRODUCT_ID" => $id, "FUSER_ID" => \CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => "NULL"),
			false, false, array("ID", "DELAY")
		)->Fetch();
		if(!empty($dbBasketItems) && $dbBasketItems["DELAY"] == "Y") {
			$arFields = array("DELAY" => "N", "SUBSCRIBE" => "N");
			if($quantity){
				$arFields['QUANTITY'] = $quantity;
			}
			\CSaleBasket::Update($dbBasketItems["ID"], $arFields);
		} else {
			$product_properties = array();
			$arSkuProp = array();
			$successfulAdd = true;
			$intProductIBlockID = (int)\CIBlockElement::GetIBlockByID($id);
			$strErrorExt='';

			if($intProductIBlockID <= 0){
				$this->errorCollection[] = new \Bitrix\Main\Error("Element not fount in catalog", "CATALOG_ERROR");
				return null;
			}
			if($successfulAdd){
				if(!\Add2BasketByProductID($id, $quantity, $arRewriteFields, $product_properties)) {
					global $APPLICATION;
					if ($ex = $APPLICATION->GetException())
						$strErrorExt = $ex->GetString();

					$this->errorCollection[] = new \Bitrix\Main\Error($strErrorExt, "ADD2BASKET_ERROR");
					return null;
				}
			}
		}
		return array();
	}

    public function deleteBasketAction($id=0) {

        self::loadModules2Basket();

        $arFilter = array(
            "FUSER_ID" => \CSaleBasket::GetBasketUserID(),
            "LID" => SITE_ID,
            "ORDER_ID" => "NULL"
            //"CAN_BUY" => "Y",
            //"SUBSCRIBE" => "N"
        );

        if((int)$id > 0) {
            $arFilter["ID"] = $id;
        }

        $dbBasketItems = \CSaleBasket::GetList(
            array("NAME" => "ASC", "ID" => "ASC"),
            $arFilter,
            false, false, array("ID")
        );
        while ($dbBasketItem = $dbBasketItems->Fetch())
        {
            \CSaleBasket::Delete($dbBasketItem["ID"]);
        }

        return null;

    }

    public function counterBasketAction($id, $quantity) {
        if(intval($quantity) > 0 && intval($id) > 0){
            self::loadModules2Basket();
            \CSaleBasket::Update($id, array("QUANTITY" => $quantity));
        }
    }

    public function addToWishAction($id) {

        if((int)$id <= 0) {
            $this->errorCollection[] = new \Bitrix\Main\Error("Do not id params", "PARAMETR_ERROR");
            return null;
        }
        self::loadModules2Basket();
        $delete = array();
        $dbBasketItems = \CSaleBasket::GetList(
            array("NAME" => "ASC", "ID" => "ASC"),
            array("PRODUCT_ID" => $id, "FUSER_ID" => \CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => "NULL", "CAN_BUY" => "Y", "SUBSCRIBE" => "N"),
            false, false, array("ID", "PRODUCT_ID", "DELAY")
        )->Fetch();
        if(!empty($dbBasketItems) && $dbBasketItems["DELAY"] == "N") {
            $arFields = array("DELAY" => "Y", "SUBSCRIBE" => "N");
            \CSaleBasket::Update($dbBasketItems["ID"], $arFields);
        } elseif (!empty($dbBasketItems) && $dbBasketItems["DELAY"] == "Y") {
            $delete = $dbBasketItems;
            \CSaleBasket::Delete($dbBasketItems["ID"]);
        } else {
            $idBasket = \Add2BasketByProductID($id, 1);

            if(!$idBasket){
                global $APPLICATION;
                if ($ex = $APPLICATION->GetException())
                    $strErrorExt = $ex->GetString();

                $this->errorCollection[] = new \Bitrix\Main\Error($strErrorExt, "ADD2BASKET_ERROR");
                return null;
            }

            $arFields = array("DELAY" => "Y", "SUBSCRIBE" => "N");
            \CSaleBasket::Update($idBasket, $arFields);
        }

        /// Add favorite feature
        \CBitrixComponent::includeComponentClass("studio7sbp:favorite");
        $favorite = new \studio7sbpFavorite();
        global $USER;
        $favorite->onPrepareComponentParams(array("USER_ID" => $USER->GetID()));
        if(empty($delete)){
            $favorite->addUserFavorite($id);
        }else{
            $favorite->deleteUserFavorite($id);
        }


        return array();
    }

}