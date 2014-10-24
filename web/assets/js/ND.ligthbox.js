ND.lightbox = {
    
    init: function(content, options) {

        var settings = {
            css : {}
        };

        jQuery.extend(settings, options);

        $.get(ND.WEBROOT + 'templates/Admin/partials/lightbox.handlebars', function(template) {
        
            // Instancie Handlerbars
            var template = Handlebars.compile(template);

            // Injecte la lightbox dans le corps de la page.
            $('body').append(template({
                content: content
            }));

            $(".ND_lightbox").css(settings.css);

            // Effets fade-in
            $(".ND_overlay").hide();
            $(".ND_overlay").fadeIn(250);
 
            $(".ND_lightbox").hide();
            $(".ND_lightbox").fadeIn(500, function(){
                ND.lightbox.complete();
            });

            // Ecouteur de click sur le bouton close.
		    $("#ND_lightbox_Close").click(function(e) { 
			    e.preventDefault();
			    ND.lightbox.close();
		    });
		    
		    // Fermeture de la lightbox lorsque la touche ESC est pressée.
		    $(document).keyup(function(e) {
			    if (e.keyCode == 27) { 
				    ND.lightbox.close();
			    }  
		    });
        });
    },
    
    close: function() {
        $(".ND_overlay").fadeOut(250, function() {
            $(".ND_overlay").remove();
        });
        
        $(".ND_lightbox").fadeOut(500, function() {
            $(".ND_lightbox").remove();
        });
    },
    
    complete: function() {},
    
    get: function(){
        return $(".ND_lightbox");
    }
    
} 
