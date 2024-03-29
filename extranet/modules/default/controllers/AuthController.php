<?php


class AuthController extends Cible_Extranet_Controller_Action
{

    function getForm()
    {
        $form = new Cible_Form(array('disabledDefaultActions' => true));

        $base_dir = $this->getFrontController()->getBaseUrl();
        $redirect = str_replace($base_dir, '', $this->_request->getParam('redirect'));

        $form->setAction("$base_dir/auth/login")
            ->setMethod('post');

        $form->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'table')),
            'Form'
        ));

        $form->setAttrib('class', 'auth-form');

        $username = new Zend_Form_Element_Text('username');
        $username->setLabel(Cible_Translation::getCibleText('form_label_username'));
        $username->setRequired(true);
        $username->addValidator('NotEmpty', true, array(
            'messages' => array(
                'isEmpty' => Cible_Translation::getCibleText('error_field_required'))
        ));
        $username->setAttrib('class', 'loginTextInput');
        $username->setDecorators(array(
            'ViewHelper',
            'Description',
            'Errors',
            'Label',
            array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'username')),
            array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'openOnly' => true))
        ));
        $form->addElement($username);

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel(Cible_Translation::getCibleText('form_label_password'));
        $password->setRequired(true);
        $password->addValidator('NotEmpty', true, array(
            'messages' => array(
                'isEmpty' => Cible_Translation::getCibleText('error_field_required'))
        ));
        $password->setAttrib('class', 'loginTextInput');
        $password->setDecorators(array(
            'ViewHelper',
            'Description',
            'Errors',
            'Label',
            array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'password')),
            array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'closeOnly' => true))
        ));

        $form->addElement($password);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel(Cible_Translation::getCibleText('button_authenticate'))
            ->setAttrib('class', 'loginButton')
            ->setAttrib('onmouseover', 'this.className=\'loginButtonOver\';')
            ->setAttrib('onmouseout', 'this.className=\'loginButton\';')
            ->removeDecorator('label')
            ->setDecorators(array(
                'ViewHelper',
                'Description',
                'Errors',
                array(array('data' => 'HtmlTag'), array('tag' => 'td', 'colspan' => '2', 'align' => 'right', 'class' => 'submit')),
                array(array('row' => 'HtmlTag'), array('tag' => 'tr'))
            ));

        $form->addElement($submit);

        $redirect_hidden = new Zend_Form_Element_Hidden('redirect');
        $redirect_hidden->setValue($redirect)
            ->setDecorators(array(
                'ViewHelper',
                array(array('data' => 'HtmlTag'), array('tag' => 'td', 'colspan' => '2')),
                array(array('row' => 'HtmlTag'), array('tag' => 'tr'))
            ));

        $form->addElement($redirect_hidden);

        return $form;
    }

    function indexAction()
    {
        $this->_redirect('login/auth');
    }

    function loginAction()
    {
        $form = $this->getForm();

        if ($this->_request->isPost())
        {

            $formData = $this->_request->getPost();

            if ($form->isValid($formData))
            {

                $auth = Zend_Auth::getInstance();

                // Setup adapter
                $adapter = new Zend_Auth_Adapter_DbTable($this->_db, 'Extranet_Users', 'EU_Username', 'EU_Password', 'MD5(?)');

                $adapter->setIdentity($_POST['username'])
                    ->setCredential($_POST['password']);

                // Authenticate
                $result = $auth->authenticate($adapter);

                switch ($result->getCode())
                {
                    case Zend_Auth_Result::FAILURE:
                    case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                    case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
                    case Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS:
                        $error = Cible_Translation::getCibleText('error_auth_failure');
                }

                if (!$result->isValid())
                {
                    $this->view->assign('error', $error);
                }
                else
                {
                    $auth->getStorage()->write($adapter->getResultRowObject(array('EU_ID', 'EU_LName', 'EU_FName', 'EU_Email')));


                    // build ACL rights
                    $data = (array) $auth->getStorage()->read();
                    $acl = Cible_FunctionsAdministrators::getACLUser($data['EU_ID']);
                    $defaultSession = new Zend_Session_Namespace();
                    $defaultSession->acl = $acl;

                    $this->_redirect($this->getRequest()->getParam('redirect'));
                }
            }
        }

        $this->view->assign('form', $form);
    }

    function logoutAction()
    {
        if (Zend_Auth::getInstance()->hasIdentity())
        {
            Zend_Auth::getInstance()->clearIdentity();
        }

        $this->_redirect('auth/login');
    }
}