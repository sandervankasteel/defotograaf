<?php
/**
 * Remco Schipper
 * Date: 02/12/14
 * Time: 15:22
 */
/** @var \entities\DiscountCode[] $discountCodes */
?>
<script type="text/javascript" src="/scripts/moment.js"></script>
<script type="text/javascript" src="/scripts/bootstrap-sortable.js"></script>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-6">
                <div class="btn-group btn-group-sm" role="group">
                    <a href="/kortingscodes/overzicht/all" type="button" class="btn btn-default <?php if($showFilter === 'all') { echo 'disabled'; } ?>">alles</a>
                    <a href="/kortingscodes/overzicht/active" type="button" class="btn btn-default <?php if($showFilter === 'active') { echo 'disabled'; } ?>">actief</a>
                    <a href="/kortingscodes/overzicht/inactive" type="button" class="btn btn-default <?php if($showFilter === 'inactive') { echo 'disabled'; } ?>">inactief</a>
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
                <th data-defaultsort="asc" class="col-lg-2 col-md-2 col-sm-3 col-xs-4">Code</th>
                <th data-firstsort="asc" class="col-lg-4 col-md-3 hidden-sm hidden-xs">Beschrijving</th>
                <th data-firstsort="asc" class="col-lg-2 col-md-1 col-sm-2 col-xs-4">Kortingsbedrag</th>
                <th data-firstsort="asc" class="col-lg-2 col-md-4 col-sm-4 hidden-xs">Verloopdatum</th>
                <th data-firstsort="desc" class="col-lg-1 col-md-1 col-sm-1 hidden-xs">Actief</th>
                <th data-defaultsort="disabled" class="col-lg-1 col-md-1 col-sm-2 col-xs-4"><i class="fa fa-edit"></i></th>
            </tr>
        </thead>
        <tbody>
        <?php
        $now = new \DateTime();

        foreach ($discountCodes as $discountCode) {
            $discountAmount = null;
            $active = '<i class="fa fa-check"></i>';
            $cancelled = 0;

            if ($discountCode->getFixedAmount() === null) {
                $discountAmount = number_format($discountCode->getPercentage(), 2) . '%';
            }
            else {
                $discountAmount = '&euro;' . number_format($discountCode->getFixedAmount(), 2);
            }

            if ($discountCode->getValidUntil() < $now) {
                $active = '<i class="fa fa-remove"></i>';
                $cancelled = 1;
            }
            ?>
            <tr>
                <td><?php echo $discountCode->getCode(); ?></td>
                <td class="hidden-xs hidden-sm"><?php echo $discountCode->getDescription(); ?></td>
                <td><?php echo $discountAmount; ?></td>
                <td class="hidden-xs"><?php echo $discountCode->getValidUntil()->format('d-m-Y H:i'); ?></td>
                <td data-value="<?php echo $cancelled; ?>" class="hidden-xs"><?php echo $active; ?></td>
                <td><a href="/kortingscodes/bewerken/<?php echo $discountCode->getId(); ?>">Bewerken</a></td>
            </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
    <div class="panel-footer">
        <a href="/kortingscodes/nieuw">Toevoegen</a>
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

        window.location.href = "/kortingscodes/overzicht/" + value;

        return false;
    });
</script>