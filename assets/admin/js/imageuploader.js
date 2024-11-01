var wordpress_ver=bg.wpv;

(function($){
    'use strict';

    var upload_button;
    $(document).on("click",".uploadimage_btn",function(event) {
        upload_button = $(this);
        var frame;
        if (wordpress_ver >= "3.5") {
            event.preventDefault();
            if (frame) {
                frame.open();
                return;
            }
            frame = wp.media();
            frame.on( "select", function() {
                var attachment = frame.state().get("selection").first();
                frame.close();
                if (upload_button.prev().hasClass("formbgimageurl")) {
                    upload_button.prev(".formbgimageurl").val(attachment.attributes.url).trigger("change");
                    if(upload_button.next(".divpreview").length>0){
                        upload_button.next(".divpreview").remove();
                    }
                    upload_button.after('<div class="divpreview thumbnail"><button type="button" title="Delete Image" class="btn btn-danger btn-sm delete_image"><span class="glyphicon glyphicon-trash"></span></button><img src="'+attachment.attributes.url+'" class="img-responsive"></div>');
                }else{
                    $(".formbgimageurl").val(attachment.attributes.url).trigger("change");
                }
                                    
            });
            frame.open();
        }else {
            tb_show("", "media-upload.php?type=image&amp;TB_iframe=true");
            return false;
        }
    });

    $(document).on("click",".delete_image",function(){
        $(".formbgimageurl").val('').trigger("change");
        $('.divpreview').remove();
    });

    if (wordpress_ver < "3.5") {
        window.send_to_editor = function(html) {
            imgurl = $("img",html).attr("src");
            if (upload_button.prev().hasClass(".formbgimageurl")) {
                upload_button.prev().val(imgurl).trigger("change");
                if(upload_button.next(".divpreview").length>0){
                    upload_button.next(".divpreview").remove();
                }

                upload_button.after('<div class="divpreview thumbnail"><button type="button" title="Delete Image" class="btn btn-danger btn-sm delete_image"><span class="glyphicon glyphicon-trash"></span></button><img src="'+imgurl+'" class="img-responsive"></div>');
            }else{
                $(".formbgimageurl").val(imgurl).trigger("change");
            }
            tb_remove();
        }
    }
})(jQuery);