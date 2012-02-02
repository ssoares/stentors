<?php

/**
 * Module Banners
 * Management of the featured elements.
 *
 * @category  Extranet_Module
 * @package   Extranet_Module_Banners
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: FormBannerFeatured.php 153 2011-07-04 20:41:52Z ssoares $
 */

/**
 * Form to add a new collection.
 *
 * @category  Extranet_Module
 * @package   Extranet_Module_Catalog
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: FormBannerFeatured.php 153 2011-07-04 20:41:52Z ssoares $id
 */
class FormBannerFeatured extends Cible_Form_Multilingual {

    protected $_imageSrcFirst;
    protected $_imageSrcSecond;
    protected $_imageSrcThird;
    protected $_imageSrcFourth;
    protected $_isNewImageSt;
    protected $_isNewImageSec;
    protected $_isNewImageRd;
    protected $_isNewImageTh;
    
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

        $this->setParameters($options);

        if ($this->_dataId == '')
        {
            $pathTmp = "../../../../../data/images/"
                . $this->_moduleName . "/tmp";
        }
        else
        {
            $pathTmp = "../../../../../data/images/"
                . $this->_moduleName . "/"
                . $this->_dataId . "/tmp";
        }

        $this->_filePath = '../../../' . $this->_filePath;

//        $config = Zend_Registry::get('config');
//        
//        $largeWidth  = $config->catalog->IF_Img1->original->maxWidth;
//        $largeHeight = $config->catalog->IF_Img1->original->maxHeight;
//
//        $smallWidth = $config->catalog->IF_Img2->original->maxWidth;
//        $smallHeight = $config->catalog->IF_Img2->original->maxHeight;
//
//        $replaceLarge = $largeWidth . ' x ' . $largeHeight;
//        $replaceSmall = $smallWidth . ' x ' . $smallHeight;
//
//        $this   = new Zend_Form_SubForm();
//        $this  = new Zend_Form_SubForm();
//        $productFormBottom = new Zend_Form_SubForm();
//
//        $largeImgLbl = str_replace(
//                '%DIMENSIONS1%',
//                $replaceLarge,
//                $this->getView()->getCibleText('large_image_for_collection_page'));
//
//        $smallImgLbl = str_replace(
//                '%DIMENSIONS2%',
//                $replaceSmall,
//                $this->getView()->getCibleText('small_image_for_collection_page'));


        // Name of the banner
        $name = new Zend_Form_Element_Text('BF_Name');
        $name->setLabel(
                $this->getView()->getCibleText('form_label_name'))
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
                    'Errors',
                    array('label', array('placement' => 'prepend')),
                    array(
                        array('row' => 'HtmlTag'),
                        array(
                            'tag' => 'dd',
                            'class' => 'form_title_inline',
                            'id' => 'title')
                    ),
                )
            )
            ->setAttrib('class', 'stdTextInput');

