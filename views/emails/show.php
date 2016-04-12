<?php
/**
 * Remco Schipper
 * Date: 04/12/14
 * Time: 20:56
 */

/** @var \entities\EmailTemplate[] $showTemplates */
/** @var string $showView */
?>
<script type="text/javascript" src="/scripts/moment.js"></script>
<script type="text/javascript" src="/scripts/bootstrap-sortable.js"></script>
<div class="row">
    <div class="col-lg-3 col-md-3 col-xs-3 col-xs-12">
        <div role="tabpanel" class="email-nav">
            <ul class="nav nav-pills nav-stacked">
                <li role="presentation" <?php if ($showView === 'status') { echo 'class="active"'; } ?>>
                    <a href="statuses">Statussen</a>
                </li>
                <li role="presentation" <?php if ($showView === 'account') { echo 'class="active"'; } ?>>
                    <a href="account">Account</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-lg-9 col-md-9 col-xs-9 col-xs-12">
        <table class="table table-responsive table-bordered table-couponShow sortable">
            <thead>
            <tr>
                <th data-defaultsort="asc" class="col-lg-10 col-md-10 col-sm-9 col-xs-7">Onderwerp</th>
                <th data-defaultsort="disabled" class="col-lg-1 col-md-1 col-sm-2 col-xs-4"><i class="fa fa-edit"></i></th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (is_array($showTemplates)) {
                foreach ($showTemplates as $showTemplate) {
                    ?>
                    <tr>
                        <td><?php echo $showTemplate->getSubject(); ?></td>
                        <td><a href="/emails/bewerken/<?php echo $showTemplate->getId(); ?>">Bewerken</a></td>
                    </tr>
                <?php
                }
            }
            ?>
            </tbody>
        </table>
        <div class="well well-sm">
            <a href="/emails/nieuw/<?php echo $showView; ?>">Toevoegen</a>
        </div>
    </div>
</div>