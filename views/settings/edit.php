<?php
/**
 * Remco Schipper
 * Date: 05/12/14
 * Time: 13:12
 */

/** @var \entities\StartingCost $startingCost */
/** @var \entities\Configuration $btwPercentage */
/** @var \entities\Configuration $ibanNumber */

/** @var \services\Text $view_text */
?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php
        if(isset($editStartingCost)) {
            if ($editStartingCost === false) {
                echo $view_text->get('settings.starting_cost.results.error');
            }
            else {
                echo $view_text->get('settings.starting_cost.results.success');
            }
        }
        if(isset($editBtwPercentage)) {
            if ($editBtwPercentage === false) {
                echo $view_text->get('settings.btw_percentage.results.error');
            }
            else {
                echo $view_text->get('settings.btw_percentage.results.success');
            }
        }
        if(isset($editIbanNumber)) {
            if ($editIbanNumber === false) {
                echo $view_text->get('settings.iban_number.results.error');
            }
            else {
                echo $view_text->get('settings.iban_number.results.success');
            }
        }
        ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-offset-3 col-lg-6 col-md-3 col-md-6 col-sm-3 col-sm-6 col-xs-12">
        <form role="form" method="POST" action="bewerken">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="form-group has-feedback <?php if (isset($editError['price'])) { echo 'has-error'; } ?>">
                        <label for="price">Opstartkosten<span class="mandatory">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon">&euro;</span>
                            <input type="text" class="form-control" name="price" id="price" value="<?php echo $startingCost->getPrice(); ?>">
                        </div>
                        <?php
                        if (isset($editError['price'])) {
                            echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                            echo '<span id="helpBlock" class="help-block">' . $editError['price'] . '</span>';
                        }
                        ?>
                    </div>
                    <div class="form-group has-feedback <?php if (isset($editError['btw'])) { echo 'has-error'; } ?>">
                        <label for="btw">BTW percentage<span class="mandatory">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="btw" id="btw" value="<?php echo $btwPercentage->getValue(); ?>">
                            <span class="input-group-addon">%</span>
                        </div>
                        <?php
                        if (isset($editError['btw'])) {
                            echo '<span id="helpBlock" class="help-block">' . $editError['btw'] . '</span>';
                        }
                        ?>
                    </div>
                    <div class="form-group has-feedback <?php if (isset($editError['iban'])) { echo 'has-error'; } ?>">
                        <label for="iban">IBAN nummer<span class="mandatory">*</span></label>
                        <input type="text" class="form-control" name="iban" id="iban" value="<?php echo $ibanNumber->getValue(); ?>">
                        <?php
                        if (isset($editError['iban'])) {
                            echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                            echo '<span id="helpBlock" class="help-block">' . $editError['iban'] . '</span>';
                        }
                        ?>
                    </div>
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