//        $label = $name->getDecorator('Label');
//        $label->setOption('class', $this->_labelCSS);

        $this->addElement($name);

        //**** 1st Image.*****
        // hidden specify if new image
        $newImageSt = new Zend_Form_Element_Hidden('isNewImageSt', array('value' => $this->_isNewImageSt));
        $newImageSt->removeDecorator('Label');

        $this->addElement($newImageSt);
        // Image to load
        $imageTmpSt = new Zend_Form_Element_Hidden('IF_Img1_tmp');
        $imageTmpSt->removeDecorator('Label');
        $this->addElement($imageTmpSt);

        $imageOrgSt = new Zend_Form_Element_Hidden('IF_Img1_original');
        $imageOrgSt->removeDecorator('Label');
        $this->addElement($imageOrgSt);

        $imageViewSt = new Zend_Form_Element_Image(
                'IF_Img1_preview',
                array('onclick' => 'return false;')
        );
        $imageViewSt->setImage($this->_imageSrcFirst)->removeDecorator('Label')
            ->setDecorators(
                array(
                    'ViewHelper',
                    array(
                        array('row' => 'HtmlTag'),
                        array(
                            'tag' => 'dd',
                            'class' => 'alignCenter',
                            'id' => 'title')
                    ),
                )
            );
        
        $this->addElement($imageViewSt);

        $imagePickerSt = new Cible_Form_Element_ImagePicker(
                'IF_Img1',
                array(
                    'onchange' => "document.getElementById('IF_Img1').src = document.getElementById('IF_Img1').value",
                    'associatedElement' => 'IF_Img1_preview',
                    'pathTmp' => $pathTmp,
                    'contentID' => $this->_dataId
            ));
        $imagePickerSt->setLabel('')
            ->setDecorators(
                array(
                    'ViewHelper',
                    array(
                        array('row' => 'HtmlTag'),
                        array(
                            'tag' => 'dd',
                            'class' => 'alignCenter',
                            'id' => 'title')
                    ),
                )
            );
        
        $imagePickerSt->removeDecorator('Label');
        $this->addElement($imagePickerSt);

        // label for the image
        $labelSt = new Zend_Form_Element_Text('IFI_Label1');
        $labelSt->setLabel(
            $this->getView()->getCibleText('form_label_label'))
            ->setDecorators(
                array(
                    'ViewHelper',
                    array('label', array('placement' => 'prepend')),
                    array(
                        array('row' => 'HtmlTag'),
                        array(
                            'tag' => 'dd',
                            'class' => 'form_title_inline',
                            'id' => 'title')
                    ),
                )
            )
            ->setAttrib('class', 'stdTextInput');

        $label = $labelSt->getDecorator('Label');
        $label->setOption('class', $this->_labelCSS);

        $this->addElement($labelSt);
        // label for the link
        $urlSt = new Zend_Form_Element_Text('IFI_Url1');
        $urlSt->setLabel(
            $this->getView()->getCibleText('form_label_url'))
            ->setDecorators(
                array(
                    'ViewHelper',
                    array('label', array('placement' => 'prepend')),
                    array(
                        array('row' => 'HtmlTag'),
                        array(
                            'tag' => 'dd',
                            'class' => 'form_title_inline',
                            'id' => 'title')
                    ),
                )
            )
            ->setAttrib('class', 'stdTextInput');

        $label = $urlSt->getDecorator('Label');
        $label->setOption('class', $this->_labelCSS);

        $this->addElement($urlSt);
        
        // List of available styles
        $styleSt = new Zend_Form_Element_Select('IF_Style1');
        $styleSt->setLabel($this->getView()->getCibleText('form_style_label'))
                ->setAttrib('class', 'largeSelect')
                ;

        $listStyles = array(
            'bluePaleText biffoNormal fontSize35' => 'Texte bleu, 35px',
            'bluePaleText biffoNormal fontSize20' => 'Texte bleu, 20px',
        );
        $styleSt->addMultiOption('', $this->getView()->getCibleText('form_select_default_label'));
        $styleSt->addMultiOptions($listStyles);

        $this->addElement($styleSt);
        
        //***** 2nd Image.****
        // hidden specify if new image
        $newImageSec = new Zend_Form_Element_Hidden('isNewImageSec', array('value' => $this->_isNewImageSec));
        $newImageSec->removeDecorator('Label');

        $this->addElement($newImageSec);
        // Image for the colection line
        $imageTmpSec = new Zend_Form_Element_Hidden('IF_Img2_tmp');
        $imageTmpSec->removeDecorator('Label');
        $this->addElement($imageTmpSec);

        $imageOrgSec = new Zend_Form_Element_Hidden('IF_Img2_original');
        $imageOrgSec->removeDecorator('Label');
        $this->addElement($imageOrgSec);

        $imageViewSec = new Zend_Form_Element_Image(
                'IF_Img2_preview',
                array('onclick' => 'return false;')
        );
        $imageViewSec->setImage($this->_imageSrcSecond)->removeDecorator('Label')
            ->setDecorators(
                array(
                    'ViewHelper',
                    array(
                        array('row' => 'HtmlTag'),
                        array(
                            'tag' => 'dd',
                            'class' => 'alignCenter',
                            'id' => 'title')
                    ),
                )
            );
        $this->addElement($imageViewSec);

        $imagePickerSec = new Cible_Form_Element_ImagePicker(
                'IF_Img2',
                array(
                    'onchange' => "document.getElementById('IF_Img2').src = document.getElementById('IF_Img2').value",
                    'associatedElement' => 'IF_Img2_preview',
                    'pathTmp' => $pathTmp,
                    'contentID' => $this->_dataId
            ));
        $imagePickerSec->setLabel('')
            ->setDecorators(
                array(
                    'ViewHelper',
                    array(
                        array('row' => 'HtmlTag'),
                        array(
                            'tag' => 'dd',
                            'class' => 'alignCenter',
                            'id' => 'title')
                    ),
                )
            );
        $imagePickerSec->removeDecorator('Label');
        $this->addElement($imagePickerSec);
        
        // label for the image
        $labelSec = new Zend_Form_Element_Text('IFI_Label2');
        $labelSec->setLabel(
                $this->getView()->getCibleText('form_label_label'))
            ->setDecorators(
                array(
                    'ViewHelper',
                    array('label', array('placement' => 'prepend')),
                    array(
                        array('row' => 'HtmlTag'),
                        array(
                            'tag' => 'dd',
                            'class' => 'form_title_inline',
                            'id' => 'title')
                    ),
                )
            )
            ->setAttrib('class', 'stdTextInput');

        $label = $labelSec->getDecorator('Label');
        $label->setOption('class', $this->_labelCSS);

        $this->addElement($labelSec);
        // List of available styles
        $styleSec = new Zend_Form_Element_Select('IF_Style2');
        $styleSec->setLabel($this->getView()->getCibleText('form_style_label'))
                ->setAttrib('class', 'largeSelect')
                ;

        $listStyles = array(
            'bluePaleText biffoNormal fontSize35' => 'Texte bleu, 35px',
            'bluePaleText biffoNormal fontSize20' => 'Texte bleu, 20px',
        );
        $styleSec->addMultiOption('', $this->getView()->getCibleText('form_select_default_label'));
        $styleSec->addMultiOptions($listStyles);

        $this->addElement($styleSec);
        
        // label for the link
        $urlSec = new Zend_Form_Element_Text('IFI_Url2');
        $urlSec->setLabel(
            $this->getView()->getCibleText('form_label_url'))
            ->setDecorators(
                array(
                    'ViewHelper',
                    array('label', array('placement' => 'prepend')),
                    array(
                        array('row' => 'HtmlTag'),
                        array(
                            'tag' => 'dd',
                            'class' => 'form_title_inline',
                            'id' => 'title')
                    ),
                )
            )
            ->setAttrib('class', 'stdTextInput');

        $label = $urlSec->getDecorator('Label');
        $label->setOption('class', $this->_labelCSS);

        $this->addElement($urlSec);
        
        //**** 3rd Image .*****
        // hidden specify if new image
        $newImageRd = new Zend_Form_Element_Hidden('isNewImageRd', array('value' => $this->_isNewImageRd));
        $newImageRd->removeDecorator('Label');

        $this->addElement($newImageRd);
        // Image to load
        $imageTmpRd = new Zend_Form_Element_Hidden('IF_Img3_tmp');
        $imageTmpRd->removeDecorator('Label');
        $this->addElement($imageTmpRd);

        $imageOrgRd = new Zend_Form_Element_Hidden('IF_Img3_original');
        $imageOrgRd->removeDecorator('Label');
        $this->addElement($imageOrgRd);

        $imageViewRd = new Zend_Form_Element_Image(
                'IF_Img3_preview',
                array('onclick' => 'return false;')
        );
        $imageViewRd->setImage($this->_imageSrcThird)->removeDecorator('Label')
            ->setDecorators(
                array(
                    'ViewHelper',
                    array(
                        array('row' => 'HtmlTag'),
                        array(
                            'tag' => 'dd',
                            'class' => 'alignCenter',
                            'id' => 'title')
                    ),
                )
            );
        $this->addElement($imageViewRd);

        $imagePickerRd = new Cible_Form_Element_ImagePicker(
                'IF_Img3',
                array(
                    'onchange' => "document.getElementById('IF_Img3').src = document.getElementById('IF_Img3').value",
                    'associatedElement' => 'IF_Img3_preview',
                    'pathTmp' => $pathTmp,
                    'contentID' => $this->_dataId
            ));
        $imagePickerRd->setLabel('')
            ->setDecorators(
                array(
                    'ViewHelper',
                    array(
                        array('row' => 'HtmlTag'),
                        array(
                            'tag' => 'dd',
                            'class' => 'alignCenter',
                            'id' => 'title')
                    ),
                )
            );
        $imagePickerRd->removeDecorator('Label');
        $this->addElement($imagePickerRd);

        // label for the image
        $labelRd = new Zend_Form_Element_Text('IFI_Label3');
        $labelRd->setLabel(
            $this->getView()->getCibleText('form_label_label'))
            ->setDecorators(
                array(
                    'ViewHelper',
                    array('label', array('placement' => 'prepend')),
                    array(
                        array('row' => 'HtmlTag'),
                        array(
                            'tag' => 'dd',
                            'class' => 'form_title_inline',
                            'id' => 'title')
                    ),
                )
            )
            ->setAttrib('class', 'stdTextInput');

        $label = $labelRd->getDecorator('Label');
        $label->setOption('class', $this->_labelCSS);

        $this->addElement($labelRd);
        
        // label for the link
        $urlRd = new Zend_Form_Element_Text('IFI_Url3');
        $urlRd->setLabel(
            $this->getView()->getCibleText('form_label_url'))
            ->setDecorators(
                array(
                    'ViewHelper',
                    array('label', array('placement' => 'prepend')),
                    array(
                        array('row' => 'HtmlTag'),
                        array(
                            'tag' => 'dd',
                            'class' => 'form_title_inline',
                            'id' => 'title')
                    ),
                )
            )
            ->setAttrib('class', 'stdTextInput');

        $label = $urlRd->getDecorator('Label');
        $label->setOption('class', $this->_labelCSS);

        $this->addElement($urlRd);
        
        // List of available styles
        $styleRd = new Zend_Form_Element_Select('IF_Style3');
        $styleRd->setLabel($this->getView()->getCibleText('form_style_label'))
                ->setAttrib('class', 'largeSelect')
                ;

        $listStyles = array(
            'bluePaleText biffoNormal fontSize35' => 'Texte bleu, 35px',
            'bluePaleText biffoNormal fontSize20' => 'Texte bleu, 20px',
        );
        $styleRd->addMultiOption('', $this->getView()->getCibleText('form_select_default_label'));
        $styleRd->addMultiOptions($listStyles);

        $this->addElement($styleRd);
        
        //***** 4th Image.****
        // hidden specify if new image
        $newImageTh = new Zend_Form_Element_Hidden('isNewImageTh', array('value' => $this->_isNewImageTh));
        $newImageTh->removeDecorator('Label');

        $this->addElement($newImageTh);
        // Image for the colection line
        $imageTmpTh = new Zend_Form_Element_Hidden('IF_Img4_tmp');
        $imageTmpTh->removeDecorator('Label');
        $this->addElement($imageTmpTh);

        $imageOrgTh = new Zend_Form_Element_Hidden('IF_Img4_original');
        $imageOrgTh->removeDecorator('Label');
        $this->addElement($imageOrgTh);

        $imageViewTh = new Zend_Form_Element_Image(
                'IF_Img4_preview',
                array('onclick' => 'return false;')
        );

        $imageViewTh->setImage($this->_imageSrcFourth)->removeDecorator('Label')
            ->setDecorators(
                array(
                    'ViewHelper',
                    array(
                        array('row' => 'HtmlTag'),
                        array(
                            'tag' => 'dd',
                            'class' => 'alignCenter',
                            'id' => 'title')
                    ),
                )
            );
        $this->addElement($imageViewTh);

        $imagePickerTh = new Cible_Form_Element_ImagePicker(
                'IF_Img4',
                array(
                    'onchange' => "document.getElementById('IF_Img4').src = document.getElementById('IF_Img4').value",
                    'associatedElement' => 'IF_Img4_preview',
                    'pathTmp' => $pathTmp,
                    'contentID' => $this->_dataId
            ));
        $imagePickerTh->setLabel('')
            ->setDecorators(
                array(
                    'ViewHelper',
                    array(
                        array('row' => 'HtmlTag'),
                        array(
                            'tag' => 'dd',
                            'class' => 'alignCenter',
                            'id' => 'title')
                    ),
                )
            );
        $imagePickerTh->removeDecorator('Label');
        $this->addElement($imagePickerTh);
        
        // label for the image
        $labelTh = new Zend_Form_Element_Text('IFI_Label4');
        $labelTh->setLabel(
                $this->getView()->getCibleText('form_label_label'))
            ->setDecorators(
                array(
                    'ViewHelper',
                    array('label', array('placement' => 'prepend')),
                    array(
                        array('row' => 'HtmlTag'),
                        array(
                            'tag' => 'dd',
                            'class' => 'form_title_inline',
                            'id' => 'title')
                    ),
                )
            )
            ->setAttrib('class', 'stdTextInput');

        $label = $labelTh->getDecorator('Label');
        $label->setOption('class', $this->_labelCSS);

        $this->addElement($labelTh);

        // label for the link
        $urlTh = new Zend_Form_Element_Text('IFI_Url4');
        $urlTh->setLabel(
            $this->getView()->getCibleText('form_label_url'))
            ->setDecorators(
                array(
                    'ViewHelper',
                    array('label', array('placement' => 'prepend')),
                    array(
                        array('row' => 'HtmlTag'),
                        array(
                            'tag' => 'dd',
                            'class' => 'form_title_inline',
                            'id' => 'title')
                    ),
                )
            )
            ->setAttrib('class', 'stdTextInput');

        $label = $urlTh->getDecorator('Label');
        $label->setOption('class', $this->_labelCSS);

        $this->addElement($urlTh);
        
        // List of available styles
        $styleTh = new Zend_Form_Element_Select('IF_Style4');
        $styleTh->setLabel($this->getView()->getCibleText('form_style_label'))
                ->setAttrib('class', 'largeSelect')
                ;

        $listStyles = array(
            'bluePaleText biffoNormal fontSize35' => 'Texte bleu, 35px',
            'bluePaleText biffoNormal fontSize20' => 'Texte bleu, 20px',
        );
        $styleTh->addMultiOption('', $this->getView()->getCibleText('form_select_default_label'));
        $styleTh->addMultiOptions($listStyles);

        $this->addElement($styleTh);
        
        $this->addDisplayGroup(
            array(
                'isNewImageSt', 
                'IF_Img1_tmp', 
                'IF_Img1_original', 
                'IF_Img1_preview', 
                'IF_Img1',
                'IFI_Label1',
                'IFI_Url1',
                'IF_Style1'), 
            'firstImage');
        
        $this->getDisplayGroup('firstImage')
            ->setLegend('Image 1')
            ->setAttrib('class', 'imageGroup first')
            ->removeDecorator('DtDdWrapper');
        $this->addDisplayGroup(
            array(
                'isNewImageSec', 
                'IF_Img2_tmp', 
                'IF_Img2_original', 
                'IF_Img2_preview', 
                'IF_Img2',
                'IFI_Label2',
                'IFI_Url2',
                'IF_Style2'), 
            'secondImage');
        
        $this->getDisplayGroup('secondImage')
            ->setLegend('Image 2')
            ->setAttrib('class', 'imageGroup second')
            ->removeDecorator('DtDdWrapper');
        $this->addDisplayGroup(
            array(
                'isNewImageRd', 
                'IF_Img3_tmp', 
                'IF_Img3_original', 
                'IF_Img3_preview', 
                'IF_Img3',
                'IFI_Label3',
                'IFI_Url3',
                'IF_Style3'), 
            'thirdImage');
        
        $this->getDisplayGroup('thirdImage')
            ->setLegend('Image 3')
            ->setAttrib('class', 'imageGroup third')
            ->removeDecorator('DtDdWrapper');
        $this->addDisplayGroup(
            array(
                'isNewImageTh', 
                'IF_Img4_tmp', 
                'IF_Img4_original', 
                'IF_Img4_preview', 
                'IF_Img4',
                'IFI_Label4',
                'IFI_Url4',
                'IF_Style4'), 
            'fourthImage');
        
        $this->getDisplayGroup('fourthImage')
            ->setLegend('Image 4')
            ->setAttrib('class', 'imageGroup fourth')
            ->removeDecorator('DtDdWrapper');
    
    }
    
    /**
     * Set all the parameters for the form.
     *
     * @param array $params Options from the controller to build the form.
     *
     * @return void
     */
    public function setParameters($params = array())
    {
        foreach ($params as $property => $value)
        {
            if ($property == 'BlockID')
                $property = 'blockID';

            $propertyName = '_' . $property;

            if (property_exists($this, $propertyName))
            {
                $this->$propertyName = $value;
            }
        }
    }

}
