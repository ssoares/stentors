<?php
    
    class FormNews extends Cible_Form_Multilingual
    {
        public function __construct($options = null)
        {
            $this->_addSubmitSaveClose = true;
            parent::__construct($options);

            $imageSrc   = $options['imageSrc'];
            $newsID     = $options['newsID'];
            $isNewImage = $options['isNewImage'];
            $catagoryID = "";
            if(isset($options['catagoryID'])){
                $catagoryID = $options['catagoryID'];
            }

            if($newsID == '')
                $pathTmp = "../../../../../data/images/news/tmp";
            else
                $pathTmp = "../../../../../data/images/news/$newsID/tmp";


            // hidden specify if new image for the news
            $newImage = new Zend_Form_Element_Hidden('isNewImage', array('value'=>$isNewImage));
            $newImage->removeDecorator('Label');
            $this->addElement($newImage);

            // Title
            $title = new Zend_Form_Element_Text('Title');
            $title->setLabel($this->getView()->getCibleText('form_label_title'))
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $this->getView()->getCibleText('validation_message_empty_field'))))
            ->setAttrib('class','stdTextInput');

            $label = $title->getDecorator('Label');
            $label->setOption('class', $this->_labelCSS);

            $this->addElement($title);

            if(isset($options['categoriesList']) && $options['categoriesList'] == 'true')
            {
                // select box category (Parameter #1)
                $categoryNews = new Zend_Form_Element_Select('Param1');
                $categoryNews->setLabel($this->getView()->getCibleText('form_label_news_category'))
                    ->setValue($catagoryID)
                    ->setAttrib('class','largeSelect');

                $categories = new Categories();
                $select = $categories->select()->setIntegrityCheck(false)
                                     ->from('Categories')
                                     ->join('CategoriesIndex', 'C_ID = CI_CategoryID')
                                     ->where('C_ModuleID = ?', 2)
                                     ->where('CI_LanguageID = ?', Zend_Registry::get("languageID"))
                                     ->order('CI_Title');

                $categoriesArray = $categories->fetchAll($select);

                foreach ($categoriesArray as $category){
                    $categoryNews->addMultiOption($category['C_ID'],$category['CI_Title']);
                }

                $this->addElement($categoryNews);
            }

            // Date picker
            $date = new Cible_Form_Element_DatePicker('Date', array('jquery.params'=> array('changeYear'=>true, 'changeMonth'=> true)));

            $date->setLabel($this->getView()->getCibleText('form_label_date'))
            ->setRequired(true)
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $this->getView()->getCibleText('validation_message_empty_field'))))
            ->addValidator('Date', true, array('messages' => array( 'dateNotYYYY-MM-DD' => $this->getView()->getCibleText('validation_message_invalid_date'),
                                                                    'dateInvalid' => $this->getView()->getCibleText('validation_message_invalid_date'),
                                                                    'dateFalseFormat' => $this->getView()->getCibleText('validation_message_invalid_date')
                                                                )));
            $this->addElement($date);

            // Date picker
            $datePicker = new Cible_Form_Element_DatePicker('ReleaseDate', array('jquery.params'=> array('changeYear'=>true, 'changeMonth'=> true)));

            $datePicker->setLabel($this->getView()->getCibleText('form_extranet_news_label_releaseDate'))
            ->setRequired(true)
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $this->getView()->getCibleText('validation_message_empty_field'))))
            ->addValidator('Date', true, array('messages' => array( 'dateNotYYYY-MM-DD' => $this->getView()->getCibleText('validation_message_invalid_date'),
                                                                    'dateInvalid' => $this->getView()->getCibleText('validation_message_invalid_date'),
                                                                    'dateFalseFormat' => $this->getView()->getCibleText('validation_message_invalid_date')
                                                                )));
            $this->addElement($datePicker);

            // Status
            $status = new Zend_Form_Element_Checkbox('Status');
            $status->setLabel('Nouvelle en ligne');
            $status->setDecorators(array(
                'ViewHelper',
                array('label', array('placement' => 'append')),
                array(array('row' => 'HtmlTag'), array('tag' => 'dd', 'class' => 'label_after_checkbox')),
            ));

            $this->addElement($status);


            // IMAGE
            $imageTmp  = new Zend_Form_Element_Hidden('ImageSrc_tmp');
            $imageTmp->removeDecorator('Label');
            $this->addElement($imageTmp);

            $imageOrg  = new Zend_Form_Element_Hidden('ImageSrc_original');
            $imageOrg->removeDecorator('Label');
            $this->addElement($imageOrg);

            $imageView = new Zend_Form_Element_Image('ImageSrc_preview', array('onclick'=>'return false;'));
            $imageView->setImage($imageSrc);
            $this->addElement($imageView);

            $imagePicker = new Cible_Form_Element_ImagePicker('ImageSrc', array( 'onchange' => "document.getElementById('imageView').src = document.getElementById('ImageSrc').value",
                                                                                    'associatedElement' => 'ImageSrc_preview',
                                                                                    'pathTmp'=>$pathTmp,
                                                                                    'contentID'=>$newsID

                                                                        ));
            $imagePicker->removeDecorator('Label');
            $this->addElement($imagePicker);

            $imageAlt = new Zend_Form_Element_Text("ImageAlt");
            $imageAlt->setLabel($this->getView()->getCibleText('form_label_description_image'))
            ->setAttrib('class','stdTextInput');

            $this->addElement($imageAlt);

            // Breif text
            $breif = new Cible_Form_Element_Editor('Brief', array('mode' => Cible_Form_Element_Editor::ADVANCED));
            $breif->setLabel($this->getView()->getCibleText('form_label_short_text'))
            ->setRequired(true)
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => Cible_Translation::getCibleText('validation_message_empty_field'))))
            ->setAttrib('class','mediumEditor');
            $breif->setDecorators(array(
                'ViewHelper',
                array('Errors', array('placement' => 'prepend')),
                array('label', array('placement' => 'prepend')),
            ));

            $label = $breif->getDecorator('Label');
            $label->setOption('class', $this->_labelCSS);

            $this->addElement($breif);

            // Text
            $text = new Cible_Form_Element_Editor('Text', array('mode'=>Cible_Form_Element_Editor::ADVANCED));
            $text->setLabel($this->getView()->getCibleText('form_label_text'))
            ->setRequired(true)
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => Cible_Translation::getCibleText('validation_message_empty_field'))))
            ->setAttrib('class','mediumEditor')
            ->setDecorators(array(
                'ViewHelper',
                array('Errors', array('placement' => 'prepend')),
                array('label', array('placement' => 'prepend')),
            ));
            $label = $text->getDecorator('Label');
            $label->setOption('class', $this->_labelCSS);

            $this->addElement($text);

            $categoryID = new Zend_Form_Element_Hidden('CategoryID');

            $this->addElement($categoryID);
        }
    }
?>
