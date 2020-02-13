<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)die();
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>
					</div>
				</section>
			</main>
		</div>
		<footer class="s7sbp--marketplace--footer">
			<div class="s7sbp--marketplace--wrapper-inner">
				<div class="s7sbp--marketplace--footer--left">
					<div class="s7sbp--marketplace--footer--left--logo">
						<a href="/">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
								array(
									"COMPONENT_TEMPLATE" => ".default",
									"PATH" => SITE_DIR."include/footer.logo.php",
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "",
									"AREA_FILE_RECURSIVE" => "Y",
									"EDIT_TEMPLATE" => "standard.php"
								),
								false
							);?>
						</a>
					</div>
					<div class="s7sbp--marketplace--footer--copy">© <?=date("Y")?></div>
					<div class="s7sbp--marketplace--footer--email">Email: <a href="mailto:<?=$moduleOptions["email_in_footer"]?>"><?=$moduleOptions["email_in_footer"]?></a></div>
					<div class="s7sbp--marketplace--footer--link"><a href="/license/"><?=Loc::getMessage("T_S7SPB_MARKETPLACE_USER_LICENSE")?></a></div>
				</div>
				<div class="s7sbp--marketplace--footer--right">				
					<div class="s7sbp--marketplace--footer--menu--col">
                        <h4><?=Loc::getMessage("T_S7SPB_MARKETPLACE_FOOTER_INFO")?></h4>
						<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", array(
							"ROOT_MENU_TYPE" => "bottom_company",
							"MENU_CACHE_TYPE" => "Y",
							"MENU_CACHE_TIME" => "3600000",
							"MENU_CACHE_USE_GROUPS" => "N",
							"MENU_CACHE_GET_VARS" => array(),
							"MAX_LEVEL" => "1",
							"USE_EXT" => "N",
							"DELAY" => "N",
							"ALLOW_MULTI_SELECT" => "N"
							),false
						);?>
					</div>
					<div class="s7sbp--marketplace--footer--menu--col">
                        <h4><?=Loc::getMessage("T_S7SPB_MARKETPLACE_FOOTER_SERVICES")?></h4>
						<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", array(
							"ROOT_MENU_TYPE" => "bottom_services",
							"MENU_CACHE_TYPE" => "Y",
							"MENU_CACHE_TIME" => "3600000",
							"MENU_CACHE_USE_GROUPS" => "N",
							"MENU_CACHE_GET_VARS" => array(),
							"MAX_LEVEL" => "1",
							"USE_EXT" => "N",
							"DELAY" => "N",
							"ALLOW_MULTI_SELECT" => "N"
							),false
						);?>
					</div>
					<div class="s7sbp--marketplace--footer--menu--col">
                        <h4><?=Loc::getMessage("T_S7SPB_MARKETPLACE_FOOTER_MORE")?></h4>
						<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", array(
							"ROOT_MENU_TYPE" => "bottom_partners",
							"MENU_CACHE_TYPE" => "Y",
							"MENU_CACHE_TIME" => "3600000",
							"MENU_CACHE_USE_GROUPS" => "N",
							"MENU_CACHE_GET_VARS" => array(),
							"MAX_LEVEL" => "1",
							"USE_EXT" => "N",
							"DELAY" => "N",
							"ALLOW_MULTI_SELECT" => "N"
							),false
						);?>
                        <a href="<?=SITE_DIR?>partner/" class="btn h4 mt-3 d-block"><?=Loc::getMessage("T_S7SPB_MARKETPLACE_FOOTER_PARTNER")?></a>
					</div>			
					<div class="s7sbp--marketplace--footer--sochial s7sbp--marketplace--footer--menu--col">
						<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
							array(
								"COMPONENT_TEMPLATE" => ".default",
								"PATH" => SITE_DIR."include/footer.social.php",
								"AREA_FILE_SHOW" => "file",
								"AREA_FILE_SUFFIX" => "",
								"AREA_FILE_RECURSIVE" => "Y",
								"EDIT_TEMPLATE" => "standard.php"
							),
							false
						);?>
					</div>
				</div>
			</div>
		</footer>
	</div>

<div id="scroll-to-top" title="<?=Loc::getMessage("T_S7SPB_MARKETPLACE_SCROLL_TOP")?>"><div></div></div>
</body>
</html>