/**
 * Load and intialize tinymce editor.
 * This will replace a textarea by the tinymce wysiwyg editor.
 *
 * @param string id    Name of the textarea to associate with.
 * @apram string theme Type of editor theme (advanced...)
 *
 * @return void
 */
var loadTinyMce = {
    load: function (id,theme){
        tinyMCE.init({
            // General options
            relative_urls : false,
            remove_script_host : true,

            extended_valid_elements : "iframe[src|width|height|name|align|frameborder|marginheight|marginwidth]",
            //cleanup : false,

            mode : "exact",
            //elements : "{$_id}",
            elements : id,
            //theme : "{$this->_mode}",
            theme : theme,
            plugins : "imagemanager,filemanager,safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
            //language : "{$_lang}",
            language : "fr",

            // Theme options
            theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontsizeselect,|,forecolor,backcolor",
            theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,insertdate,inserttime",
            theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,media,advhr,|,print,fullscreen",
            theme_advanced_buttons4 : "cite,abbr,acronym,del,ins,|,visualchars,nonbreaking,pagebreak",
            theme_advanced_toolbar_location : "top",
            theme_advanced_toolbar_align : "left",
            theme_advanced_statusbar_location : "bottom",
            theme_advanced_resizing : false,

            // Example content CSS (should be your site CSS)
            //content_css : "{$_cssPath}",
            content_css : "/ss/edith/www/themes/default/css/integration.css",
            theme_advanced_styles : "Grand titre blue =h2_large_blue;Grand titre orange =h2_large_orange;Grand titre rouge =h2_large_red;Moyen titre rouge =h2_medium_red;Grand texte gris =large_gray_text;Lien - TÚlÚchargement =telechargement;Ligne de tableau - Pair=even;Ligne de tableau - Impaire=odd",
            theme_advanced_blockformats : "p,h2",

            // Drop lists for link/image/media/template dialogs
            template_external_list_url : "lists/template_list.js",
            external_link_list_url : "lists/link_list.js",
            external_image_list_url : "lists/image_list.js",
            media_external_list_url : "lists/media_list.js"
        });
    }
};
