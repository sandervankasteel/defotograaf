<?php
/**
 * Remco Schipper
 * Date: 04/12/14
 * Time: 12:22
 */


/** @var \entities\OrderStatus $editStatus */
/** @var \entities\Order[] $viewOrders */
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php
        if(isset($editResult)) {
            if ($editResult === false) {
                echo $view_text->get('global.results.edit.error', array('bestelstatus'));
            }
            else {
                $url = '/orderstatussen/bewerken/' . $editResult['id'];
                echo $view_text->get('global.results.edit.success', array('bestelstatus', $url, $editResult['description'], '/orderstatussen/index'));
            }
        }
        ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <form role="form" method="POST" action="<?php echo $editStatus->getId(); ?>">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Bewerken</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group has-feedback <?php if (isset($editError['description'])) { echo 'has-error'; } ?>">
                        <label for="description">Beschrijving<span class="mandatory">*</span></label>
                        <input type="text" class="form-control" name="description" id="description" value="<?php echo $editStatus->getDescription(); ?>">
                        <?php
                        if (isset($editError['description'])) {
                            echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                            echo '<span id="helpBlock" class="help-block">' . $editError['description'] . '</span>';
                        }
                        ?>
                    </div>

                    <?php
                    if (!is_array($viewOrders) || count($viewOrders) === 0) {
                        ?>
                        <div class="btn-group" data-toggle="buttons">
                            <label class="btn btn-default <?php if ($editStatus->isActive()) { echo 'active'; } ?>">
                                <input type="radio" value="actief" name="active" id="active" autocomplete="off" <?php if ($editStatus->isActive()) { echo 'checked'; } ?>> actief
                            </label>
                            <label class="btn btn-default <?php if (!$editStatus->isActive()) { echo 'active'; } ?>">
                                <input type="radio" value="inactief" name="active" id="active" autocomplete="off" <?php if (!$editStatus->isActive()) { echo 'checked'; } ?>> inactief
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
                                    <label class="btn btn-default <?php if ($editStatus->isActive()) { echo 'active'; } ?>">
                                        <input type="radio" value="actief" name="active" id="active" autocomplete="off" <?php if ($editStatus->isActive()) { echo 'checked'; } ?>> actief
                                    </label>
                                    <label class="btn btn-default <?php if (!$editStatus->isActive()) { echo 'active'; } ?>">
                                        <input type="radio" value="inactief" name="active" id="active" autocomplete="off" <?php if (!$editStatus->isActive()) { echo 'checked'; } ?>> inactief
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
        var r = confirm('<?php echo $view_text->resolve('global.remove.confirm', array('bestelstatus')); ?>');
        if (r == true) {
            window.location.href = "/orderstatussen/verwijderen/<?php echo $editStatus->getId(); ?>";
        }

        return false;
    }

    var element = $('#remove');

    if (element.length > 0) {
        element.on('click', remove);
    }
</script>