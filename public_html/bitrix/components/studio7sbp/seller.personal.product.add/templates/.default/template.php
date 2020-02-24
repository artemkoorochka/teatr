<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

CJSCore::Init(array('ajax'));
?>
<div class="s7sbp--marketplace--saler--lk--right--inner">
	<div class="s7sbp--marketplace--saler--lk--product-add--title">
		<?if((int)$arParams["ELEMENT_ID"]> 0):?>Изменение товара<?else:?>Добавление товара<?endif;?>
		<span class="s7sbp--marketplace--saler--lk--product-add--back"><a href="<?=$arParams["SEF_FOLDER"]?>products/"><?=Loc::getMessage("SPPA_BACK_TO_PRODUCT_PAGE")?></a></span>
	</div>

	<form class="s7sbp--marketplace--saler--lk--product-add ff--roboto" name="form-roduct-add" method="POST">

		<?if(!empty($arResult["error"])):
			ShowError(implode("<br />", $arResult["error"]));
		endif;?>

		<?=bitrix_sessid_post()?>

		<div class="s7sbp--marketplace--saler--lk--product-add--field">
			<div class="s7sbp--marketplace--saler--lk--product-add--field--label">
				<label for="SECTION_ID">Категория <span class="required">*</span></label>
			</div>
			<div class="s7sbp--marketplace--saler--lk--product-add--field--value">
				<select name="SECTION_ID" id="SECTION_ID" required="required">
					<option value=""></option>
					<?foreach ($arResult["cat_tree"] as $aCatTreeItem):?>
						<option value="<?=$aCatTreeItem["ID"]?>" <?if($arResult["ELEMENT"]["IBLOCK_SECTION_ID"] == $aCatTreeItem["ID"]):?> selected="selected"<?endif;?>><?=str_repeat(" . ", $aCatTreeItem["DEPTH_LEVEL"])?><?=$aCatTreeItem["NAME"]?></option>
					<?endforeach?>
				</select>
			</div>
		</div>
		<div class="s7sbp--marketplace--saler--lk--product-add--field">
			<div class="s7sbp--marketplace--saler--lk--product-add--field--label">
				<label for="NAME">Название <span class="required">*</span></label>
			</div>
			<div class="s7sbp--marketplace--saler--lk--product-add--field--value">
				<textarea name="NAME" id="NAME" required="required"><?=$arResult["ELEMENT"]["NAME"]?></textarea>
			</div>
		</div>

		<div class="s7sbp--marketplace--saler--lk--product-add--field">
			<div class="s7sbp--marketplace--saler--lk--product-add--field--label">
				<label for="MEASURE_ID">Базовая ед. измерения <span class="required">*</span></label>
			</div>
			<div class="s7sbp--marketplace--saler--lk--product-add--field--value">
				<select name="MEASURE_ID" id="MEASURE_ID" required="required">
					<option value=""></option>
					<?foreach ($arResult["measure_list"] as $aMeasureItem):?>
						<option value="<?=$aMeasureItem["ID"]?>" <?if($arResult["ELEMENT_PRODUCT_INFO"]["MEASURE"] == $aMeasureItem["ID"]):?> selected="selected"<?endif;?>><?=$aMeasureItem["MEASURE_TITLE"]?></option>
					<?endforeach?>
				</select>
			</div>
		</div>

		<div class="s7sbp--marketplace--saler--lk--product-add--field">
			<div class="s7sbp--marketplace--saler--lk--product-add--field--label">
				<label for="PRICE">Цена <span class="required">*</span></label>
			</div>
			<div class="s7sbp--marketplace--saler--lk--product-add--field--value">
				<input type="text" name="PRICE" id="PRICE" value="<?=$arResult["ELEMENT_PRICE_INFO"]["PRICE"]?>" required="required">
			</div>
		</div>

		<div class="s7sbp--marketplace--saler--lk--product-add--field">
			<div class="s7sbp--marketplace--saler--lk--product-add--field--label" style="color: red">
				<label for="PRICE_DISCOUNT">Цена со скидкой <span class="required">*</span></label>
			</div>
			<div class="s7sbp--marketplace--saler--lk--product-add--field--value">
				<input type="text" name="PRICE_DISCOUNT" id="PRICE_DISCOUNT" value="<?=$arResult["ELEMENT_PROPERTIES"][15][0]["VALUE"]?>" required="required">
			</div>
		</div>

		<div class="s7sbp--marketplace--saler--lk--product-add--field">
			<div class="s7sbp--marketplace--saler--lk--product-add--field--label">
				<label for="QUANTITY">Количество <span class="required">*</span></label>
			</div>
			<div class="s7sbp--marketplace--saler--lk--product-add--field--value">
				<input type="text" name="QUANTITY" id="QUANTITY" value="<?=$arResult["ELEMENT_PRODUCT_INFO"]["QUANTITY"]?>" required="required">
			</div>
		</div>

		<div class="s7sbp--marketplace--saler--lk--product-add--field">
			<div class="s7sbp--marketplace--saler--lk--product-add--field--label w-100">
				<b>Краткое описание</b> <span class="required">*</span>
			</div>
			<?$APPLICATION->IncludeComponent(
				"studio7sbp:lhe",
				"",
				Array(
					"LHE_NAME" => "lhe_preview_text_form",
					"LHE_ID" => "lhe_preview_text_form",
					"INPUT_NAME" => "PREVIEW_TEXT",
					"INPUT_VALUE" => $arResult["ELEMENT"]["PREVIEW_TEXT"],
				),
				$component,
				Array("HIDE_ICONS" => "Y")
			);?>
		</div>

		<div class="s7sbp--marketplace--saler--lk--product-add--field">
			<div class="s7sbp--marketplace--saler--lk--product-add--field--label w-100">
				<b>Подробное описание товара</b> <span class="required">*</span>
			</div>
			<?$APPLICATION->IncludeComponent(
				"studio7sbp:lhe",
				"",
				Array(
					"LHE_NAME" => "lhe_detail_text_form",
					"LHE_ID" => "lhe_detail_text_form",
					"INPUT_NAME" => "DETAIL_TEXT",
					"INPUT_VALUE" => $arResult["ELEMENT"]["DETAIL_TEXT"],
				),
				$component,
				Array("HIDE_ICONS" => "Y")
			);?>
		</div>

		<div class="s7sbp--marketplace--saler--lk--product-add--header">Изображения товара</div>

		<?$APPLICATION->IncludeComponent("bitrix:main.file.input", "",
			array(
				"INPUT_NAME"=>"DETAIL_PICTURE",
				"INPUT_VALUE" => $arResult["ELEMENT"]["DETAIL_PICTURE"],
				"MULTIPLE"=>"N",
				"MODULE_ID"=>"iblock",
				"MAX_FILE_SIZE"=>"",
				"ALLOW_UPLOAD"=>"I", 
				"ALLOW_UPLOAD_EXT"=>""
			),
			$component,
			Array("HIDE_ICONS" => "Y")
		);?>
		

		<?
		$aPicturesIds = array();
		foreach ($arResult["ELEMENT_PROPERTIES"][6] as $picItem) {
			$aPicturesIds[] = $picItem["VALUE"];
		}
		$APPLICATION->IncludeComponent("bitrix:main.file.input", "dnd",
			array(
				"INPUT_NAME"=>"D_N_D_PICTURES",
				"INPUT_VALUE" => $aPicturesIds,
				"MULTIPLE"=>"Y",
				"MODULE_ID"=>"iblock",
				"MAX_FILE_SIZE"=>"",
				"ALLOW_UPLOAD"=>"I", 
				"ALLOW_UPLOAD_EXT"=>""
			),
			$component,
			Array("HIDE_ICONS" => "Y")
		);?>

		<div class="s7sbp--marketplace--saler--lk--product-add--sub">
			Максимальный размер каждого файла - 5 МБ.
		</div>

		<div class="s7sbp--marketplace--saler--lk--product-add--header">Характеристики товара</div>

        <?
        $arParams["COMPANY"] = $arResult["COMPANY"];
        $APPLICATION->IncludeComponent(
			"studio7sbp:iblock.property.edit",
			"",
			$arParams,
			$component,
			array("HIDE_ICONS"=>"Y")
		);
        ?>
		
		<input type="hidden" name="Update" value="Y">
		
		<div class="s7sbp--marketplace--saler--lk--product-add--field" style="text-align: right">
			<button type="submit" class="s7sbp--marketplace--saler--lk--product-add--field--btn">Сохранить</button>
		</div>

	</form>
</div>
