<?php
    
class FormBannerImage extends Cible_Form_Multilingual
{
    public function __construct($options = null)
    {
        parent::__construct($options);



        $imageSrc   = $options['imageSrc'];
        $dataId     = $options['dataId'];
        $imgField   = $options['imgField'];
        $isNewImage = $options['isNewImage'];
        $moduleName = $options['moduleName'];     
        

        if ($dataId == '')
            $pathTmp = "../../../../../data/images/"
                    . $moduleName . "/tmp";
        else
            $pathTmp = "../../../../../data/images/"
                    . $moduleName . "/". $dataId . "/tmp";


        // hidden specify if new image for the news
        $newImage = new Zend_Form_Element_Hidden('isNewImage', array('value' => $isNewImage));
        $newImage->removeDecorator('Label');
        $this->addElement($newImage);

        // Image for the product line
        $imageTmp  = new Zend_Form_Element_Hidden($imgField . '_tmp');
        $imageTmp->removeDecorator('Label');
        $this->addElement($imageTmp);

        $imageOrg  = new Zend_Form_Element_Hidden($imgField . '_original');
        $imageOrg->removeDecorator('Label');
        $this->addElement($imageOrg);

        // Name of the group of banner
        // Set the texte for the image
        $textDescription = new Zend_Form_Element_Textarea('BII_Text');
        $textDescription->setLabel($this->_view->getCibleText('form_banner_image_text_label'))
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            //->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $this->_view->getCibleText('validation_message_empty_field'))))
            ->setAttrib('class','stdTextarea');
        $label = $textDescription->getDecorator('Label');
        $label->setOption('class', $this->_labelCSS);

        $groupImage = new Zend_Form_Element_Select('BI_GroupID');
        $groupImage->setLabel($this->_view->getCibleText('form_banner_image_group'))
        ->setAttrib('class','largeSelect');
       
        $group = new GroupObject();
        $groupArray = $group->groupCollection();
        foreach ($groupArray as $group1){
            $groupImage->addMultiOption($group1['BG_ID'],$group1['BG_Name']);
        }        
        $label = $groupImage->getDecorator('Label');
        $label->setOption('class', $this->_labelCSS);


         // Image for the product line   
        $imageView = new Zend_Form_Element_Image(
                $imgField . '_preview',
                array('onclick'=>'return false;')
            );
        $imageView->setImage($imageSrc);
       
        
        $imagePicker = new Cible_Form_Element_ImagePicker(
                $imgField,
                array(
                    'onchange' => "document.getElementById('imageView').src = document.getElementById('" . $imgField . "').value",
                                    'associatedElement' => $imgField . '_preview',
                                    'pathTmp'=>$pathTmp,
                                    'contentID'=>$dataId
                   
                    ));
        $imagePicker->removeDecorator('Label');




        $this->addElement($imageView);
        $this->addElement($imagePicker);

        $this->addElement($groupImage);
        $this->addElement($textDescription);

    }
}