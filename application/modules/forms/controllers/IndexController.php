<?php

class Forms_IndexController extends Cible_Controller_Action
{

    protected $_moduleID = 11;

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

        $bannersRob = new FormsRobots();
        $dataXml = $bannersRob->getXMLFile($this->_registry->absolute_web_root, $this->_request->getParam('lang'));

        parent::siteMapAction($dataXml);
    }

    public function formscontactAction()
    {
        $blockParamEmail = Cible_FunctionsBlocks::getBlockParameter($this->_getParam('BlockID'), '1');
        if (isset($blockParamEmail))
            $mailTo = $blockParamEmail;

        $form = new FormContact();
        $this->view->assign('form', $form);
        if ($this->_request->isPost())
        {
            $formData = $this->_request->getPost();

            if (array_key_exists('submit', $formData))
            {
                if ($form->isValid($formData))
                {
                    // send the mail
                    $data = array(
                        'firstName' => $formData['prenom'],
                        'lastName' => $formData['name'],
                        'email' => $formData['email'],
                        'comments' => $formData['commentaire'],
                        'language' => 1,
                    );
                    $options = array(
                        'send' => true,
                        'isHtml' => true,
                        'moduleId' => $this->_moduleID,
                        'event' => 'contact',
                        'type' => 'email',
                        'recipient' => 'admin',
                        'data' => $data
                    );
                    if (!empty($mailTo))
                        $options['to'] = $mailTo;

                    $oNotification = new Cible_NotificationManager($options);

                    $this->view->assign('inscriptionValidate', true);
                }
            }
        }
        else
        {

        }
    }

    public function captchaReloadAction()
    {
        $baseDir = $this->view->baseUrl();
        $captcha_image = new Zend_Captcha_Image(array(
                'captcha' => 'Word',
                'wordLen' => 6,
                'height' => 50,
                'width' => 150,
                'timeout' => 600,
                'dotNoiseLevel' => 0,
                'lineNoiseLevel' => 0,
                'font' => Zend_Registry::get('application_path') . "/../{$this->_config->document_root}/captcha/fonts/ARIAL.TTF",
                'imgDir' => Zend_Registry::get('application_path') . "/../{$this->_config->document_root}/captcha/tmp",
                'imgUrl' => "$baseDir/captcha/tmp"
            ));

        $image = $captcha_image->generate();
        $captcha['id'] = $captcha_image->getId();
        $captcha['word'] = $captcha_image->getWord();
        $captcha['url'] = $captcha_image->getImgUrl() . $image . $captcha_image->getSuffix();

        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        echo Zend_Json::encode($captcha);
    }

    public function thankYouAction()
    {

    }

}