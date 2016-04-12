<?php
/**
 * Remco Schipper
 * Date: 04/12/14
 * Time: 21:59
 */

/** @var array[] $createParams */
/** @var string $createAction */
/** @var array $createValues */
/** @var array $createError */
/** @var array $createResult */
/** @var array $createParams */

/** @var \entities\OrderStatus[]|string[] $createOptions */

/** @var \services\Text $view_text */
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php
        if(isset($createResult)) {
            if ($createResult === false) {
                echo $view_text->get('global.results.create.error', array('e-mail template'));
            }
            else {
                $url = '/emails/bewerken/' . $createResult['id'];

                echo $view_text->get('global.results.create.success', array('e-mail template', $url, $createResult['subject'], '/emails/index'));
            }
        }
        ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <form role="form" method="POST" action="/emails/nieuw/<?php echo $createAction; ?>">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Toevoegen</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group has-feedback <?php if (isset($createError['subject'])) { echo 'has-error'; } ?>">
                        <label for="subject">Onderwerp<span class="mandatory">*</span></label>
                        <input type="text" class="form-control" name="subject" id="subject" value="<?php if (isset($createValues['subject'])) { echo $createValues['subject']; } ?>">
                        <?php
                        if (isset($createError['subject'])) {
                            echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                            echo '<span id="helpBlock" class="help-block">' . $createError['subject'] . '</span>';
                        }
                        ?>
                    </div>
                    <div class="form-group has-feedback <?php if (isset($createError['reply_to'])) { echo 'has-error'; } ?>">
                        <label for="reply_to">Antwoord adres<span class="mandatory">*</span></label>
                        <input type="text" class="form-control" name="reply_to" id="reply_to" value="<?php if (isset($createValues['reply_to'])) { echo $createValues['reply_to']; } ?>">
                        <?php
                        if (isset($createError['reply_to'])) {
                            echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                            echo '<span id="helpBlock" class="help-block">' . $createError['reply_to'] . '</span>';
                        }
                        ?>
                    </div>
                    <div class="form-group has-feedback <?php if (isset($createError['reply_to_name'])) { echo 'has-error'; } ?>">
                        <label for="reply_to_name">Weergaven naam van antwoord adres<span class="mandatory">*</span></label>
                        <input type="text" class="form-control" name="reply_to_name" id="reply_to_name" value="<?php if (isset($createValues['reply_to_name'])) { echo $createValues['reply_to_name']; } ?>">
                        <?php
                        if (isset($createError['reply_to_name'])) {
                            echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
                            echo '<span id="helpBlock" class="help-block">' . $createError['reply_to_name'] . '</span>';
                        }
                        ?>
                    </div>
                    <div class="form-group has-feedback <?php if (isset($createError['option'])) { echo 'has-error'; } ?>">
                        <label for="option">Actie<span class="mandatory">*</span></label>
                        <select name="option" id="option" class="form-control">
                            <?php
                            foreach ($createOptions as $key => $editOption) {
                                if ($createAction === 'status') {
                                    if ($createValues['option'] == $editOption->getId()) {
                                        echo '<option selected="selected" value="' . $editOption->getId() . '">' . $editOption->getDescription() . '</option>';
                                    }
                                    else {
                                        echo '<option value="' . $editOption->getId() . '">' . $editOption->getDescription() . '</option>';
                                    }
                                }
                                else {
                                    if ($createValues['option'] == $key) {
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
                        if (isset($createError['option'])) {
                            echo '<span id="helpBlock" class="help-block">' . $createError['option'] . '</span>';
                        }
                        ?>
                    </div>
                    <div class="well well-sm">
                        <p class="text-primary"><span id="body_html_span" onclick="change('body_html')">Opmaak</span> / <span id="body_span" onclick="change('body')">tekst</span></p>
                        <div id="body_html" class="form-group has-feedback <?php if (isset($createError['body_html'])) { echo 'has-error'; } ?>">
                            <p class="text-primary">Dit is het e-mail bericht zoals een klant hem te zien krijgt wanneer er een modern apparaat gebruikt word.</p>
                            <label for="body_html_input">Opmaak<span class="mandatory">*</span></label>
                            <textarea class="form-control" name="body_html" id="body_html_input" rows="3"><?php if (isset($createValues['body_html'])) { echo $createValues['body_html']; } ?></textarea>
                            <?php
                            if (isset($createError['body_html'])) {
                                echo '<span id="helpBlock" class="help-block">' . $createError['body_html'] . '</span>';
                            }
                            ?>
                        </div>
                        <div id="body" class="form-group has-feedback <?php if (isset($createError['body'])) { echo 'has-error'; } ?>">
                            <p class="text-primary">Dit is het e-mail bericht zoals een klant hem te zien krijgt wanneer er een verouderd apparaat gebruikt word.</p>
                            <label for="body_input">Tekst<span class="mandatory">*</span></label>
                            <textarea class="form-control" name="body" id="body_input" rows="3"><?php if (isset($createValues['body'])) { echo $createValues['body']; } ?></textarea>
                            <?php
                            if (isset($createError['body'])) {
                                echo '<span id="helpBlock" class="help-block">' . $createError['body'] . '</span>';
                            }
                            ?>
                        </div>
                    </div>
                    <input type="hidden" id="active" name="active" value="body_html">
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
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <?php
            foreach($createParams as $key => $params) {
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

    function change(to) {
        var from = (to === 'body') ? 'body_html' : 'body';

        $('#' + from).hide();
        $('#' + to).show();

        $('#active').val(to);
        $('#' + from + '_span').css('font-weight', 'normal');
        $('#' + to + '_span').css('font-weight', 'bold');
    }

    change(createActive);

    CKEDITOR.replace('body_html_input', {
        language: 'nl'
    });
</script>