<?
use Bitrix\Main\Page\Asset;

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');

Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/jquery.mask.min.js")
?>

    <div class="input-group">
        <label for="phone_us">US Telephone</label>
        <input type="text" class="phone_us" id="phone_us"/>
    </div>

<script>
    $(function() {

        $('.phone_us').mask('(000) 000-0000');

    });
</script>

<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');?>