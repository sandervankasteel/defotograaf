<?php
/**
 * Remco Schipper
 * Date: 02/12/14
 * Time: 22:10
 */

/** @var array $createValues */
/** @var array $createError */
/** @var array $createResult */
/** @var \services\Text $view_text */
?>
<div class="row">
    <div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-12 col-xs-offset-0">
        <?php
        if(isset($createResult)) {
            if ($createResult === false) {
                echo $view_text->get('global.results.create.error', array('kortingscode'));
            }
            else {
                $url = '/kortingscodes/bewerken/' . $createResult['id'];

                echo $view_text->get('global.results.create.success', array('kortingscode', $url, $createResult['code'], '/kortingscodes/index'));
            }
        }
        ?>
        <form role="form" method="POST" action="nieuw">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="form-group has-feedback <?php if (isset($createError['code'])) { echo 'has-error'; } ?>">
                        <label for="code">Code<span class="mandatory">*</span></label>
                        <input type="text" class="form-control" name="code" id="code" value="<?php if (isset($createValues['code'])) { echo $createValues['code']; } ?>">
                        <?php
                        if (isset($createError['code'])) {
                            echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                            echo '<span id="helpBlock" class="help-block">' . $createError['code'] . '</span>';
                        }
                        ?>
                    </div>
                    <div class="form-group has-feedback <?php if (isset($createError['description'])) { echo 'has-error'; } ?>">
                        <label for="description">Beschrijving<span class="mandatory">*</span></label>
                        <input type="text" class="form-control" name="description" id="description" value="<?php if (isset($createValues['description'])) { echo $createValues['description']; } ?>">
                        <?php
                        if (isset($createError['description'])) {
                            echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                            echo '<span id="helpBlock" class="help-block">' . $createError['description'] . '</span>';
                        }
                        ?>
                    </div>
                    <div class="well well-sm">
                        <p class="text-primary">Kortings methode: <span id="percentage_span" onclick="change('percentage')">percentage</span> / <span id="amount_span" onclick="change('amount')">bedrag</span></p>
                        <div id="percentage" class="form-group has-feedback <?php if (isset($createError['percentage'])) { echo 'has-error'; } ?>">
                            <label for="percentage">Percentage<span class="mandatory">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="percentage" id="percentage" value="<?php if (isset($createValues['percentage'])) { echo $createValues['percentage']; } ?>">
                                <span class="input-group-addon">%</span>
                            </div>
                            <?php
                            if (isset($createError['percentage'])) {
                                echo '<span id="helpBlock" class="help-block">' . $createError['percentage'] . '</span>';
                            }
                            ?>
                        </div>
                        <div id="amount" class="form-group has-feedback <?php if (isset($createError['fixed_amount'])) { echo 'has-error'; } ?>">
                            <label for="fixed_amount">Bedrag<span class="mandatory">*</span></label>
                            <div class="input-group">
                                <span class="input-group-addon">&euro;</span>
                                <input type="text" class="form-control" name="fixed_amount" id="fixed_amount" value="<?php if (isset($createValues['fixed_amount'])) { echo $createValues['fixed_amount']; } ?>">
                            </div>
                            <?php
                            if (isset($createError['fixed_amount'])) {
                                echo '<span id="helpBlock" class="help-block">' . $createError['fixed_amount'] . '</span>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="form-group has-feedback <?php if (isset($createError['valid_until'])) { echo 'has-error'; } ?>">
                        <label for="valid_until">Geldig tot<span class="mandatory">*</span></label>
                        <input data-date-format="DD-MM-YYYY HH:mm" type="text" class="form-control" name="valid_until" id="valid_until" value="<?php if (isset($createValues['valid_until'])) { echo $createValues['valid_until']; } ?>">
                        <?php
                        if (isset($createError['valid_until'])) {
                            echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                            echo '<span id="helpBlock" class="help-block">' . $createError['valid_until'] . '</span>';
                        }
                        ?>
                    </div>
                    <div class="form-group has-feedback <?php if (isset($createError['amount'])) { echo 'has-error'; } ?>">
                        <label for="amount">Maximaal aantal keer te gebruiken</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="amount" id="amount" value="<?php if (isset($createValues['amount'])) { echo $createValues['amount']; } ?>">
                            <span class="input-group-addon">x</span>
                        </div>
                        <?php
                        if (isset($createError['amount'])) {
                            echo '<span id="helpBlock" class="help-block">' . $createError['amount'] . '</span>';
                        }
                        ?>
                    </div>
                    <input type="hidden" id="active" name="active" value="percentage">
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input class="btn btn-yellow pull-right" type="submit" name="submit" value="Opslaan">
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="mandatory-note">
            <?php echo $view_text->resolve('global.mandatory'); ?>
        </div>
    </div>
</div>
<script type="text/javascript" src="/scripts/moment.js"></script>
<script type="text/javascript" src="/scripts/bootstrap-datepicker.js"></script>
<script type="text/javascript">
    var createActive = '<?php echo (isset($createActive)) ? $createActive : 'percentage'; ?>';

    function change(to) {
        var from = (to === 'amount') ? 'percentage' : 'amount';

        $('#' + from).hide();
        $('#' + to).show();

        $('#active').val(to);
        $('#' + from + '_span').css('font-weight', 'normal');
        $('#' + to + '_span').css('font-weight', 'bold');
    }

    $('#valid_until').datetimepicker();
    change(createActive);
</script>