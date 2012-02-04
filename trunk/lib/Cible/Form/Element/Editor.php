<?php
    /** Zend_Form_Element_Xhtml */
    require_once 'Zend/Form/Element/Textarea.php';

    class Cible_Form_Element_Editor extends Zend_Form_Element_Textarea
    {
        /**
        * Use formTextarea view helper by default
        * @var string
        */
        //public $helper = 'formEditor';

        const SIMPLE = 'simple';
        const ADVANCED = 'advanced';

        protected $_mode = 'simple';
        protected $_script;

        protected $_request;

        /**
         * Constructor
         *
         * $spec may be:
         * - string: name of element
         * - array: options with which to configure element
         * - Zend_Config: Zend_Config with options for configuring element
         *
         * @param  string|array|Zend_Config $spec
         * @return void
         * @throws Zend_Form_Exception if no element name after initialization
         */
        public function __construct($spec, $options = null)
        {
            parent::__construct($spec, $options);

            if( !empty($options['mode']) )
            {
                $this->_mode = $options['mode'];
            }

            $fc = Zend_Controller_Front::getInstance();
            $this->_request = $fc->getRequest();

            if( null === $this->_view)
                $this->setView($this->getView());

            $_id = $this->getId();
            if(!empty($options['subFormID']))
                $_id = $options['subFormID'] . "-" . $_id;

            $_lang = Zend_Registry::get('languageID') == 1 ? 'fr' : 'en';
            $_cssPath = Zend_Registry::get('www_root') . '/themes/default/css/integration.css';
            $mediaPath = Zend_Registry::get('www_root') . "/data/images/lists/media_list.js";

            $this->_script = <<< EOS

            tinyMCE.init({
                // General options
                relative_urls : false,
                remove_script_host : true,
                doctype: "<!DOCTYPE html>",

                extended_valid_elements : "iframe[src|width|height|name|align|frameborder|marginheight|marginwidth]",
                extended_valid_elements : "a[href|charset|coords|hreflang|class|data-show-count|name|rel|rev|shape|target|title]",
                //cleanup : false,

                mode : "exact",
                elements : "{$_id}",
                theme : "{$this->_mode}",
                plugins : "imagemanager, filemanager,safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
                language : "{$_lang}",
                paste_text_use_dialog : true,

                 // Theme options
                theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontsizeselect,|,forecolor,backcolor",
                theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,insertdate,inserttime",
                theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,media,advhr,|,print,fullscreen",
                theme_advanced_buttons4 : "cite,abbr,acronym,del,ins,|,visualchars,nonbreaking,pagebreak",
                theme_advanced_toolbar_location : "top",
                theme_advanced_toolbar_align : "left",
                theme_advanced_statusbar_location : "bottom",
                theme_advanced_resizing : false,

                // Style formats
                style_formats : [
                        {title : 'Bold text', inline : 'b'},
                        {title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
                        {title : 'Header styles'},
                        {title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
                        {title : 'Example 1', block : 'span', classes : 'example1'},
                        {title : 'Example 2', inline : 'span', classes : 'example2'},
                        {title : 'Table styles'},
                        {title : 'Table row 1', selector : 'tr', classes : 'tablerow1'},
                        {title : 'Images'},
                        {title : 'Agrandissement photo', selector : 'img', classes : 'add_prettyphoto'},
                        {title : 'Bouton'},
                        {title : 'Bouton lien', block : 'div', classes : 'grayish-button2'}
                ],

                formats : {
                        alignleft : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'left'},
                        aligncenter : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'center'},
                        alignright : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'right'},
                        alignfull : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'full'},
                        bold : {inline : 'span', 'classes' : 'bold'},
                        italic : {inline : 'span', 'classes' : 'italic'},
                        underline : {inline : 'span', 'classes' : 'underline', exact : true},
                        strikethrough : {inline : 'del'},
                        customformat : {inline : 'span', styles : {color : '#00ff00', fontSize : '20px'}, attributes : {title : 'My custom format'}}
                },

                // Example content CSS (should be your site CSS)
                content_css : "{$_cssPath}",
                theme_advanced_styles : "",
                theme_advanced_blockformats : "p,h2,h3",

                // Drop lists for link/image/media/template dialogs
                template_external_list_url : "lists/template_list.js",
                external_link_list_url : "lists/link_list.js",
                external_image_list_url : "lists/image_list.js",
                media_external_list_url : "{$mediaPath}"
            });
EOS;

            if( $this->_request->isXmlHttpRequest() ){

                $this->getView()->inlineScript()->appendScript($this->_script);
            }

        }

        /**
         * Render form element
         *
         * @param  Zend_View_Interface $view
         * @return string
         */
        public function render(Zend_View_Interface $view = null)
        {
            if( null === $this->_view)
                $this->setView($this->getView());

            if( !$this->_request->isXmlHttpRequest() ){
                $this->_view->headScript()->appendScript($this->_script);
            }


            $content = '';
            foreach ($this->getDecorators() as $decorator) {
                $decorator->setElement($this);
                $content = $decorator->render($content);
            }
            return $content;
        }
    }
