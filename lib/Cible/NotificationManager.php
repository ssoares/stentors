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
 * @version   $Id: NotificationManager.php 728 2011-12-08 05:00:08Z ssoares $
 */
class Cible_NotificationManager extends Cible_Notify
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

    protected $_view;
    protected $_request;
    protected $_moduleId = "";
    protected $_type = "";
    protected $_isActive = false;
    protected $_event = "";
    protected $_recipient = "";
    protected $_siteName = "";
    protected $_message;
    protected $_emailRenderData = array();
    protected $_data = array();

    public function setView($_view)
    {
        $this->_view = $_view;
    }

    public function setRequest($_request)
    {
        $this->_request = $_request;
    }

    public function setModuleId($_moduleId)
    {
        $this->_moduleId = $_moduleId;
    }

    public function setType($_type)
    {
        $this->_type = $_type;
    }

    public function setEvent($_event)
    {
        $this->_event = $_event;
    }

    public function setRecipient($_recipient)
    {
        $this->_recipient = $_recipient;
    }

    public function setData($_data)
    {
        $this->_data = $_data;
    }

    public function setMessage($_message)
    {
        $this->_message = $_message;
    }


    public function __construct($options = null)
    {
        $this->fetchMessage();
        if ($this->_isActive)
        {
            $_frontController = Zend_Controller_Front::getInstance();
            $this->_request = $_frontController->getRequest();

            if (null === $this->_view)
            {
                require_once 'Zend/Controller/Action/HelperBroker.php';
                $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
                $this->_view = $viewRenderer->view;
            }

            $this->_siteName = Zend_Registry::get('siteName');
            $this->setProperties($options);

            if (isset ($options['isHtml']))
                $this->_isHtml = $options['isHtml'];

            if (isset ($options['to']))
                $this->addTo($options['to']);

            if (is_null($this->_to))
                $this->addTo($this->_data['email']);


            $this->_emailRenderData['emailHeader'] = $this->_view->clientImage('logo.jpg', null, true);
    //        $this->_emailRenderData['emailHeader'] .= $this->_view->getClientText("email_notification_header", $this->_data['language']);
            $footer = $this->_view->getClientText("email_notification_footer", $this->_data['language']);
            $this->_emailRenderData['footer'] = str_replace('##SITE-NAME##', $this->_siteName, $footer);

            $method = '_' . $this->_event . ucfirst($this->_recipient);
            $this->$method();

            $this->_view->assign('emailRenderData', $this->_emailRenderData);
            $this->_message = $this->_view->render('index/emailNotification.phtml');

            if ($options['send'])
                $this->send();
        }
    }

    /**
     * Set the properties of he class.
     *
     * @param array $otpions Properties to build the notification.
     *
     * @return void
     */
    public function setProperties($options = array())
    {
        foreach ($options as $property => $value)
        {
            $methodName = 'set' . ucfirst($property);

            if (property_exists($this, '_' . $property)
                && method_exists($this, $methodName))
            {
                $this->$methodName($value);
            }
        }
    }

    protected function fetchMessage()
    {
        $oData = new NotificationManagerObject();

        $notification = $oData->fetchData($this->_moduleId, $this->_event, $this->_recipient);

        $this->_isActive = (bool)$notification['NM_Active'];
        $this->_message = $this->_view->getClientText($notification['NM_Message'], $this->_data['language']);

        $this->_title = $this->_view->getClientText($notification['NM_Title'], $this->_data['language']);
        $this->_from = $notification['NM_Email'];

        if ($this->_recipient == 'admin' && is_null($this->_to))
            $this->addTo($notification['NM_Email']);
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
