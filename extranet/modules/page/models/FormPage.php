<?php

class FormPage extends Cible_Form_Block_Multilingual
{
    public function __construct($options = null)
    {
        $this->_addSubmitSaveClose = true;
        parent::__construct($options);
        $this->setName('page');
        //$imageSrc = $options['imageSrc'];
        $pageID = $options['pageID'];
        $imageHeaderArray = $options['imageHeaderArray'];
        
        // contains the id of the page
        $id = new Zend_Form_Element_Hidden('id');
        $id->removeDecorator('Label');
        $id->removeDecorator('HtmlTag');
        
        // input text for the title of the page
        $title = new Zend_Form_Element_Text('PI_PageTitle');
        $title->setLabel($this->getView()->getCibleText('label_titre_page'))
        ->setRequired(true)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty',true,array('messages'=> Cible_Translation::getCibleText('error_field_required')))
        ->setAttrib('class','stdTextInput')
        ->setAttrib('onBlur','javascript:fillInControllerName();');
        $lblTit = $title->getDecorator('Label');
        $lblTit->setOption('class', $this->_labelCSS);
        
        // input text for the index of the page
        $uniqueIndexValidator = new Zend_Validate_Db_NoRecordExists('PagesIndex', 'PI_PageIndex');
        $uniqueIndexValidator->setMessage($this->getView()->getCibleText('label_index_already_exists'), Zend_Validate_Db_NoRecordExists::ERROR_RECORD_FOUND);

        $reservedWordValidator = new Cible_Validate_Db_NoRecordExists('Modules', 'M_MVCModuleTitle');
        $reservedWordValidator->setMessage($this->getView()->getCibleText('label_index_reserved'), Zend_Validate_Db_NoRecordExists::ERROR_RECORD_FOUND);

        $index = new Zend_Form_Element_Text('PI_PageIndex');
        $index->setLabel($this->getView()->getCibleText('label_name_controller'))
        ->setRequired(true)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addFilter('StringToLower')
        ->addValidator('NotEmpty',true,array('messages'=>Cible_Translation::getCibleText('error_field_required')))
        ->addValidator('stringLength', true, array(1,50,'messages' => array(
                                                                        Zend_Validate_StringLength::TOO_SHORT =>$this->getView()->getCibleText('label_index_more_char'),
                                                                        Zend_Validate_StringLength::TOO_LONG =>$this->getView()->getCibleText('label_index_less_char')
                                                                        )))
        ->addValidator('regex', true, array('/^[a-z0-9][a-z0-9_-]*[a-z0-9]$/', 'messages' => $this->getView()->getCibleText('label_only_character_allowed')))
        ->addValidator($uniqueIndexValidator, true)
        ->addValidator($reservedWordValidator, true)
        ->setAttrib('class','stdTextInput');
        $lblId = $index->getDecorator('Label');
        $lblId->setOption('class', $this->_labelCSS);

        // textarea for the meta and title of the page
        $metaTitle = new Zend_Form_Element_Textarea('PI_MetaTitle');
        $metaTitle->setLabel('Titre (meta)')
        ->setRequired(false)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        //->setAttrib('class','stdTextarea');
        ->setAttrib('class','stdTextareaShort');
        $lblMetaTitle= $metaTitle->getDecorator('Label');
        $lblMetaTitle->setOption('class', $this->_labelCSS);
        
        // textarea for the meta description of the page 
        $metaDescription = new Zend_Form_Element_Textarea('PI_MetaDescription');
        $metaDescription->setLabel($this->getView()->getCibleText('label_description_meta'))
        ->setRequired(false)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        //->setAttrib('class','stdTextarea');
        ->setAttrib('class','stdTextareaShort');
        $lblMetaDescr= $metaDescription->getDecorator('Label');
        $lblMetaDescr->setOption('class', $this->_labelCSS);
        
        // textarea for the meta keywords of the page
        $metaKeyWords = new Zend_Form_Element_Textarea('PI_MetaKeywords');
        $metaKeyWords->setLabel($this->getView()->getCibleText('label_keywords_meta'))
        ->setRequired(false)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        //->setAttrib('class','stdTextarea');
        ->setAttrib('class','stdTextareaShort');
        $lblMetaKey= $metaKeyWords->getDecorator('Label');
        $lblMetaKey->setOption('class', $this->_labelCSS);

        // textarea for the meta keywords of the page
        $metaOthers = new Zend_Form_Element_Textarea('PI_MetaOther');
        $metaOthers->setLabel($this->getView()->getCibleText('label_other_meta'))
        ->setRequired(false)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        //->setAttrib('class','stdTextarea');
        ->setAttrib('class','stdTextareaShort');
        $lblMetaOther= $metaOthers->getDecorator('Label');
        $lblMetaOther->setOption('class', $this->_labelCSS);
        
        // select box for the templates
        $layout = new Zend_Form_Element_Select('P_LayoutID');
        $layout->setLabel($this->getView()->getCibleText('label_layout_page'))
               ->setAttrib('class','stdSelect');
        
        // select box for the templates
        $template = new Zend_Form_Element_Select('P_ViewID');
        $template->setLabel($this->getView()->getCibleText('label_model_page'))
        ->setAttrib('class','stdSelect');
        
        
        // checkbox for the status (0 = offline, 1 = online)
        $status = new Zend_Form_Element_Checkbox('PI_Status');
        $status->setValue(1);
        $status->setLabel($this->getView()->getCibleText('form_check_label_online'));
        $status->setDecorators(array(
            'ViewHelper',
            array('label', array('placement' => 'append')),
            array(array('row' => 'HtmlTag'), array('tag' => 'dd', 'class' => 'label_after_checkbox')),
        ));
        
        // checkbox for the show title of the page (0 = offline, 1 = online)
        $showTitle = new Zend_Form_Element_Checkbox('P_ShowTitle');
        $showTitle->setValue(1);
        $showTitle->setLabel($this->getView()->getCibleText('form_check_label_show_title'));
        $showTitle->setDecorators(array(
            'ViewHelper',
            array('label', array('placement' => 'append')),
            array(array('row' => 'HtmlTag'), array('tag' => 'dd', 'class' => 'label_after_checkbox')),
        ));                           
        
        
        // image group
        // ImageSrc
        /*$imageSrc = new Zend_Form_Element_Select('P_BannerGroupID');
        $imageSrc->setLabel($this->getView()->getCibleText('form_banner_image_group_extranet'))->setAttrib('class','stdSelect');
        $imageSrc->addMultiOption('', 'Sans image');

        $group = new GroupObject();
        $groupArray = $group->groupCollection();
        foreach ($groupArray as $group1)
        {
            $imageSrc->addMultiOption($group1['BG_ID'],$group1['BG_Name']);
        }*/
        
        
        // page image
        $imageSrc = new Zend_Form_Element_Select('PI_TitleImageSrc');
        $imageSrc->setLabel("Image de l'entête")->setAttrib('class','stdSelect');
        $imageSrc->addMultiOption('', 'Sans image');
        $i = 1;
        foreach($imageHeaderArray as $img => $path)
        {
            $imageSrc->addMultiOption($path, $path);
            $i++;
        }
        
        $altImage = new Zend_Form_Element_Text('PI_AltPremiereImage');
        $altImage->setLabel($this->getView()->getCibleText('label_altFirstImage'))        
            ->setAttrib('class','stdTextInput');        
        

        // add element to the form
        $this->addElements(array($title, $index, $status, $showTitle, $layout, $template, $imageSrc, $altImage, $metaTitle, $metaDescription, $metaKeyWords, $metaOthers, $id));


    }
}
