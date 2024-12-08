<?php
use Bitrix\Main\Loader;
use Bitrix\LaptopStore\LaptopTable;
use Bitrix\LaptopStore\ModelTable;
use Bitrix\LaptopStore\ManufacturerTable;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

Loader::includeModule('laptopstore');

$arResult['MANUFACTURERS'] = ManufacturerTable::getList([
    'select' => ['ID', 'NAME'],
    'order' => ['NAME' => 'ASC']
])->fetchAll();


$arResult['LAPTOPS'] = LaptopTable::getList([
    'select' => ['ID', 'MODEL_ID']
])->fetchAll();

$arResult['ROWS'] = [];
foreach ($arResult['LAPTOPS'] as $laptop) {
    $model = ModelTable::getById($laptop['MODEL_ID'])->fetch();
    $manufacturer = ManufacturerTable::getById($model['MANUFACTURER_ID'])->fetch();

    $arResult['ROWS'][] = [
        'id' => $laptop['ID'],
        'data' => [
            'NAME' => htmlspecialchars($manufacturer['NAME'] . ' ' . $model['NAME']), // Формируем название
            'MODEL_ID' => $laptop['MODEL_ID'],
        ],
    ];
}

$this->IncludeComponentTemplate();
?>

