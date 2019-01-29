// A $( document ).ready() block.
(function($){
    $( document ).ready(function() {
       
        var bgleayr = $(".bgleayr").css("display");
        
        $(".kb_qrcode_div").click(function(){

           if( bgleayr == "none" ){

               $(".bgleayr").css("display","block");
           
            }
            
        });

        $(".bgleayr").click(function(){
          
            setTimeout(function(){
                $(".bgleayr").css("display","none");
            }, 30);
           
            
        });

        $( "#tabs" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
        $( "#tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
        $( "fieldset input" ).checkboxradio();

        $('#cr_shortcode').tabs();
       



        

    });
})(jQuery);