(function($){
    'use strict';

    $(function() {
        $( '.cpa-color-picker' ).wpColorPicker();

        var lefttabheight=$(".left-tab").height();
        $(".right-tab-content").css('min-height',lefttabheight);
        $(document).on("click",".custom-tab-a",function(){
            $(".left-tab li.active").removeClass('active');
            $(".mycutomtab-content").removeClass('active-tab');
            $(this).parent('li').addClass('active');
            var tabclss=$(this).data('class');
            $("."+tabclss).addClass('active-tab');

        })

    });

})(jQuery);