<?php

class Cible_Form_Block_Multilingual extends Cible_Form
{

    // Set default lang to ID 1 in case not specified in config
    protected $_currentEditLanguage = 1;
    protected $_currentMode = 'edit';
    protected $_labelCSS;

    public function __construct($options = null)
    {
        parent::__construct($options);

        $this->_currentEditLanguage = Zend_Registry::get('currentEditLanguage');
        $this->_labelCSS = Cible_FunctionsGeneral::getLanguageLabelColor($options);

        if (isset($options['addAction']))
            $this->_currentMode = 'add';

        $lang = new Cible_Form_Element_LanguageSelector('langSelector', $this->_params, array('lang' => $this->_currentEditLanguage, 'mode' => $this->_currentMode));
        $lang->setValue($this->_currentEditLanguage)
            ->removeDecorator('Label');

        $this->addElement($lang);
    }

}