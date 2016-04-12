<?php
/**
 * Remco Schipper
 * Date: 02/12/14
 * Time: 22:10
 */

/** @var \entities\DiscountCode $viewCoupon */
/** @var \entities\Order[] $viewOrders */
/** @var array|bool $editResult */
/** @var array $editError */
/** @var \services\Text $view_text */
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php
        if(isset($editResult)) {
            if ($editResult === false) {
                echo $view_text->get('global.results.edit.error', array('kortingscode'));
            }
            else {
                $url = '/kortingscodes/bewerken/' . $editResult['id'];
                echo $view_text->get('global.results.edit.success', array('kortingscode', $url, $editResult['code'], '/kortingscodes/index'));
            }
        }
        ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <form role="form" method="POST" action="<?php echo $viewCoupon->getId(); ?>">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Bewerken</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group has-feedback <?php if (isset($editError['code'])) { echo 'has-error'; } ?>">
                        <label for="code">Code<span class="mandatory">*</span></label>
                        <input type="text" class="form-control" name="code" id="code" value="<?php echo $viewCoupon->getCode(); ?>">
                        <?php
                        if (isset($editError['code'])) {
                            echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                            echo '<span id="helpBlock" class="help-block">' . $editError['code'] . '</span>';
                        }
                        ?>
                    </div>
                    <div class="form-group has-feedback <?php if (isset($editError['description'])) { echo 'has-error'; } ?>">
                        <label for="description">Beschrijving<span class="mandatory">*</span></label>
                        <input type="text" class="form-control" name="description" id="description" value="<?php echo $viewCoupon->getDescription(); ?>">
                        <?php
                        if (isset($editError['description'])) {
                            echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                            echo '<span id="helpBlock" class="help-block">' . $editError['description'] . '</span>';
                        }
                        ?>
                    </div>
                    <div class="well well-sm">
                        <p class="text-primary">Kortings methode: <span id="percentage_span" onclick="change('percentage')">percentage</span> / <span id="amount_span" onclick="change('amount')">bedrag</span></p>
                        <div id="percentage" class="form-group has-feedback <?php if (isset($editError['percentage'])) { echo 'has-error'; } ?>">
                            <label for="percentage">Percentage<span class="mandatory">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="percentage" id="percentage" value="<?php echo $viewCoupon->getPercentage(); ?>">
                                <span class="input-group-addon">%</span>
                            </div>
                            <?php
                            if (isset($editError['percentage'])) {
                                echo '<span id="helpBlock" class="help-block">' . $editError['percentage'] . '</span>';
                            }
                            ?>
                        </div>
                        <div id="amount" class="form-group has-feedback <?php if (isset($editError['fixed_amount'])) { echo 'has-error'; } ?>">
                            <label for="fixed_amount">Bedrag<span class="mandatory">*</span></label>
                            <div class="input-group">
                                <span class="input-group-addon">&euro;</span>
                                <input type="text" class="form-control" name="fixed_amount" id="fixed_amount" value="<?php echo $viewCoupon->getFixedAmount(); ?>">
                            </div>
                            <?php
                            if (isset($editError['fixed_amount'])) {
                                echo '<span id="helpBlock" class="help-block">' . $editError['fixed_amount'] . '</span>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="form-group has-feedback <?php if (isset($editError['valid_until'])) { echo 'has-error'; } ?>">
                        <label for="valid_until">Geldig tot<span class="mandatory">*</span></label>
                        <input data-date-format="DD-MM-YYYY HH:mm" type="text" class="form-control" name="valid_until" id="valid_until" value="<?php echo $viewCoupon->getValidUntil()->format('d-m-Y H:i'); ?>">
                        <?php
                        if (isset($editError['valid_until'])) {
                            echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                            echo '<span id="helpBlock" class="help-block">' . $editError['valid_until'] . '</span>';
                        }
                        ?>
                    </div>
                    <div class="form-group has-feedback <?php if (isset($editError['amount'])) { echo 'has-error'; } ?>">
                        <label for="amount">Maximaal aantal keer te gebruiken</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="amount" id="amount" value="<?php echo $viewCoupon->getAmount(); ?>">
                            <span class="input-group-addon">x</span>
                        </div>
                        <?php
                        if (isset($editError['amount'])) {
                            echo '<span id="helpBlock" class="help-block">' . $editError['amount'] . '</span>';
                        }
                        ?>
                    </div>
                    <input type="hidden" id="active" name="active" value="percentage">
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <?php
                            if (!is_array($viewOrders) || count($viewOrders) === 0) {
                                echo '<button class="btn btn-danger" id="remove">Verwijderen</button>';
                            }
                            ?>                    </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
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
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Bestellingen</h3>
            </div>
            <div class="panel-body">
                <table class="table table-responsive table-striped">
                    <thead>
                    <tr>
                        <th class="col-lg-6 col-md-6 col-sm-6 col-xs-6">Klant</th>
                        <th class="col-lg-4 col-md-4 col-sm-4 col-xs-4">Datum</th>
                        <th class="col-lg-2 col-md-2 col-sm-2 col-xs-2"><i class="fa fa-edit"></i></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (is_array($viewOrders)) {
                        foreach ($viewOrders as $viewOrder) {
                            ?>
                            <tr>
                                <td><?php echo $viewOrder->getUser()->getFirstName() . ' ' . $viewOrder->getUser()->getLastName(); ?></td>
                                <td><?php echo $viewOrder->getOrderPlaced()->format('d-m-Y H:i'); ?></td>
                                <td><a href="#">Bewerken</a></td>
                            </tr>
                        <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/scripts/moment.js"></script>
<script type="text/javascript" src="/scripts/bootstrap-datepicker.js"></script>
<script type="text/javascript">
    var createActive = '<?php echo ($viewCoupon->getPercentage() === null) ? 'amount' : 'percentage'; ?>';

    function change(to) {
        var from = (to === 'amount') ? 'percentage' : 'amount';

        $('#' + from).hide();
        $('#' + to).show();

        $('#active').val(to);
        $('#' + from + '_span').css('font-weight', 'normal');
        $('#' + to + '_span').css('font-weight', 'bold');
    }

    function remove() {
        var r = confirm('<?php echo $view_text->resolve('global.remove.confirm', array('kortingscode')); ?>');
        if (r == true) {
            window.location.href = "/kortingscodes/verwijderen/<?php echo $viewCoupon->getId(); ?>";
        }

        return false;
    }

    var element = $('#remove');

    if (element.length > 0) {
        element.on('click', remove);
    }

    $('#valid_until').datetimepicker();
    change(createActive);
</script>