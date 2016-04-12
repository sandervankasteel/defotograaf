<?php
/**
 * Remco Schipper
 * Date: 06/12/14
 * Time: 15:28
 */
?>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">Ik heb/wil een account</div>
            <div class="panel-body">
                <form role="form" method="POST" action="/customers/login">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter email">
                    </div>
                    <div class="form-group has-feedback">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                    </div>
                    <button type="submit" name="login" class="small-button">Login</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">Bestel zonder account</div>
            <div class="panel-body">
                <p class="text-default">Het aanmaken van een account heeft enkele voordelen zoals:</p>
                <ul>
                    <li>U kunt ten alle tijden de huidige status van uw bestelling inzien</li>
                    <li>U hoeft niet elke keer uw gegevens in te vullen</li>
                </ul>
                <p class="text-default">Klik <a href="/orders/create/2">hier</a> als u wilt doorgaan zonder account.</p>
            </div>
        </div>
    </div>
</div>