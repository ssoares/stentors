<?php

class FormNotifications extends Cible_Form
{

    public function __construct($options = null)
    {
        // variable
        parent::__construct($options);
        $baseDir = $options['baseDir'];
        if (array_key_exists('profile', $options))
            $profile = $options['profile'];
        else
            $profile = false;


        // Module id
        $moduleId = new Zend_Form_Element_Select('NM_ModuleId');
        $moduleId->setLabel($this->getView()->getCibleText('form_label_module'))
            ->setRequired(true)
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $this->getView()->getCibleText('validation_message_empty_field'))))
            ->setAttrib('class', 'stdSelect');
        $modules = Cible_FunctionsModules::getModulesList();

        $this->addElement($moduleId);

        // Trigger event
        $event = new Zend_Form_Element_Text('NM_Event');
        $event->setLabel($this->getView()->getCibleText('form_label_event'))
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $this->getView()->getCibleText('validation_message_empty_field'))))
            ->setAttrib('class', 'stdTextInput');

        $this->addElement($event);

        // Recipient

        $recipient = new Zend_Form_Element_Text('NM_Recipient');
        $recipient->setLabel($this->getView()->getCibleText('form_label_recipient'))
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $this->getView()->getCibleText('validation_message_empty_field'))))
            ->setAttrib('class', 'stdTextInput');

        $this->addElement($recipient);

        // Static text identifier for message
        $identifier = new Zend_Form_Element_Text('NM_Message');
        $identifier->setLabel($this->getView()->getCibleText('form_label_username'))
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $this->getView()->getCibleText('validation_message_empty_field'))))
            ->setAttrib('class', 'stdTextInput')
            ->setAttrib('autocomplete', 'on');

        $this->addElement($identifier);

        // Static text identifier for the title
        $titleId = new Zend_Form_Element_Text('NM_Title');
        $titleId->setLabel($this->getView()->getCibleText('form_label_title'))
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setAttrib('class', 'stdTextInput')
            ->setAttrib('autocomplete', 'on');
        ;

        $this->addElement($titleId);

        
    }

}
