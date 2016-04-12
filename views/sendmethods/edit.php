<?php
/**
 * Roy Hendriks
 * Date: 05/12/14
 * Time: 22:10
 */

/** @var \entities\ShippingMethod $viewSendMethod */
/** @var \entities\Order[] $viewOrders */

/** @var \services\Text $view_text */
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php
        if(isset($editResult)) {
            if ($editResult === false) {
                echo $view_text->get('global.results.edit.error', array('verzendmethode'));
            }
            else {
                $url = '/verzendmethodes/bewerken/' . $editResult['id'];
                echo $view_text->get('global.results.edit.success', array('verzendmethode', $url, $editResult['name'], '/verzendmethodes/overzicht'));
            }
        }
        ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <form role="form" method="POST" action="<?php echo $viewSendMethod->getId(); ?>">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Bewerken</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group has-feedback <?php if (isset($editError['name'])) { echo 'has-error'; } ?>">
                        <label for="name">Name<span class="mandatory">*</span></label>
                        <input type="text" class="form-control" name="name" id="name" value="<?php echo $viewSendMethod->getName(); ?>">
                        <?php
                        if (isset($editError['name'])) {
                            echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                            echo '<span id="helpBlock" class="help-block">' . $editError['name'] . '</span>';
                        }
                        ?>
                    </div>

                    <?php
                    if (!is_array($viewOrders) || count($viewOrders) === 0) {
                        ?>
                        <div class="btn-group" data-toggle="buttons">
                            <label class="btn btn-default <?php if ($viewSendMethod->isActive()) { echo 'active'; } ?>">
                                <input type="radio" value="actief" name="active" id="active" autocomplete="off" <?php if ($viewSendMethod->isActive()) { echo 'checked'; } ?>> actief
                            </label>
                            <label class="btn btn-default <?php if (!$viewSendMethod->isActive()) { echo 'active'; } ?>">
                                <input type="radio" value="inactief" name="active" id="active" autocomplete="off" <?php if (!$viewSendMethod->isActive()) { echo 'checked'; } ?>> inactief
                            </label>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <?php
                            if (!is_array($viewOrders) || count($viewOrders) === 0) {
                                echo '<button class="btn btn-danger" id="remove">Verwijderen</button>';
                            }
                            else {
                                ?>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-default <?php if ($viewSendMethod->isActive()) { echo 'active'; } ?>">
                                        <input type="radio" value="actief" name="active" id="active" autocomplete="off" <?php if ($viewSendMethod->isActive()) { echo 'checked'; } ?>> actief
                                    </label>
                                    <label class="btn btn-default <?php if (!$viewSendMethod->isActive()) { echo 'active'; } ?>">
                                        <input type="radio" value="inactief" name="active" id="active" autocomplete="off" <?php if (!$viewSendMethod->isActive()) { echo 'checked'; } ?>> inactief
                                    </label>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
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
<script type="text/javascript">
    function remove() {
        var r = confirm('<?php echo $view_text->resolve('global.remove.confirm', array('verzendmethode')); ?>');
        if (r == true) {
            window.location.href = "/verzendmethodes/verwijderen/<?php echo $viewSendMethod->getId(); ?>";
        }

        return false;
    }

    var element = $('#remove');

    if (element.length > 0) {
        element.on('click', remove);
    }
</script>