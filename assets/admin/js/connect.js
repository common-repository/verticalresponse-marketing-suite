(function($){
    'use strict';
    $(document).on("click",".show_text",function(){
    	if($(this).hasClass('showed')){
    		$(this).removeClass('showed');
    		$(this).prev('input').val($(this).data('hidetext'));
    		$(this).text('Show');
    	}else{
    		$(this).addClass('showed');
    		$(this).prev('input').val($(this).data('showtext'));
    		$(this).text('Hide');
    	}
    	
    });
})(jQuery);