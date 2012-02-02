<?php

/**
 * Edith
 *
 * @category  Cible
 * @package   Cible_Form
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: Form.php 731 2011-12-09 19:43:39Z ssoares $
 */

/**
 * Defines default elements and methods for forms.
 *
 * @category  Cible
 * @package   Cible_Form
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: Form.php 731 2011-12-09 19:43:39Z ssoares $
 */
class Cible_Form extends Zend_Form
{

    protected $_db;
    protected $_params;
    protected $_disabledDefaultActions = false;
    protected $_disabledSaveAction = false;
    protected $_addRequiredAsterisks = true;
    protected $_addSubmitSaveClose = false;
    protected $_view;
    protected $_config;
    protected $_object;
    protected $_elemNameId = '';
    protected $_srcData = array();

    /**
     * Constructor
     *
     * Registers form view helper as decorator
     *
     * @param mixed $options
     * @return void
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
        //$token   = new Zend_Form_Element_Hash('token',array('salt' => srand()));
        //$this->addElement($token);
        $this->setMethod('post');
        $this->_db = Zend_Registry::get('db');
        $this->_view = $this->getView();
        $this->_config = Zend_Registry::get('config');

        $this->_view->headLink()->appendStylesheet($this->_view->locateFile('form.css'));

        if (isset($options['disabledDefaultActions']) && $options['disabledDefaultActions'] == true)
            $this->_disabledDefaultActions = true;
        if (isset($options['disabledSaveAction']) && $options['disabledSaveAction'] == true)
            $this->_disabledSaveAction = true;

        $_request = Zend_Controller_Front::getInstance()->getRequest();

        $this->_params = $_request->getParams();

        if ($this->_disabledDefaultActions == false)
        {

            $cancel_url = isset($options['cancelUrl']) ? $options['cancelUrl'] : '';
            // submit button  (save)
            $submitSave = new Zend_Form_Element_Submit('submit');
            $submitSave->setLabel($this->getView()->getCibleText('button_save'))
                ->setName('submitSave')
                ->setAttrib('id', 'submitSave')
                ->setAttrib('class','stdButton')
                ->setDecorators(array(
                    'ViewHelper',
                    array(array('data'=>'HtmlTag'),array('tag'=>'li')),
                    array(array('row'=>'HtmlTag'),array('tag'=>'ul', 'openOnly'=>true, 'class' => 'actions-buttons'))
                ))
                ->setOrder(1);

            $this->addElement($submitSave);

            // submit and close button: save and go to the return url
            $submitSaveClose = new Zend_Form_Element_Submit('submitClose');
            $submitSaveClose->setLabel($this->getView()->getCibleText('button_save_close'))
                ->setName('submitSaveClose')
                ->setAttrib('id', 'submitSaveClose')
                ->setAttrib('class','stdButton')
                ->setDecorators(
                    array(
                        'ViewHelper',
                        array(array('data' => 'HtmlTag'), array('tag' => 'li')),
                ))
                ->setOrder(2);

            if ($this->_addSubmitSaveClose)
                $this->addElement($submitSaveClose);

            // cancel button (don't save and return to the main page)

            $cancel_params = empty($cancel_url) ? array() : array('onclick' => "document.location.href='$cancel_url'");
            $cancel = new Zend_Form_Element_Button('cancel', $cancel_params);
            $cancel->setLabel($this->getView()->getCibleText('button_cancel'))
                ->setAttrib('class', 'stdButton')
                ->setDecorators(array(
                    'ViewHelper',
                    array(array('data' => 'HtmlTag'), array('tag' => 'li')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'ul', 'closeOnly' => true))
                ))
                ->setOrder(10);

            $this->addElement($cancel);

            // create an action display group with element name previously added to the form
            $this->addDisplayGroup(
                array('submitSave', 'submitSaveClose', 'cancel'), 'actions'
            );

            $actions = $this->getDisplayGroup('actions');
            $this->setDisplayGroupDecorators(array(
                'formElements',
                array(array('innerHtmlTag' => 'HtmlTag'), array('tag' => 'div')),
                'fieldset',
                array(array('outerHtmlTag' => 'HtmlTag'), array('tag' => 'dd'))
            ));
            if (!empty($this->_object))
                $this->autoGenerate();
        }
    }

    /**
     * Render form
     *
     * @param  Zend_View_Interface $view
     * @return string
     */
    public function render(Zend_View_Interface $view = null)
    {
        $firstElementID = null;

        foreach($this->getElements() as $_element){
            if ($firstElementID == null && ($_element->getType() == 'Zend_Form_Element_Text' || $_element->getType()  == 'Cible_Form_Element_Editor' || $_element->getType()  == 'Zend_Form_Element_TextArea' ) ) $firstElementID = $_element->getID();

            if ($_element->getType() == 'Cible_Form_Element_Editor'){
                $this->getView()->headScript()->appendFile($this->getView()->baseUrl() . '/js/tiny_mce/tiny_mce.js');
                break;
            }

            if($_element->isRequired() && $this->_addRequiredAsterisks )
            {
                $_element->setLabel("{$_element->getLabel()} <span class='field_required'>*</span>");
            }
        }

        if($firstElementID != null){
            $this->getView()->jQuery()->enable();
            $script = <<< EOS


EOS;
            $this->getView()->jQuery()->addOnLoad($script);
        }

        // render parent
        return parent::render($view);
    }

