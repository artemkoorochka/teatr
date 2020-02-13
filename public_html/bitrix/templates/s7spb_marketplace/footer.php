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
                </div>
                <div class="s7sbp--marketplace--footer--right">
                    <div class="s7sbp--marketplace--footer--menu--col">
                        <h4><?=Loc::getMessage("T_S7SPB_MARKETPLACE_FOOTER_INFO")?></h4>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:news.list",
                            "bottom",
                            array(
                                "COMPONENT_TEMPLATE" => "bottom",
                                "CURRENT_PAGE" => $APPLICATION->GetCurPage(),
                                "IBLOCK_TYPE" => "marketplace",
                                "IBLOCK_ID" => "10",
                                "NEWS_COUNT" => "100",
                                "SORT_BY1" => "SORT",
                                "SORT_ORDER1" => "ASC",
                                "SORT_BY2" => "SORT",
                                "SORT_ORDER2" => "ASC",
                                "FILTER_NAME" => "",
                                "FIELD_CODE" => array(
                                    0 => "",
                                    1 => "",
                                ),
                                "PROPERTY_CODE" => array(
                                    0 => "",
                                    1 => "",
                                ),
                                "CHECK_DATES" => "Y",
                                "DETAIL_URL" => "",
                                "AJAX_MODE" => "N",
                                "AJAX_OPTION_JUMP" => "N",
                                "AJAX_OPTION_STYLE" => "N",
                                "AJAX_OPTION_HISTORY" => "N",
                                "AJAX_OPTION_ADDITIONAL" => "",
                                "CACHE_TYPE" => "A",
                                "CACHE_TIME" => "36000000",
                                "CACHE_FILTER" => "N",
                                "CACHE_GROUPS" => "N",
                                "PREVIEW_TRUNCATE_LEN" => "",
                                "ACTIVE_DATE_FORMAT" => "",
                                "SET_TITLE" => "N",
                                "SET_BROWSER_TITLE" => "N",
                                "SET_META_KEYWORDS" => "N",
                                "SET_META_DESCRIPTION" => "N",
                                "SET_LAST_MODIFIED" => "N",
                                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                                "ADD_SECTIONS_CHAIN" => "N",
                                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                                "PARENT_SECTION" => "",
                                "PARENT_SECTION_CODE" => "",
                                "INCLUDE_SUBSECTIONS" => "N",
                                "STRICT_SECTION_CHECK" => "N",
                                "DISPLAY_DATE" => "N",
                                "DISPLAY_NAME" => "N",
                                "DISPLAY_PICTURE" => "N",
                                "DISPLAY_PREVIEW_TEXT" => "N",
                                "PAGER_TEMPLATE" => ".default",
                                "DISPLAY_TOP_PAGER" => "N",
                                "DISPLAY_BOTTOM_PAGER" => "N",
                                "PAGER_TITLE" => "Новости",
                                "PAGER_SHOW_ALWAYS" => "N",
                                "PAGER_DESC_NUMBERING" => "N",
                                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                                "PAGER_SHOW_ALL" => "N",
                                "PAGER_BASE_LINK_ENABLE" => "N",
                                "SET_STATUS_404" => "N",
                                "SHOW_404" => "N",
                                "MESSAGE_404" => ""
                            ),
                            false
                        );?>
                    </div>
                    <div class="s7sbp--marketplace--footer--menu--col">
                        <h4><?=Loc::getMessage("T_S7SPB_MARKETPLACE_FOOTER_SERVICES")?></h4>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:news.list",
                            "bottom",
                            array(
                                "COMPONENT_TEMPLATE" => "bottom",
                                "CURRENT_PAGE" => $APPLICATION->GetCurPage(),
                                "IBLOCK_TYPE" => "marketplace",
                                "IBLOCK_ID" => "11",
                                "NEWS_COUNT" => "100",
                                "SORT_BY1" => "ACTIVE_FROM",
                                "SORT_ORDER1" => "DESC",
                                "SORT_BY2" => "SORT",
                                "SORT_ORDER2" => "ASC",
                                "FILTER_NAME" => "",
                                "FIELD_CODE" => array(
                                    0 => "",
                                    1 => "",
                                ),
                                "PROPERTY_CODE" => array(
                                    0 => "",
                                    1 => "",
                                ),
                                "CHECK_DATES" => "Y",
                                "DETAIL_URL" => "",
                                "AJAX_MODE" => "N",
                                "AJAX_OPTION_JUMP" => "N",
                                "AJAX_OPTION_STYLE" => "N",
                                "AJAX_OPTION_HISTORY" => "N",
                                "AJAX_OPTION_ADDITIONAL" => "",
                                "CACHE_TYPE" => "A",
                                "CACHE_TIME" => "36000000",
                                "CACHE_FILTER" => "N",
                                "CACHE_GROUPS" => "N",
                                "PREVIEW_TRUNCATE_LEN" => "",
                                "ACTIVE_DATE_FORMAT" => "",
                                "SET_TITLE" => "N",
                                "SET_BROWSER_TITLE" => "N",
                                "SET_META_KEYWORDS" => "N",
                                "SET_META_DESCRIPTION" => "N",
                                "SET_LAST_MODIFIED" => "N",
                                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                                "ADD_SECTIONS_CHAIN" => "N",
                                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                                "PARENT_SECTION" => "",
                                "PARENT_SECTION_CODE" => "",
                                "INCLUDE_SUBSECTIONS" => "N",
                                "STRICT_SECTION_CHECK" => "N",
                                "DISPLAY_DATE" => "N",
                                "DISPLAY_NAME" => "N",
                                "DISPLAY_PICTURE" => "N",
                                "DISPLAY_PREVIEW_TEXT" => "N",
                                "PAGER_TEMPLATE" => ".default",
                                "DISPLAY_TOP_PAGER" => "N",
                                "DISPLAY_BOTTOM_PAGER" => "N",
                                "PAGER_TITLE" => "Новости",
                                "PAGER_SHOW_ALWAYS" => "N",
                                "PAGER_DESC_NUMBERING" => "N",
                                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                                "PAGER_SHOW_ALL" => "N",
                                "PAGER_BASE_LINK_ENABLE" => "N",
                                "SET_STATUS_404" => "N",
                                "SHOW_404" => "N",
                                "MESSAGE_404" => ""
                            ),
                            false
                        );?>
                    </div>
                    <div class="s7sbp--marketplace--footer--menu--col">
                        <h4><?=Loc::getMessage("T_S7SPB_MARKETPLACE_FOOTER_MORE")?></h4>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:news.list",
                            "bottom",
                            array(
                                "COMPONENT_TEMPLATE" => "bottom",
                                "CURRENT_PAGE" => $APPLICATION->GetCurPage(),
                                "IBLOCK_TYPE" => "marketplace",
                                "IBLOCK_ID" => "12",
                                "NEWS_COUNT" => "100",
                                "SORT_BY1" => "ACTIVE_FROM",
                                "SORT_ORDER1" => "DESC",
                                "SORT_BY2" => "SORT",
                                "SORT_ORDER2" => "ASC",
                                "FILTER_NAME" => "",
                                "FIELD_CODE" => array(
                                    0 => "",
                                    1 => "",
                                ),
                                "PROPERTY_CODE" => array(
                                    0 => "",
                                    1 => "",
                                ),
                                "CHECK_DATES" => "Y",
                                "DETAIL_URL" => "",
                                "AJAX_MODE" => "N",
                                "AJAX_OPTION_JUMP" => "N",
                                "AJAX_OPTION_STYLE" => "N",
                                "AJAX_OPTION_HISTORY" => "N",
                                "AJAX_OPTION_ADDITIONAL" => "",
                                "CACHE_TYPE" => "A",
                                "CACHE_TIME" => "36000000",
                                "CACHE_FILTER" => "N",
                                "CACHE_GROUPS" => "N",
                                "PREVIEW_TRUNCATE_LEN" => "",
                                "ACTIVE_DATE_FORMAT" => "",
                                "SET_TITLE" => "N",
                                "SET_BROWSER_TITLE" => "N",
                                "SET_META_KEYWORDS" => "N",
                                "SET_META_DESCRIPTION" => "N",
                                "SET_LAST_MODIFIED" => "N",
                                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                                "ADD_SECTIONS_CHAIN" => "N",
                                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                                "PARENT_SECTION" => "",
                                "PARENT_SECTION_CODE" => "",
                                "INCLUDE_SUBSECTIONS" => "N",
                                "STRICT_SECTION_CHECK" => "N",
                                "DISPLAY_DATE" => "N",
                                "DISPLAY_NAME" => "N",
                                "DISPLAY_PICTURE" => "N",
                                "DISPLAY_PREVIEW_TEXT" => "N",
                                "PAGER_TEMPLATE" => ".default",
                                "DISPLAY_TOP_PAGER" => "N",
                                "DISPLAY_BOTTOM_PAGER" => "N",
                                "PAGER_TITLE" => "Новости",
                                "PAGER_SHOW_ALWAYS" => "N",
                                "PAGER_DESC_NUMBERING" => "N",
                                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                                "PAGER_SHOW_ALL" => "N",
                                "PAGER_BASE_LINK_ENABLE" => "N",
                                "SET_STATUS_404" => "N",
                                "SHOW_404" => "N",
                                "MESSAGE_404" => ""
                            ),
                            false
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
<a href="<?=SITE_DIR?>basket/" id="stiky-bascet">
    <span class="icon-basket"></span>
    <span class="s7sbp--marketplace--full--basket--counter"></span>
</a>
</body>
</html>