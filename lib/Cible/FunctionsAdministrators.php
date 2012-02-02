<?php
    abstract class Cible_FunctionsAdministrators
    {
        public static function getAllAdministratorGroups($searchText = "", $listOrder = "")
        {
            $administratorGroupData = new ExtranetGroups();
            $select = $administratorGroupData->select()
            ->setIntegrityCheck(false)
            ->from('Extranet_Groups')
            ->join('Extranet_GroupsIndex', 'Extranet_GroupsIndex.EGI_GroupID = Extranet_Groups.EG_ID')
            ->where('Extranet_GroupsIndex.EGI_LanguageID = ?', Zend_Registry::get("languageID"))
            ->where('EG_ID > 1');
          
            /* search */
            if ($searchText <> ""){
              $select->Where("Extranet_GroupsIndex.EGI_Name LIKE '%".$searchText."%'"); 
            }

            /* order */
            if ($listOrder <> ""){
              $select->order($listOrder);
            }

            $select->order('EGI_Name');

            return $administratorGroupData->fetchAll($select);
        }
        
        public static function getAdministratorGroupData($groupID)
        {
            $groupData = new ExtranetGroups();
            $select = $groupData->select()
            ->setIntegrityCheck(false)
            ->from('Extranet_Groups')
            ->join('Extranet_GroupsIndex', 'Extranet_Groups.EG_ID = Extranet_GroupsIndex.EGI_GroupID')
            ->where("EG_ID = ?", $groupID)
            ->where("EGI_LanguageID = ?", Zend_Registry::get("languageID"));
            
            return $groupData->fetchRow($select);
        }
        
        
        
        public static function getAdministratorData($administratorID)
        {
            $users = new ExtranetUsers();
            $select = $users->select()
            ->where("EU_ID = ?", $administratorID);
            
            return $users->fetchRow($select);
        }
        
        public static function checkAdministratorPageAccess($adminID, $pageID, $permission)
        {
            $administrator = new ExtranetUsersGroups();
            $select = $administrator->select();
            $select->where('EUG_UserID = ?', $adminID)
            ->where('EUG_GroupID = 1');
            $row = $administrator->fetchRow($select);
            
            if (count($row) == 0){
            
                $permissionPage = new ExtranetUsersGroups();
                $select = $permissionPage->select()->setIntegrityCheck(false);
                $select->from('Extranet_UsersGroups')
                ->join('Extranet_Groups','EG_ID = EUG_GroupID')
                ->join('Extranet_Groups_Pages_Permissions','EGPP_GroupID = EUG_GroupID')
                ->where('EUG_UserID = ?', $adminID)
                ->where('EGPP_PageID = ?', $pageID)
                ->where('EG_Status = "active"');
                
                
                if ($permission == "structure")
                    $select->where('EGPP_Structure = "Y"');
                elseif ($permission == "data")
                    $select->where('EGPP_Data = "Y"');
                    
                $row = $permissionPage->fetchRow($select);
                
                if(count($row) == 0)
                    return false;
                else
                    return true;
            }
            else
                return true;
        }
        public static function CheckGroupPagesPermissions($groupID, $pageID, $permission){
            if($groupID == null)
                return false;
                
            $groupPagePermission = new ExtranetGroupsPagesPermissions();
            $select = $groupPagePermission->select()->setIntegrityCheck(false);
            $select->from('Extranet_Groups_Pages_Permissions')
                    ->where('EGPP_GroupID = ?', $groupID)
                    ->where('EGPP_PageID = ?', $pageID);
            
            if ($permission == "structure")
                $select->where('EGPP_Structure = "Y"');
            elseif ($permission == "data")
                $select->where('EGPP_Data = "Y"');           
                     
            $row = $groupPagePermission->fetchRow($select);   
            if (count($row) == 0)
                return false;
            else
                return true;            
        
        }
        
        public static function getAllUserGroups($userID)
        {
            $userGroupAssociationData = new ExtranetUsersGroups();
            $select = $userGroupAssociationData->select()
            ->where('EUG_UserID = ?', $userID);
            
            return $userGroupAssociationData->fetchAll($select);
        }
        
        public static function getACLUser($authID)
        {
            // get user data
            //$authData = $this->view->user;
            //$authID     = $authData['EU_ID'];    
            
            $acl = new Zend_Acl();
            
            
            /***************** ADDING ALL RESOURCES ************************/
            $resourcesSelect = new ExtranetResources();
            $select = $resourcesSelect->select();
            $resourcesData = $resourcesSelect->fetchAll($select);
            
            foreach ($resourcesData as $resource){
                $resource = new Zend_Acl_Resource($resource['ER_ControlName']);
                $acl->add($resource);  
            }
            
            /*************** ADDING ALL ROLES ********************************/
            $rolesSelect = new ExtranetRoles();
            $select = $rolesSelect->select();
            $rolesData = $rolesSelect->fetchAll($select);
            
            $rolesArray = array();
            foreach($rolesData as $role){
                $rolesArray[$role['ER_ID']]['name'] = $role['ER_ControlName'];
                $rolesArray[$role['ER_ID']]['parent'] = array(); 
                
                $rolesParentSelect = new ExtranetRolesResources();
                $select = $rolesParentSelect->select()->setIntegrityCheck(false);
                $select->where('ERR_RoleID = ?', $role['ER_ID'])
                ->order('ERR_InheritedParentID');
                
                $rolesParentData = $rolesParentSelect->fetchAll($select);
                $rolesParentArray = array();
                foreach ($rolesParentData as $roleParent){
                    if ($roleParent['ERR_InheritedParentID'] <> 0){
                        $roleSelect = new ExtranetRolesResources();
                        $select = $roleSelect->select()->setIntegrityCheck(false);
                        $select->from('Extranet_RolesResources')
                        ->join('Extranet_Roles', 'ER_ID = ERR_RoleID')
                        ->where('ERR_ID = ?', $roleParent['ERR_InheritedParentID']);
                        
                        $roleData = $roleSelect->fetchRow($select);
                        if (!in_array($roleData['ER_ControlName'],$rolesParentArray))
                            $rolesParentArray[count($rolesParentArray)] = $roleData['ER_ControlName'];
                    }
                }
            }
            $rolesArray[$role['ER_ID']]['parent'] = $rolesParentArray;
             
            foreach ($rolesArray as $roleArray){
                $role = new Zend_Acl_Role($roleArray['name']);
                $acl->addRole($role,$roleArray['parent']);    
            }
            
            $role = new Zend_Acl_Role($authID);
            $acl->addRole($role);    
            
            // get all groups of the current user
            $groupsData = Cible_FunctionsAdministrators::getAllUserGroups($authID);
            
            $admin = false;
            foreach($groupsData as $group){
                if ($group['EUG_GroupID'] == 1)
                    $admin = true;
                $groupRoleResourceSelect = new ExtranetGroupsRolesResources();
                $select = $groupRoleResourceSelect->select();
                $select->where('EGRRP_GroupID = ?', $group['EUG_GroupID']);
                $groupRoleResourceData = $groupRoleResourceSelect->fetchAll($select)->toArray();
                //$this->view->dump($groupRoleResourceData);
                
                foreach ($groupRoleResourceData as $groupRoleResource){
                    $acl = Cible_FunctionsAdministrators::addAllRolesResourcesPermissionsUser($acl,$authID,$groupRoleResource['EGRRP_RoleResourceID']);
                }
                    
            }
            return $acl;
            //echo $acl->isAllowed($authID, 'news', 'publish') ? "autorisé" : "refusé";  
                                    
        }
    
        public static function addAllRolesResourcesPermissionsUser($acl,$userID,$roleRessourceID)
        {
            $roleResourceSelect = new  ExtranetRolesResources();
            $select = $roleResourceSelect->select()->setIntegrityCheck(false);
            $select->from('Extranet_RolesResources', array('ResourceName'=>'Extranet_Resources.ER_ControlName','RoleName'=>'Extranet_Roles.ER_ControlName', 'ERR_InheritedParentID','ERR_ID'))
            ->join('Extranet_Resources', 'Extranet_Resources.ER_ID = ERR_ResourceID')
            ->join('Extranet_Roles', 'Extranet_Roles.ER_ID = ERR_RoleID')
            ->where('ERR_ID = ?', $roleRessourceID);
            
            $roleResourceData = $roleResourceSelect->fetchAll($select)->toArray();
            //print_r($roleResourceData);
            
            
            
            foreach($roleResourceData as $roleResource){
                if ($roleResource['ERR_InheritedParentID'] <> 0){
                    $acl = Cible_FunctionsAdministrators::addAllRolesResourcesPermissionsUser($acl,$userID,$roleResource['ERR_InheritedParentID']);
                }
                
                // get all permission
                //$this->view->dump($roleResource);   
                
                // get all permission of a role resources associated
                $roleResourcePermissionsSelect = new ExtranetRolesResourcesPermissions();
                $select = $roleResourcePermissionsSelect->select()->setIntegrityCheck(false);
                $select->from('Extranet_RolesResourcesPermissions')
                ->join('Extranet_Permissions', 'EP_ID = ERRP_PermissionID')
                ->where('ERRP_RoleResourceID = ?', $roleResource['ERR_ID']);
                
                $roleResourcePermissionsData = $roleResourcePermissionsSelect->fetchAll($select);
                //$this->view->dump($roleResourcePermissionsData->toArray());
                
                
                foreach ($roleResourcePermissionsData as $permission){
                    $acl->allow($userID, $roleResource['ResourceName'], $permission['EP_ControlName']);         
                }
            }
            
            return $acl;    
        }
  }
?>
