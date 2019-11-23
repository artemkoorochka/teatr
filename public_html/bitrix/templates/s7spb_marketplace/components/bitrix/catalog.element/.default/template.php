<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

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
			<?/*mobile*/?>
			<?if(!$showCustomOffer || empty($arResult['OFFERS_PROP'])):?>
				<div class="s7sbp--marketplace--catalog-element-detail-product--slides flex flexslider" data-plugin-options='{"animation": "slide", "directionNav": false, "controlNav": true, "animationLoop": false, "slideshow": true, "slideshowSpeed": 10000, "animationSpeed": 600}'>
					<ul class="slides">
						<?if($arResult["MORE_PHOTO"]){
							foreach($arResult["MORE_PHOTO"] as $i => $arImage){?>
								<?$isEmpty=($arImage["SMALL"]["src"] ? false : true );?>
								<li id="mphoto-<?=$i?>" <?=(!$i ? 'class="current"' : 'style="display: none;"')?>>
									<?
									$alt = $arImage["ALT"];
									$title = $arImage["TITLE"];
									?>
									<?if(!$isEmpty){?>
										<a href="<?=$arImage["BIG"]["src"]?>" data-fancybox-group="item_slider_flex" class="fancy" title="<?=$title;?>" >
											<img src="<?=$arImage["SMALL"]["src"]?>" alt="<?=$alt;?>" title="<?=$title;?>" />
										</a>
									<?}else{?>
										<img  src="<?=$arImage["SRC"];?>" alt="<?=$alt;?>" title="<?=$title;?>" />
									<?}?>
								</li>
							<?}
						}?>
					</ul>
				</div>
			<?else:?>
				<div class="item_slider flex"></div>
			<?endif;?>
		</div>
		<div class="s7sbp--marketplace--catalog-element-detail-product--right">
			<h1><span class="s7sbp--marketplace--catalog-element-detail-product--title"><?=$arResult["NAME"]?></span></h1>
			<div class="s7sbp--marketplace--catalog-element-detail-product--brand" style="background-image: url(/upload/xiaomi.png)"></div>

			<div class="s7sbp--marketplace--catalog-element-detail-product--header-line">
				<div class="s7sbp--marketplace--catalog-element-detail-product--header-line--item">			
					<?$frame = $this->createFrame('dv_'.$arResult["ID"])->begin('');?>
						<span class="iblock-vote-title"><?=GetMessage("RATING-title")?></span>
						<div class="rating">
							<?$APPLICATION->IncludeComponent(
								"bitrix:iblock.vote",
								"product_rating",
								Array(
									"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
									"IBLOCK_ID" => $arResult["IBLOCK_ID"],
									"ELEMENT_ID" => $arResult["ID"],
									"MAX_VOTE" => 5,
									"VOTE_NAMES" => array(),
									"CACHE_TYPE" => $arParams["CACHE_TYPE"],
									"CACHE_TIME" => $arParams["CACHE_TIME"],
									"DISPLAY_AS_RATING" => 'vote_avg'
								),
								$component, array("HIDE_ICONS" =>"Y")
							);?>
						</div>
					<?$frame->end();?>
				</div>
				<div class="s7sbp--marketplace--catalog-element-detail-product--header-line--item">
					<div class="s7sbp--marketplace--catalog-element-detail-product--header-line--item--review button" data-action="showTab" data-tabname="reviews">
						<?
							$reviewDeclension = new \Bitrix\Main\Grid\Declension('отзыв', 'отзыва', 'отзывов');
							$cntReviewProduct = (int)$arResult["PROPERTIES"]["FORUM_MESSAGE_CNT"]["VALUE"];
						?>
						<?=$cntReviewProduct?>&nbsp;<?=$reviewDeclension->get($cntReviewProduct)?>
					</div>
				</div>
				<div class="s7sbp--marketplace--catalog-element-detail-product--header-line--item">
                    <?if(in_array($arResult["ID"], $arParams["USER_FAVORITES"])):?>
                        <div class="s7sbp--marketplace--catalog-element-detail-product--header-line--item--wish-list button active"
                             data-title="<?=GetMessage("CATALOG_IZB")?>"
                             data-title-in="<?=GetMessage("CATALOG_IZB_IN")?>"
                             data-item-id="<?=$arResult["ID"]?>"><?=GetMessage("CATALOG_IZB_IN")?></div>
                    <?else:?>
                        <div class="s7sbp--marketplace--catalog-element-detail-product--header-line--item--wish-list button"
                             data-title="<?=GetMessage("CATALOG_IZB")?>"
                             data-title-in="<?=GetMessage("CATALOG_IZB_IN")?>"
                             data-item-id="<?=$arResult["ID"]?>"><?=GetMessage("CATALOG_IZB")?></div>
                    <?endif;?>
				</div>
				<div class="s7sbp--marketplace--catalog-element-detail-product--header-line--item">
					<div class="s7sbp--marketplace--catalog-element-detail-product--header-line--item--share">
						<script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
						<script src="//yastatic.net/share2/share.js" charset="utf-8"></script>
						<div class="share_wrapp">
							<div class="text button transparent"><?=GetMessage("SHARE_BUTTON");?></div>
							<div class="ya-share2 yashare-auto-init shares" data-services="vkontakte,facebook,odnoklassniki,moimir,twitter,viber,whatsapp,skype,telegram"></div>
						</div>
					</div>
				</div>
			</div>

			<div class="s7sbp--marketplace--catalog-element-detail-product--about">
				<div class="s7sbp--marketplace--catalog-element-detail-product--about-property">
					<div class="s7sbp--marketplace--catalog-element-detail-product--about-property--title"><?=GetMessage("PRODUCT_ABOUT_TITLE")?></div>
					<div class="s7sbp--marketplace--catalog-element-detail-product--about-property--list">
						<div class="s7sbp--marketplace--catalog-element-detail-product--about-property--list--item">
							<table class="props_list">
								<?
								$i = 0;
								foreach($arResult["DISPLAY_PROPERTIES"] as $arProp):?>
									<?if(!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE", "CML2_ARTICLE"))):?>
										<?if((!is_array($arProp["DISPLAY_VALUE"]) && strlen($arProp["DISPLAY_VALUE"])) || (is_array($arProp["DISPLAY_VALUE"]) && implode('', $arProp["DISPLAY_VALUE"]))):
											if($i++ > 3) continue;
										?>
											<tr itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">
												<td class="char_name">
													<span <?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"){?>class="whint"<?}?>><?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"):?><div class="hint"><span class="icon"><i>?</i></span><div class="tooltip"><?=$arProp["HINT"]?></div></div><?endif;?><span itemprop="name"><?=$arProp["NAME"]?></span></span>
												</td>
												<td class="char_value">
													<span itemprop="value">
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
					<div class="s7sbp--marketplace--catalog-element-detail-product--about-property--show-all" data-action="showTab" data-tabname="about"><?=GetMessage("PRODUCT_PROPERTY_SHOW_ALL")?></div>
				</div>
				<div class="s7sbp--marketplace--catalog-element-detail-product--about-store">
					<div class="s7sbp--marketplace--catalog-element-detail-product--about-store--title"><?=GetMessage("PRODUCT_STORE_TITLE")?></div>
					<div class="s7sbp--marketplace--catalog-element-detail-product--about-store--logo" style="background: #f6f6f6"></div>
					<div class="s7sbp--marketplace--catalog-element-detail-product--about-store--rating">
						<div class="s7sbp--marketplace--catalog-element-detail-product--about-store--rating--star star-empty"></div>
						<div class="s7sbp--marketplace--catalog-element-detail-product--about-store--rating--star star-empty"></div>
						<div class="s7sbp--marketplace--catalog-element-detail-product--about-store--rating--star star-empty"></div>
						<div class="s7sbp--marketplace--catalog-element-detail-product--about-store--rating--star star-empty"></div>
						<div class="s7sbp--marketplace--catalog-element-detail-product--about-store--rating--star star-empty"></div>
					</div>
					<div class="s7sbp--marketplace--catalog-element-detail-product--about-store--review">
						<span class="s7sbp--marketplace--catalog-element-detail-product--about-store--review--count">25 отзывов</span>
						<span class="s7sbp--marketplace--catalog-element-detail-product--about-store--review--text"><?=GetMessage("PRODUCT_STORE_REVIEW_TEXT")?></span>
					</div>
				</div>
			</div>

			<div class="s7sbp--marketplace--catalog-element-detail-product--price">
				<?if($arResult["MIN_PRICE"]["DISCOUNT_VALUE"] < $arResult["MIN_PRICE"]["VALUE"]):?>
					<div class="s7sbp--marketplace--catalog-element-detail-product--price--discount">
						<span class="s7sbp--marketplace--catalog-element-detail-product--price--discount-value">
							<?=$arResult["MIN_PRICE"]["PRINT_VALUE"]?>
						</span>
						<span class="s7sbp--marketplace--catalog-element-detail-product--price--discount-percent">-<?=$arResult["MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"]?>%</span>
					</div>
				<?endif;?>
				<div class="s7sbp--marketplace--catalog-element-detail-product--price--value">
					<?=$arResult["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]?>
				</div>
			</div>

			<div class="s7sbp--marketplace--catalog-element-detail-product--controls">
				<div class="s7sbp--marketplace--catalog-element-detail-product--controls--amount">
					<span class="product-item-amount-field-btn-minus no-select product-item-amount-field-btn-disabled" id="<?=$arResult['ID']?>_quant_down"></span>
					<input class="product-item-amount-field" type="number" name="<?=$arParams['PRODUCT_QUANTITY_VARIABLE']?>" value="1">
					<span class="product-item-amount-field-btn-plus no-select" id="<?=$arResult['ID']?>_quant_up"></span>
				</div>
				<div class="s7sbp--marketplace--catalog-element-detail-product--controls--add-to-basket">
					<button class="btn" data-item-id="<?=$arResult["ID"]?>"><?=GetMessage("PRODUCT_ADD_TO_BASKET")?></button>
				</div>
				<?/*
				<div class="s7sbp--marketplace--catalog-element-detail-product--controls--buy-one-click">
					<button class="btn" data-item-id="<?=$arResult["ID"]?>">Купить сейчас</button>
				</div>
				*/?>
			</div>
		</div>
	</div>

	<div class="s7sbp--marketplace--catalog-element-detail-product--tabs">
		<div class="s7sbp--marketplace--catalog-element-detail-product--tabs--header">
			<div class="s7sbp--marketplace--catalog-element-detail-product--tabs--header--item active" data-tabname="about"><?=GetMessage("PRODUCT_TABS_ABOUT")?></div>
			<div class="s7sbp--marketplace--catalog-element-detail-product--tabs--header--item" data-tabname="reviews"><?=GetMessage("PRODUCT_TABS_REVIEW")?></div>
			<div class="s7sbp--marketplace--catalog-element-detail-product--tabs--header--item" data-tabname="delivery"><?=GetMessage("PRODUCT_TABS_DELLIVERY")?></div>
			<div class="s7sbp--marketplace--catalog-element-detail-product--tabs--header--item" data-tabname="garanty"><?=GetMessage("PRODUCT_TABS_GARANTY")?></div>
		</div>
		<div class="s7sbp--marketplace--catalog-element-detail-product--tabs--body">
			<div class="s7sbp--marketplace--catalog-element-detail-product--tabs--body--item active" data-tabname="about">
				<div class="s7sbp--marketplace--catalog-element-detail-product--tabs--body--item--title"><?=GetMessage("PRODUCT_TABS_ABOUT_TITLE")?></div>
				<div class="s7sbp--marketplace--catalog-element-detail-product--tabs--body--item--detail-text">
					<?=$arResult["DETAIL_TEXT"]?>
				</div>
				
				<div class="s7sbp--marketplace--catalog-element-detail-product--tabs--body--item--property">
					<table class="props_list">
						<?
							$aCompGrupperProperties = array();
							$aSkipProperty = array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE", "CML2_ARTICLE");
							foreach($arResult["DISPLAY_PROPERTIES"] as $arProp){
								if(in_array($arProp["CODE"], $aSkipProperty)) continue;
								$aCompGrupperProperties[] = $arProp;
							}
							$APPLICATION->IncludeComponent('studio7sbp:grupper.list', '', array('DISPLAY_PROPERTIES' => $aCompGrupperProperties), $component);
						?>

						<?/*foreach($arResult["DISPLAY_PROPERTIES"] as $arProp):?>
							<?if(!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE", "CML2_ARTICLE"))):?>
								<?if((!is_array($arProp["DISPLAY_VALUE"]) && strlen($arProp["DISPLAY_VALUE"])) || (is_array($arProp["DISPLAY_VALUE"]) && implode('', $arProp["DISPLAY_VALUE"]))):?>
									<tr itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">
										<td class="char_name">
											<span <?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"){?>class="whint"<?}?>><?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"):?><div class="hint"><span class="icon"><i>?</i></span><div class="tooltip"><?=$arProp["HINT"]?></div></div><?endif;?><span itemprop="name"><?=$arProp["NAME"]?></span></span>
										</td>
										<td class="char_value">
											<span itemprop="value">
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
						<?endforeach;*/?>
					</table>
				</div>
			</div>
			<div class="s7sbp--marketplace--catalog-element-detail-product--tabs--body--item" data-tabname="delivery">
				<div class="s7sbp--marketplace--catalog-element-detail-product--tabs--body--item--title"><?=GetMessage("PRODUCT_TABS_DELLIVERY")?></div>
				<?=$arResult["DELIVERY_TEXT"]?>
			</div>
			<div class="s7sbp--marketplace--catalog-element-detail-product--tabs--body--item" data-tabname="garanty">
				<div class="s7sbp--marketplace--catalog-element-detail-product--tabs--body--item--title"><?=GetMessage("PRODUCT_TABS_GARANTY")?></div>
				<?=$arResult["COMPANY_INFO"]["PROPERTY_COMP_GARANTY_VALUE"]["TEXT"]?>
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