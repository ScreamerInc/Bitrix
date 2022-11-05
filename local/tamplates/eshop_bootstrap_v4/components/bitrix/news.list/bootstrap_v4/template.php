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

\Bitrix\Main\UI\Extension::load('ui.fonts.opensans');

$themeClass = isset($arParams['TEMPLATE_THEME']) ? ' bx-' . $arParams['TEMPLATE_THEME'] : '';
?>
<div class="row news-list<?= $themeClass ?>">
	<div class="col">
		<? if ($arParams["DISPLAY_TOP_PAGER"]) : ?>
			<?= $arResult["NAV_STRING"] ?><br />
		<? endif; ?>
		<div class="row">
			<? foreach ($arResult["ITEMS"] as $arItem) : ?>
				<?
				$this->AddEditAction(
					$arItem['ID'],
					$arItem['EDIT_LINK'],
					CIBlock::GetArrayByID(
						$arItem["IBLOCK_ID"],
						"ELEMENT_EDIT"
					)
				);
				$this->AddDeleteAction(
					$arItem['ID'],
					$arItem['DELETE_LINK'],
					CIBlock::GetArrayByID(
						$arItem["IBLOCK_ID"],
						"ELEMENT_DELETE"
					),
					array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM'))
				);
				?>
				<div class="news-list-item mb-2 col-sm" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
				<!-- Работа над карточкой офиса и подключение Карты -->
					<div class="card_list">
						<div class="office_card">
							<a class="link_office_card" href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><h2 class="office_card_name"><?= $arItem["NAME"];?></h2></a>
							<div class="office_details">
								<ul class="card_properties">
									<!-- Создание свойств карточки офиса и подключение Карты -->
									<? foreach ($arItem["DISPLAY_PROPERTIES"] as $pid => $arProperty) : ?>
										<li class="propertie_office">
											<?
											if (is_array($arProperty["DISPLAY_VALUE"]))
												$value = implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);
											else
												$value = $arProperty["DISPLAY_VALUE"];
											?>
											<!-- Не пустой массив -->
											<? if ($value != "") : ?>
												<!-- Если не карта, заносить шаблон классов для свойств -->
												<? if ($arProperty["CODE"] !== "YANDEX_MAP_OFFICE_RU") : ?>
													<div class="news_list_view">
														<span class="office_list_icon"></span>
														<span class="office_list_param"><?= $arProperty["NAME"] ?>:</span>
														<span class="office_list_value"><?= $value; ?></span>
													</div>
												<? else : ?>
													<!-- Если заданы данные в карту, то изменить классы полей -->
													<div class="news_list_view_yandex_map">
														<span class="office_list_icon_yandex_map"></span>
														<span class="office_yandex_map"><?= $value; ?></span>
													</div>
												<? endif; ?>
											<? endif; ?>
										</li>
									<? endforeach; ?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			<? endforeach; ?>
		</div>

		<? if ($arParams["DISPLAY_BOTTOM_PAGER"]) : ?>
			<?= $arResult["NAV_STRING"] ?>
		<? endif; ?>
	</div>
</div>