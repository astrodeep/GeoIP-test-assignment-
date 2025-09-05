<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = [
    "NAME" => "GeoIP поиск с HL-блоком",
    "DESCRIPTION" => "Ищет IP в HL-блоке; если записи нет - делает запрос к внешнему GeoIP",
    "PATH" => [
        "ID" => "custom_components",
        "NAME" => "Custom Components",
    ],
];
