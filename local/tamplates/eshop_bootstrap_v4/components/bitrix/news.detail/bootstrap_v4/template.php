<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$themeClass = isset($arParams['TEMPLATE_THEME']) ? ' bx-' . $arParams['TEMPLATE_THEME'] : '';
CUtil::InitJSCore(['fx', 'ui.fonts.opensans']);
?>
<div class="news-detail<?= $themeClass ?>">
	<div class="mb-3" id="<? echo $this->GetEditAreaId($arResult['ID']) ?>">

		<!-- Перебор свойств в детальной карточке и подключаем Карту -->
		<? foreach ($arResult["DISPLAY_PROPERTIES"] as $pid => $arProperty) : ?>
			<li class="propertie_office">
				<?
				if (is_array($arProperty["DISPLAY_VALUE"]))
					$value = implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);
				else
					$value = $arProperty["DISPLAY_VALUE"];
				?>
				<? if ($value != "") : ?>
					<? if ($arProperty["CODE"] !== "YANDEX_MAP_OFFICE_RU") : ?>
						<div class="news_list_view">
							<span class="office_list_icon"></span>
							<span class="office_list_param"><?= $arProperty["NAME"] ?>:</span>
							<span class="office_list_value"><?= $value; ?></span>
						</div>
					<? else : ?>
						<div class="news_list_view_yandex_map">
							<span class="office_list_icon_yandex_map"></span>
							<span class="office_yandex_map"><?= $value; ?></span>
						</div>
					<? endif; ?>


				<? endif; ?>
			</li>
		<? endforeach; ?>

		<? if ($arParams["DISPLAY_DATE"] != "N" && $arResult["DISPLAY_ACTIVE_FROM"]) : ?>
			<div class="news-detail-date"><? echo $arResult["DISPLAY_ACTIVE_FROM"] ?></div>
		<? endif ?>



	</div>
</div>
<script type="text/javascript">
	BX.ready(function() {
		var slider = new JCNewsSlider('<?= CUtil::JSEscape($this->GetEditAreaId($arResult['ID'])); ?>', {
			imagesContainerClassName: 'news-detail-slider-container',
			leftArrowClassName: 'news-detail-slider-arrow-container-left',
			rightArrowClassName: 'news-detail-slider-arrow-container-right',
			controlContainerClassName: 'news-detail-slider-control'
		});
	});
</script>