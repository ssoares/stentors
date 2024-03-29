<?php
    
    class FormTextzoneForm extends Cible_Form
    {
        /**
         *
         * @param array $options Options to build the form
         */
        public function __construct($options = null)
        {

            parent::__construct($options);
          
            // Text
            $text = new Cible_Form_Element_Editor('FTI_Text', array('mode'=>Cible_Form_Element_Editor::ADVANCED));
            $text->setLabel($this->getView()->getCibleText('form_label_text'))
                 ->setAttrib('class','mediumEditor');

            $this->addElement($text);
        }
    }
?>
