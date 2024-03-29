<?php
    class FormBlockEvents extends Cible_Form_Block
    {
        protected $_moduleName = 'events';
        
        public function __construct($options = null)
        {
            $baseDir = $options['baseDir'];
            $pageID = $options['pageID'];
            
            parent::__construct($options);
            
            /****************************************/
            // PARAMETERS
            /****************************************/
            
            // select box category (Parameter #1)
            $blockCategory = new Zend_Form_Element_Select('Param1');
            $blockCategory->setLabel('Catégorie d\'évènement de ce bloc')
            ->setAttrib('class','largeSelect');
            
            $categories = new Categories();
            $select = $categories->select()->setIntegrityCheck(false)
                                 ->from('Categories')
                                 ->join('CategoriesIndex', 'C_ID = CI_CategoryID')
                                 ->where('C_ModuleID = ?', 7)
                                 ->where('CI_LanguageID = ?', Zend_Registry::get("languageID"))
                                 ->order('CI_Title');
            
            $categoriesArray = $categories->fetchAll($select);
            
            foreach ($categoriesArray as $category){
                $blockCategory->addMultiOption($category['C_ID'],$category['CI_Title']); 
            }
            
            // number of news to show in front-end (Parameter #2)
            $at_least_one = new Zend_Validate_GreaterThan('0');
            $at_least_one->setMessage('Vous devez afficher au moins un événement.');
            
            $not_null = new Zend_Validate_NotEmpty();
            $not_null->setMessage( $this->getView()->getCibleText('validation_message_empty_field') );
            
            $blockNewsMax = new Zend_Form_Element_Text('Param2');
            $blockNewsMax->setLabel('Nombre d\'évènement à afficher')
                         ->setRequired(true)
                         ->setValue('1')
                         ->addValidator($not_null,true)
                         ->addValidator($at_least_one, true)
                         ->setAttrib('class','smallTextInput');
          
            // show the breif text in front-end (Parameter #3)
            $blockShowBrief = new Zend_Form_Element_Checkbox('Param3');
            $blockShowBrief->setLabel('Afficher le texte bref');
            $blockShowBrief->setDecorators(array(
                'ViewHelper',
                array('label', array('placement' => 'append')),
                array(array('row' => 'HtmlTag'), array('tag' => 'dd', 'class' => 'label_after_checkbox')),
            ));
            
            $this->addElements(array($blockCategory, $blockNewsMax, $blockShowBrief));
            
            $this->removeDisplayGroup('parameters');
            
            $this->addDisplayGroup(array('Param999', 'Param1', 'Param2','Param3'),'parameters');
            $parameters = $this->getDisplayGroup('parameters');
            //$parameters->setLegend($this->_view->getCibleText('form_parameters_fieldset'));
        }
    }
?>
