<?php
// Проверка, что файл подключён  
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

// Подключаем необходимые классы
use Bitrix\Main\Loader;
use Bitrix\Main\Type\DateTime;
use Bitrix\Highloadblock as HL;

// Загружаем модуль Highload-блоков
Loader::includeModule("highloadblock");

// Имя HL-блока, в котором будем хранить кэш GeoIP
$HL_BLOCK_NAME = "GeoipCache";

// Массив результата, который будет передан в шаблон
$arResult = [
    "ERROR" => "",        // Сообщение об ошибке
    "DATA"  => [],        // Данные GeoIP
    "IP"    => "",        // Введённый пользователем IP
    "FROM_CACHE" => false,
];

// Проверяем, что форма отправлена 
if ($_SERVER["REQUEST_METHOD"] === "POST" && check_bitrix_sessid()) {
    // Получаем IP из формы и обрезаем лишние пробелы
    $ip = trim($_POST["ip"]);

    // Валидация IP-адреса
    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        $arResult["ERROR"] = "Введите корректный IP-адрес.";
    } else {
        $arResult["IP"] = $ip;

        //  Ищем HL-блок по имени ---
        $hlblock = HL\HighloadBlockTable::getList([
            "filter" => ["=NAME" => $HL_BLOCK_NAME]
        ])->fetch();

        if ($hlblock) {
            // Компилируем HL-блок в объект для работы с данными
            $entity = HL\HighloadBlockTable::compileEntity($hlblock);
            $entityClass = $entity->getDataClass();

            //  Проверяем, есть ли уже запись с таким IP ---
            $res = $entityClass::getList([
                "select" => ["*"],
                "filter" => ["UF_IP" => $ip],
                "limit" => 1,
            ])->fetch();

            if ($res) {
                // Если запись есть - берем данные из кэша
                $arResult["FROM_CACHE"] = true;
                $arResult["DATA"] = json_decode($res["UF_DATA"], true);
            } else {
                //  Запрашиваем данные с внешнего сервиса SypexGeo ---
                $url = "https://api.sypexgeo.net/json/" . urlencode($ip);
                $response = CHTTP::sGet($url, [], ["timeout" => 5]);

                if ($response) {
                    $data = json_decode($response, true);
                    if (is_array($data)) {
                        // Если ответ корректный, сохраняем данные в массив результата
                        $arResult["DATA"] = $data;

                        //  Сохраняем полученные данные в HL-блок ---
                        $entityClass::add([
                            "UF_IP" => $ip,                                // IP адрес
                            "UF_DATA" => json_encode($data, JSON_UNESCAPED_UNICODE), // JSON с данными
                            "UF_SOURCE" => "sypexgeo",                     // Источник данных
                            "UF_CREATED_AT" => new DateTime(),             // Время добавления
                        ]);
                    } else {
                        $arResult["ERROR"] = "Некорректный ответ сервиса.";
                    }
                } else {
                    $arResult["ERROR"] = "Не удалось получить данные от GeoIP сервиса.";
                }
            }
        } else {
            // Если HL-блок не найден, выводим ошибку
            $arResult["ERROR"] = "HL-блок GeoipCache не найден.";
        }
    }
}

// Подключаем шаблон компонента для вывода формы и результатов
$this->IncludeComponentTemplate();

