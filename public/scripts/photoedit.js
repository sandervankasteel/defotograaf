/**
 * Created by sander on 11/14/14.
 */


$( document ).ready(function() {

    var active;

    var products = new Array();
    var currentEffect = 'no_effect';

    Caman.debug = true;

    // To begin with, lets hide the active div so the user won't see that border!
    $('#active').hide();
    $('#explaination').hide();

    $('#question_fit-fill-in').click(function() {
        $('#explaination').toggle();
    });

    $('#rotate-90').click(function() {
       Caman('#active', function() {
           this.rotate(270);
           this.render();
       });
    });

    $('#rotate-270').click(function() {
        Caman('#active', function() {
            this.rotate(90);
            this.render();
        });
    });

    //$("#photo_list").mCustomScrollbar({
    $("#photos").mCustomScrollbar({
        //theme: "rounded-dots-3",
        theme: "dark-thick",
        scrollInertia: 100,
        scrollButtons: {
            enable: true
        },

        scrollbarPosition:"inside"
    });

    $('.files').each(function(index) {

        var number = index + 1;

        Caman('#picture' + number , "/mediaupload/" + window.location.pathname.substring(17, window.location.pathname.length) + "/" +$(this).text(), function() {

            this.resize({
                height: parseInt(parseInt(this.imageHeight() / this.imageWidth() * $('#photos').width() / 2)),
                width: parseInt($('#photos').width() / 2)
            });

            this.render();
        });
    });


    $("li").click(function() {
        var selected = $(this).prop('id');
        active = selected;

        $('#amount').val('');

        // Then let's empty the active DIV
        $('#photo_edit').html('<canvas id="active"></canvas> ');
        $('#active').show();

        // Finally we show the selected file
        Caman('#active', Caman('#picture' + selected).imageUrl, function() {

            this.resize({
                height: this.imageHeight() / this.imageWidth() * $('#photo_edit').width(),
                width: $('#photo_edit').width()
            });

            this.render();

        });

        $('input:radio').prop('checked', false);
        $('#fit-in-selector').hide();

    });

    $('#sepia').click(function() {
        Caman("#active", function() {
            // First we reset the file to it's orignal state
            this.revert();

            Caman("#picture" + active).sepia(100).render();

            currentEffect = 'sepia';

            this.sepia(100).render();

        });
    });

    $('#greyscale').click(function() {
        Caman("#active", function() {
            // First we reset the file to it's orignal state
            this.revert();

            Caman("#picture" + active).greyscale().render();

            currentEffect = 'black & white';

            this.greyscale().render();
        });
    });

    $('#normal').click(function() {
        Caman("#active", function() {
            this.revert();
            this.render();
        });

        currentEffect = 'no_effect';

        Caman("#picture" + active).revert();
    });

    $('#done').click(function() {
        // Change the value of the current active div to an Base64 encoded string representation
        // And submit that to an input field, so it can get converted back to a JPG at the PHP side.
        $('#file_' + active).val(document.getElementById("active").toDataURL("image/jpeg"));
    });

    $('#fit-in').click(function() {
        Caman('#active', function() {

            this.reset();

            this.resize({
                height: this.imageHeight() / this.imageWidth() * $('#photo_edit').width(),
                width: $('#photo_edit').width()
            });

            this.render(function() {
                // When the render is done!

                setFitInSelector();

                // Now we finally gonna show the div
                $('#fit-in-selector').show();
            });
        });

        if (parseInt($('#amount').val()) > 0)
        {
            $('#confirm').prop('disabled', false);
        }
    });

    $('#fill-in').click(function() {
        $('#fit-in-selector').hide();

        setFillInPreview();

        if (parseInt($('#amount').val()) > 0)
        {
            $('#confirm').prop('disabled', false);
        }
    });

    $('#selectfield').change(function() {
       setFitInSelector();

        if ($('#fill-in').prop('checked')) {
            setFillInPreview();
        }
    });

    $('#confirm').click(function() {

        // A simple check if the product is already in the list if so, then remove it!
        for(var i =0; i<products.length; i++)
        {
           if(products[i][0].photoId == active)
           {
               products.splice(products[i], 1);

               // removes the visual element of the order
               $('#' + active).prev().remove();
           }
        }

        //$('#' + active).append().before('<div id="order_product_' + products.length +'"><h6> - ' + $('#amount').val() + ' stuk(s)</h6><h6>' + $('#product_' + $('#selectfield').val()).text() + ' <a href="#" id="order_product_delete_' + products.length + '"><i class="fa fa-trash"></a></i></h6></div>');
        $('#' + active).before('<div id="order_product_' + products.length +'"><h6> - ' + $('#amount').val() + ' stuk(s)</h6><h6>' + $('#product_' + $('#selectfield').val()).text() + ' <a href="#" id="order_product_delete_' + products.length + '"><i class="fa fa-trash"></a></i></h6></div>');


        Caman("#active", function() {

            // Gets the original imageHeight + imageWidth
            var orgHeight = Caman("#active").imageHeight();
            var orgWidth = Caman("#active").imageWidth();

            // Gets the current height + width;
            var viewHeight = Caman("#active").height;
            var viewWidth = Caman("#active").width;

            var stretch = "";

            if ($('#fit-in').prop('checked')) {

                // gets the current preview dimensions!
                var previewHeight = $('#active').height();
                var previewWidth = $('#active').width();

                // Then lets get the product height + width in CM
                var productHeightCM = $('#selectfield :selected').text().match(/[0-9]/g)[2] + $('#selectfield :selected').text().match(/[0-9]/g)[3];
                var productWidthCM = $('#selectfield :selected').text().match(/[0-9]/g)[0] + $('#selectfield :selected').text().match(/[0-9]/g)[1];

                // Now we are gonna calculate the products height and width in pixels.
                var productHeightPixels = Math.round(((productHeightCM * 10) * 300) / 25.4);
                var productWidthPixels = Math.round(((productWidthCM * 10) * 300) / 25.4);

                this.resize({
                    height: orgHeight,
                    width: orgWidth
                });

                this.render();

                var croppedHeight = productHeightPixels;
                var croppedWidth = productWidthPixels;
                var croppedY = ($('#fit-in-selector').position().top * (Caman('#active').imageHeight() / previewHeight ));
                var croppedX = ($('#fit-in-selector').position().left - 15) * (Caman('#active').imageWidth() / previewWidth);

                this.crop(croppedHeight, croppedWidth, croppedX, croppedY);

                this.render();

                this.stretch = "fit-in";

            } else if ($('#fill-in').prop('checked')) {

                var productHeightCM = $('#selectfield :selected').text().match(/[0-9]/g)[2] + $('#selectfield :selected').text().match(/[0-9]/g)[3];
                var productWidthCM = $('#selectfield :selected').text().match(/[0-9]/g)[0] + $('#selectfield :selected').text().match(/[0-9]/g)[1];

                var productHeightPixels = Math.round(((productHeightCM * 10) * 300) / 25.4);
                var productWidthPixels = Math.round(((productWidthCM * 10) * 300) / 25.4);

                // Now we resize it the fill-in dimensions
                this.resize({
                    height: productHeightPixels,
                    width: productWidthPixels
                });

                this.stretch = "fill-in";

                this.render();
            }

            // Now we write everything back to the currentProduct array
            // Syntax for products is, currentPhotoID, amount, effect, fitt-in / fill, size, imagedata
            var currentProduct = new Array({
                'photoId': active,
                'amount' : $('#amount').val(),
                'photoEffect' : currentEffect,
                'stretch' : this.stretch.toString(),
                'size' : $('#selectfield option:selected').text(),
                'imageData' : document.getElementById("active").toDataURL("image/jpeg")
            });

            products.push(currentProduct);

            this.reset();

            // Now we resize back to the viewing dimensions
            this.resize({
                height: viewHeight,
                width: viewWidth
            });

            if (currentEffect == "black & white") {
                this.greyscale();
            } else if (currentEffect == "sepia") {
                this.sepia(100);
            }

            this.render();

        });

        $("[id^=order_product_delete_]").click(function() {

            // removes the visual part of the order.
            var selected = $(this).attr('id').match(/[0-9]/).toString();
            $('#order_product_' + selected).remove();

            // removes the selected product from the products array
            products.splice(selected, 1);

        });
    })

    $('#next_step').click(function() {

        for(var i = 0; i<products.length; i++) {

            $.ajax({
                url: '/PhotoEdit/file_processing',
                type: 'post',
                data: products[i][0],
                processData: true
            }).done(function (data){

            }).always(function() {
                alert("Nu hoort de gebruiker door te gaan naar het volgende scherm in het bestelproces");
            })
        }

    });

    $('#fit-in-selector').hide();
    $('#fit-in-selector').drags();

    // We now are gonna load file number 1
    var selected = $(this).prop('id');
    active = selected;

    $('#amount').val('');

    // Then let's empty the active DIV
    $('#photo_edit').html('<canvas id="active"></canvas> ');
    $('#active').show();

    // Finally we show the selected file

    if ($('.files').length > 0) {
        Caman('#active', "/mediaupload/" + $('.files')[0].innerHTML , function() {

            this.resize({
                height: this.imageHeight() / this.imageWidth() * $('#photo_edit').width(),
                width: $('#photo_edit').width()
            });

            this.render();
        });
    }

    $('#amount').keyup(function (event) {
        if ($('#amount').val() != "") {
            if ($('#fit-in').prop('checked') || $('#fit-in').prop('checked')) {
                $('#confirm').prop("disabled", false);
            }
        } else {
            $('#confirm').prop("disabled", true);
        }
    });

    $('#confirm').prop("disabled", true);

    $('#amount').keyup();

});

