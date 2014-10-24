//------------------------------------------------------------//
//  ND MEDIA SELECTOR
//  ------
//  Author : Nicolas DEL CASTILLO
//  Date : 2014/04/18
//  Note : Depend to Twitter Boostrap and Jquery.
//------------------------------------------------------------//
ND.mediaSelector = {

    page        : 1,
    pageSize    : 10,
    templates   : [],
    options     : {},
    
    init: function(options) 
    {
    
        if(typeof(options.onSave) != "undefined") {
            this.onSave = options.onSave;
        }
        
        var template = Handlebars.compile(this.getTemplate("main"));
        
        ND.lightbox.init(
            template({
                medias: null, 
                pagination: null
            })
        );

        ND.lightbox.complete = function(){
            ND.mediaSelector.setGallery(options);
        };

    },

    // When save button is activated
    onSave: function(data) {
        console.log("saving...");
        console.log(data);
    },

    getTemplate: function(id) {

        if(typeof(this.templates[ id ]) != "undefined")
            return this.templates[ id ];

        return null;
    },

    setGallery: function(options) {
    
        var type = null;

        if(options) {
            if(typeof(options.type) != "undefined") {
                type = options.type;
            }
        }
        
        $.get('/admin/medias/?page_number=' + ND.mediaSelector.page, {format: "json", type: type},
            function(data) {

            if(ND.isJSON(data)) {
                var JsonData = JSON.parse(data);
            } else {
                var JsonData = data;
            }

			var pages           = new Array();
            
            var nbPages = 0;
            var page = 0;
            
            if(JsonData.pagination) {
            	nbPages         = parseInt(JsonData.pagination.total);
	            page            = parseInt(JsonData.pagination.current);
	
	            pages["next_page"]  = parseInt(JsonData.pagination.next_page);
	            pages["prev_page"]  = parseInt(JsonData.pagination.prev_page);
            }

            var start           = page - Math.ceil(ND.mediaSelector.pageSize / 2);
            var end             = page + Math.floor(ND.mediaSelector.pageSize / 2);

            if(end > nbPages) {
                start   = nbPages - ND.mediaSelector.pageSize;
                end     = nbPages;
            }
            
            if(start < 1) {
                start   = 1;
                end     = start + ND.mediaSelector.pageSize;
            }
            
            if(end > nbPages) {
                end = nbPages;
            }
            
            // Creating array of pages
            for(i=start; i <= end; i++) {
                pages.push(i);
            }

            // Pagination
            if(end > 1) {
                 var template = Handlebars.compile(ND.mediaSelector.getTemplate("pagination"));
                $(".mediaSelector .pages").html(
                    template({
                        pagination: pages
                    })
                );
            }
            
            // Rendu
            var template = Handlebars.compile(ND.mediaSelector.getTemplate("medias"));
            $(".mediaSelector .selectorTable").html(
                template({
                    MEDIAS: JsonData.MEDIAS,
                    WEBROOT: ND.WEBROOT
                })
            );

            // Ecouteur
            $(".selectorTable a").click(function(e) { 
                e.preventDefault();
                ND.mediaSelector.loadMediaData($(this).attr('href').replace(/^#/, ''));
            });
            
            $(".ND_lightbox .content .pages a").click(function(e) { 
                e.preventDefault();
                ND.mediaSelector.page = $(this).attr("data-page");
                //var text    = $(".ND_lightbox .content .form-filter input:text").val();
                ND.mediaSelector.setGallery(options);
                
            });
            
            /*
            $(".ND_lightbox .content .form-filter").submit(function(e) { 
            
                e.preventDefault();
                
                var text    = $("input:text", $(this)).val();
                var offset  = 0;
                var limit   = ND.mediaSelector.limit;
                ND.mediaSelector.setGallery(limit, offset, text);
                
            });
            */
            
            ND.mediaSelector.setDropZone();
            }
        );
    },


    loadMediaData: function(id) {
        // Chargement du media.
        $.get('/admin/medias/' + id, {format: "json"},
            function(data) {
				
				
				if(ND.isJSON(data)) {
                    var data = JSON.parse(data);
                } else {
                    var data = data;
                }

                data.WEBROOT = ND.WEBROOT;

                // Ajout des données dans la page.
                var template = Handlebars.compile(ND.mediaSelector.getTemplate("form-image"));
                $(".ND_lightbox .content .form").empty().append(
                    template(data)
                );
                
                $(".ND_lightbox .content .clear-form").click(function(e) { 
                    $(".ND_lightbox .content .form").empty().append("<div class=\"filedropzone\"></div>");
                    ND.mediaSelector.setDropZone();
                });
                
                // Envoi du formulaire.
                $(".ND_lightbox .content .form form").submit(function(e) {
                
                    // Stop event.
		            e.preventDefault();
		            
		            // Initialise j'objet JSON qui contient les valeurs du formulaire.
		            var formData = {};
		            
		            // Ajoute les valeurs du formulaire dans l'objet.
                    jQuery.map($(this).serializeArray(), function(n, i){
                        formData[ n['name'] ] = n['value'];
                    });
                    
                    // L'objet JSON de la meta.
                    /*
                    json["value"]   = JSON.stringify(json);

                    // La clé de la meta.
                    json["key"]     = ND.mediaSelector.key;
                    */
                    // Saving...
		            ND.mediaSelector.onSave(formData);

	            });
            }
        );
    },
    
    
    setDropZone: function() {

        var objId = ".filedropzone";

        var myDropzone = new Dropzone(objId, {
            url                 : '/admin/medias/save?format=json',
            paramName           : 'data[file]',
            thumbnail: function(file, dataUrl) {
                /* do something else with the dataUrl */
            }
        });

        myDropzone.on("totaluploadprogress", function(progress) {
            $("#progress-bar").parent().addClass("progress-striped active");
            $("#progress-bar").width(progress + "%");
            $("#progress-bar").html(progress + "%");  
        });

        myDropzone.on("maxfilesreached", function() {
            alert('Too many files added !');
        });

        myDropzone.on("dragenter", function() {
            $(objId).addClass("active");
        });

        myDropzone.on("dragleave dragend dragover", function() {
            $(objId).removeClass("active");
        });

        myDropzone.on("maxfilesexceeded", function(file) {
            alert('File ' + file.name + ' is too big !');
        });


        myDropzone.on("success", function(file, response) {
            if(ND.isJSON(response)) {
                var response = JSON.parse(response);
            }

            // Gestion d'erreur.
            if(response.error) {
                ND.alert(response.message);
                return;
            }

            $(objId).removeClass("active");
        
            ND.mediaSelector.setGallery();
            
            if(response.Media.media_id) {
                ND.mediaSelector.loadMediaData(response.Media.media_id);
            }
        });
    },
    
    save: function(media) {
    },

    close: function() 
    {
        ND.lightbox.close();
    },
    
    remove: function(element) {
        $(element).parent().fadeOut(500, function() {
            $(element).parent().remove();
        });
    }
    
} 
//------------------------------------------------------------//


//------------------------------------------------------------//
//                  PRELOAD TEMPLATES
//------------------------------------------------------------//
$.get(ND.WEBROOT + 'templates/Admin/partials/mediaselector.handlebars', function(content) {
    
    templates = $.parseHTML(content);

    $.each( templates, function( i, el ) {
        if(typeof(el.id) !== "undefined" && el.nodeName == "SCRIPT") {
            var HTML = $(el).html();
            ND.mediaSelector.templates[el.id] = HTML;
        }
    });
});
//------------------------------------------------------------//

//------------------------------------------------------------//
//                  HANDLEBARS HELPERS
//------------------------------------------------------------//
Handlebars.registerHelper('ifvalue', function (conditional, options) {
  if (options.hash.value == conditional) {
    return options.fn(this) 
  } else {
    return options.inverse(this);
  }
});
//------------------------------------------------------------//