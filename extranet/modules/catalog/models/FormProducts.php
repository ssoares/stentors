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
 * @version   $Id: FormProducts.php 536 2011-08-08 21:02:41Z ssoares $id
 */

/**
 * Form to add a new product.
 *
 * @category  Extranet_Module
 * @package   Extranet_Module_Catalog
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: FormProducts.php 536 2011-08-08 21:02:41Z ssoares $id
 */
class FormProducts extends Cible_Form_Multilingual
{
    /**
     * Class constructor
     *
     * @return void
     */
    public function __construct($options)
    {
        $this->_addSubmitSaveClose = true;
        parent::__construct($options);

        $imageSrc   = $options['imageSrc'];
        $dataId     = $options['dataId'];
        $imgField   = $options['imgField'];
        $isNewImage = $options['isNewImage'];
        $moduleName = $options['moduleName'];

        $productFormLeft   = new Zend_Form_SubForm();
        $productFormRight  = new Zend_Form_SubForm();
        $productFormBotPub = new Zend_Form_SubForm();
        $productFormBotPro = new Zend_Form_SubForm();

        if($dataId == '')
            $pathTmp = "../../../../../data/images/" 
                        . $moduleName . "/tmp";
        else
            $pathTmp = "../../../../../data/images/" 
                        . $moduleName . "/"
                        . $dataId . "/tmp";

        // hidden specify if new image for the news
            $newImage = new Zend_Form_Element_Hidden('isNewImage', array('value'=>$isNewImage));
            $newImage->removeDecorator('Label');
            $productFormRight->addElement($newImage);


        // Name of the product line
        $name = new Zend_Form_Element_Text('PI_Name');
        $name->setLabel(
                $productFormLeft->getView()->getCibleText('product_label_name') . "<span class='field_required'>*</span>")
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator(
                'NotEmpty',
                true,
                array(
                    'messages' => array(
                        'isEmpty' => $productFormLeft->getView()->getCibleText(
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
                            'tag'   => 'dd',
                            'class' => 'form_title_inline',
                            'id'    => 'title')
                        ),
                    )
                )
        ->setAttrib('class','stdTextInput');

        $label = $name->getDecorator('Label');
        $label->setOption('class', $this->_labelCSS);

        $productFormLeft->addElement($name);

        // List of sub categories
        $oSubCategories = new SubCategoriesObject();
        $listSubCat     = $oSubCategories->subcatCollection(Zend_Registry::get('currentEditLanguage'));

        $subCategories = new Zend_Form_Element_Select('P_SubCategoryID');
        $subCategories->setLabel($productFormLeft->getView()->getCibleText('form_products_subcat_label'). "<span class='field_required'>*</span>")
                ->setAttrib('class', 'largeSelect')
                ->setRequired(true)
                ->addValidator(
                    'NotEmpty',
                    true,
                    array(
                        'messages' => array(
                            'isEmpty' => $productFormLeft->getView()->getCibleText(
                                    'validation_message_empty_field')
                         )
                    )
                );

        $subCategories->addMultiOption('', $this->getView()->getCibleText('form_select_default_label'));
        $subCategories->addMultiOptions($listSubCat);

        $productFormLeft->addElement($subCategories);

        // Checkbox for new product
        $isNewProd = new Zend_Form_Element_Checkbox('P_New');
        $isNewProd->setLabel($productFormLeft->getView()->getCibleText('form_product_isnew_label'));
        $isNewProd->setDecorators(array(
            'ViewHelper',
            array('label', array('placement' => 'append')),
            array(array('row' => 'HtmlTag'), array('tag' => 'dd', 'class' => 'label_after_checkbox')),
        ));

        $productFormLeft->addElement($isNewProd);

        // id of the associated meta data
        $metaTagId = new Zend_Form_Element_Hidden('PI_MetaId');
        $metaTagId->removeDecorator('Label');
        $productFormLeft->addElement($metaTagId);

        // Image for the product line
        $imageTmp  = new Zend_Form_Element_Hidden($imgField . '_tmp');
        $imageTmp->removeDecorator('Label');
        $productFormRight->addElement($imageTmp);

        $imageOrg  = new Zend_Form_Element_Hidden($imgField . '_original');
        $imageOrg->removeDecorator('Label');
        $productFormRight->addElement($imageOrg);

        $imageView = new Zend_Form_Element_Image(
                $imgField . '_preview',
                array('onclick'=>'return false;')
            );
        $imageView->setImage($imageSrc);
        $productFormRight->addElement($imageView);

        $imagePicker = new Cible_Form_Element_ImagePicker(
                $imgField,
                array(
                    'onchange' => "document.getElementById('imageView').src = document.getElementById('" . $imgField . "').value",
                                                                                'associatedElement' => $imgField . '_preview',
                                                                                'pathTmp'=>$pathTmp,
                                                                                'contentID'=>$dataId
                                                                    ));
        $imagePicker->removeDecorator('Label');
        $productFormRight->addElement($imagePicker);
        //Keywords field
        $keywords = new Zend_Form_Element_Text('PI_MotsCles');
        $keywords->setLabel(
                $productFormLeft->getView()->getCibleText('form_product_keywords_label'))
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators(
                array(
                    'ViewHelper',
                    array('label', array('placement' => 'prepend')),
                    array(
                        array('row' => 'HtmlTag'),
                        array(
                            'tag'   => 'dd',
                            'class' => 'form_title_inline marginTop30',
                            'id'    => 'title')
                        ),
                    )
                )
        ->setAttrib('class','largeTextInput');

        $label = $keywords->getDecorator('Label');
        $label->setOption('class', $this->_labelCSS);

        $productFormLeft->addElement($keywords);
        
        // Description of the product
        $descrPublic = new Cible_Form_Element_Editor(
                'PI_DescriptionPublic',
                array(
                    'mode'      => Cible_Form_Element_Editor::ADVANCED,
                    'subFormID' => 'productFormBotPub'));
        $descrPublic->setLabel(
                $this->getView()->getCibleText('product_label_descriptionPublic'))
                    ->setAttrib('class','largeEditor');

        $label = $descrPublic->getDecorator('label');
        $label->setOption('class',  $this->_labelCSS);
        
        $productFormBotPub->addElement($descrPublic);

        // Technical specs of the product for public.
        $urlTechFile = new Zend_Form_Element_Hidden('PI_FicheTechniquePublicPDF');
        $urlTechFile->removeDecorator('Label');
        $productFormBotPub->addElement($urlTechFile);

        $techfile = new Zend_Form_Element_Hidden('technicalSpecsName');
        $techfile->removeDecorator('Label');
        $productFormBotPub->addElement($techfile);

        // Technical specs of the product.
        $technicalSpecs = new Cible_Form_Element_FileManager(
                'PI_FicheTechniquePublicPDF',
                array(
                    'associatedElement' => 'productFormBotPub',
                    'displayElement'    => 'technicalSpecsName',
                    'pathTmp'           => $this->_filePath,
                    'contentID'         => $this->_dataId,
                    'setInit'           => true
                )
            );
        $technicalSpecs->setLabel(
                $productFormBotPub->getView()->getCibleText('product_label_technical_specs'))
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators(
                array(
                    'ViewHelper',
                    array('label', array('placement' => 'prepend')),
                    array(
                        array('row' => 'HtmlTag'),
                        array(
                            'tag'   => 'dd',
                            'class' => 'form_title_inline',
                            'id'    => 'title')
                        ),
                    )
                )
            ->setAttrib('class','stdTextInput');

        $label = $technicalSpecs->getDecorator('Label');
        $label->setOption('class', $this->_labelCSS);

        $productFormBotPub->addElement($technicalSpecs);

        //-----------------------------------------------------

        // Description of the product for pro
        $descrPro = new Cible_Form_Element_Editor(
                'PI_DescriptionPro',
                array(
                    'mode'      => Cible_Form_Element_Editor::ADVANCED,
                    'subFormID' => 'productFormBotPro'));
        $descrPro->setLabel(
                $this->getView()->getCibleText('product_label_descriptionPro'))
                    ->setAttrib('class','largeEditor');

        $label = $descrPro->getDecorator('label');
        $label->setOption('class',  $this->_labelCSS);

        $productFormBotPro->addElement($descrPro);

        // Technical specs of the product for pro.
        $urlTechFile = new Zend_Form_Element_Hidden('PI_FicheTechniqueProPDF');
        $urlTechFile->removeDecorator('Label');
        $productFormLeft->addElement($urlTechFile);

        $techfile = new Zend_Form_Element_Hidden('technicalSpecsPro');
        $techfile->removeDecorator('Label');
        $productFormBotPro->addElement($techfile);

        // Technical specs of the product.
        $technicalSpecsPro = new Cible_Form_Element_FileManager(
                'PI_FicheTechniqueProPDF',
                array(
                    'associatedElement' => 'productFormBotPro',
                    'displayElement'    => 'technicalSpecsPro',
                    'pathTmp'           => $this->_filePath,
                    'contentID'         => $this->_dataId,
                    'setInit'           => true
                )
            );
        $technicalSpecsPro->setLabel(
                $productFormBotPro->getView()->getCibleText('product_label_technical_specs'))
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators(
                array(
                    'ViewHelper',
                    array('label', array('placement' => 'prepend')),
                    array(
                        array('row' => 'HtmlTag'),
                        array(
                            'tag'   => 'dd',
                            'class' => 'form_title_inline',
                            'id'    => 'title')
                        ),
                    )
                )
            ->setAttrib('class','stdTextInput');

        $label = $technicalSpecsPro->getDecorator('Label');
        $label->setOption('class', $this->_labelCSS);

        $productFormBotPro->addElement($technicalSpecsPro);

        $productFormBotPub->setLegend($this->getView()->getCibleText('subform_public_legend'));
        $productFormBotPub->setAttrib('class', 'fieldsetBorder');
        $productFormBotPro->setLegend($this->getView()->getCibleText('subform_professional_legend'));
        $productFormBotPro->setAttrib('class', 'fieldsetBorder');

        $this->addSubForm($productFormLeft, 'productFormLeft');
        $this->addSubForm($productFormRight, 'productFormRight');
        $this->addSubForm($productFormBotPub, 'productFormBotPub');
        $this->addSubForm($productFormBotPro, 'productFormBotPro');

    }
}