function setFillInPreview() {
    var productHeightCM = $('#selectfield :selected').text().match(/[0-9]/g)[2] + $('#selectfield :selected').text().match(/[0-9]/g)[3];
    var productWidthCM = $('#selectfield :selected').text().match(/[0-9]/g)[0] + $('#selectfield :selected').text().match(/[0-9]/g)[1];

    // Now we are gonna calculate the products height and width in pixels.
    var productHeightPixels = Math.round(((productHeightCM * 10) * 300) / 25.4);
    var productWidthPixels = Math.round(((productWidthCM * 10) * 300) / 25.4);

    var ratio = productWidthPixels / $('#active').width();

    Caman('#active', function() {
        this.resize({
            height: productHeightPixels / ratio,
            width: $('#active').width()
        });

        this.render();
    })
}

function setFitInSelector() {
    // 10 cm = 1181 px
    // 15 cm = 1772 px

    // First let's get the preview Height + width
    var previewPictureHeight = $('#active').css('height').substr(0, $('#active').css('height').length - 2);
    var previewPictureWidth = $('#active').css('width').substr(0, $('#active').css('width').length - 2);

    // Then lets get the product height + width in CM
    var productHeightCM = $('#selectfield :selected').text().match(/[0-9]/g)[0] + $('#selectfield :selected').text().match(/[0-9]/g)[1];
    var productWidthCM = $('#selectfield :selected').text().match(/[0-9]/g)[2] + $('#selectfield :selected').text().match(/[0-9]/g)[3];

    // Now we are gonna calculate the products height and width in pixels.
    var productHeightPixels = Math.round(((productHeightCM * 10) * 300) / 25.4);
    var productWidthPixels = Math.round(((productWidthCM * 10) * 300) / 25.4);

    // previewUitsnijde hoogte = oorspronkelijke uitsnede hoogte / (plaatje Orginele hoogte / geschaalde hoogte) ?
    // previewUitsnijde breedte = oorspronkelijke uitsnede breedte / (plaatje Orginele breedte / geschaalde breedte) ?

    if ((Caman('#active').imageHeight() / $('#active').height()) > 1) {
        $('#fit-in-selector').height(productHeightPixels / (Caman('#active').imageHeight() / $('#active').height()));
    } else {
        $('#fit-in-selector').height($('#active').height());
    }

    if ((Caman('#active').imageWidth() / $('#active').width()) > 1) {
        $('#fit-in-selector').width(productWidthPixels / (Caman('#active').imageWidth() / $('#active').width()));
    } else {
        $('#fit-in-selector').width($('#active').width());
    }

}

