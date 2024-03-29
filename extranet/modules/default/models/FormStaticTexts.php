<?php
    
    class FormStaticTexts extends Cible_Form_Multilingual
    {
        public function __construct($options = null)
        {
            parent::__construct($options);
            $baseDir = $options['baseDir'];
            $identifierID = $options['identifierID'];
                         
            
            // tinymce editor for the text online
            $draftText = new Cible_Form_Element_Editor('ST_Value', array('mode'=>Cible_Form_Element_Editor::ADVANCED));
            $draftText->setLabel($this->getView()->getCibleText('list_column_ST_Value'))
                //->setRequired(true)
                //->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $this->getView()->getCibleText('validation_message_empty_field'))))
                //->setAttrib('class','largeEditor');
                ->setAttrib('class','mediumEditor');
                            
            // Adds all elements to the form
            $this->addElements( array($draftText));                         
        }
    }
?>
