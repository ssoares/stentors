<?php

class Retailers_IndexController extends Cible_Controller_Action
{

    /**
     * Overwrite the function define in the SiteMapInterface implement in Cible_Controller_Action
     *
     * This function return the sitemap specific for this module
     *
     * @access public
     *
     * @return a string containing xml sitemap
     */
    public function siteMapAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $newsRob = new RetailersRobots();
        $dataXml = $newsRob->getXMLFile($this->_registry->absolute_web_root, $this->_request->getParam('lang'));

        parent::siteMapAction($dataXml);
    }

    public function indexAction()
    {
        $form = new FormRetailers();
        $this->view->assign('form', $form);


        if ($this->_request->isPost())
        {

        }
        else
        {

        }

        $list = '';
        $this->view->assign('list', $list);
        $this->view->assign('selectedState', '11');
    }

    public function selectOneAction()
    {
        $authentication = Cible_FunctionsGeneral::getAuthentication();


        if ($authentication)
        {
            $profile = new MemberProfile();
            $memberInfos = $profile->findMember(
                array(
                    'email' => $authentication['email']));

            $data = array(
                'city' => $memberInfos['cityId'],
                'country' => $memberInfos['country'],
                'state' => $memberInfos['state']);

            $this->view->assign('selectedState', $data['state']);
            $this->view->assign('selectedCity', $data['city']);
        }

        $form = new FormRetailersSelectOne();
        $this->view->assign('form', $form);


        if ($this->_request->isPost())
        {

        }
        else
        {
            $form->populate($data);
        }

        $list = '';
        $this->view->assign('list', $list);
    }

    public function ajaxCitiesAction()
    {
        if ($this->_isXmlHttpRequest)
        {
            $this->getHelper('viewRenderer')->setNoRender();

            $stateId = $this->_getParam('stateId');
            $filter = $this->_getParam('filter');

            $cities = new CitiesObject();

            /*
             * if $filter = true(1) then only display cities having
             * retailers else list all the cities for registration for
             * example.
             */
            if ($filter)
                $cities->setFilter(true);

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
        $filter = 0;

        if ($this->_isXmlHttpRequest)
        {
            $this->getHelper('viewRenderer')->setNoRender();

            $countryId = $this->_getParam('countryId');
            $languageId = $this->_getParam('langId');
            $filter = $this->_getParam('filter');

            $states = Cible_FunctionsGeneral::getStateByCode(
                    $countryId, null, $languageId);

            if (is_array($states))
            {
                foreach ($states as $id => $data)
                {
                    $statesData[$id]['id'] = $data['id'];
                    $statesData[$id]['name'] = utf8_encode($data['name']);
                }
            }

            echo json_encode($statesData);
        }
    }

    public function ajaxRetailersAction()
    {
        if ($this->_isXmlHttpRequest)
        {
            $this->getHelper('viewRenderer')->setNoRender();
            $this->disableLayout();
            $this->disableView();

            $langId = $this->_getParam('langId');
            $retailerId = $this->_getParam('retailerId');
            $field = $this->_getParam('field');
            $oRetailers = new RetailersObject();

            $params = array(
                'field' => $field,
                'value' => $this->_getParam('value')
            );

            if ($retailerId)
            {
                $retailersData = $oRetailers->getRetailersDataByCities($params, $retailerId, $langId);
            }
            else
                $retailersData = $oRetailers->getRetailersDataByCities($params, null, $langId);

            if ($this->_getParam('render') != 'false')
            {
                $retailers = $this->renderRetailers($retailersData);
            }
            else
            {
                foreach ($retailersData as $data)
                {
                    foreach ($data as $key => $value)
                    {
                        $data[$key] = utf8_encode($value);
                    }
                    $dataForJson[] = $data;
                }
                $retailers = $dataForJson;
            }
            echo json_encode($retailers);
        }
    }

    private function renderRetailers($retailersData)
    {
        $tmp = '';
        $i = 0;
        $columns = 2;
        $modulo = 0;

        if ($retailersData)
        {
            $telLabel = $this->view->getClientText('phone_short_label');
            $faxLabel = $this->view->getClientText('fax_short_label');

            $tmp .= '<table class="table_partenaire">';
            foreach ($retailersData as $retailer)
            {
                $modulo = $i % $columns;
                if ($modulo == 0)
                    $tmp .= '<tr><td width="300">';
                else
                    $tmp .= '<td width="300">';

                // Retailer info
                $tmp .= '<span class="greenParagraph">'
                    . utf8_encode($retailer['Name'])
                    . '</span><br />';
                if (!empty($retailer['FirstAddress']))
                    $tmp .= utf8_encode($retailer['FirstAddress']) . '<br />';
                if (!empty($retailer['SecondAddress']))
                    $tmp .= utf8_encode($retailer['SecondAddress']) . '<br />';
                if (!empty($retailer['cityName']))
                    $tmp .= utf8_encode($retailer['cityName']);
                if (!empty($retailer['stateName']))
                    $tmp .= ', ' . utf8_encode($retailer['stateName']) . '<br />';
                if (!empty($retailer['ZipCode']))
                    $tmp .= '<span class="zipCode_format">' . utf8_encode($retailer['ZipCode']) . '</span><br />';
                if (!empty($retailer['FirstTel']))
                    $tmp .= utf8_encode($telLabel) . ": " . utf8_encode($retailer['FirstTel']);
                if (!empty($retailer['FirstExt']))
                    $tmp .= '  ext: ' . utf8_encode($retailer['FirstExt']);
                $tmp .= '<br />';
                if (!empty($retailer['SecondTel']))
                    $tmp .= utf8_encode($telLabel) . ": " . utf8_encode($retailer['SecondTel']);
                if (!empty($retailer['SecondExt']))
                    $tmp .= '  ext: ' . utf8_encode($retailer['SecondExt']);
                $tmp .= '<br />';
                if (!empty($retailer['Fax']))
                    $tmp .= utf8_encode($faxLabel) . ": " . utf8_encode($retailer['Fax']) . '<br />';
                if (!empty($retailer['Email']))
                    $tmp .= utf8_encode($retailer['Email']) . '<br />';
                if (!empty($retailer['Website']))
                    $tmp .= utf8_encode($retailer['Website']) . '<br />';
                if ($modulo == $columns - 1)
                    $tmp .= '</td>' . chr(13) . '</tr>';
                else
                    $tmp .= '</td>' . chr(13);

                $i++;
            }

            $emptyRowCount = ($columns - 1) - $modulo;
            for ($i = 0; $i < $emptyRowCount; $i++)
                $tmp .= "<td>&nbsp;</td>" . chr(13);

            if ($emptyRowCount > 0)
                $tmp .= '</tr>';

            $tmp .= '</table>';
        }
        return $tmp;
    }

}