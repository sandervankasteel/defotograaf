<?php
/**
 * Created by PhpStorm.
 * User: roy
 * Date: 5-12-2014
 * Time: 14:05
 */

/** @var \entities\ShippingMethod[] $showMethods */
?>
<script type="text/javascript" src="/scripts/moment.js"></script>
<script type="text/javascript" src="/scripts/bootstrap-sortable.js"></script>
<ol class="breadcrumb">
    <li><a href="/instellingen/index">Instellingen</a></li>
    <li class="active">betaalmethodes</li>
</ol>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-6">
                <div class="btn-group btn-group-sm" role="group">
                    <a href="/betaalmethodes/overzicht/all" type="button" class="btn btn-default <?php if($showFilter === 'all') { echo 'disabled'; } ?>">alles</a>
                    <a href="/betaalmethodes/overzicht/active" type="button" class="btn btn-default <?php if($showFilter === 'active') { echo 'disabled'; } ?>">actief</a>
                    <a href="/betaalmethodes/overzicht/inactive" type="button" class="btn btn-default <?php if($showFilter === 'inactive') { echo 'disabled'; } ?>">inactief</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                <div class="input-group input-group-sm">
                    <input type="text" id="search" class="form-control form-control-sm" value="<?php if($showFilter !== 'all' && $showFilter !== 'active' &&  $showFilter !== 'inactive') { echo $showFilter; } ?>">
                        <span class="input-group-btn">
                            <button class="btn btn-default" id="submit" type="button">zoeken</button>
                        </span>
                </div>
            </div>
        </div>
    </div>
    <table class="table table-striped table-responsive table-bordered table-couponShow sortable">
        <thead>
        <tr>
            <th data-defaultsort="asc" class="col-lg-10 col-md-10 col-sm-9 col-xs-8">Naam</th>
            <th data-firstsort="desc" class="col-lg-1 col-md-1 col-sm-1 hidden-xs">Actief</th>
            <th data-defaultsort="disabled" class="col-lg-1 col-md-1 col-sm-2 col-xs-4"><i class="fa fa-edit"></i></th>
        </tr>
        </thead>
        <tbody>
        <?php

        foreach ($showMethods as $showMethod) {
            $active = '<i class="fa fa-check"></i>';
            $cancelled = 0;

            if ($showMethod->isActive() == false) {
                $active = '<i class="fa fa-remove"></i>';
                $cancelled = 1;
            }
            ?>
            <tr>
                <td><?php echo $showMethod->getName(); ?></td>
                <td data-value="<?php echo $cancelled; ?>" class="hidden-xs"><?php echo $active; ?></td>
                <td><a href="/betaalmethodes/bewerken/<?php echo $showMethod->getId(); ?>">Bewerken</a></td>
            </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
    <div class="panel-footer">
        <a href="/betaalmethodes/nieuw">Toevoegen</a>
    </div>
</div>
<script type="text/javascript">
    $('#search').keyup(function(e){
        if(e.keyCode == 13) {
            $('#submit').trigger("click");
        }
    });

    $('#submit').on('click', function() {
        var value = $('#search').val();

        if (value === '') {
            value = 'all';
        }

        window.location.href = "/betaalmethodes/overzicht/" + value;

        return false;
    });
</script>