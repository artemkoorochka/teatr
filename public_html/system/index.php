<?
use Bitrix\Main\Page\Asset;

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');

Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/jquery.mask.min.js")
?>

    <section class="examples">
        <div class="container">
            <h3 id="examples">View in action</h3>

            <div class="inputs">
                <div class="input-group">
                    <label for="date">Date</label>
                    <input type="text" class="date" id="date"/>
                </div>

                <div class="input-group">
                    <label for="time">Hour</label>
                    <input type="text" class="time" id="time"/>
                </div>

                <div class="input-group">
                    <label for="date_time">Date &amp; Hour</label>
                    <input type="text" class="date_time" id="date_time"/>
                </div>

                <div class="input-group">
                    <label for="cep">ZIP Code</label>
                    <input type="text" class="cep" id="cep"/>
                </div>

                <div class="input-group">
                    <label for="cep_with_callback">With Callbacks (open console)</label>
                    <input type="text" class="cep_with_callback" id="cep_with_callback"/>
                </div>

                <div class="input-group">
                    <label for="crazy_cep">Crazy Zip Code</label>
                    <input type="text" class="crazy_cep" id="crazy_cep"/>
                </div>

                <div class="input-group">
                    <label for="money">Money</label>
                    <input type="text" class="money" id="money"/>
                </div>

                <div class="input-group">
                    <label for="placeholder">Mask placeholder option</label>
                    <input type="text" class="placeholder" id="placeholder"/>
                </div>

                <div class="input-group">
                    <label for="placeholder">Masks on div elements!</label>
                    <input type="text" class="placeholder" id="placeholder"/>
                </div>

                <div class="input-group">
                    <label for="phone">Telephone</label>
                    <input type="text" class="phone" id="phone"/>
                </div>

                <div class="input-group">
                    <label for="phone_with_ddd">Telephone with Code Area</label>
                    <input type="text" class="phone_with_ddd" id="phone_with_ddd"/>
                </div>

                <div class="input-group">
                    <label for="phone_us">US Telephone</label>
                    <input type="text" class="phone_us" id="phone_us"/>
                </div>

                <div class="input-group">
                    <label for="sp_celphones">São Paulo Celphones</label>
                    <input type="text" class="sp_celphones" id="sp_celphones"/>
                </div>

                <div class="input-group">
                    <label for="mixed">Mixed Type Mask</label>
                    <input type="text" class="mixed" id="mixed"/>
                </div>

                <div class="input-group">
                    <label for="cpf">CPF</label>
                    <input type="text" class="cpf" id="cpf"/>
                </div>

                <div class="input-group">
                    <label for="cnpj">CNPJ</label>
                    <input type="text" class="cnpj" id="cnpj"/>
                </div>

                <div class="input-group">
                    <label for="ip_address">IP Address</label>
                    <input type="text" class="ip_address" id="ip_address"/>
                </div>

                <div class="input-group">
                    <label for="clear-if-not-match">With Clear If Not Match Option</label>
                    <input type="text" class="clear-if-not-match" id="clear-if-not-match"/>
                </div>

                <div class="input-group">
                    <label for="fallback">With a fallback digit</label>
                    <input type="text" class="fallback" id="fallback"/>
                </div>

                <div class="input-group">
                    <label for="selectonfocus">With selectOnFocus</label>
                    <input type="text" class="selectonfocus" id="selectonfocus"/>
                </div>
            </div>

            <label>Masks on div elements!</label>
            <div class="mask-on-div">12345678909</div> <input type="button" class="bt-mask-it" value="Mask it!"/>
        </div>
    </section>

<script>
    $(function() {
        $('.date').mask('00/00/0000');
        $('.time').mask('00:00:00');
        $('.date_time').mask('00/00/0000 00:00:00');
        $('.cep').mask('00000-000');
        $('.phone').mask('0000-0000');
        $('.phone_with_ddd').mask('(00) 0000-0000');
        $('.phone_us').mask('(000) 000-0000');
        $('.mixed').mask('AAA 000-S0S');
        $('.ip_address').mask('099.099.099.099');
        $('.percent').mask('##0,00%', {reverse: true});
        $('.clear-if-not-match').mask("00/00/0000", {clearIfNotMatch: true});
        $('.placeholder').mask("00/00/0000", {placeholder: "__/__/____"});
        $('.fallback').mask("00r00r0000", {
            translation: {
                'r': {
                    pattern: /[\/]/,
                    fallback: '/'
                },
                placeholder: "__/__/____"
            }
        });

        $('.selectonfocus').mask("00/00/0000", {selectOnFocus: true});

        $('.cep_with_callback').mask('00000-000', {onComplete: function(cep) {
                console.log('Mask is done!:', cep);
            },
            onKeyPress: function(cep, event, currentField, options){
                console.log('An key was pressed!:', cep, ' event: ', event, 'currentField: ', currentField.attr('class'), ' options: ', options);
            },
            onInvalid: function(val, e, field, invalid, options){
                var error = invalid[0];
                console.log ("Digit: ", error.v, " is invalid for the position: ", error.p, ". We expect something like: ", error.e);
            }
        });

        $('.crazy_cep').mask('00000-000', {onKeyPress: function(cep, e, field, options){
                var masks = ['00000-000', '0-00-00-00'];
                mask = (cep.length>7) ? masks[1] : masks[0];
                $('.crazy_cep').mask(mask, options);
            }});

        $('.cnpj').mask('00.000.000/0000-00', {reverse: true});
        $('.cpf').mask('000.000.000-00', {reverse: true});
        $('.money').mask('#.##0,00', {reverse: true});

        var SPMaskBehavior = function (val) {
                return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
            },
            spOptions = {
                onKeyPress: function(val, e, field, options) {
                    field.mask(SPMaskBehavior.apply({}, arguments), options);
                }
            };

        $('.sp_celphones').mask(SPMaskBehavior, spOptions);

        $(".bt-mask-it").click(function(){
            $(".mask-on-div").mask("000.000.000-00");
            $(".mask-on-div").fadeOut(500).fadeIn(500)
        })

        $('pre').each(function(i, e) {hljs.highlightBlock(e)});
    });
</script>

<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');?>