    public function getFirstElement()
    {
        foreach ($this->getElements() as $element)
        {
            return $element;
        }
        return null;
    }

    public function addActionButton($element)
    {
        if ($element->getType() != 'Cible_Form_Element_LanguageSelector')
        {
            $element->setDecorators(array(
                'ViewHelper',
                array(array('data' => 'HtmlTag'), array('tag' => 'li')),
            ));
        }
        $actions = $this->getDisplayGroup('actions');
        $actions->addElement($element);
    }

    public function disableElementValidation($elements)
    {
        $elems = array();

        if (!is_array($elements))
            array_push($elems, $elements);
        else
            $elems = $elements;

        foreach ($elems as $el)
        {
            $this->getElement($el)->clearValidators()
                ->setRequired(false);
        }
    }

    protected function _addSubFormAsteriks($subForms)
    {
        foreach ($subForms->getElements() as $_element)
        {
            if ($_element->getType() == 'Cible_Form_Element_Editor')
            {
                $this->getView()->headScript()->appendFile($this->getView()->baseUrl() . '/js/tiny_mce/tiny_mce.js');
                break;
            }

            if ($_element->isRequired() && $this->_addRequiredAsterisks)
            {
                $_element->setLabel("{$_element->getLabel()} <span class='field_required'>*</span>");
            }
        }

        $tmpForm = $subForms->getSubForms();
        if (is_array($tmpForm))
            $tmpForm = current($tmpForm);

        if (count($tmpForm) && $tmpForm instanceof Zend_Form_SubForm)
        {
            $this->_addSubFormAsteriks($tmpForm);
        }
    }

    public function autoGenerate()
    {
        $metaData = array();
        $object = $this->_object;

        $metaData = $object->getColsData();

        foreach ($metaData as $key => $meta)
        {
            $params = Cible_FunctionsGeneral::fetchParams($meta['COMMENT']);
            if (!isset($params['exclude']) || true == (bool)$params['exclude'])
            {
                $this->_elemNameId = $meta['COLUMN_NAME'];
                switch ($meta['DATA_TYPE'])
                {
                    case 'decimal':
                    case 'float':
                    case 'tinyint':
                    case 'int':
                        if (!$meta['PRIMARY'])
                            $this->setElementInput($meta, $params);
                        break;

                    case 'char':
                    case 'varchar':
                        $this->setElementTextField($meta, $params);
                        break;

                    case 'text':
                        $this->setElementText($meta, $params);
                        break;
                    case 'date':
                    case 'timestamp':
                    case 'datetime':
                        $this->setElementDatepicker($meta, $params);
                        break;
                    case 'enum':
                        $params['elem'] = 'select';
                        $params['src'] = 'enum';
                        $this->setElementInput($meta, $params);
                        break;

                    default:
                        break;
                }
            }
        }

        $indexTable = $object->getIndexTableName();
        if (!empty ($indexTable))
            $metaIndex = $object->getColsIndex();
    }

    /**
     * Defines and build an input field which is not a text field.
     * According to the parameter elem, it will set the element.
     *
     * $params['exclude']   => boolean; The column will not be built.<br />
     * $params['required '] => boolean;<br />
     * $params['elem']      => select, checkbox, radio, editor;<br />
     *                         If $params['elem'] = select, then the $params['src']<br />
     *                         parameter must be defined.
     * $params['src']       => string; name of the source for the element.<br />
     *
     * @param array $meta
     * @param array $params
     *
     * @return void
     */
    public function setElementInput(Array $meta, Array $params)
    {
        if (!empty ($params))
        {
            switch ($params['elem'])
            {
                case 'select':
                    if (empty($params['src']))
                        throw new Exception ('Trying to build an elemnt but no data source given');

                    $fieldId = $meta['COLUMN_NAME'];
                    $srcName = $params['src'];
                    $srcData = '_' . $srcName . 'Src';
                    $this->$srcData();
                    $element = new Zend_Form_Element_Select($fieldId);
                    $element->setLabel($this->getView()->getCibleText('form_label_' . $fieldId))
                        ->setAttrib('class', 'largeSelect')
                        ->addMultiOptions($this->_srcData);
                    break;
                case 'checkbox':

                    break;
                case 'radio':

                    break;
                case 'multi-checkbox':

                    break;
                case 'multi-select':

                    break;

                default:
                    break;
            }

            $this->addElement($element);
        }
    }

