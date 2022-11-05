<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Карты");
?>
//* Запуск скрипта на очитску страницы//
<?php 
ob_start(); 
session_start();
?>

<?php require '.parameters.php'?>
<?php
CModule::IncludeModule('iblock');
$ib = new CIBlock();

// Создаем инфоблок каталога товаров //

// Настройка доступа
$arAccess = array(
    "2" => "R", // Все пользователи
);
if ($contentGroupId) $arAccess[$contentGroupId] = "X"; // Полный доступ
if ($editorGroupId) $arAccess[$editorGroupId] = "W"; // Запись
if ($ownerGroupId) $arAccess[$ownerGroupId] = "X"; // Полный доступ

$arFields = array(
    "ACTIVE" => "Y",
    "NAME" => "Офисы Газпром",
    "CODE" => "gazprom_office",
    "IBLOCK_TYPE_ID" => $IBLOCK_TYPE,
    "SITE_ID" => $SITE_ID,
    "SORT" => "5",
    "GROUP_ID" => $arAccess, // Права доступа
    "FIELDS" => array(
        "DETAIL_PICTURE" => array(
            "IS_REQUIRED" => "N", // не обязательное
            "DEFAULT_VALUE" => array(
                "SCALE" => "Y",
                "WIDTH" => "600",
                "HEIGHT" => "600",
                "IGNORE_ERRORS" => "Y",
                "METHOD" => "resample",
                "COMPRESSION" => "95",
            ),
        ),
        "PREVIEW_PICTURE" => array(
            "IS_REQUIRED" => "N", // не обязательное
            "DEFAULT_VALUE" => array(
                "SCALE" => "Y",
                "WIDTH" => "140",
                "HEIGHT" => "140",
                "IGNORE_ERRORS" => "Y",
                "METHOD" => "resample",
                "COMPRESSION" => "95",
                "FROM_DETAIL" => "Y",
                "DELETE_WITH_DETAIL" => "Y",
                "UPDATE_WITH_DETAIL" => "Y",
            ),
        ),
        "SECTION_PICTURE" => array(
            "IS_REQUIRED" => "N", // не обязательное
            "DEFAULT_VALUE" => array(
                "SCALE" => "Y",
                "WIDTH" => "235",
                "HEIGHT" => "235",
                "IGNORE_ERRORS" => "Y",
                "METHOD" => "resample",
                "COMPRESSION" => "95",
                "FROM_DETAIL" => "Y",
                "DELETE_WITH_DETAIL" => "Y",
                "UPDATE_WITH_DETAIL" => "Y",
            ),
        ),
        // Символьный код элементов
        "CODE" => array(
            "IS_REQUIRED" => "Y", // Обязательное
            "DEFAULT_VALUE" => array(
                "UNIQUE" => "Y", // Проверять на уникальность
                "TRANSLITERATION" => "Y", // Транслитерировать
                "TRANS_LEN" => "30", // Максмальная длина транслитерации
                "TRANS_CASE" => "L", // Приводить к нижнему регистру
                "TRANS_SPACE" => "-", // Символы для замены
                "TRANS_OTHER" => "-",
                "TRANS_EAT" => "Y",
                "USE_GOOGLE" => "N",
            ),
        ),
        // Символьный код разделов
        "SECTION_CODE" => array(
            "IS_REQUIRED" => "Y",
            "DEFAULT_VALUE" => array(
                "UNIQUE" => "Y",
                "TRANSLITERATION" => "Y",
                "TRANS_LEN" => "30",
                "TRANS_CASE" => "L",
                "TRANS_SPACE" => "-",
                "TRANS_OTHER" => "-",
                "TRANS_EAT" => "Y",
                "USE_GOOGLE" => "N",
            ),
        ),
        "DETAIL_TEXT_TYPE" => array( // Тип детального описания
            "DEFAULT_VALUE" => "html",
        ),
        "SECTION_DESCRIPTION_TYPE" => array(
            "DEFAULT_VALUE" => "html",
        ),
        "IBLOCK_SECTION" => array( // Привязка к разделам обязательноа
            "IS_REQUIRED" => "N",
        ),
        "LOG_SECTION_ADD" => array("IS_REQUIRED" => "Y"), // Журналирование
        "LOG_SECTION_EDIT" => array("IS_REQUIRED" => "Y"),
        "LOG_SECTION_DELETE" => array("IS_REQUIRED" => "Y"),
        "LOG_ELEMENT_ADD" => array("IS_REQUIRED" => "Y"),
        "LOG_ELEMENT_EDIT" => array("IS_REQUIRED" => "Y"),
        "LOG_ELEMENT_DELETE" => array("IS_REQUIRED" => "Y"),
    ),

    // Шаблоны страниц
    "LIST_PAGE_URL" => "#SITE_DIR#//",
    "SECTION_PAGE_URL" => "#SITE_DIR#/gazprom_office/#SECTION_CODE#/",
    "DETAIL_PAGE_URL" => "#SITE_DIR#/gazprom_office/#SECTION_CODE#/#ELEMENT_CODE#/",

    "INDEX_SECTION" => "Y", // Индексировать разделы для модуля поиска
    "INDEX_ELEMENT" => "Y", // Индексировать элементы для модуля поиска

    "VERSION" => 1, // Хранение элементов в общей таблице

    "ELEMENT_NAME" => "Данные",
    "ELEMENTS_NAME" => "Данные",
    "ELEMENT_ADD" => "Добавить данные",
    "ELEMENT_EDIT" => "Изменить данные",
    "ELEMENT_DELETE" => "Удалить данные",
    "SECTION_NAME" => "Категории данных",
    "SECTIONS_NAME" => "Категория данных",
    "SECTION_ADD" => "Добавить данные",
    "SECTION_EDIT" => "Изменить категорию данных",
    "SECTION_DELETE" => "Удалить категорию данных",

    "SECTION_PROPERTY" => "Y",
);