(function($) {
    $.fn.drags = function(opt) {

        opt = $.extend({handle:"",cursor:"move"}, opt);

        if(opt.handle === "") {
            var $el = this;
        } else {
            var $el = this.find(opt.handle);
        }

        return $el.css('cursor', opt.cursor).on("mousedown", function(e) {
            if(opt.handle === "") {
                var $drag = $(this).addClass('draggable');
            } else {
                var $drag = $(this).addClass('active-handle').parent().addClass('draggable');
            }

            var z_idx = $drag.css('z-index'),
                drg_h = $drag.outerHeight(),
                drg_w = $drag.outerWidth(),
                pos_y = $drag.offset().top + drg_h - e.pageY,
                pos_x = $drag.offset().left + drg_w - e.pageX;
            $drag.css('z-index', 1000).parents().on("mousemove", function(e) {

                if($('#fit-in-selector').position().top < 0)
                {
                    $('#fit-in-selector').offset({
                        top: $('#active').offset().top,
                        left: this.left
                    });
                } else if ($('#fit-in-selector').position().left < 0 ) {
                    $('#fit-in-selector').offset({
                        top: this.top,
                        left: $('#active').offset().left
                    });
                } else if ($('#fit-in-selector').position().top > $('#active').height()) {
                    $('#fit-in-selector').offset({
                        top: $('#fit-in-selector').position().top - $('#active').height(),
                        left: $('#active').offset().left
                    });

                } else if ($('#fit-in-selector').position().left > $('#active').width()) {
                    $('#fit-in-selector').offset({
                        top: this.top,
                        left: $('#active').offset().left - $('#active').width()
                    });
                    /* }  else if (a) { */
                    // TODO MEER TEST CLAUSULES WAARDOOR DE SELECTIE NIET BUITEN HET PLAATJE MAG KOMEN! ZOALS DAT ALS TOP EN LEFT NEGATIEF ZIJN!
                } else if (($('#fit-in-selector').position().left - 15) + $('#fit-in-selector').width() + parseInt($('#fit-in-selector').css('border-width')) > $('#active').width()) {
                    $('#fit-in-selector').offset({
                        top: this.top,
                        left: ($('#active').offset().left + $('#active').width()) - $('#fit-in-selector').width() - parseInt($('#fit-in-selector').css('border-width')) - parseInt($('#fit-in-selector').css('border-width'))
                    });
                    //alert("Hallo je mag niet verder");
                //} else if (($('#fit-in-selector').position().top + $('#fit-in-selector').height()) + $('#fit-in-selector').height - parseInt($('#fit-in-selector').css('border-width')) - parseInt($('#fit-in-selector').css('border-width'))) {
                //    alert("Je mag in de hoogte niet verder dan de bedoeling is!");
                } else {
                    $('.draggable').offset({
                        top:e.pageY + pos_y - drg_h,
                        left:e.pageX + pos_x - drg_w
                    }).on("mouseup", function() {
                        $(this).removeClass('draggable').css('z-index', z_idx);
                    });
                }
        });
            e.preventDefault(); // disable selection
        }).on("mouseup", function() {
            if(opt.handle === "") {
                $(this).removeClass('draggable');
            } else {
                $(this).removeClass('active-handle').parent().removeClass('draggable');
            }
        });

    }
})(jQuery);