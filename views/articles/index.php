<?php
/**
 * Created by PhpStorm.
 * User: Albert Feijen
 * Date: 3-12-2014
 * Time: 21:49
 */
/** @var \entities\Product[] $products */

/*<div id="contentLeft">
    <ul>
        <li id="recordsArray_&lt;?php echo $Product->getName();  ?&gt;">&nbsp;</li>
    </ul>
</div> */

?>
<script type="text/javascript" src="/scripts/moment.js" xmlns="http://www.w3.org/1999/html"></script>
<script type="text/javascript" src="/scripts/bootstrap-sortable.js"></script>
<script src='js/jquery-sortable.js'></script>

<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">Artikel overzicht</h3></div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered table-couponShow orderView sortable"> <!-- Tabel met head en sorteer functie-->
                    <thead>
                    <tr>
                        <th class="hidden-xs hidden-sm" class="col-lg-2 col-md-2 col-sm-2 col-xs-2">Artikel ID</th>
                        <th class="col-lg-2 col-md-2 col-sm-2 col-xs-2">Naam</th>
                        <th class="hidden-xs hidden-sm hidden-md" class="col-lg-2 col-md-2 col-sm-2 col-xs-2">Hoogte</th>
                        <th class="hidden-xs hidden-sm hidden-md" class="col-lg-2 col-md-2 col-sm-2 col-xs-2">Lengte</th>
                        <th class="col-lg-2 col-md-2 col-sm-2 col-xs-2">Prijs</th>
                        <th class="col-lg-2 col-md-2 col-sm-2 col-xs-2">Verzendkosten</th>
                        <th class="hidden-xs" class="col-lg-2 col-md-2 col-sm-2 col-xs-2">Maximaal artikel</th>
                        <th class="hidden-xs" class="col-lg-2 col-md-2 col-sm-2 col-xs-2">Hogere verzendkosten</th>
                        <th class="col-lg-2 col-md-2 col-sm-2 col-xs-2">Levertijd</th>
                        <th class="hidden-xs hidden-sm" class="col-lg-2 col-md-2 col-sm-2 col-xs-2">Actief</th>
                        <th class="text-center"><i class="fa fa-edit"></i></th>
                    </tr>
                    </thead>
                    <?php
                    //Tabel met een overzicht van artikelen weergeven
                    if (is_array($products)) {
                        foreach ($products as $Product) {
                            $length = null;
                            $length = $Product->getDimLength() . ' cm';
                            $width = null;
                            $width = $Product->getDimwidth() . ' cm';
                            $diltime = null;
                            $diltime = $Product->getDeliveryTime() . ' dag(en)';
                            if ($Product->isActive() == 1) {
                                $isactive = '<i class="fa fa-check"></i>';
                            } else {
                                $isactive = '<i class="fa fa-remove"></i>';
                            }

                            ?>
                            <tr>
                                <td class="hidden-xs hidden-sm"><?php echo $Product->getId(); ?></td>
                                <td><?php echo $Product->getName(); ?></td>
                                <td class="hidden-xs hidden-sm hidden-md"><?php echo $width; ?></td>
                                <td class="hidden-xs hidden-sm hidden-md"><?php echo $length; ?></td>
                                <td>&euro;<?php echo number_format($Product->getPrice(), 2); ?></td>
                                <td>&euro;<?php echo number_format($Product->getShippingCosts(), 2); ?></td>
                                <td class="hidden-xs"><?php echo $Product->getMaxBeforeShippingCosts(); ?></td>
                                <td class="hidden-xs">&euro;<?php echo number_format($Product->getHigherShippingCosts(), 2); ?></td>
                                <td><?php echo $diltime; ?></td>
                                <td class="hidden-xs hidden-sm"><?php echo $isactive; ?></td>
                                <td><a href="/articles/update/<?php echo $Product->getId(); ?>">Bewerken</a></td>
                            </tr>
                        <?php
                        }
                    }
                    ?>

                </table>
            </div>
        </div>
    </div>
    <button class="btn btn-yellow"><a href="/articles/create/">Nieuw artikel</a></button>
    <br/>
    <br/>
    <br/>
    <script>
        $(function  () {
            $("ol.simple_with_animation").sortable()
        })
    </script>
    <ol class='simple_with_animation'>
        <li>First</li>
        <li>Second</li>
        <li>Third</li>
    </ol>


    <script type="text/javascript">
        var adjustment

        $("ol.simple_with_animation").sortable({
            group: 'simple_with_animation',
            pullPlaceholder: false,
            // animation on drop
            onDrop: function  (item, targetContainer, _super) {
                var clonedItem = $('<li/>').css({height: 0})
                item.before(clonedItem)
                clonedItem.animate({'height': item.height()})

                item.animate(clonedItem.position(), function  () {
                    clonedItem.detach()
                    _super(item)
                })
            },

            // set item relative to cursor position
            onDragStart: function ($item, container, _super) {
                var offset = $item.offset(),
                    pointer = container.rootGroup.pointer

                adjustment = {
                    left: pointer.left - offset.left,
                    top: pointer.top - offset.top
                }

                _super($item, container)
            },
            onDrag: function ($item, position) {
                $item.css({
                    left: position.left - adjustment.left,
                    top: position.top - adjustment.top
                })
            }
        })
    </script>
