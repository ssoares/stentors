<?php
    
    class Retailers_IndexController extends Cible_Controller_Block_Abstract
    {
        protected $_moduleID = 16;
        protected $_defaultAction = '';
        
        public function addAction(){
            throw new Exception('Not implemented');
        }
        
        public function editAction(){
            throw new Exception('Not implemented');
        }
        
        public function deleteAction(){
            throw new Exception('Not implemented');
        }
        public function ajaxCitiesAction()
        {
            if ($this->_isXmlHttpRequest)
            {
                $this->getHelper('viewRenderer')->setNoRender();

                $stateId    = $this->_getParam('stateId');
                $cities     = new VilleObject();
                $citiesData = $cities->getCitiesDataByStates($stateId);

                foreach ($citiesData as $id => $data)
                {
                    $citiesData[$id]['C_Name'] = utf8_encode($data['C_Name']);
                }

                echo json_encode($citiesData);
            }
        }

        public function ajaxStatesAction()
        {
            if ($this->_isXmlHttpRequest)
            {
                $this->getHelper('viewRenderer')->setNoRender();

                $countryId  = $this->_getParam('countryId');
                $languageId = $this->_getParam('langId');

                $states = Cible_FunctionsGeneral::getStateByCode(
                        $countryId,
                        null,
                        $languageId);

                if (is_array($states))
                {
                    foreach ($states as $id => $data)
                    {
                        $statesData[$id ]['id']   = $data['id'];
                        $statesData[$id ]['name'] = utf8_encode($data['name']);
                    }
                }

                echo json_encode($statesData);
            }
        }
    }
?>