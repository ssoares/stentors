<?php
    class Cible_View_Helper_IsAdministrator extends Zend_View_Helper_Abstract
    {
        public function IsAdministrator(){
            $auth = Zend_Auth::getInstance();
            $data = (array)$auth->getStorage()->read();
            
            if(empty($data))
                return false;
                
            $authID = $data['EU_ID'];
            
            $administrator = new ExtranetUsersGroups();
            $select = $administrator->select();
            $select->where('EUG_UserID = ?', $authID)
            ->where('EUG_GroupID = 1');
            $row = $administrator->fetchRow($select);
            if (count($row) == 0)
                return false;
            else
                return true;
        }
    }