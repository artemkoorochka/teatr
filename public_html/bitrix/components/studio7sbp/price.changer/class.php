<?
use Bitrix\Main\Loader;


class priceСhanger extends CBitrixComponent {

    private $_prices;

    /**
     * @return mixed
     */
    public function getPrices()
    {
        return $this->_prices;
    }

    /**
     * @param mixed $prices
     */
    public function setPrices()
    {
        if(CModule::IncludeModule("catalog")){
            $prices = CCatalogGroup::GetList(
                array(),
                $this->arParams["filter"],
                false,
                false,
                array()
            );
            while ($price = $prices->fetch())
            {
                $this->_prices[] = $price;
            }
        }
    }

    /**
     * @return mixed|void
     */
    public function executeComponent()
    {
        if($this->startResultCache())
        {
            $this->setPrices();
            $this->endResultCache();
        }
        $this->arResult["PRICES"] = $this->getPrices();
        $this->includeComponentTemplate();
    }
}