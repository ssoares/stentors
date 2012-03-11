<?php

class Cible_View_Helper_RenderYesNo extends Zend_View_Helper_Abstract
{

    public function renderYesNo($fieldValue)
    {
        $imgBox = $this->view->image($this->view->locateFile('box.png'), array('class' => 'checkboxPrint'));
        $imgChecked = $this->view->image($this->view->locateFile('checkmark.png'), array('class' => 'checkboxPrint'));
        $yes = $imgChecked . ' OUI ' . $imgBox . ' NON';
        $no  = $imgBox . ' OUI ' . $imgChecked . ' NON';
        $yesNoEmpty = $imgBox . ' OUI ' . $imgBox . ' NON';
        switch ($fieldValue)
        {
            case 1:
                $result = $yes;
                break;
            case 2:
                $result = $no;
                break;
            default:
                $result = $yesNoEmpty;
                break;
        }
        return $result;
    }

}