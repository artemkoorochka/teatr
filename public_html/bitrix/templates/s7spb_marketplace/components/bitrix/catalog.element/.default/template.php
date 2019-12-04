<?
/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

use Bitrix\Main\Localization\Loc;
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
Loc::loadLanguageFile(__FILE__);
$MOQ = $arResult["DISPLAY_PROPERTIES"]["MOQ"]["DISPLAY_VALUE"] ? $arResult["DISPLAY_PROPERTIES"]["MOQ"]["DISPLAY_VALUE"] : 1;
?>

<div class="s7sbp--marketplace--catalog-element-detail">
	
	<div class="s7sbp--marketplace--catalog-element-detail-product">
		<div class="s7sbp--marketplace--catalog-element-detail-product--left">
            <div class="s7sbp--marketplace--catalog-element-detail-product--slides">
                <?
                reset($arResult['MORE_PHOTO']);
                $arFirstPhoto = current($arResult['MORE_PHOTO']);
                ?>
                <div class="slides">
                    <?if($arResult["MORE_PHOTO"]):
                        $bIsOneImage = count($arResult["MORE_PHOTO"]) <= 1;
                        ?>
                        <ul>
                            <?foreach($arResult["MORE_PHOTO"] as $i => $arImage):
                                $isEmpty = ($arImage["SMALL"]["src"] ? false : true );
                                $alt = $arImage["ALT"];
                                $title = $arImage["TITLE"];
                                ?>
                                <li id="photo-<?=$i?>" <?=(!$i ? 'class="current"' : 'style="display: none;"')?>>
                                    <?if(!$isEmpty):?>
                                        <a href="<?=$arImage["BIG"]["src"]?>" <?=($bIsOneImage ? '' : 'data-fancybox-group="item_slider"')?> class="popup_link fancy" title="<?=$title;?>">
                                            <img  src="<?=$arImage["SMALL"]["src"]?>" <?=($viewImgType=="MAGNIFIER" ? "class='zoom_picture'" : "");?> <?=($viewImgType=="MAGNIFIER" ? 'xoriginal="'.$arImage["BIG"]["src"].'" xpreview="'.$arImage["THUMB"]["src"].'"' : "");?> alt="<?=$alt;?>" title="<?=$title;?>"<?=(!$i ? ' itemprop="image"' : '')?>/>
                                        </a>
                                    <?else:?>
                                        <img  src="<?=$arImage["SRC"]?>" alt="<?=$alt;?>" title="<?=$title;?>" />
                                    <?endif;?>
                                </li>
                            <?endforeach;?>
                        </ul>
                    <?endif;?>
                </div>
                <?/*thumbs*/?>
                <?if(count($arResult["MORE_PHOTO"]) > 1):?>
                    <div class="wrapp_thumbs xzoom-thumbs">
                        <div class="thumbs flexslider" data-plugin-options='{"animation": "slide", "selector": ".slides_block > li", "directionNav": true, "itemMargin":10, "itemWidth": 54, "controlsContainer": ".thumbs_navigation", "controlNav" :false, "animationLoop": true, "slideshow": false}' style="max-width:<?=ceil(((count($arResult['MORE_PHOTO']) <= 4 ? count($arResult['MORE_PHOTO']) : 4) * 64) - 10)?>px;">
                            <ul class="slides_block" id="thumbs">
                                <?foreach($arResult["MORE_PHOTO"]as $i => $arImage):?>
                                    <li <?=(!$i ? 'class="current"' : '')?> data-big_img="<?=$arImage["BIG"]["src"]?>" data-small_img="<?=$arImage["SMALL"]["src"]?>">
                                        <span><img class="xzoom-gallery" width="50" xpreview="<?=$arImage["THUMB"]["src"];?>" src="<?=$arImage["THUMB"]["src"]?>" alt="<?=$arImage["ALT"];?>" title="<?=$arImage["TITLE"];?>" /></span>
                                    </li>
                                <?endforeach;?>
                            </ul>
                            <span class="thumbs_navigation custom_flex"></span>
                        </div>
                    </div>
                    <script>
                        $(document).ready(function(){
                            $('.s7sbp--marketplace--catalog-element-detail-product--slides .thumbs li').first().addClass('current');
                            $('.s7sbp--marketplace--catalog-element-detail-product--slides .thumbs .slides_block').delegate('li:not(.current)', 'click', function(){
                                var slider_wrapper = $(this).parents('.s7sbp--marketplace--catalog-element-detail-product--slides'),
                                    index = $(this).index();
                                $(this).addClass('current').siblings().removeClass('current')
                                slider_wrapper.find('.slides li').removeClass('current').hide();
                                slider_wrapper.find('.slides li:eq('+index+')').addClass('current').show();
                            });
                        })
                    </script>
                <?endif;?>
            </div>
		</div>

		<div class="s7sbp--marketplace--catalog-element-detail-product--right">
			<h1><span class="s7sbp--marketplace--catalog-element-detail-product--title"><?=$arResult["NAME"]?></span></h1>

			<div class="s7sbp--marketplace--catalog-element-detail-product--header-line">
                <?if(in_array($arResult["ID"], $arParams["USER_FAVORITES"])):?>
                    <div class="s7sbp--marketplace--catalog-element-detail-product--header-line--item--wish-list d-inline-block active cursor"
                         data-title="<?=GetMessage("CATALOG_IZB")?>"
                         data-title-in="<?=GetMessage("CATALOG_IZB_IN")?>"
                         data-item-id="<?=$arResult["ID"]?>"><?=GetMessage("CATALOG_IZB_IN")?></div>
                <?else:?>
                    <div class="s7sbp--marketplace--catalog-element-detail-product--header-line--item--wish-list d-inline-block cursor"
                         data-title="<?=GetMessage("CATALOG_IZB")?>"
                         data-title-in="<?=GetMessage("CATALOG_IZB_IN")?>"
                         data-item-id="<?=$arResult["ID"]?>"><?=GetMessage("CATALOG_IZB")?></div>
                <?endif;?>
			</div>

			<div class="s7sbp--marketplace--catalog-element-detail-product--about">

                <div class="s7sbp--marketplace--catalog-element-detail-product--about-property">

					<div class="s7sbp--marketplace--catalog-element-detail-product--about-property--list">
						<div class="s7sbp--marketplace--catalog-element-detail-product--about-property--list--item">
							<table class="props_list">
                                <tr itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">
                                    <td class="char_name">
                                        <span><span itemprop="name"><?=Loc::getMessage("PRODUCT_FIELD_ID")?></span></span>
                                    </td>
                                    <td class="char_value">
                                        <span><?=$arResult["ID"]?></span>
                                    </td>
                                </tr>
								<?
								//$i = 0;
								foreach($arResult["DISPLAY_PROPERTIES"] as $arProp):?>
									<?if(!in_array($arProp["CODE"], array("MORE_PHOTO", "MOQ", "Production_time_days"))):?>
										<?if((!is_array($arProp["DISPLAY_VALUE"]) && strlen($arProp["DISPLAY_VALUE"])) || (is_array($arProp["DISPLAY_VALUE"]) && implode('', $arProp["DISPLAY_VALUE"]))):
											//if($i++ > 10) continue;
										?>
											<tr itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">
												<td class="char_name">
													<span <?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"){?>class="whint"<?}?>><?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"):?><div class="hint"><span class="icon"><i>?</i></span><div class="tooltip"><?=$arProp["HINT"]?></div></div><?endif;?><span itemprop="name"><?=$arProp["NAME"]?></span></span>
												</td>
												<td class="char_value">
													<span itemprop="value" id="property-<?=$arProp["CODE"]?>">
														<?if(count($arProp["DISPLAY_VALUE"]) > 1):?>
															<?=implode(', ', $arProp["DISPLAY_VALUE"]);?>
														<?else:?>
															<?=$arProp["DISPLAY_VALUE"];?>
														<?endif;?>
													</span>
												</td>
											</tr>
										<?endif;?>
									<?endif;?>
								<?endforeach;?>
							</table>
						</div>
					</div>

                    <?if(!empty($arResult["PRICES"])):?>
                        <table class="border border-light width-100 mt-5">
                            <?
                            $i = 0;
                            foreach ($arResult["PRICES"] as $code=>$arPrice):
                                $i++;
                            ?>
                                <tr>
                                    <td class="p-3<?=$i===1?" bg-light text-18 text-bold":" "?>">
                                        <?
                                        echo Loc::getMessage("PRODUCT_PRICE_QUANT");
                                        echo ", ";
                                        echo $arResult["CAT_PRICES"][$code]["TITLE"]
                                        ?>
                                    </td>
                                    <td class="p-3 text-right<?=$i===1?" bg-light text-18 text-bold":" "?>">
                                        <?
                                        // $arPrice["PRINT_VALUE"]
                                        $arPrice["VALUE"] = round($arPrice["VALUE"], 2);
                                        echo CurrencyFormat($arPrice["VALUE"], $arPrice["CURRENCY"]);
                                        ?>
                                    </td>
                                </tr>
                            <?
                            endforeach;
                            ?>
                        </table>
                    <?endif;?>

                </div>

				<div class="s7sbp--marketplace--catalog-element-detail-product--about-store">

                    <?if($arResult["DISPLAY_PROPERTIES"]["MOQ"]):?>
                        <div class="d-inline-block pt-1 pb-1 pl-2 pr-2 bg-warning">
                            <?=Loc::getMessage("PRODUCT_PROPERTY_MOQ", array("COUNT" => $arResult["DISPLAY_PROPERTIES"]["MOQ"]["DISPLAY_VALUE"]))?>
                        </div>
                    <?endif;?>

                    <?if($arResult["DISPLAY_PROPERTIES"]["Production_time_days"]):?>
                        <div class="d-inline-block pt-1 pb-1 pl-2 pr-2">
                            <?=Loc::getMessage("PRODUCT_PROPERTY_PRODACTION_DAYS", array("COUNT" => $arResult["DISPLAY_PROPERTIES"]["Production_time_days"]["DISPLAY_VALUE"]))?>
                        </div>
                    <?endif;?>

                    <div class="s7sbp--marketplace--catalog-element-detail-product--controls">
                        <div class="s7sbp--marketplace--catalog-element-detail-product--controls--amount">
                            <span class="product-item-amount-field-btn-minus no-select product-item-amount-field-btn-disabled" id="<?=$arResult['ID']?>_quant_down"></span>
                            <input class="product-item-amount-field"
                                   type="number"
                                   name="<?=$arParams['PRODUCT_QUANTITY_VARIABLE']?>"
                                   data-moq="<?=$MOQ?>"
                                   data-hint="<?=$arResult["DISPLAY_PROPERTIES"]["MOQ"]["HINT"]?>"
                                   data-title="<?=Loc::getMessage("PRODUCT_WARNING")?>"
                                   value="<?=$MOQ?>">
                            <span class="product-item-amount-field-btn-plus no-select" id="<?=$arResult['ID']?>_quant_up"></span>
                        </div>
                        <div class="s7sbp--marketplace--catalog-element-detail-product--controls--add-to-basket">
                            <button class="btn" data-item-id="<?=$arResult["ID"]?>"><?=GetMessage("PRODUCT_ADD_TO_BASKET")?></button>
                        </div>
                    </div>

                    <div class="mt-3"><?=Loc::getMessage("PRODUCT_ORDER_ITOG")?>:</div>
                    <table class="width-100 border border-light mt-2 text-bold">

                        <?if(!empty($arResult["PROPERTIES"]["Master_CTN_PCS"]["VALUE"])):?>
                            <tr>
                                <td class="p-2"><?=Loc::getMessage("PRODUCT_ORDER_ITOG_PCS")?></td>
                                <td class="p-2 text-right">
                                    <span id="calculator-<?=$arResult["PROPERTIES"]["Master_CTN_PCS"]["CODE"]?>">
                                        <?
                                        $Master_CTN_PCS = intval($arResult["PROPERTIES"]["Master_CTN_PCS"]["VALUE"]) * $MOQ;
                                        $Master_CTN_PCS = round($Master_CTN_PCS, 2);
                                        echo $Master_CTN_PCS;
                                        ?>
                                    </span>
                                    <?=Loc::getMessage("PRODUCT_ORDER_ITOG_PCS_QUANT")?>
                                </td>
                            </tr>
                        <?endif;?>

                        <?if(!empty($arResult["PROPERTIES"]["Master_CTN_CBM"]["VALUE"])):?>
                            <tr>
                                <td class="p-2"><?=Loc::getMessage("PRODUCT_ORDER_ITOG_CTN")?></td>
                                <td class="p-2 text-right">
                                    <span id="calculator-<?=$arResult["PROPERTIES"]["Master_CTN_CBM"]["CODE"]?>">
                                        <?
                                        $Master_CTN_CBM = $arResult["PROPERTIES"]["Master_CTN_CBM"]["VALUE"] * $MOQ;
                                        $Master_CTN_CBM = round($Master_CTN_CBM, 3);
                                        echo $Master_CTN_CBM;
                                        ?>
                                    </span>
                                    <?=Loc::getMessage("PRODUCT_ORDER_ITOG_CTN_QUANT")?>
                                </td>
                            </tr>
                        <?endif;?>

                        <?if(!empty($arResult["PRICES"])):?>
                            <?
                            foreach ($arResult["PRICES"] as $code=>$arPrice):
                                $currency = explode(" ", $arPrice["PRINT_VALUE"]);
                                $currency = array_pop($currency);
                            ?>
                                <tr class="<?=$code == "normal_price" ? "bg-light text-18" : ""?>">
                                    <td class="p-2">
                                        <?
                                        echo Loc::getMessage("CALCULATOR_ITOG");
                                        echo " ";
                                        $arResult["CAT_PRICES"][$code]["TITLE"] = explode(" ", $arResult["CAT_PRICES"][$code]["TITLE"]);
                                        $arResult["CAT_PRICES"][$code]["TITLE"] = array_shift($arResult["CAT_PRICES"][$code]["TITLE"]);
                                        echo $arResult["CAT_PRICES"][$code]["TITLE"];
                                        ?>
                                    </td>
                                    <td class="p-2 text-right text-nowrap"
                                        id="price-<?=$code?>"
                                        data-currency="<?=$currency?>"
                                        data-pcs="<?=$arResult["PROPERTIES"]["Master_CTN_PCS"]["VALUE"]?>"
                                        data-price="<?=$arPrice["VALUE"] * $arResult["PROPERTIES"]["Master_CTN_PCS"]["VALUE"]?>">
                                        <?
                                        $arPrice["VALUE"] = $arPrice["VALUE"] * $arResult["PROPERTIES"]["Master_CTN_PCS"]["VALUE"] * $MOQ;
                                        $arPrice["VALUE"] = round($arPrice["VALUE"], 2);
                                        echo $arPrice["VALUE"];
                                        echo " ";
                                        echo $currency;
                                        ?>
                                    </td>
                                </tr>
                            <?endforeach;?>
                        <?endif;?>

                    </table>

				</div>

			</div>
		</div>
	</div>

	<div class="s7sbp--marketplace--catalog-element-detail-product--tabs">
		<div class="s7sbp--marketplace--catalog-element-detail-product--tabs--header">
			<div class="s7sbp--marketplace--catalog-element-detail-product--tabs--header--item active" data-tabname="about"><?=GetMessage("PRODUCT_TABS_ABOUT")?></div>
			<div class="s7sbp--marketplace--catalog-element-detail-product--tabs--header--item" data-tabname="video"><?=GetMessage("PRODUCT_TABS_VIDEO")?></div>
		</div>
		<div class="s7sbp--marketplace--catalog-element-detail-product--tabs--body">

			<div class="s7sbp--marketplace--catalog-element-detail-product--tabs--body--item active" data-tabname="about">

                <?if(!empty($arResult["DETAIL_TEXT"])):?>
                    <div class="s7sbp--marketplace--catalog-element-detail-product--tabs--body--item--detail-text">
                        <?=$arResult["DETAIL_TEXT"]?>
                    </div>
                <?endif;?>
				
				<div class="s7sbp--marketplace--catalog-element-detail-product--tabs--body--item--property">
					<table class="props_list">
						<?

							$aCompGrupperProperties = array();
							$aSkipProperty = array("MORE_PHOTO", "VIDEO", "DISCOUNT");
							foreach($arResult["PROPERTIES"] as $arProp){
								if(in_array($arProp["CODE"], $aSkipProperty))
								    continue;
								$arProp["DISPLAY_VALUE"] = $arProp["VALUE"] ? $arProp["VALUE"] : Loc::getMessage("PRODUCT_NONE_VALUE");
								$aCompGrupperProperties[] = $arProp;
							}
							$APPLICATION->IncludeComponent('studio7sbp:grupper.list', '', array('DISPLAY_PROPERTIES' => $aCompGrupperProperties), $component);

						?>
					</table>

				</div>

			</div>

            <div class="s7sbp--marketplace--catalog-element-detail-product--tabs--body--item" data-tabname="video">
                <?
                $newWidth = 700;
                $newHeight = 500;

                $arResult["PROPERTIES"]["VIDEO"]["VALUE"] = preg_replace(
                    array('/width="\d+"/i', '/height="\d+"/i'),
                    array(sprintf('width="%d"', $newWidth), sprintf('height="%d"', $newHeight)),
                    $arResult["PROPERTIES"]["VIDEO"]["~VALUE"]);

                echo $arResult["PROPERTIES"]["VIDEO"]["VALUE"]
                ?>
            </div>

		</div>
	</div>
</div>

<script>
	BX.message({
		PRODUCT_ADD_TO_BASKET: '<?=GetMessage("PRODUCT_ADD_TO_BASKET")?>',
		PRODUCT_ADD_TO_BASKET_IN_BASKET: '<?=GetMessage("PRODUCT_ADD_TO_BASKET_IN_BASKET")?>',
	});
</script>