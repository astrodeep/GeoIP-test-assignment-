<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<form method="post" class="mb-3">
    <?=bitrix_sessid_post()?>
    <input type="text" name="ip" value="<?=htmlspecialcharsbx($arResult["IP"])?>" placeholder="Введите IP">
    <input type="submit" value="Найти">
</form>

<?if($arResult["ERROR"]):?>
    <div style="color:red;"><?=$arResult["ERROR"]?></div>
<?endif;?>

<?if($arResult["DATA"]):?>
    <h3>Результат (<?=$arResult["FROM_CACHE"] ? "из HL-блока" : "из сервиса"?>):</h3>
    <ul>
        <li><b>Страна:</b> <?=$arResult["DATA"]["country"]["name_ru"]?></li>
        <li><b>Регион:</b> <?=$arResult["DATA"]["region"]["name_ru"]?></li>
        <li><b>Город:</b> <?=$arResult["DATA"]["city"]["name_ru"]?></li>
        <li><b>Широта:</b> <?=$arResult["DATA"]["city"]["lat"]?></li>
        <li><b>Долгота:</b> <?=$arResult["DATA"]["city"]["lon"]?></li>
    </ul>
<?endif;?>
