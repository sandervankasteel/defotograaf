<div class="row">
    <ul id="file_list">
        <?php foreach($files as $file): ?>
            <li class="files"><?php echo $file; ?></li>
        <?php endforeach; ?>
    </ul>

    <div class="col-md-2">
        <div id="photos">
            <ul id="photo_list">
                <?php for($i = 1; $i <= count($files); $i++): ?>
                   <li id="<?php echo $i; ?>">
                       <a href="#">
                           <canvas id="picture<?php echo $i; ?>"></canvas>
                       </a>
                   </li>
                <?php endfor; ?>
            </ul>
        </div>
    </div>

    <div class="col-md-8">
        <div id="fit-in-selector"></div>
        <div id="photo_edit">
            <canvas id="active"></canvas>
        </div>
    </div>
    <div class="col-md-2">
        <div id="buttons">
            <div class="row">
                <div class="col-md-6">
                    <button class="btn btn-yellow" id="sepia">Sepia</button>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-yellow" id="greyscale">Zwart-Wit</button>
                </div>
            </div>
            <div class="row">
                <br />
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-yellow" id="normal">Effect ongedaan maken</button>
                </div>
            </div>
            <div class="row">
                <br />
            </div>
            <div class="row">
                <div class="col-md-6">
                    <button class="btn btn-yellow" id="rotate-90"><i class="fa fa-undo"></i></button>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-yellow" id="rotate-270"><i class="fa fa-repeat"></i></button>
                </div>
            </div>
            <div class="row">
                <br />
            </div>
            <div class="row">
                <div class="col-md-12">
                    <select id="selectfield">
                        <?php foreach($products as $product):?>
                            <option id="product_<?php echo $product['id']; ?>" value="<?php echo $product['id']?>"><?php echo $product['name']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <br />
            </div>
            <div class="row">
                <div class="col-md-12">
                    Aantal
                </div>
                <div class="col-md-8">
                    <input class="form-control" type="number" name="amount" maxlength="2" size="2" min="1" max="99" id="amount">
                </div>
            </div>
            <div class="row">
                <br />
            </div>
            <div class="row">
                <input type="radio" name="resize" id="fit-in" value="fit-in"> Fit-in <i id="question_fit-fill-in" class="fa fa-question-circle fa-1x fa-fw"></i><br />
                <input type="radio" name="resize" id="fill-in" value="fill-in"> Fill-in <br />
                <br />
            </div>
            <div class="row">
                <div id="explaination">
                    <p>Uitleg verhaaltje over fill-in en fit-in!</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <button class="btn btn-yellow" id="confirm">Bevestig</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3 col-md-push-9">
        <button id="next_step" class="btn btn-yellown">Klaar met bewerken</button>
    </div>
</div>
