<?php

/**
 * Module Catalog
 * Management of the catalog.
 *
 * @category  Extranet_Module
 * @package   Extranet_Module_Catalog
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: FormItems.php 478 2011-05-13 13:55:48Z ssoares $id
 */

/**
 * Form to add a new item.
 *
 * @category  Extranet_Module
 * @package   Extranet_Module_Catalog
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: FormItems.php 478 2011-05-13 13:55:48Z ssoares $id
 */
class FormItems extends Cible_Form_Multilingual
{

    /**
     * Class constructor
     *
     * @return void
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
        $labelCSS = Cible_FunctionsGeneral::getLanguageLabelColor($options);

        $imageSrc = $options['imageSrc'];
        $dataId = $options['dataId'];
        $imgField = $options['imgField'];
        $isNewImage = $options['isNewImage'];
        $moduleName = $options['moduleName'];

        $formItemPrices = new Zend_Form_SubForm();
        $formTop        = new Zend_Form_SubForm();
        $formBottom     = new Zend_Form_SubForm();
//        $this = new Zend_Form_SubForm();

        if ($dataId == '')
            $pathTmp = "../../../../../data/images/"
                    . $moduleName . "/tmp";
        else
            $pathTmp = "../../../../../data/images/"
                    . $moduleName . "/"
                    . $dataId . "/tmp";

        // hidden specify if new image for the news
//        $newImage = new Zend_Form_Element_Hidden('isNewImage', array('value' => $isNewImage));
//        $newImage->removeDecorator('Label');
//        $this->addElement($newImage);
        // Name of the product line
        $name = new Zend_Form_Element_Text('II_Name');
        $name->setLabel(
                    $this->getView()->getCibleText('item_label_name')
                    . "<span class='field_required'>*</span>")
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
                ->setAttrib('class', 'stdTextInput');

        $label = $name->getDecorator('Label');
        $label->setOption('class', $this->_labelCSS);

        $formTop->addElement($name);

        // List of products
        $oProducts = new ProductsObject();
        $listProd = $oProducts->productsCollection(Zend_Registry::get('currentEditLanguage'));

        $products = new Zend_Form_Element_Select('I_ProductID');
        $products->setLabel(
                    $this->getView()->getCibleText('form_item_products_label')
                    . "<span class='field_required'>*</span>")
                ->setRequired(true)
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
                ->setAttrib('class', 'largeSelect')
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
                );

        $products->addMultiOption('', $this->getView()->getCibleText('form_select_default_label'));
        $products->addMultiOptions($listProd);

//        foreach ($listProd as $data)
//        {
//            $products->addMultiOption($data['P_ID'], $data['PI_Name']);
//        }

        $formTop->addElement($products);

        // Product
        $productCode = new Zend_Form_Element_Text('I_ProductCode');
        $productCode->setLabel(
                        $this->getView()->getCibleText('form_product_code_label'))
                ->setRequired(false)
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
                                array('row' => 'HtmlTag'),
                                array(
                                    'tag' => 'dd',
                                    'class' => 'form_title_inline',
                                    'id' => 'title')
                            ),
                        )
                )
                ->setAttrib('class', 'stdTextInput');

        $formTop->addElement($productCode);
        
        // Item sequence
        $sequence = new Zend_Form_Element_Text('I_Seq');
        $sequence->setLabel(
                        $this->getView()->getCibleText('form_product_sequence_label'))
                ->setRequired(false)
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
                                array('row' => 'HtmlTag'),
                                array(
                                    'tag' => 'dd',
                                    'class' => 'form_title_inline',
                                    'id' => 'title')
                            ),
                        )
                )
                ->setAttrib('class', 'smallTextInput');

        $formTop->addElement($sequence);

        // Detail Price
        $detailPrice = new Zend_Form_Element_Text('I_PriceDetail');
        $detailPrice->setLabel(
                        $this->getView()->getCibleText('form_item_pricedetail_label'))
                ->setRequired(false)
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
                                array('row' => 'HtmlTag'),
                                array(
                                    'tag' => 'dd',
                                    'class' => 'form_title_inline',
                                    'id' => 'title')
                            ),
                        )
                )
                ->setAttrib('class', 'smallTextInput');

        $formTop->addElement($detailPrice);

        // Pro price
        $proPrice = new Zend_Form_Element_Text('I_PricePro');
        $proPrice->setLabel(
                        $this->getView()->getCibleText('form_item_pricepro_label'))
                ->setRequired(false)
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
                                array('row' => 'HtmlTag'),
                                array(
                                    'tag' => 'dd',
                                    'class' => 'form_title_inline',
                                    'id' => 'title')
                            ),
                        )
                )
                ->setAttrib('class', 'smallTextInput');

        $formTop->addElement($proPrice);
        //********************************************************
        //* Sub form containing data defining prices and volumes *
        //********************************************************

        $txtQty = new Cible_Form_Element_Html(
                        'lblQty',
                        array(
                            'value' => $this->getView()->getCibleText('form_item_qty_label')
                        )
        );
        $txtQty->setDecorators(
                array(
                    'ViewHelper',
                    array('label', array('placement' => 'prepend')),
                    array(
                        array('row' => 'HtmlTag'),
                        array(
                            'tag' => 'dd',
                            'class' => 'form_title_inline left')
                    ),
                )
        );
        $formItemPrices->addElement($txtQty);

        $txtPrices = new Cible_Form_Element_Html(
                        'lblPrices',
                        array(
                            'value' => $this->getView()->getCibleText('form_item_prices_label')
                        )
        );
        $txtPrices->setDecorators(
                array(
                    'ViewHelper',
                    array(
                        array('row' => 'HtmlTag'),
                        array(
                            'tag' => 'dd',
                            'class' => 'form_title_inline right')
                    ),
                )
        );
        $formItemPrices->addElement($txtPrices);

        // Qty limit 1
        $qtyInf = new Zend_Form_Element_Text('I_LimitVol1');
        $qtyInf->setLabel(
                        $this->getView()->getCibleText('form_item_limitvol1_label'))
                ->setRequired(false)
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
                                array('row' => 'HtmlTag'),
                                array(
                                    'tag' => 'dd',
                                    'class' => 'inputColLeft')
                            ),
                        )
                )
                ->setAttrib('class', 'smallTextInput left');

        $formItemPrices->addElement($qtyInf);
        // Price Vol 1
        $firstPrice = new Zend_Form_Element_Text('I_PriceVol1');
        $firstPrice->removeDecorator('Label')
                ->setRequired(false)
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
                                array('row' => 'HtmlTag'),
                                array(
                                    'tag' => 'dd',
                                    'class' => 'inputColRight'
                                )
                            ),
                        )
                )
                ->setAttrib('class', 'smallTextInput');

        $formItemPrices->addElement($firstPrice);

        // Qty limit 2
        $qtyMiddle = new Zend_Form_Element_Text('I_LimitVol2');
        $qtyMiddle->setLabel(
                        $this->getView()->getCibleText('form_item_limitvol2_label'))
                ->setRequired(false)
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
                                array('row' => 'HtmlTag'),
                                array(
                                    'tag' => 'dd',
                                    'class' => 'inputColLeft'
                                )
                            ),
                        )
                )
                ->setAttrib('class', 'smallTextInput textRight');

        $formItemPrices->addElement($qtyMiddle);
        // Price vol 2
        $secondPrice = new Zend_Form_Element_Text('I_PriceVol2');
        $secondPrice->removeDecorator('Label')
                ->setRequired(false)
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
                                array('row' => 'HtmlTag'),
                                array(
                                    'tag' => 'dd',
                                    'class' => 'inputColRight',
                                    'id' => 'title')
                            ),
                        )
                )
                ->setAttrib('class', 'smallTextInput textRight');

        $formItemPrices->addElement($secondPrice);
        // Price vol 3
        $thirdPrice = new Zend_Form_Element_Text('I_PriceVol3');
        $thirdPrice->setLabel(
                        $this->getView()->getCibleText('form_item_priceVol3_label'))
                ->setRequired(false)
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
                                array('row' => 'HtmlTag'),
                                array(
                                    'tag' => 'dd',
                                    'class' => 'inputColRight')
                            ),
                        )
                )
                ->setAttrib('class', 'smallTextInput textRight');

        $formItemPrices->addElement($thirdPrice);

        //********************************************************
        $special = new Zend_Form_Element_Checkbox('I_Special');
        $special->setLabel($this->getView()->getCibleText('form_item_special_label'));
        $special->setDecorators(array(
            'ViewHelper',
            array('label', array('placement' => 'append')),
            array(array('row' => 'HtmlTag'), array('tag' => 'dd', 'class' => 'label_after_checkbox')),
        ));

        $formBottom->addElement($special);
        // Special Price
        $specialPrice = new Zend_Form_Element_Text('I_PrixSpecial');
        $specialPrice->setLabel(
                        $this->getView()->getCibleText('form_item_specialPrice_label'))
                ->setRequired(false)
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
                                array('row' => 'HtmlTag'),
                                array(
                                    'tag' => 'dd',
                                    'class' => 'form_title_inline',
                                    'id' => 'title')
                            ),
                        )
                )
                ->setAttrib('class', 'smallTextInput');

        $formBottom->addElement($specialPrice);
        
        // Checkbox for tax of the province
        $taxProv = new Zend_Form_Element_Checkbox('P_TaxProv');
        $taxProv->setLabel($this->getView()->getCibleText('form_item_taxprov_label'))
                ->setAttrib('checked', 'checked');
        $taxProv->setDecorators(array(
            'ViewHelper',
            array('label', array('placement' => 'append')),
            array(array('row' => 'HtmlTag'), array('tag' => 'dd', 'class' => 'label_after_checkbox')),
        ));

        $formBottom->addElement($taxProv);

        // Checkbox for federal tax
        $taxFed = new Zend_Form_Element_Checkbox('P_TaxFed');
        $taxFed->setLabel($this->getView()->getCibleText('form_item_taxfed_label'))
                ->setAttrib('checked', 'checked');
        ;
        $taxFed->setDecorators(array(
            'ViewHelper',
            array('label', array('placement' => 'append')),
            array(array('row' => 'HtmlTag'), array('tag' => 'dd', 'class' => 'label_after_checkbox')),
        ));

        $formBottom->addElement($taxFed);

        $formItemPrices->setLegend($this->getView()->getCibleText('subform_itemprices_legend'));
        $formItemPrices->setAttrib('class', 'smallFieldsetBorder');
//        $this->setLegend($this->getView()->getCibleText('subform_professional_legend'));
//        $this->setAttrib('class', 'fieldsetBorder');
//        $this->addSubForm($this, 'productFormLeft');
//        $this->addSubForm($this, 'productFormRight');
        $this->addSubForm($formTop, 'formTop');
        $this->addSubForm($formItemPrices, 'formItemPrices');
        $this->addSubForm($formBottom, 'formBottom');
    }

}