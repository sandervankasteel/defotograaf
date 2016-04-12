<?php
/**
 * Remco Schipper
 * Date: 04/12/14
 * Time: 21:59
 */

/** @var \entities\EmailTemplate $editTemplate */
/** @var array[] $editParams */
/** @var \entities\OrderStatus[]|array $editOptions */
/** @var string $editValue */
/** @var string $editAction */

/** @var \services\Text $view_text */
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php
        if(isset($editResult)) {
            if ($editResult === false) {
                echo $view_text->get('global.results.edit.error', array('e-mail template'));
            }
            else {
                $url = '/emails/bewerken/' . $editResult['id'];
                echo $view_text->get('global.results.edit.success', array('e-mail template', $url, $editResult['subject'], '/emails/index'));
            }
        }
        ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <form role="form" method="POST" action="<?php echo $editTemplate->getId(); ?>">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Bewerken</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group has-feedback <?php if (isset($editError['code'])) { echo 'has-error'; } ?>">
                        <label for="subject">Onderwerp<span class="mandatory">*</span></label>
                        <input type="text" class="form-control" name="subject" id="subject" value="<?php echo $editTemplate->getSubject(); ?>">
                        <?php
                        if (isset($editError['subject'])) {
                            echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                            echo '<span id="helpBlock" class="help-block">' . $editError['subject'] . '</span>';
                        }
                        ?>
                    </div>
                    <div class="form-group has-feedback <?php if (isset($editError['reply_to'])) { echo 'has-error'; } ?>">
                        <label for="reply_to">Antwoord adres<span class="mandatory">*</span></label>
                        <input type="text" class="form-control" name="reply_to" id="reply_to" value="<?php echo $editTemplate->getReplyTo(); ?>">
                        <?php
                        if (isset($editError['reply_to'])) {
                            echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                            echo '<span id="helpBlock" class="help-block">' . $editError['reply_to'] . '</span>';
                        }
                        ?>
                    </div>
                    <div class="form-group has-feedback <?php if (isset($editError['reply_to_name'])) { echo 'has-error'; } ?>">
                        <label for="reply_to_name">Weergaven naam van antwoord adres<span class="mandatory">*</span></label>
                        <input type="text" class="form-control" name="reply_to_name" id="reply_to_name" value="<?php echo $editTemplate->getReplyToName(); ?>">
                        <?php
                        if (isset($editError['reply_to_name'])) {
                            echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                            echo '<span id="helpBlock" class="help-block">' . $editError['reply_to_name'] . '</span>';
                        }
                        ?>
                    </div>
                    <div class="form-group has-feedback <?php if (isset($editError['option'])) { echo 'has-error'; } ?>">
                        <label for="option">Actie<span class="mandatory">*</span></label>
                        <select name="option" id="option" class="form-control">
                            <?php
                            foreach ($editOptions as $key => $editOption) {
                                if ($editAction === 'status') {
                                    if ($editValue == $editOption->getId()) {
                                        echo '<option selected="selected" value="' . $editOption->getId() . '">' . $editOption->getDescription() . '</option>';
                                    }
                                    else {
                                        echo '<option value="' . $editOption->getId() . '">' . $editOption->getDescription() . '</option>';
                                    }
                                }
                                else {
                                    if ($editValue == $key) {
                                        echo '<option selected="selected" value="' . $key . '">' . $editOption . '</option>';
                                    }
                                    else {
                                        echo '<option value="' . $key . '">' . $editOption . '</option>';
                                    }
                                }
                            }
                            ?>
                        </select>
                        <?php
                        if (isset($editError['option'])) {
                            echo '<span id="helpBlock" class="help-block">' . $editError['option'] . '</span>';
                        }
                        ?>
                    </div>
                    <div class="well well-sm">
                        <p class="text-primary"><span id="body_html_span" onclick="change('body_html')">Opmaak</span> / <span id="body_span" onclick="change('body')">tekst</span></p>
                        <div id="body_html" class="form-group has-feedback <?php if (isset($editError['body_html'])) { echo 'has-error'; } ?>">
                            <p class="text-primary">Dit is het e-mail bericht zoals een klant hem te zien krijgt wanneer er een modern apparaat gebruikt word.</p>
                            <label for="body_html_input">Opmaak<span class="mandatory">*</span></label>
                            <textarea class="form-control" name="body_html" id="body_html_input" rows="3"><?php echo $editTemplate->getBodyHtml(); ?></textarea>
                            <?php
                            if (isset($editError['body_html'])) {
                                echo '<span id="helpBlock" class="help-block">' . $editError['body_html'] . '</span>';
                            }
                            ?>
                        </div>
                        <div id="body" class="form-group has-feedback <?php if (isset($editError['body'])) { echo 'has-error'; } ?>">
                            <p class="text-primary">Dit is het e-mail bericht zoals een klant hem te zien krijgt wanneer er een verouderd apparaat gebruikt word.</p>
                            <label for="body_input">Tekst</label>
                            <textarea class="form-control" name="body" id="body_input" rows="3"><?php echo $editTemplate->getBody(); ?></textarea>
                            <?php
                            if (isset($editError['body'])) {
                                echo '<span id="helpBlock" class="help-block">' . $editError['body'] . '</span>';
                            }
                            ?>
                        </div>
                    </div>
                    <input type="hidden" id="active" name="active" value="body_html">
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <button class="btn btn-danger" id="remove">Verwijderen</button>
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
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <?php
            foreach($editParams as $key => $params) {
                $id = md5($key);
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="heading<?php echo $key; ?>">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#<?php echo $id; ?>" aria-expanded="true" aria-controls="<?php echo $id; ?>">
                                <?php echo ucfirst($key); ?>
                            </a>
                        </h4>
                    </div>
                    <div id="<?php echo $id; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="<?php echo $id; ?>">
                        <div class="panel-body">
                            <dl>
                                <?php
                                $count = count($params);
                                $i = 0;
                                foreach ($params as $param => $content) {
                                    ?>
                                    <dt><?php echo ucfirst($param); ?> <span class="label label-primary pull-right" style="font-size: 12px">%<?php echo $param; ?>%</span></dt>
                                    <dd><?php echo $content['message']; ?></dd>
                                    <?php
                                        $i++;
                                        if ($i < $count) {
                                            echo '<br>';
                                        }
                                    ?>
                                <?php
                                }
                                ?>
                            </dl>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>
<script type="text/javascript" src="/scripts/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    var createActive = 'body_html';

    function remove() {
        var r = confirm('<?php echo $view_text->resolve('global.remove.confirm', array('e-mail template')); ?>');
        if (r == true) {
            window.location.href = "/emails/verwijderen/<?php echo $editTemplate->getId(); ?>";
        }

        return false;
    }

    function change(to) {
        var from = (to === 'body') ? 'body_html' : 'body';

        $('#' + from).hide();
        $('#' + to).show();

        $('#active').val(to);
        $('#' + from + '_span').css('font-weight', 'normal');
        $('#' + to + '_span').css('font-weight', 'bold');
    }

    change(createActive);

    $('#remove').on('click', remove);

    CKEDITOR.replace('body_html_input', {
        language: 'nl'
    });
</script>