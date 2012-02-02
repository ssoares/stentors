
<?php
    /** Zend_Form_Element_Xhtml */
    require_once 'Zend/Form/Element/Hidden.php';
    
    class Cible_Form_Element_ImagePicker extends Zend_Form_Element_Hidden
    {
        public function __construct($spec, $options = null)
        {
            parent::__construct($spec, $options);
            
            $this->associatedElement    = $options['associatedElement'];  
            $this->pathTmp              = $options['pathTmp'];
            $this->contentID            = $options['contentID'];
        }
        /**
         * Render form element
         * 
         * @param  Zend_View_Interface $view 
         * @return string
         */
        public function render(Zend_View_Interface $view = null)
        {
            
            //$_id = $this->getId();
            $_lang = Zend_Registry::get('languageID') == 1 ? 'fr' : 'en';
            
            $_baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
            $defaultImage = $_baseUrl . "/icons/image_non_ disponible.jpg";
            
            $this->_view->headScript()->appendFile($this->getView()->baseUrl().'/js/tiny_mce/plugins/imagemanager/js/mcimagemanager.js');
            if (null !== $view) {
                $this->setView($view);
            }

            $content = '';
            
            $content .= '
                <script>
                    function separateImage($associatedElement, $defaultImage, $imagePicker){
                        document.getElementById($associatedElement).src = $defaultImage;
                        document.getElementById($imagePicker).value = "";                
                    }
                </script>';
                
            $content .= "<a href=\"javascript:mcImageManager.upload({fields : '".$this->getId()."', 
                        path : '".$this->pathTmp."',
                        insert_filter : function (data){
                        },
                        onupload :  function(info) {
                                        /*alert(dump(info)); */
                                        document.getElementById('".$this->getId()."_tmp').value = info.files[0].custom.thumbnail_url;
                                        document.getElementById('".$this->getId()."_original').value = info.files[0].url;
                                        document.getElementById('".$this->getId()."_preview').src = info.files[0].custom.thumbnail_url;
                                        document.getElementById('".$this->getId()."').value = info.files[0].name;
                                    }});\">[Parcourir]</a>";
            
            //$content .= "<a href=\"javascript:mcImageManager.browse({fields : '".$this->getId()."', no_host : true});\">[Parcourir]</a>";
            $content .=  "&nbsp;&nbsp;<img class='action_icon' alt='Supprimer' src='".$_baseUrl."/icons/del_icon_16x16.png' onclick='separateImage(\"".$this->associatedElement."\",\"".$defaultImage."\",\"".$this->getId()."\")' />";
                
            foreach ($this->getDecorators() as $decorator) {
                $decorator->setElement($this);
                $content = $decorator->render($content);
            }
            
            return $content;
        }
    }
?>
