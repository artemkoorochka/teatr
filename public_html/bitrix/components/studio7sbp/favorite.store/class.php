<?
use Bitrix\Iblock\ElementTable,
    Bitrix\Main\Loader,
    Bitrix\Main\Application,
    Studio7spb\Marketplace\ElementsFavoriteTable;

class favoriteStore extends CBitrixComponent
{
    private $_module = "iblock";
    private $_elements;

    /**
     * @return mixed
     */
    public function getElements()
    {
        return $this->_elements;
    }

    /**
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function setElementsFromDB()
    {
        $elements = array();
        if(Loader::includeModule($this->_module)){

            $result = CIBlockElement::GetList(
                array(),
                array(
                    "IBLOCK_ID" => $this->arParams["IBLOCK_ID"],
                    "ACTIVE" => "Y",
                    "PROPERTY_TRADE_MARK" => 2531
                ),
                false,
                false,
                array("ID", "NAME", "IBLOCK_ID", "PREVIEW_PICTURE")
            );

            while ($element = $result->Fetch()){
                if($element["PREVIEW_PICTURE"] > 0){
                    $element["PREVIEW_PICTURE"] = CFile::ResizeImageGet(
                        $element["PREVIEW_PICTURE"],
                        array(
                            "width" => 150,
                            "height" => 150
                        ),
                        BX_RESIZE_IMAGE_EXACT,
                        false
                        );
                }
                $element["PREVIEW_PICTURE"]["SRC"] = $element["PREVIEW_PICTURE"]["src"];
                $elements[$element["ID"]] = $element;
            }
        }
        $this->_elements = $elements;
    }

    public function fillStores(){
        if(empty($this->getElements())){
            if($this->startResultCache()){
                $this->setElementsFromDB();
                $this->endResultCache();
            }
        }
        $this->arResult["STORES"] = $this->getElements();
    }

    public function userActionListen(){
        $request = Application::getInstance()->getContext()->getRequest();
        $id = intval($request->get("id"));
        if($id > 0){
            switch ($request->get("action")){
                case "add":
                    $this->addStoreToFavorite($id);
                    break;
                case "delete":
                    $this->removeStoreFromFavorite($id);
                    break;
            }

        }
    }
    public function addStoreToFavorite($store){
        if($store > 0){
            $store = $this->_elements[$store];
            $store = array(
                "ELEMENT_ID" => $store["ID"],
                "IBLOCK_ID" => $store["IBLOCK_ID"],
                "USER_ID" => $this->arParams["USER_ID"]
            );
            if(ElementsFavoriteTable::getList(array("filter" => $store))->getSelectedRowsCount() <= 0){
                ElementsFavoriteTable::add($store);
            }
        }
    }

    public function removeStoreFromFavorite($store){
        if($store > 0){
            $stores = ElementsFavoriteTable::getList(array(
                "filter" => array(
                    "ELEMENT_ID" => $store,
                    "IBLOCK_ID" => $this->arParams["IBLOCK_ID"],
                    "USER_ID" => $this->arParams["USER_ID"]
                ),
                "select" => array("ID")
            ));
            while ($store = $stores->fetch()){
                ElementsFavoriteTable::delete($store["ID"]);
            }
        }
    }

    private function setFavoriteStores(){
        $favorites = array();
        $stores = ElementsFavoriteTable::getList(array(
            "filter" => array(
                "IBLOCK_ID" => $this->arParams["IBLOCK_ID"],
                "USER_ID" => $this->arParams["USER_ID"]
            ),
            "select" => array("ELEMENT_ID")
        ));
        while ($store = $stores->fetch()){
            $favorites[] = $store["ELEMENT_ID"];
        }

        foreach ($this->getElements() as $store) {
            if(in_array($store["ID"], $favorites)){
                $this->arResult["STORES"][$store["ID"]]["FAVORITE"] = "Y";
            }
        }
    }

    /**
     * @return bool
     */
    public function executeComponent()
    {
        $this->fillStores();
        $this->userActionListen();
        $this->setFavoriteStores();
        $this->includeComponentTemplate();
        return false;
    }
}