(function(){

    var ND = {
        WEBROOT : "/",
        alert: function (msg) {

            var html = '<div class="alert alert-error">';
            html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html += msg;
            html += '</div>';
            
            $(body).append('<div class="alert_wrapper"></div>');
            $(html).appendTo(".alert_wrapper");
        },

        isJSON: function(str) {
            try {
                JSON.parse(str);
            } catch (e) {
                return false;
            }
            return true;
        },
        
        accentsTidy: function(s){
                var r=s.toLowerCase();
                //r = r.replace(new RegExp("\\s", 'g'),"");
                r = r.replace(new RegExp("[àáâãäå]", 'g'),"a");
                r = r.replace(new RegExp("æ", 'g'),"ae");
                r = r.replace(new RegExp("ç", 'g'),"c");
                r = r.replace(new RegExp("[èéêë]", 'g'),"e");
                r = r.replace(new RegExp("[ìíîï]", 'g'),"i");
                r = r.replace(new RegExp("ñ", 'g'),"n");                            
                r = r.replace(new RegExp("[òóôõö]", 'g'),"o");
                r = r.replace(new RegExp("œ", 'g'),"oe");
                r = r.replace(new RegExp("[ùúûü]", 'g'),"u");
                r = r.replace(new RegExp("[ýÿ]", 'g'),"y");
                r = r.replace(new RegExp("\\W", 'g'),"-");
                return r;
        },

        confirm: function(msg, event) {

            event.preventDefault();

            var url = event.currentTarget;

            var html = '<div style="padding-top: 50px">';
            html += '<p align="center"><strong>' + msg + '</strong></p>';
            html += '<p align="center">';
            html += '<a href="#" class="btn btn-danger si" id="confirm_yes">Si</a>';
            html += ' <a href="#" class="btn btn-default" id="confirm_no">No</a>';
            html += '</p>';
            html += '</div>';

            ND.lightbox.init(html, {
                css: {
                    "width": "400",
                    "height": "150",
                    "top": "50%",
                    "left": "50%",
                    "margin-top": -75,
                    "margin-left": -200
                }
            });

            ND.lightbox.complete = function(){
                $("#confirm_yes").click(function(e){
                    e.preventDefault();
                    ND.lightbox.close();
                    window.location.href = url;
                });

                $("#confirm_no").click(function(e){
                    ND.lightbox.close();
                    e.preventDefault();
                });
            };
        },

        setTagsAutocomplete: function(obj, url) {

            var tagList = new Bloodhound({
              datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
              queryTokenizer: Bloodhound.tokenizers.whitespace,
              limit: 10,
              prefetch: {
                url: url,
                filter: function(list) {
                  return $.map(list, function(tag) { return { name: tag }; });
                }
              }
            });
            tagList.initialize();

            var tags = obj;
            tags.tagsinput();

            tags.tagsinput('input').typeahead(null, {
                name: 'tags',
                displayKey: 'name',
                source: tagList.ttAdapter()
            }).bind('typeahead:selected', $.proxy(function (obj, datum) { 
                tags.tagsinput('add', datum.name);
                tags.tagsinput('input').val("");
                //tags.tagsinput('input').typeahead('setQuery', '');
            }, obj));

        },

        setDropzone: function(objId) {

            var myDropzone = new Dropzone(objId, {
                url                 : '/admin/medias/save?format=json',
                paramName           : 'file',
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

                $(objId).removeClass("active");

                //console.log(file);
                // Gestion d'erreur.
                if(response.error) {
                    APW.alert(response.message);
                    return;
                }

                $.get(
                    document.URL,
                    {format: "json"},
                    function(data) {

                        if(ND.isJSON(data)) {
                            var JsonData = JSON.parse(data);
                        } else {
                            var JsonData = data;
                        }

                        JsonData.WEBROOT    = ND.WEBROOT;

                        $.get(ND.WEBROOT + 'templates/Admin/partials/medias.index.row.handlebars', function(content) {
                            var template = Handlebars.compile(content);
                            $("#medias-table").html(template(JsonData));
                            $("#pagination").html(JsonData.pagination);
                        });

                        $(".confirm").click(function(e){
                            ND.confirm("Borrar este media ?", e);
                        });
                    }
                );
            });

        },

        setMetasSelector: function(metaName, targetEl)
        {
            ND.mediaSelector.init({
              onSave : function(data) {
                
                var Meta    = data;
                Meta.data   = JSON.stringify(Meta);
                Meta.mkey   = metaName;

                $.get(ND.WEBROOT + 'templates/Admin/partials/media.row.handlebars', function(template) {
                    var template = Handlebars.compile(template);
                    targetEl.append(template(data));
                });

                ND.mediaSelector.close();
              }
            });
        },

        setEditorMediasSelector: function(ckInstance)
        {
            // Get CKEditor instance
              var oEditor = null;
              for(var i in CKEDITOR.instances) {
                if(CKEDITOR.instances[i].name == ckInstance) {
                  oEditor = CKEDITOR.instances[i];
                }
              }

              ND.mediaSelector.init({
                fieldName : "media",
                onSave : function(data) {

                  if(data.mime_type != "") {

                    var split = data.mime_type.split('/');

                    switch(split[0]) {
                        case "image":
                            var element = CKEDITOR.dom.element.createFromHtml('<img src="/web/uploads/medias/'+ data.file + '" />');
                        break;
                    }

                    oEditor.insertElement(element);
                  }

                  ND.mediaSelector.close();
                }
              });
        }
    }

    if(!window.ND){
	    window.ND=ND;
    }

})();