    /**
     * Defines and build an input text field.
     * According to the parameter elem, it will set the element.
     *
     * $params['exclude']   => boolean; defines if the column is built.
     * $params['required '] => boolean;
     * $params['elem']      => select, checkbox, radio, editor;
     *                         If $params['elem'] = select, then the $params['src']
     *                         parameter must be defined.
     * $params['src']       => string; name of the source for the element.
     *
     * @param array $meta
     * @param array $params
     *
     * @return void
     */
    public function setElementTextField(Array $meta, Array $params)
    {
        $isUnique = '';
        $validators = array();

        if (!empty ($params))
        {
            if (isset($params['validate']))
            {
                $validateName = '_' . $params['validate'] . 'Validate';
                if (isset($params['unique']))
                    $isUnique = $meta['COLUMN_NAME'];

                $validators = $this->$validateName($isUnique);
            }
        }

        $element = new Zend_Form_Element_Text($meta['COLUMN_NAME']);
        $element->setLabel($this->getView()->getCibleText('form_label_' . $meta['COLUMN_NAME']));

        if (!$meta['NULLABLE'])
        {
            $element->setRequired(true);
            $element->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $this->getView()->getCibleText('validation_message_empty_field'))));
        }
        if (count($validators) > 0)
            $element->addValidators ($validators);

            $this->addElement($element);
    }

    /**
     * Defines and build a textarea.
     * According to the parameter elem, it will set the element.
     *
     * $params['exclude']   => boolean; defines if the column is built.
     * $params['required '] => boolean;
     * $params['elem']      => select, checkbox, radio, editor;
     *                         If $params['elem'] = select, then the $params['src']
     *                         parameter must be defined.
     * $params['src']       => string; name of the source for the element.
     *
     * @param array $meta
     * @param array $params
     *
     * @return void
     */
    public function setElementText(Array $meta, Array $params)
    {

    }

    /**
     * Defines and build a date picker.
     * According to the parameter elem, it will set the element.
     *
     * $params['exclude']   => boolean; defines if the column is built.
     * $params['required '] => boolean;
     * $params['elem']      => select, checkbox, radio, editor;
     *                         If $params['elem'] = select, then the $params['src']
     *                         parameter must be defined.
     * $params['src']       => string; name of the source for the element.
     *
     * @param array $meta
     * @param array $params
     *
     * @return void
     */
    public function setElementDatepicker(Array $meta, Array $params)
    {
        $date = new Cible_Form_Element_DatePicker($this->_elemNameId, array('jquery.params'=> array('changeYear'=>true, 'changeMonth'=> true)));

        $date->setLabel($this->getView()->getCibleText('form_label_' . $this->_elemNameId));

        if (!$meta['NULLABLE'])
            $date->setRequired(true)
            ->addValidator(
                'NotEmpty',
                true,
                array(
                    'messages' => array(
                        'isEmpty' => $this->getView()->getCibleText('validation_message_empty_field')
                        )
                    )
                );

          $date->addValidator('Date',
              true,
              array(
                  'messages' => array(
                      'dateNotYYYY-MM-DD' => $this->getView()->getCibleText('validation_message_invalid_date'),
                      'dateInvalid' => $this->getView()->getCibleText('validation_message_invalid_date'),
                      'dateFalseFormat' => $this->getView()->getCibleText('validation_message_invalid_date')
                    )
              )
          );
        $this->addElement($date);
    }

    protected function _salutationsSrc()
    {
        $greetings = $this->getView()->getAllSalutation();
        foreach ($greetings as $greeting)
        {
            $this->_srcData[$greeting['S_ID']]= $greeting['ST_Value'];
        }
    }
    protected function _languagesSrc()
    {
        $langs = Cible_FunctionsGeneral::getAllLanguage();

        foreach ($langs as $lang)
        {
            $this->_srcData[$lang['L_ID']]= $lang['L_Title'];
        }
    }

    protected function _emailValidate($isUnique = '')
    {
        $validators    = array();
        $regexValidate = new Cible_Validate_Email();
        $regexValidate->setMessage($this->getView()->getCibleText('validation_message_emailAddressInvalid'), 'regexNotMatch');
        array_push($validators, $regexValidate);

        if (!empty($isUnique))
        {
            $emailNotFoundInDBValidator = new Zend_Validate_Db_NoRecordExists($this->_object->getDataTableName(), $isUnique);
            $emailNotFoundInDBValidator->setMessage($this->getView()->getClientText('validation_message_email_already_exists'), 'recordFound');
            array_push($validators, $emailNotFoundInDBValidator);
        }

        return $validators;
    }

}
