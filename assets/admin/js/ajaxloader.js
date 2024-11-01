jQuery(window).on("beforeunload",function(){
    jQuery(".ajaxloader").show();
});

jQuery(window).load(function(){
    setTimeout(function(){
        jQuery(".ajaxloader").hide();    
    },3000);
    
});