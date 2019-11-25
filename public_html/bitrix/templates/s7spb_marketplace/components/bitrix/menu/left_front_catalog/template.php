<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?if( !empty( $arResult ) ):?>
	<?$bIndexBot = (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Lighthouse') !== false);?>
	<div class="s7sbp--marketplace--index--catalog--menu">
		<div class="s7sbp--marketplace--index--catalog--menu--title"><?=GetMessage("MENU_TITLE")?></div>
		<ul class="s7sbp--marketplace--index--catalog--menu--list">
			<?foreach( $arResult as $key => $arItem ){?>
				<li class="full s7sbp--marketplace--index--catalog--menu--list--item caption <?=($arItem["CHILD"] ? "has-child" : "");?> <?=($arItem["SELECTED"] ? "active opened" : "");?> m_line v_hover">
					<span class="s7sbp--marketplace--index--catalog--menu--list--item--icon <?=$arItem["XML_ID"]?>"></span>
					<a class="s7sbp--marketplace--index--catalog--menu--list--item--link <?=($arItem["CHILD"] ? "parent" : "");?>" href="<?=$arItem["SECTION_PAGE_URL"]?>" ><?=$arItem["NAME"]?><div class="toggle_block"></div></a>
					<?if($arItem["CHILD"] && !$bIndexBot){?>
						<ul class="dropdown">
							<?foreach($arItem["CHILD"] as $arChildItem){?>
								<li class="<?=($arChildItem["CHILD"] ? "has-childs" : "");?> <?if($arChildItem["SELECTED"]){?> active <?}?>">
									<a class="section dark_link" href="<?=$arChildItem["SECTION_PAGE_URL"];?>"><span><?=$arChildItem["NAME"];?></span></a>
									<?if($arChildItem["CHILD"]){?>
										<ul class="dropdown">
											<?foreach($arChildItem["CHILD"] as $arChildItem1){?>
												<li class="menu_item <?if($arChildItem1["SELECTED"]){?> active <?}?>">
													<a class="parent1 section1" href="<?=$arChildItem1["SECTION_PAGE_URL"];?>"><span><?=$arChildItem1["NAME"];?></span></a>
												</li>
											<?}?>
										</ul>
									<?}?>
									<div class="clearfix"></div>
								</li>
							<?}?>
						</ul>
					<?}?>
				</li>
			<?}?>
		</ul>
	</div>
<?endif;?>