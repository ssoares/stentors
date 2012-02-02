<?php

class FormImage extends Cible_Form_Block_Multilingual
{

    public function __construct($options = null)
    {
        $this->_addSubmitSaveClose = false;
        parent::__construct($options);

        $galleryID = $options['galleryID'];
        $imageSrc = "";

        // show online
        $showOnline = new Zend_Form_Element_Checkbox('GI_Online');
        $showOnline->setValue(1);
        $showOnline->setLabel($this->getView()->getCibleText('form_label_showOnline'));
        $showOnline->setDecorators(array(
            'ViewHelper',
            array('label', array('placement' => 'append')),
            array(array('row' => 'HtmlTag'), array('tag' => 'dd', 'class' => 'label_after_checkbox')),
        ));
        $this->addElement($showOnline);

        // image position
        $position = new Zend_Form_Element_Text('GI_Position');
        $position->setLabel($this->getView()->getCibleText('form_label_position'));
        $position->setRequired(true);
        $position->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $this->getView()->getCibleText('validation_message_empty_field'))));
        $position->addValidator('Int', true, array('messages' => array('notInt' => $this->getView()->getCibleText('validation_message_int_field'))));
        $this->addElement($position);

        // IMAGE
        /*
          $imageSrc = "";
          $imageView = new Zend_Form_Element_Image('imageView',array('src' => $imageSrc));
          $imageView->setOrder(4);

          $this->addElement($imageView);
         */
        if ($galleryID <> "")
        {
            $imageTmp = new Zend_Form_Element_Hidden('ImageSrc_tmp');
            $imageTmp->setDecorators(array(
                'ViewHelper',
                array(array('row' => 'HtmlTag'), array('tag' => 'dd', 'openOnly' => true))
            ));
            $this->addElement($imageTmp);

            $imageOrg = new Zend_Form_Element_Hidden('ImageSrc_original');
            $imageOrg->setDecorators(array(
                'ViewHelper',
            ));
            $this->addElement($imageOrg);

            $imageView = new Zend_Form_Element_Image('ImageSrc_preview', array('src' => $imageSrc, 'onclick' => 'return false;'));
            $imageView->setDecorators(array(
                'ViewHelper',
                array(array('row' => 'HtmlTag'), array('tag' => 'dd', 'closeOnly' => true))
            ));
            $this->addElement($imageView);

            $imagePicker = new Cible_Form_Element_ImageGalleryPicker('ImageSrc', array('associatedElement' => 'imageView',
                    'galleryID' => $galleryID
                ));
            $imagePicker->removeDecorator('label');
            $this->addElement($imagePicker);
        }

        // Title
        $title = new Zend_Form_Element_Text('II_Title');
        $title->setLabel($this->getView()->getCibleText('form_label_title'))
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $this->getView()->getCibleText('validation_message_empty_field'))))
            ->setAttrib('class', 'stdTextInput');

        $this->addElement($title);

        // Description
        $title = new Zend_Form_Element_Text('II_Description');
        $title->setLabel($this->getView()->getCibleText('form_label_description'))
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $this->getView()->getCibleText('validation_message_empty_field'))))
            ->setAttrib('class', 'stdTextInput');

        $this->addElement($title);
    }

}
?>
