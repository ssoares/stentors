<?php

abstract class Cible_Extranet_Controller_Action extends Cible_Controller_Action
{
    public function init()
    {
        parent::init();
        
        $this->view->assign( 'user', $this->view->auth() );

        $session = new Cible_Sessions();
        
        // Defines the default interface language
        if( $this->_config->defaultInterfaceLanguage )
            $this->_defaultInterfaceLanguage = $this->_config->defaultInterfaceLanguage;

        // Check if the current interface language should be different than the default one
        $this->_currentInterfaceLanguage = !empty( $session->languageID ) ? $session->languageID : $this->_defaultInterfaceLanguage;

        if( $this->_getParam('setLang'))
            $this->_currentInterfaceLanguage = Cible_FunctionsGeneral::getLanguageID( $this->_getParam('setLang') );

        // Registers the current interface language for future uses
        $this->_registry->set('languageID', $this->_currentInterfaceLanguage);
        $session->languageID = $this->_currentInterfaceLanguage;

        $suffix = Cible_FunctionsGeneral::getLanguageSuffix( $this->_currentInterfaceLanguage );
        $this->_registry->set('languageSuffix', $suffix);

        // Defines the default edit language
        if( $this->_config->defaultEditLanguage )
            $this->_currentEditLanguage = $this->_config->defaultEditLanguage;
        else
            $this->_currentEditLanguage = $this->_defaultEditLanguage;

        $this->_currentEditLanguage = !empty( $session->currentEditLanguage ) ? $session->currentEditLanguage : $this->_currentEditLanguage;

        // Check if the current edit language should be different than the default one
        if( $this->_getParam('lang') ) {
            $this->_currentEditLanguage = Cible_FunctionsGeneral::getLanguageID( $this->_getParam('lang') );
        }

        // Registers the current edit language for future uses
        $this->_registry->set('currentEditLanguage', $this->_currentEditLanguage);
        $session->currentEditLanguage = $this->_currentEditLanguage;

        if( Cible_FunctionsGeneral::extranetLanguageIsAvailable($this->getCurrentInterfaceLanguage()) == 0 ){
            
            $session = new Cible_Sessions();
            
            $this->_currentInterfaceLanguage = $this->_config->defaultInterfaceLanguage;
            
            // Registers the current interface language for future uses
            $this->_registry->set('languageID', $this->_currentInterfaceLanguage);
            $session->languageID = $this->_currentInterfaceLanguage;
            
            $suffix = Cible_FunctionsGeneral::getLanguageSuffix( $this->_currentInterfaceLanguage );
            $this->_registry->set('languageSuffix', $suffix);
        }   
    }
    
}