$ID = $ib->Add($arFields);
if ($ID > 0) {
    echo "Инфоблок \"Инфоблок данных\" успешно создан<br />";
	$_SESSION['ID'] = $ID;
} else {
    echo "Ошибка создания инфоблока \"Инфоблок данных\"<br />";
}
// Добавляем свойства к каталогу товаров //

// Определяем, есть ли у инфоблока свойства
$dbProperties = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $ID));
if ($dbProperties->SelectedRowsCount() <= 0) {
    $ibp = new CIBlockProperty;

    $arFields = array(
        "NAME" => "Название офиса",
        "ACTIVE" => "Y",
        "SORT" => 2,
        "CODE" => "OFFICE_NAME",
        "PROPERTY_TYPE" => "S", // Строка
        "ROW_COUNT" => 1, // Количество строк
        "COL_COUNT" => 60, // Количество столбцов
        "IBLOCK_ID" => $ID,
        "HINT" => "Указывается название офиса",
    );
    $propId = $ibp->Add($arFields);
    if ($propId > 0) {
        $arFields["ID"] = $propId;
        $arCommonProps[$arFields["CODE"]] = $arFields;
        echo "&mdash; Добавлено свойство " . $arFields["NAME"] . "<br />";
    } else
        echo "&mdash; Ошибка добавления свойства " . $arFields["NAME"] . "<br />";


    $arFields = array(
        "NAME" => "Телефон",
        "ACTIVE" => "Y",
        "SORT" => 3,
        "CODE" => "TELEPHONE_OFFICE",
        "PROPERTY_TYPE" => "S", // Строка
        "ROW_COUNT" => 1, // Количество строк
        "COL_COUNT" => 60, // Количество столбцов
        "IBLOCK_ID" => $ID,
        "HINT" => "Указывается телефон офиса",
    );
    $propId = $ibp->Add($arFields);
    if ($propId > 0) {
        $arFields["ID"] = $propId;
        $arCommonProps[$arFields["CODE"]] = $arFields;
        echo "&mdash; Добавлено свойство " . $arFields["NAME"] . "<br />";
    } else
        echo "&mdash; Ошибка добавления свойства " . $arFields["NAME"] . "<br />";

    $arFields = array(
        "NAME" => "Email",
        "ACTIVE" => "Y",
        "SORT" => 4,
        "CODE" => "EMAIL_OFFICE",
        "PROPERTY_TYPE" => "S", // Строка
        "ROW_COUNT" => 3, // Количество строк
        "COL_COUNT" => 70, // Количество столбцов
        "IBLOCK_ID" => $ID,
        "HINT" => "Указывается email офиса",
    );
    $propId = $ibp->Add($arFields);
    if ($propId > 0) {
        $arFields["ID"] = $propId;
        $arCommonProps[$arFields["CODE"]] = $arFields;
        echo "&mdash; Добавлено свойство " . $arFields["NAME"] . "<br />";
    } else
        echo "&mdash; Ошибка добавления свойства " . $arFields["NAME"] . "<br />";

    $arFields = array(
        "NAME" => "Координата х",
        "ACTIVE" => "Y",
        "SORT" => 5,
        "CODE" => "KOORDINATION_OFFICE_X",
        "PROPERTY_TYPE" => "S", // Строка
        "ROW_COUNT" => 3, // Количество строк
        "COL_COUNT" => 70, // Количество столбцов
        "IBLOCK_ID" => $ID,
        "HINT" => "Координата х для отметки точки на карте",
    );
    $propId = $ibp->Add($arFields);
    if ($propId > 0) {
        $arFields["ID"] = $propId;
        $arCommonProps[$arFields["CODE"]] = $arFields;
        echo "&mdash; Добавлено свойство " . $arFields["NAME"] . "<br />";
    } else
        echo "&mdash; Ошибка добавления свойства " . $arFields["NAME"] . "<br />";

    $arFields = array(
        "NAME" => "Координата y",
        "ACTIVE" => "Y",
        "SORT" => 6,
        "CODE" => "KOORDINATION_OFFICE_Y",
        "PROPERTY_TYPE" => "S", // Строка
        "ROW_COUNT" => 3, // Количество строк
        "COL_COUNT" => 70, // Количество столбцов
        "IBLOCK_ID" => $ID,
        "HINT" => "Координата у для отметки точки на карте",
    );
    $propId = $ibp->Add($arFields);
    if ($propId > 0) {
        $arFields["ID"] = $propId;
        $arCommonProps[$arFields["CODE"]] = $arFields;
        echo "Добавлено свойство " . $arFields["NAME"] . "<br />";
    } else
        echo "Ошибка добавления свойства " . $arFields["NAME"] . "<br />";

    $arFields = array(
        "NAME" => "Город",
        "ACTIVE" => "Y",
        "SORT" => 7,
        "CODE" => "CITY_OFFICE",
        "PROPERTY_TYPE" => "S", // Строка
        "ROW_COUNT" => 3, // Количество строк
        "COL_COUNT" => 70, // Количество столбцов
        "IBLOCK_ID" => $ID,
        "HINT" => "Укажите город, где находится офис",
    );
    $propId = $ibp->Add($arFields);
    if ($propId > 0) {
        $arFields["ID"] = $propId;
        $arCommonProps[$arFields["CODE"]] = $arFields;
        echo "&mdash; Добавлено свойство " . $arFields["NAME"] . "<br />";
    } else
        echo "&mdash; Ошибка добавления свойства " . $arFields["NAME"] . "<br />";

        $arFields = array(
            "NAME" => "Яндекс карта",
            "ACTIVE" => "Y",
            "SORT" => 8,
            "CODE" => "YANDEX_MAP_OFFICE_RU",
            "PROPERTY_TYPE" => 'S', // Строка
            "USER_TYPE" => 'map_yandex', // Тип карты
            "IBLOCK_ID" => $ID,
        );
        $propId = $ibp->Add($arFields);
        if ($propId > 0) {
            $arFields["ID"] = $propId;
            $arCommonProps[$arFields["CODE"]] = $arFields;
            echo "Добавлено свойство " . $arFields["NAME"] . "<br />";
        } else
            echo "Ошибка добавления свойства " . $arFields["NAME"] . "<br />";

} else
    echo "Для данного инфоблока уже существуют свойства<br />";

