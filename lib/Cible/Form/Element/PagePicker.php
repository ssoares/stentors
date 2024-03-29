
<?php
    /** Zend_Form_Element_Xhtml */
    require_once 'Zend/Form/Element/Hidden.php';
    
    class Cible_Form_Element_PagePicker extends Zend_Form_Element_Hidden
    {
     	protected $_menu;
        protected $_associatedElement;
        protected $_onClick;
   
        public function __construct($spec, $options = null)
        {
            parent::__construct($spec, $options);
			$this->_menu = empty($options['menu']) ? '' : $options['menu'];

            $this->_associatedElement = empty($options['associatedElement']) ? '' : $options['associatedElement'];
            $this->_onClick = "javascript:assignPage(\"{$this->getId()}\",\"%PAGEID%\",\"{$this->_associatedElement}\",\"%PAGEINDEX%\");";
            
            if( !empty( $options['onclick'] ) ){
                $this->_onClick .= $options['onclick'];  
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
             
            $_baseUrl = $this->getView()->baseUrl();
            
            $this->_view->headScript()->appendFile("{$_baseUrl}/js/cible.form.element.pagepicker.js");

            $pages_list = Cible_FunctionsPages::getAllPagesDetailsArray(0, Cible_Controller_Action::getDefaultEditLanguage());

            $_pages = array();

            foreach($pages_list as $page){
                $tmp = array(
                    'ID' => $page['P_ID'],
                    'Title' => $page['PI_PageTitle'],
                    'onClick' => str_replace(array('%PAGEID%','%PAGEINDEX%'),array($page['P_ID'],$page['PI_PageIndex']), $this->_onClick)
                );

                if( !empty($page['child']) )
                    $tmp['child'] = $this->fillChildren($page['child']);

                array_push($_pages, $tmp);
            }
            
			$content = $this->getView()->collapsibleTree($_pages, array(
                'id' => $this->getId(),
                'images'=> array(
                    'close'=> $this->getView()->baseUrl() . '/themes/default/images/treeview-open.gif',
                    'open'=> $this->getView()->baseUrl() . '/themes/default/images/treeview-close.gif'
                ),
                'class' => 'collapsible_tree'
            ));
    
            foreach ($this->getDecorators() as $decorator) {
                $decorator->setElement($this);
                $content = $decorator->render($content);
            }

            return $content;
        }

        private function fillChildren($children){
            $_pages = array();

            foreach($children as $page){
                $tmp = array(
                    'ID' => $page['P_ID'],
                    'Title' => $page['PI_PageTitle'],
                    'onClick' => str_replace(array('%PAGEID%','%PAGEINDEX%'),array($page['P_ID'],$page['PI_PageIndex']), $this->_onClick)
                );

                if( !empty($page['child']) )
                    $tmp['child'] = $this->fillChildren($page['child']);

                array_push($_pages, $tmp);
            }

            return $_pages;
        }
    }
?>
