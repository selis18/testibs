<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$arParameters = [
    "SEF_FOLDER" => [
        "NAME" => "Путь к компоненту",
        "TYPE" => "STRING",
    ],
    "SEF_LIST_MANUFACTURERS" => [
        "NAME" => "Список производителей",
        "TYPE" => "STRING",
        "DEFAULT" => "#SEF_FOLDER#/",
    ],
    "SEF_LIST_MODELS" => [
        "NAME" => "Список моделей",
        "TYPE" => "STRING",
        "DEFAULT" => "#SEF_FOLDER#/#BRAND#/",
    ],
    "SEF_DETAIL" => [
        "NAME" => "Детальная страница ноутбука",
        "TYPE" => "STRING",
        "DEFAULT" => "#SEF_FOLDER#/detail/#NOTEBOOK#/",
    ],
];
?>

