<?php
/**
 * Remco Schipper
 * Date: 10/12/14
 * Time: 17:24
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php
        if(isset($createResult)) {
            if ($createResult === false) {
                echo $view_text->get('global.results.create.error', array('bestelstatus'));
            }
            else {
                $url = '/orderstatussen/bewerken/' . $createResult['id'];

                echo $view_text->get('global.results.create.success', array('bestelstatus', $url, $createResult['description'], '/orderstatussen/index'));
            }
        }
        ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-offset-3 col-lg-6 col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-12">
        <form role="form" method="POST" action="nieuw">
            <div class="panel panel-default">
                <div class="panel-body">
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
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-default active">
                                    <input type="radio" value="actief" name="active" id="active" autocomplete="off" checked> actief
                                </label>
                                <label class="btn btn-default">
                                    <input type="radio" value="inactief" name="active" id="active" autocomplete="off"> inactief
                                </label>
                            </div>
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
</div>