<?php

/**
 * Module Catalog
 * Management of the products for Logiflex.
 *
 * @category  Extranet_Module
 * @package   Extranet_Module_Catalog
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: FormCategories.php 453 2011-04-14 04:16:53Z ssoares $id
 */

/**
 * Form to add a new collection.
 *
 * @category  Extranet_Module
 * @package   Extranet_Module_Catalog
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: FormCategories.php 453 2011-04-14 04:16:53Z ssoares $id
 */
class FormCategories extends Cible_Form_Multilingual
{

    protected $_imageSrcL;
    protected $_imageSrcS;
    protected $_isNewImageL;
    protected $_isNewImageS;
    protected $_dataId;
    protected $_moduleName;
    protected $_filePath;

    /**
     * Class constructor
     *
     * @return void
     */
    public function __construct($options)
    {
        parent::__construct($options);

        $imageSrc = $options['imageSrc'];

        if (isset($options['imgField']))
            $imgField = $options['imgField'];
        
        $dataId = $options['dataId'];
        $isNewImage = $options['isNewImage'];
        $moduleName = $options['moduleName'];

        if ($dataId == '')
            $pathTmp = "../../../../../data/images/"
                    . $moduleName . "/tmp";
        else
            $pathTmp = "../../../../../data/images/"
                    . $moduleName . "/"
                    . $dataId . "/tmp";

        $config = Zend_Registry::get('config');

        // hidden specify if new image for the news
        $newImage = new Zend_Form_Element_Hidden('isNewImage', array('value' => $isNewImage));
        $newImage->removeDecorator('Label');
        $this->addElement($newImage);

        // Name of the product line
        $name = new Zend_Form_Element_Text('CCI_Name');
        $name->setLabel(
                        $this->getView()->getCibleText('form_category_name_label'))
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator(
                        'NotEmpty',
                        true,
                        array(
                            'messages' => array(
                                'isEmpty' => $this->getView()->getCibleText(
                                        'validation_message_empty_field')
                            )
                        )
                )
                ->setDecorators(
                        array(
                            'ViewHelper',
                            array('label', array('placement' => 'prepend')),
                            array(
                                'Errors',
                                array('placement' => 'append')
                            ),
                            array(
                                array('row' => 'HtmlTag'),
                                array(
                                    'tag' => 'dd',
                                    'class' => 'form_title_inline',
                                    'id' => 'title')
                            ),
                        )
                )
                ->setAttrib('class', 'stdTextInput ');

        $label = $name->getDecorator('Label');
        $label->setOption('class', $this->_labelCSS);

        $this->addElement($name);

        if (!empty($imgField))
        {
            // Image for the category
            $imageTmp = new Zend_Form_Element_Hidden($imgField . '_tmp');
            $imageTmp->removeDecorator('Label');
            $this->addElement($imageTmp);

            $imageOrg = new Zend_Form_Element_Hidden($imgField . '_original');
            $imageOrg->removeDecorator('Label');
            $this->addElement($imageOrg);


            $imageView = new Zend_Form_Element_Image(
                            $imgField . '_preview',
                            array('onclick' => 'return false;')
            );
            $imageView->setImage($imageSrc);
            $this->addElement($imageView);

            $imagePicker = new Cible_Form_Element_ImagePicker(
                            $imgField,
                            array(
                                'onchange' => "document.getElementById('imageView').src = document.getElementById('" . $imgField . "').value",
                                'associatedElement' => $imgField . '_preview',
                                'pathTmp' => $pathTmp,
                                'contentID' => $dataId
                    ));
            $imagePicker->setLabel($this->getView()->getCibleText('form_category_logo_label'));
            $this->addElement($imagePicker);
        }

        // ImageSrc
        $imageBanner = new Zend_Form_Element_Select('C_BannerGroupID');
        $imageBanner->setLabel($this->getView()->getCibleText('form_banner_image_group_extranet'))->setAttrib('class','stdSelect');
        $imageBanner->addMultiOption('', 'Sans image');
        $group = new GroupObject();
        $groupArray = $group->groupCollection();
        foreach ($groupArray as $group1){
            $imageBanner->addMultiOption($group1['BG_ID'],$group1['BG_Name']);
        }
        $this->addElement($imageBanner);

        // id of the associated meta data
        $metaTagId = new Zend_Form_Element_Hidden('CCI_MetaId');
        $metaTagId->removeDecorator('Label');
        $this->addElement($metaTagId);
    }

}