$obUserType = new CUserTypeEntity();

$fieldTitle = "Регион";
$arPropFields = array(
    "ENTITY_ID" => "IBLOCK_" . $ID . "_SECTION",
    "FIELD_NAME" => "REGION",
    "USER_TYPE_ID" => "string",
    "XML_ID" => "12332",
    "SORT" => 500,
    "MULTIPLE" => "N", // Множественное
    "MANDATORY" => "N", // Обязательное
    "SHOW_FILTER" => "S",
    "SHOW_IN_LIST" => "Y",
    "EDIT_IN_LIST" => "Y",
    "IS_SEARCHABLE" => "N",
    "SETTINGS" => array(
        "SIZE" => "70", // длина поля ввода
        "ROWS" => "1" // высота поля ввода
    ),
    "EDIT_FORM_LABEL" => array("ru" => $fieldTitle, "en" => ""),
    "LIST_COLUMN_LABEL" => array("ru" => $fieldTitle, "en" => ""),
    "LIST_FILTER_LABEL" => array("ru" => $fieldTitle, "en" => ""),
);
$FIELD_ID = $obUserType->Add($arPropFields);

$fieldTitle = "Описание";
$arPropFields = array(
    "ENTITY_ID" => "IBLOCK_" . $ID . "_SECTION",
    "FIELD_NAME" => "UF_SEO_DESCRIPTION",
    "USER_TYPE_ID" => "string",
    "XML_ID" => "12334",
    "SORT" => 500,
    "MULTIPLE" => "N", // Множественное
    "MANDATORY" => "N", // Обязательное
    "SHOW_FILTER" => "S",
    "SHOW_IN_LIST" => "Y",
    "EDIT_IN_LIST" => "Y",
    "IS_SEARCHABLE" => "N",
    "SETTINGS" => array(
        "SIZE" => "70", // длина поля ввода
        "ROWS" => "3" // высота поля ввода
    ),
    "EDIT_FORM_LABEL" => array("ru" => $fieldTitle, "en" => ""),
    "LIST_COLUMN_LABEL" => array("ru" => $fieldTitle, "en" => ""),
    "LIST_FILTER_LABEL" => array("ru" => $fieldTitle, "en" => ""),
);
$FIELD_ID = $obUserType->Add($arPropFields);

// Создаем привязку к инфоблоку и создаем элементы внтури инфоблока $ID

$res = $ib->Add($iblock_id, array());

for ($i = 0; $i < count($data_raw["NAME_INFOBLOCK_OFFICE"]); $i++) {
     //ID элемента
    $arNewEl = [
        'IBLOCK_ID' => $ID,
        'NAME' => "{$data_raw["NAME_OFFICE"][$i]}",
        "CODE" => "{$data_raw["NAME_INFOBLOCK_OFFICE"][$i]}",
        'PREVIEW_TEXT' => 'Краткое описание',
        'ACTIVE' => 'Y',
        'PROPERTY_VALUES' => $arProps[$i]

    ];
    $obIblockEl = new CIblockElement();
    //Добавляем элемент в инфоблок
    $newElementId = $obIblockEl->add($arNewEl);
    if ($newElementId) {
        echo "{$newElementId}</br>";
    } else {
        echo $obIblockEl->LAST_ERROR;
    }
};

unlink(__FILE__); // Уничтожаем страницу, чтобы предотвратить повторное создание блока
ob_end_clean();
header('Location: ./gazprom_office/index.php '); // Редиректим на исполняемую страницу


?><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>