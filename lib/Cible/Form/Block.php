<?php
    class Cible_Form_Block extends Cible_Form_Block_Multilingual
    {   
        protected $_moduleName = '';
        
        public function __construct($options = null)
        {
            parent::__construct($options);
            
            $this->setAttrib('class', 'form_block');
            
            $baseDir = $options['baseDir'];
            $pageID = $options['pageID'];
            
            // contains the id of the page
            $id = new Zend_Form_Element_Hidden('id');
            $id->removeDecorator('Label');
            $id->removeDecorator('HtmlTag');
            
            $this->addElement($id);
            
            if(isset($options['moduleName']))
                $this->setName($options['moduleName']);
          
            // input text for the title of the text module
            $blockTitle = new Zend_Form_Element_Text('BI_BlockTitle');
            $blockTitle->setLabel($this->getView()->getCibleText('form_label_title'))
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $this->getView()->getCibleText('validation_message_empty_field'))))
            ->setAttrib('class','stdTextInput');
            
            $this->addElement($blockTitle);
            
            // select box for the position of the module
            $security = new Zend_Form_Element_Select('B_Secured');
            $security->setLabel($this->getView()->getCibleText('manage_block_secured_status'))    
                ->setAttrib('class','stdSelect')
                ->addMultiOption('0', $this->getView()->getCibleText('manage_block_secured_none'))
                ->addMultiOption('1', $this->getView()->getCibleText('manage_block_secured_logged'))
                ->addMultiOption('2', $this->getView()->getCibleText('manage_block_secured_notlog'))
                ->setRegisterInArrayValidator(false);

            $this->addElement($security);
            
            // checkbox for determine if show block title in frontend
            $showBlockTitle = new Zend_Form_Element_Hidden('B_ShowHeader');
            $showBlockTitle->removeDecorator('Label');
            $showBlockTitle->setValue(0);
                              
            $this->addElement($showBlockTitle);
            
            $_request = Zend_Controller_Front::getInstance()->getRequest();
            $_action = $_request->getActionName();
            
            if( $_action == 'add-block'){
                
                // select box for the zone of the module
                $zone = new Zend_Form_Element_Select('B_ZoneID');
                $zone->setLabel($this->getView()->getCibleText('form_label_zone'))    
                ->setAttrib('class','largeSelect');
                
                $this->addElement($zone);
                
                // select box for the position of the module
                $position = new Zend_Form_Element_Select('B_Position');
                $position->setLabel($this->getView()->getCibleText('form_label_position'))    
                ->setAttrib('class','largeSelect')
                ->setRegisterInArrayValidator(false);
                
                $this->addElement($position);
            }
            
            // submit button  (save)
            $submit = new Zend_Form_Element_Submit('submit');
            $submit->setLabel($this->getView()->getCibleText('button_add'))
                    ->setAttrib('id', 'submitSave')
                    ->setAttrib('class','stdButton')
                    ->removeDecorator('DtDdWrapper')
                    ->removeDecorator('Label');
            
            $this->addElement($submit);
            
            // cancel button (don't save and return to the main page)
            $cancel = new Zend_Form_Element_Button('cancel');
            
            $cancel->setLabel($this->getView()->getCibleText('button_cancel'))
                    ->setAttrib('class','stdButton')
                    ->setAttrib('onclick', "document.location.href='$baseDir/page/manage/index/ID/$pageID'")
                    ->removeDecorator('DtDdWrapper')
                    ->removeDecorator('Label');
            
            $this->addElement($cancel);
            
            // create an action display group with element name previously added to the form
            $this->addDisplayGroup(
                array('submit', 'cancel'),
                'actions'
            );
            
            // Set the decorators we want for the display group
            $this->setDisplayGroupDecorators(array('FormElements', 'Fieldset',array('HtmlTag', array('tag' => 'dd')),));
            
            $viewSelector = new Zend_Form_Element_Select('Param999');
            $viewSelector->setLabel( $this->getView()->getCibleText('form_select_label_associated_view') )
                        ->setAttrib('class','stdSelect')
                        ->setOrder(1);
                         
            
            if( $this->_moduleName ){
                foreach(Cible_FunctionsModules::getAvailableViews( $this->_moduleName ) as $view){
                    $viewSelector->addMultiOption( $view['MV_Name'], $this->getView()->getCibleText("form_select_option_view_{$this->_moduleName}_{$view['MV_Name']}") );
                }       
            }
            
            if( count($viewSelector->options) == 0)
                $viewSelector->addMultiOption('index',$this->getView()->getCibleText("form_select_option_view_default_index"));
            
            $this->addElement($viewSelector);
            
            $this->addDisplayGroup(array('Param999'),'parameters');
            $parameters = $this->getDisplayGroup('parameters');
            //$parameters->setLegend($this->_view->getCibleText('form_parameters_fieldset'));
        }
    }
?>
