<?php
/**
 * Remco Schipper
 * Date: 06/12/14
 * Time: 16:08
 */

/** @var \entities\Product[] $viewProducts */
/** @var array $jsProducts */
?>

<div class="row">
    <div class="alert alert-info" role="alert">Klik <a id="openUploadModal">hier</a> om foto's te uploaden</div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-3 hidden-xs" id="thumbnails">
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" id="preview">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div style="padding-top: 10px"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="preview-container">
                <img src="http://placehold.it/440x440" id="preview-img" class="img-responsive center-block" alt="Responsive image">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 hidden-xs">
                <div style="padding-top: 50px"></div>
            </div>
            <div class="visible-xs col-xs-12">
                <div style="padding-top: 10px"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <button type="button" class="btn btn-yellow btn-block">Vorige</button>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <button type="button" class="btn btn-yellow btn-block pull-right">Volgende</button>
            </div>
        </div>
    </div>
    <div class="visible-xs col-xs-12">
        <hr/>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" id="options">
        <div class="row" id="photo-options">
            <div class="col-lg-12 col-md-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <p><strong>Kleuropties</strong></p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <button type="button" id="convertToGrayScale" class="btn btn-yellow btn-block"><i class="fa fa-spinner fa-spin" style="display: none"></i> Zwart-wit</button>
                    </div>
                    <div class="hidden-lg hidden-md visible-xs visible-sm col-sm-12 col-xs-12">
                        <div style="padding-top: 10px"></div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-bottom: 10px">
                        <button type="button" id="convertToSepia" class="btn btn-yellow btn-block"><i class="fa fa-spinner fa-spin" style="display: none"></i> Sepia</button>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <a href="#" id="convertToOriginal">Wis kleuropties</a>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div style="padding-top: 10px"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="format">Formaat en afdruksoort</label>
                            <select class="form-control" id="format" name="format">
                                <?php
                                foreach($viewProducts as $viewProduct) {
                                    echo '<option value="' . $viewProduct->getId() . '">' . $viewProduct->getName() . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="format">Fill-in of fit-in</label>
                            <select class="form-control" id="style" name="style">
                                <option value="fill">Fill-in</option>
                                <option value="fit">Fit-in</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <label for="amount">Aantal</label>
                        <input type="text" class="form-control" name="amount" id="amount" value="1">
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="space">

        </div>
        <div class="row" id="next-step">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <button type="button" class="btn btn-yellow btn-block" id="saveChanges" disabled="disabled">Opslaan</button>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div style="padding: 10px"></div>
        <button type="button" class="btn btn-yellow pull-right" id="goToNextStep" disabled="disabled">Volgende stap</button>
    </div>
</div>

<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Sluiten</span></button>
                <h4 class="modal-title" id="uploadModal-title">Uploaden</h4>
            </div>
            <div class="modal-body">
                <div id="dropzone" class="jumbotron">
                    <p>Sleep uw foto's hierheen of klik op de knop hieronder om ze te selecteren</p>
                    <p><a class="btn btn-default btn-lg" id="openFileUploadDialog" href="#" role="button">Selecteren</a></p>


                    <input type="file" id="fileUp" class="hidden" accept="image/*" name="files[]" data-url="/foto/upload" multiple>
                </div>

<!--                <div class="progress">-->
<!--                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">-->
<!--                        60%-->
<!--                    </div>-->
<!--                </div>-->
            </div>
            <div class="modal-footer" id="uploadModal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Sluiten</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="savePhoto" tabindex="-1" role="dialog" aria-labelledby="savePhoto" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="uploadModal-title">Opslaan</h4>
            </div>
            <div class="modal-body">
                Uw foto word opgeslagen, moment geduld a.u.b.
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="savePhotos" tabindex="-1" role="dialog" aria-labelledby="savePhotos" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="savePhotos-title">Opslaan</h4>
            </div>
            <div class="modal-body">
                Uw foto's worden op dit moment opgeslagen, moment geduld a.u.b
            </div>
            <div class="modal-footer" id="savePhotos-footer">
                <div class="progress" style="margin-bottom: 0;">
                    <div class="progress-bar" id="savePhotos-progress" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" id="thumbnail-template" style="display: none">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="wrapper">
            <div class="content">
                <div class="overlay-wrapper remove-wrapper">
                    <div class="overlay-content">
                        <i class="fa fa-remove"></i>
                    </div>
                </div>
                <img src="http://placehold.it/440x440" class="img-responsive" alt="Responsive image">
                <div class="overlay-wrapper description-wrapper">
                    <div class="overlay-content">
                        <div class="row" style="padding: 0; margin: 0">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="amount">25 stuks</div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="product">50x50 glans</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<canvas id="render-canvas" style="display: none"></canvas>

<script type="text/javascript" src="/scripts/async.js"></script>
<script type="text/javascript">
    var $thumbnails = $('#thumbnails');
    var $options =  $('#options');
    var $preview = $('#preview');
    var $nextStep = $('#next-step');
    var $photoOptions = $('#photo-options');
    var $previewImage = $('#preview-img');

    var $canvas =  $('#render-canvas');
    var context = $canvas[0].getContext('2d');

    var nextStepHeight = $nextStep.height();

    var products = JSON.parse('<?php echo json_encode($jsProducts); ?>');
    var thumbs = [];
    var activeThumb = null;

    window.requestAnimFrame = (function(){
        return  window.requestAnimationFrame       ||
        window.webkitRequestAnimationFrame ||
        window.mozRequestAnimationFrame    ||
        function( callback ){
            window.setTimeout(callback, 1);
        };
    })();

    var proportionalScale = function (originalSize, newSize)  {
        var ratio = originalSize[0] / originalSize[1];

        var maximizedToWidth = [newSize[0], newSize[0] / ratio];
        var maximizedToHeight = [newSize[1] * ratio, newSize[1]];

        if(maximizedToWidth[1] > newSize[1]) {
            return maximizedToHeight;
        }
        else {
            return maximizedToWidth;
        }
    };
    var resize = function() {
        var height = $preview.height();

        $thumbnails.css('height', height);
        $options.css('height', height);

        var top = (height - $photoOptions.height()) - nextStepHeight - 10;

        $('#space').css('height', top);
    };

    var openUploadModal = function(e) {
        $('#uploadModal').modal();

        e.stopPropagation();
        return false;
    };
    var openFileUploadDialog = function(e) {
        $('#fileUp').click();

        e.stopPropagation();
        return false;
    };

    var goToNextStep = function(e) {
        if (thumbs.length > 0) {
            $('#savePhotos').modal({
                backdrop: 'static',
                keyboard: false
            });
            prepareNextStep(0);
        }

        e.stopPropagation();
        return false;
    };
    var progressNextStep = function(i) {
        var percent = (i / thumbs.length) * 100;

        $('#savePhotos-progress').css('width', percent+'%').attr('aria-valuenow', percent);
    };
    var prepareNextStep = function(i) {
        progressNextStep(i);

        if(thumbs.length > i) {
            thumbs[i].complete(function() { prepareNextStep(i+1); });
        }
        else {
            window.location.href = '/bestelling/plaatsen/4';
        }
    };

    var fileUploadSuccess = function(e) {
        thumbs.push(new Thumbnail(e));

        $('#goToNextStep').removeAttr('disabled');
    };
    var fileUploadError = function(e) {

    };
    var fileUploadProgress = function(e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        console.log(progress);
    };

    var optionsSwitch = function(to) {
        $('#format').val(to.getProduct().id);
        $('#style').val(to.fillOrFit());

        if (to.isSepia()) {
            $('#convertToSepia').attr('disabled', 'disabled');
        }
        else {
            $('#convertToSepia').removeAttr('disabled');
        }

        if (to.isGrayScale()) {
            $('#convertToGrayScale').attr('disabled', 'disabled');
        }
        else {
            $('#convertToGrayScale').removeAttr('disabled');
        }
    };
    var thumbnailSwitch = function(to, callback) {
        to.getCurrentImg(function(data) {
            $previewImage.attr('src', data);
            //$('img', $('#thumbnail-' + to.getId())).attr('src', data);

            to.tSaveButton();
            optionsSwitch(to);
            activeThumb = to;

            if (callback !== undefined) {
                callback();
            }
        });
    };

    var changeThumbStyle = function() {
        if (activeThumb !== null) {
            var $this = $(this);
            var value = $this.find(":selected").attr('value');

            activeThumb.setFill((value === 'fill'));
        }
    };
    var changeFormat = function() {
        if (activeThumb !== null) {
            var $this = $(this);
            var value = $this.find(":selected").attr('value');

            activeThumb.setProduct(value);
        }
    };

    var convertToGrayScale = function(e) {
        if (activeThumb !== null) {
            var $this = $(this);
            $this.attr('disabled', 'disabled');
            $('.fa', $this).show();

            activeThumb.setFilter('grayScale', function() {
                $('.fa', $this).hide();
                $('#convertToSepia').removeAttr('disabled');
            });
        }

        e.stopPropagation();
        return false;
    };
    var convertToSepia = function(e) {
        if (activeThumb !== null) {
            var $this = $(this);
            $this.attr('disabled', 'disabled');
            $('.fa', $this).show();

            activeThumb.setFilter('sepia', function() {
                $('.fa', $this).hide();
                $('#convertToGrayScale').removeAttr('disabled');
            });
        }

        e.stopPropagation();
        return false;
    };
    var revertToOriginal = function(e) {
        if (activeThumb !== null) {
            activeThumb.setFilter(null, function() {
                $('#convertToSepia').removeAttr('disabled');
                $('#convertToGrayScale').removeAttr('disabled');
            });
        }

        e.stopPropagation();
        return false;
    };

    var saveChanges = function(e) {
        if (activeThumb !== null) {
            var $this = $(this);
            $this.attr('disabled', 'disabled');
            $('.fa', $this).show();

            activeThumb.save(function() {
                $('.fa', $this).hide();
                $this.removeAttr('disabled');
            });
        }

        e.stopPropagation();
        return false;
    };
    var changeAmount = function(e) {
        if (activeThumb !== null) {
            $this = $(this);

            activeThumb.setAmount($this.val());
        }
    };

    var toggleSaveButton = function(e) {
        console.log(e);
        if (e === true) {
            $('#saveChanges').removeAttr('disabled');
        }
        else {
            $('#saveChanges').attr('disabled', 'disabled');
        }
    };

    var Thumbnail = function(data) {
        var Self = this;
        var id = data.id;
        var name = data.name;
        var file_name = data.file_name;
        var path = data.path;
        var img = null;
        var product = null;
        var fill = true;
        var filter = null;
        var amount = 1;
        var rotated = false;

        var lastData = {
            fill: 0,
            filter: 0,
            product: 0,
            amount: 0
        };

        var postData = function(data, callback) {
            $.ajax({
                type: 'POST',
                url: '/foto/bewerken/' + Self.getId(),
                data: data,
                success: function() {
                    lastData = {
                        fill: fill,
                        filter: filter,
                        product: product,
                        amount: amount
                    }
                },
                complete: function() {
                    if (callback !== undefined) {
                        callback();
                    }
                }
            });
        };

        this.getId = function() {
            return id;
        };
        this.getName = function() {
            return name;
        };
        this.getFileName = function() {
            return file_name;
        };
        this.getPath = function() {
            return path;
        };

        this.getImg = function() {
            return img;
        };
        this.getCurrentImg = function(callback) {
            draw.start(callback);
        };

        this.setProduct = function(id, first) {
            if (product !== id) {
                product = id;

                if (first !== true) {
                    thumbnailSwitch(Self);
                }
            }

            Self.tSaveButton(first);
        };
        this.getProduct = function() {
            return products[product];
        };

        this.doesFill = function() {
            return fill;
        };
        this.doesFit = function() {
            return !fill;
        };
        this.fillOrFit = function() {
            return (fill === true) ? 'fill' : 'fit';
        };
        this.setFill = function(value) {
            if (fill !== value) {
                fill = value;

                thumbnailSwitch(Self);
            }

            Self.tSaveButton(false);
        };

        this.isSepia = function() {
            return (filter === 'sepia');
        };
        this.isGrayScale = function() {
            return (filter === 'grayScale');
        };
        this.getFilter = function() {
            return filter;
        };
        this.setFilter = function(value, callback) {
            if (value !== filter) {
                filter = value;

                thumbnailSwitch(Self, callback);
            }

            Self.tSaveButton(false);
        };

        this.isRotated = function() {
            return rotated;
        };

        this.setAmount = function(value) {
            amount = value;

            Self.tSaveButton(false);
        };

        this.tSaveButton = function(first) {
            if (first !== true && (lastData.fill !== fill || lastData.product !== product || lastData.filter !== filter || lastData.amount !== amount)) {
                toggleSaveButton(true);
            }
            else {
                toggleSaveButton(false);
            }
        };
        this.save = function(callback) {
            var data = {};

            if (lastData.amount !== amount) {
                data.amount = amount;
            }


            if (lastData.filter !== filter || lastData.product !== product) {
                if (lastData.product !== product) {
                    data.product_id = product;
                }

                if(filter === null) {
                    data.effect_id = "none";
                }
                else {
                    data.effect_id = (Self.isGrayScale()) ? 1 : 2;
                }

                data.stretch_id = (Self.doesFill()) ? 0 : 1;

                save.start(function(d) {
                    data.image = d;

                    postData(data, callback);
                });
            }
            else {
                postData(data, callback);
            }
        };
        this.complete = function(callback) {
            if (lastData.fill !== fill || lastData.product !== product || lastData.filter !== filter || lastData.amount !== amount) {
                console.log('save');
                Self.save(callback);
            }
            else {
                callback();
            }
        };

        var init = {};
        init.start = function() {
            for (var prop in products) {
                if (products.hasOwnProperty(prop)) {
                    Self.setProduct(prop, true);
                    break;
                }
            }

            async.waterfall([init.load, init.template, init.calculate]);
        };
        init.load = function(callback) {
            var image = new Image();
            image.onload = function() {
                img = image;

                callback(null);
            };
            image.src = Self.getPath();
        };
        init.template = function(callback) {
            var template = $('#thumbnail-template').clone();
            template.attr('id','thumbnail-' + Self.getId());
            template.attr('data-id', Self.getId());
            template.css('display', 'block');
            template.appendTo($('#thumbnails'));

            $('img', template).attr('src', Self.getImg().src);
            template.on('click', function() {
                thumbnailSwitch(Self);
            });

            callback(null);
        };
        init.calculate = function(callback) {
            if (img.width > img.height) {
                rotated = true;
            }

            callback(null);
        };

        var effect = {};
        effect.start = function(settings, callback) {
            async.waterfall([function(callback) {
                effect.extract(settings, callback);
            }, effect.work, effect.toImageUrl], function(err, result) {
                callback(null, result);
            });
        };
        effect.extract = function(settings, callback) {
            var data = context.getImageData(0, 0, settings.pixels.width, settings.pixels.height);
            callback(null, data, settings);
        };
        effect.work = function(data, settings, callback) {
            var result = null;

            if (settings.filter === 'grayScale') {
                result = filters.gray(data);
            }
            else {
                result = filters.sepia(data);
            }

            callback(null, result);
        };
        effect.toImageUrl = function(data, callback) {
            context.putImageData(data, 0, 0);

            callback(null, $canvas[0].toDataURL());
        };

        var draw = {};
        draw.start = function(callback) {
            async.waterfall([draw.calculate, draw.resize, draw.sizes, draw.image], function(err, result) {
                callback(result);
            });
        };
        draw.calculate = function(callback) {
            var product = Self.getProduct();
            var inches = null;

            if (Self.isRotated()) {
                inches = {height: product.width * 0.393700787, width: product.height * 0.393700787};
            }
            else {
                inches = { height: product.height * 0.393700787, width: product.width * 0.393700787 };
            }

            var pixels = { height: inches.height * product.ppi, width: inches.width * product.ppi };
            var fill = Self.doesFill();
            var filter = Self.getFilter();

            callback(null, { inches: inches, pixels: pixels, filter: filter, fill: fill });
        };
        draw.resize = function(settings, callback) {
            $canvas.css('width', settings.pixels.width);
            $canvas.css('height', settings.pixels.height);
            $canvas.attr('width', settings.pixels.width);
            $canvas.attr('height', settings.pixels.height);

            callback(null, settings);
        };
        draw.sizes = function(settings, callback) {
            var img = Self.getImg();

            var sizes = {
                source: { x: 0, y: 0, width: img.width, height: img.height },
                dest: { x: 0, y: 0, width: 0, height: 0 }
            };

            if (settings.fill) {
                var factor = { height: 1, width: 1 };

                if (settings.pixels.width > img.width) {
                    factor.width = settings.pixels.width / img.width;
                }
                if (settings.pixels.height > img.height) {
                    factor.height = settings.pixels.height / img.height;
                }

                var nFactor = (factor.height > factor.width) ? factor.height : factor.width;
                sizes.dest.width = img.width * nFactor;
                sizes.dest.height = img.height * nFactor;
            }
            else {
                var nSizes = proportionalScale([img.width, img.height], [settings.pixels.width, settings.pixels.height]);
                sizes.dest.width = nSizes[0];
                sizes.dest.height = nSizes[1];
            }

            callback(null, sizes, settings);
        };
        draw.image = function(sizes, settings, callback) {
            context.fillStyle= "#FF0000";

            requestAnimFrame(function() {
                context.fillRect(0,0,settings.pixels.width,settings.pixels.height);

                context.drawImage(Self.getImg(), 0, 0, sizes.source.width, sizes.source.height, 0, 0, sizes.dest.width, sizes.dest.height);

                if (settings.filter === null) {
                    callback(null, $canvas[0].toDataURL());
                }
                else {
                    effect.start(settings, callback);
                }
            });
        };

        var save = {};
        save.start = function(callback) {
            if(Self.doesFill()) {
                draw.start(callback);
            }
            else {
                async.waterfall([draw.calculate, draw.sizes, save.resize, save.image], function (err, result) {
                    callback(result);
                });
            }
        };
        save.resize = function(sizes, settings, callback) {
            $canvas.css('width',  sizes.dest.width);
            $canvas.css('height', sizes.dest.height);
            $canvas.attr('width', sizes.dest.width);
            $canvas.attr('height', sizes.dest.height);
            context.clearRect (0, 0, sizes.dest.width, sizes.dest.height);

            callback(null, sizes, settings);
        };
        save.image = function(sizes, settings, callback) {
            context.drawImage(Self.getImg(), 0, 0, sizes.source.width, sizes.source.height, 0, 0, sizes.dest.width, sizes.dest.height);

            if (settings.filter === null) {
                callback(null, $canvas[0].toDataURL());
            }
            else {
                effect.start(settings, callback);
            }
        };

        init.start();
    };

    $(document).ready(function() {
        $(window).resize(resize);
        $('#openUploadModal').on('click', openUploadModal);
        $('#openFileUploadDialog').on('click', openFileUploadDialog);
        $('#style').on('change', changeThumbStyle);
        $('#format').on('change', changeFormat);

        $('#convertToOriginal').on('click', revertToOriginal);
        $('#convertToGrayScale').on('click', convertToGrayScale);
        $('#convertToSepia').on('click', convertToSepia);

        $('#goToNextStep').on('click', goToNextStep);

        $('#saveChanges').on('click', saveChanges);
        $('#amount').on('change', changeAmount);

        $previewImage.on('load', resize);

        $('#fileUp').fileupload({
            dataType: 'json',
            dropZone: $("#dropzone"),
            success: fileUploadSuccess,
            fail: fileUploadError,
            progress: fileUploadProgress
        });
    });

    var filters = {};
    filters.gray = function (imageData) {
        var data = imageData.data;

        for(var i = 0; i < data.length; i += 4) {
            var brightness = 0.34 * data[i] + 0.5 * data[i + 1] + 0.16 * data[i + 2];
            data[i] = brightness;
            data[i + 1] = brightness;
            data[i + 2] = brightness;
        }

        return imageData;
    };
    filters.sepia = function(imageData) {
        var r = [0, 0, 0, 1, 1, 2, 3, 3, 3, 4, 4, 4, 5, 5, 5, 6, 6, 7, 7, 7, 7, 8, 8, 8, 9, 9, 9, 9, 10, 10, 10, 10, 11, 11, 12, 12, 12, 12, 13, 13, 13, 14, 14, 15, 15, 16, 16, 17, 17, 17, 18, 19, 19, 20, 21, 22, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 39, 40, 41, 42, 44, 45, 47, 48, 49, 52, 54, 55, 57, 59, 60, 62, 65, 67, 69, 70, 72, 74, 77, 79, 81, 83, 86, 88, 90, 92, 94, 97, 99, 101, 103, 107, 109, 111, 112, 116, 118, 120, 124, 126, 127, 129, 133, 135, 136, 140, 142, 143, 145, 149, 150, 152, 155, 157, 159, 162, 163, 165, 167, 170, 171, 173, 176, 177, 178, 180, 183, 184, 185, 188, 189, 190, 192, 194, 195, 196, 198, 200, 201, 202, 203, 204, 206, 207, 208, 209, 211, 212, 213, 214, 215, 216, 218, 219, 219, 220, 221, 222, 223, 224, 225, 226, 227, 227, 228, 229, 229, 230, 231, 232, 232, 233, 234, 234, 235, 236, 236, 237, 238, 238, 239, 239, 240, 241, 241, 242, 242, 243, 244, 244, 245, 245, 245, 246, 247, 247, 248, 248, 249, 249, 249, 250, 251, 251, 252, 252, 252, 253, 254, 254, 254, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255],
            g = [0, 0, 1, 2, 2, 3, 5, 5, 6, 7, 8, 8, 10, 11, 11, 12, 13, 15, 15, 16, 17, 18, 18, 19, 21, 22, 22, 23, 24, 26, 26, 27, 28, 29, 31, 31, 32, 33, 34, 35, 35, 37, 38, 39, 40, 41, 43, 44, 44, 45, 46, 47, 48, 50, 51, 52, 53, 54, 56, 57, 58, 59, 60, 61, 63, 64, 65, 66, 67, 68, 69, 71, 72, 73, 74, 75, 76, 77, 79, 80, 81, 83, 84, 85, 86, 88, 89, 90, 92, 93, 94, 95, 96, 97, 100, 101, 102, 103, 105, 106, 107, 108, 109, 111, 113, 114, 115, 117, 118, 119, 120, 122, 123, 124, 126, 127, 128, 129, 131, 132, 133, 135, 136, 137, 138, 140, 141, 142, 144, 145, 146, 148, 149, 150, 151, 153, 154, 155, 157, 158, 159, 160, 162, 163, 164, 166, 167, 168, 169, 171, 172, 173, 174, 175, 176, 177, 178, 179, 181, 182, 183, 184, 186, 186, 187, 188, 189, 190, 192, 193, 194, 195, 195, 196, 197, 199, 200, 201, 202, 202, 203, 204, 205, 206, 207, 208, 208, 209, 210, 211, 212, 213, 214, 214, 215, 216, 217, 218, 219, 219, 220, 221, 222, 223, 223, 224, 225, 226, 226, 227, 228, 228, 229, 230, 231, 232, 232, 232, 233, 234, 235, 235, 236, 236, 237, 238, 238, 239, 239, 240, 240, 241, 242, 242, 242, 243, 244, 245, 245, 246, 246, 247, 247, 248, 249, 249, 249, 250, 251, 251, 252, 252, 252, 253, 254, 255],
            b = [53, 53, 53, 54, 54, 54, 55, 55, 55, 56, 57, 57, 57, 58, 58, 58, 59, 59, 59, 60, 61, 61, 61, 62, 62, 63, 63, 63, 64, 65, 65, 65, 66, 66, 67, 67, 67, 68, 69, 69, 69, 70, 70, 71, 71, 72, 73, 73, 73, 74, 74, 75, 75, 76, 77, 77, 78, 78, 79, 79, 80, 81, 81, 82, 82, 83, 83, 84, 85, 85, 86, 86, 87, 87, 88, 89, 89, 90, 90, 91, 91, 93, 93, 94, 94, 95, 95, 96, 97, 98, 98, 99, 99, 100, 101, 102, 102, 103, 104, 105, 105, 106, 106, 107, 108, 109, 109, 110, 111, 111, 112, 113, 114, 114, 115, 116, 117, 117, 118, 119, 119, 121, 121, 122, 122, 123, 124, 125, 126, 126, 127, 128, 129, 129, 130, 131, 132, 132, 133, 134, 134, 135, 136, 137, 137, 138, 139, 140, 140, 141, 142, 142, 143, 144, 145, 145, 146, 146, 148, 148, 149, 149, 150, 151, 152, 152, 153, 153, 154, 155, 156, 156, 157, 157, 158, 159, 160, 160, 161, 161, 162, 162, 163, 164, 164, 165, 165, 166, 166, 167, 168, 168, 169, 169, 170, 170, 171, 172, 172, 173, 173, 174, 174, 175, 176, 176, 177, 177, 177, 178, 178, 179, 180, 180, 181, 181, 181, 182, 182, 183, 184, 184, 184, 185, 185, 186, 186, 186, 187, 188, 188, 188, 189, 189, 189, 190, 190, 191, 191, 192, 192, 193, 193, 193, 194, 194, 194, 195, 196, 196, 196, 197, 197, 197, 198, 199];
        var noise = 20;

        for (var i=0; i < imageData.data.length; i+=4) {

            // change image colors
            imageData.data[i] = r[imageData.data[i]];
            imageData.data[i+1] = g[imageData.data[i+1]];
            imageData.data[i+2] = b[imageData.data[i+2]];

            // apply noise
            if (noise > 0) {
                var noise = Math.round(noise - Math.random() * noise);

                for(var j=0; j<3; j++){
                    var iPN = noise + imageData.data[i+j];
                    imageData.data[i+j] = (iPN > 255) ? 255 : iPN;
                }
            }
        }

        return imageData;
    };
</script>