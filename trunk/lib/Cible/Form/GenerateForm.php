<?php

class Cible_Form_GenerateForm extends Cible_Form_Multilingual
{
    protected $_elemNameId = '';
    protected $_srcData = array();

    public function autoGenerate()
    {
        $metaData = array();
        $object = $this->_object;

        $metaData = $object->getColsData();

        foreach ($metaData as $key => $meta)
            $this->setFormFields($meta, $key);

        $indexTable = $object->getIndexTableName();
        if (!empty ($indexTable))
        {
            $metaIndex = $object->getColsIndex();
            foreach ($metaIndex as $key => $meta)
                $this->setFormFields($meta, $key);
        }
    }
    public function setFormFields($meta, $key)
    {
        $params = Cible_FunctionsGeneral::fetchParams($meta['COMMENT']);

        if (!isset($params['exclude']) || false == (bool)$params['exclude'])
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

                default:
                    if (preg_match('/^enum/', $meta['DATA_TYPE']))
                    {
                        $params['elem'] = 'select';
                        $params['src'] = 'enum';
                        $this->setElementInput($meta, $params);
                    }
                    break;
            }
        }
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
            $fieldId = $meta['COLUMN_NAME'];
            switch ($params['elem'])
            {
                case 'select':
                    if (empty($params['src']))
                        throw new Exception ('Trying to build an element but no data source given');

                    $this->_defineSrc($params, $meta);

                    $element = new Zend_Form_Element_Select($fieldId);
                    $element->setLabel($this->getView()->getCibleText('form_label_' . $fieldId))
                        ->setAttrib('class', 'largeSelect')
                        ->addMultiOptions($this->_srcData);
                    break;
                case 'checkbox':
                    $element = new Zend_Form_Element_Checkbox($fieldId);
                    $element->setLabel($this->getView()->getCibleText('form_label_' . $fieldId));
                    $element->setDecorators(array(
                        'ViewHelper',
                        array('label', array('placement' => 'append')),
                        array(array('row' => 'HtmlTag'), array('tag' => 'dd', 'class' => 'label_after_checkbox')),
                    ));
                    break;
                case 'radio':

                    $this->_defineSrc($params, $meta);

                    $element = new Zend_Form_Element_Radio($fieldId);
                    $element->setLabel($this->getView()->getCibleText('form_label_' . $fieldId));
                    $element->setDecorators(array(
                        'ViewHelper',
                        array('label', array('placement' => 'append')),
                        array(array('row' => 'HtmlTag'), array('tag' => 'dd', 'class' => 'radio radioInline')),
                        ))
                        ->addMultiOptions($this->_srcData);
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
        switch ($params['elem'])
        {
            case 'tiny':
                $element = new Cible_Form_Element_Editor(
                    $meta['COLUMN_NAME'],
                    array('mode'=>Cible_Form_Element_Editor::ADVANCED));
                $element->setLabel($this->getView()->getCibleText('form_label_' . $meta['COLUMN_NAME']))
                    ->setAttrib('class','mediumEditor');
                break;

            default:
                $element = new Zend_Form_Element_Textarea($meta['COLUMN_NAME']);
                $element->setLabel($this->getView()->getCibleText('form_label_' . $meta['COLUMN_NAME']))
                    ->setAttrib('class','mediumEditor');
                break;
        }
        $label = $element->getDecorator('Label');
        $label->setOption('class', $this->_labelCSS);
        $this->addElement($element);
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
        
          if (!empty($params['validate']))
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

    protected function _enumSrc(Array $meta = array())
    {
        $values = explode(',',str_replace(array('enum(', ')', "'"), '', $meta['DATA_TYPE']));
        foreach ($values as $key => $value)
        {
            $this->_srcData[$key + 1] = $value;
        }
    }
    protected function _salutationsSrc(Array $meta = array())
    {
        $greetings = $this->getView()->getAllSalutation();
        foreach ($greetings as $greeting)
        {
            $this->_srcData[$greeting['S_ID']]= $greeting['ST_Value'];
        }
    }
    protected function _languagesSrc(Array $meta = array())
    {
        $langs = Cible_FunctionsGeneral::getAllLanguage();

        foreach ($langs as $lang)
        {
            $this->_srcData[$lang['L_ID']]= $lang['L_Title'];
        }
    }
    protected function _modulesSrc(Array $meta = array())
    {
        $modules = Cible_FunctionsModules::getModules();

        foreach ($modules as $data)
        {
            $this->_srcData[$data['M_ID']]= Cible_Translation::getCibleText($data['M_MVCModuleTitle'] . "_module_name");
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

    private function _defineSrc($params, $meta)
    {
        $srcName = $params['src'];
        $srcMethod = '_' . $srcName . 'Src';
        $this->_srcData = array();
        if (!in_array($srcMethod, get_class_methods($this)))
        {
            $this->_srcData = $this->_object->$srcMethod($meta);
        }
        else
            $this->$srcData($meta);
    }
}