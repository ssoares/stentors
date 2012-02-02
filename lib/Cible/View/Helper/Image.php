<?php
    class Cible_View_Helper_Image extends Zend_View_Helper_Abstract
    {
        public function image($source, $option=null){

            $_image_tag = '<img src="%SOURCE%" alt="%ALT%" %ATTR%  />';

            $_source = $source;
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