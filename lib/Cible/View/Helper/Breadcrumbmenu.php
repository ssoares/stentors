<?php

class Cible_View_Helper_Breadcrumbmenu extends Zend_View_Helper_Abstract
{

    public function breadcrumbmenu($options)
    {
        $selectedItemMenuID = 0;
        $menuTitle = isset($options['menuTitle']) ? $options['menuTitle'] : '';
        if (Zend_Registry::isRegistered('selectedItemMenuID'))
            $selectedItemMenuID = Zend_Registry::get('selectedItemMenuID');

        return Cible_FunctionsPages::buildClientBreadcrumbMenu($selectedItemMenuID, 1, $menuTitle);
    }

}