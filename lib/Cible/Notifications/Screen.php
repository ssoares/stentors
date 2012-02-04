<?php
/**
 * LICENSE
 *
 * @category
 * @package
 * @copyright Copyright (c)2011 Cibles solutions d'affaires - http://www.ciblesolutions.com
 * @license   Empty
 */

/**
 * Build and dispatch the messages for specific events.
 *
 * @category Cible
 * @package
 * @copyright Copyright (c)2011 Cibles solutions d'affaires - http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: Screen.php 825 2012-02-01 04:13:56Z ssoares $
 */
class Cible_Notifications_Screen extends Cible_Notifications
{
    const NEWACCOUNT = 'newAccount';
    const EDITRESEND = 'editResend';
    const EDITACCOUNT = 'editAccount';
    const WELCOME = 'welcome';
    const NEWORDER = 'newOrder';
    const CONFIRMORDER = 'confirmOrder';
    const REJECTORDER = 'rejectOrder';
    const NEWPWD = 'newPassword';
    const CONTACT = 'contact';

    protected $_data = array();

    public function __construct($options = null)
    {
        parent::__construct($options);
        var_dump($options);
        exit;

    }

    private function _newAccountClient()
    {
        $confirm_page = Zend_Registry::get('absolute_web_root') . "/"
                . Cible_FunctionsCategories::getPagePerCategoryView(
                        0,
                        'confirm_email',
                        $this->_moduleId,
                        $this->_data['language'])
                . "/email/{$this->_data['email']}/validateNumber/{$this->_data['validatedEmail']}";

        $this->_message = str_replace('##validated_email_link##', $confirm_page, $this->_message);
        $this->_message = str_replace('##siteName##', $this->_siteName, $this->_message);

        foreach ($this->_data as $key => $value)
        {
            $search = '##' . $key . '##';
            $this->_message = str_replace($search, $value, $this->_message);
        }

        $this->_emailRenderData['message'] = $this->_message;
    }

    private function _newAccountAdmin()
    {
        $siteDomain = Zend_Registry::get('absolute_web_root');
        $this->_message = str_replace('##siteDomain##', $siteDomain, $this->_message);

        foreach ($this->_data as $key => $value)
        {
            $search = '##' . $key . '##';
            $this->_message = str_replace($search, $value, $this->_message);
        }

        $this->_emailRenderData['message'] = $this->_message;
    }
    private function _editResendClient()
    {
        $this->_newAccountClient();
    }

    private function _editAccountAdmin()
    {
        $states = Cible_FunctionsGeneral::getStatesByCountry($addressFact['A_CountryId']);
        foreach ($states as $value)
            $tmpStates[$value['ID']] = $value['Name'];

        $this->_view->assign('data', $this->_data['notifyAdmin']);
        $this->_view->assign('form', $this->_data['form']);
        $this->_view->assign('states', $tmpStates);
        $changesList = $this->_view->render('index/changesList.phtml');
        $this->_message = str_replace('##TABLE##', $changesList, $this->_message);

        foreach ($this->_data as $key => $value)
        {
            $search = '##' . $key . '##';
            $this->_message = str_replace($search, $value, $this->_message);
        }
    }

    private function _welcomeClient()
    {

    }
    private function _newOrderClient()
    {

    }
    private function _newOrderAdmin()
    {

    }
    private function _confirmOrderClient()
    {

    }
    private function _rejectOrderClient()
    {

    }
    private function _newPasswordClient()
    {
        foreach ($this->_data as $key => $value)
        {
            $search = '##' . $key . '##';
            $this->_message = str_replace($search, $value, $this->_message);
        }

        $this->_emailRenderData['message'] = $this->_message;
    }
    private function _contactAdmin()
    {
        $this->_message = str_replace('##siteDomain##', $this->_siteName, $this->_message);

        foreach ($this->_data as $key => $value)
        {
            $search = '##' . $key . '##';
            $this->_message = str_replace($search, $value, $this->_message);
        }

        $this->_emailRenderData['message'] = $this->_message;
    }
}
