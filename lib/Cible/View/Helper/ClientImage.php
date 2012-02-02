<?php
    class Cible_View_Helper_ClientImage extends Zend_View_Helper_Abstract
    {
        public function clientImage($source, $option=null, $absolutePath = false){

            $_image_tag = '<img src="%SOURCE%" alt="%ALT%" %ATTR%  />';
            $_source = '';

            $server_path = Zend_Registry::get('document_root') .'/';

            $base_path = "{$this->view->baseUrl()}/";

            if ($absolutePath)
                $base_path = Zend_Registry::get('absolute_web_root') . '/';

            $suffix = Zend_Registry::get('languageSuffix');

            $_config = Zend_Registry::get('config');

            $theme_path = $_config->themes->path;
            $default_theme = $_config->themes->defaultTheme;

            $current_theme = Zend_Registry::get('current_theme');

            // We try to find the image in the localized folder (fr/en/etc), if not found, we look in the common folder
            if( file_exists("{$server_path}{$theme_path}$current_theme/images/$suffix/$source") )
                $_source = "{$base_path}{$theme_path}$current_theme/images/$suffix/$source";
            else if( file_exists("{$server_path}{$theme_path}$current_theme/images/common/$source") )
                $_source = "{$base_path}{$theme_path}$current_theme/images/common/$source";

            // if image has not been found in the current theme folders and the current theme folder is not the default theme,
            // then we look in the default localizec folder, if still not
            if( $current_theme != $default_theme && empty( $_source ) ){

                if( file_exists("{$server_path}{$theme_path}$default_theme/images/$suffix/$source") )
                    $_source = "{$base_path}{$theme_path}$default_theme/images/$suffix/$source";
                else if( file_exists("{$server_path}{$theme_path}$default_theme/images/common/$source") )
                    $_source = "{$base_path}{$theme_path}$default_theme/images/common/$source";

            }

            $_alt = !empty($option['alt']) ? $option['alt'] : '';

            $_attr = '';

            if( !empty($option) )
            {
                foreach($option as $key => $value){
                    if(empty($_alt) || $key != 'alt')
                        $_attr .= "$key=\"$value\" ";
                }
            }

            return str_replace(array('%SOURCE%','%ALT%','%ATTR%'), array($_source, $_alt, $_attr), $_image_tag);
        }
    }