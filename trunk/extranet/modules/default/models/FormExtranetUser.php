<?php
    class FormExtranetUser extends Cible_Form
    {
        public function __construct($options = null, $groupsData = array())
        {
            // variable
            parent::__construct($options);
            $baseDir = $options['baseDir'];
            if(array_key_exists('profile',$options))
                $profile = $options['profile'];
            else
                $profile = false;


            // lastname
            $lname = new Zend_Form_Element_Text('EU_LName');
            $lname->setLabel($this->getView()->getCibleText('form_label_lname'))
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $this->getView()->getCibleText('validation_message_empty_field'))))
            ->setAttrib('class','stdTextInput')
            ->setAttrib('escape',false);

            $this->addElement($lname);

            // firstname
            $fname = new Zend_Form_Element_Text('EU_FName');
            $fname->setLabel($this->getView()->getCibleText('form_label_fname'))
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $this->getView()->getCibleText('validation_message_empty_field'))))
            ->setAttrib('class','stdTextInput');

            $this->addElement($fname);

            // email
            $regexValidate = new Cible_Validate_Email();
            $regexValidate->setMessage($this->getView()->getCibleText('validation_message_emailAddressInvalid'), 'regexNotMatch');

            $email = new Zend_Form_Element_Text('EU_Email');
            $email->setLabel($this->getView()->getCibleText('form_label_email'))
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addFilter('StringToLower')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $this->getView()->getCibleText('validation_message_empty_field'))))
            ->addValidator($regexValidate)
            ->setAttrib('class','stdTextInput');

            $this->addElement($email);

            // username
            $username = new Zend_Form_Element_Text('EU_Username');
            $username->setLabel($this->getView()->getCibleText('form_label_username'))
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $this->getView()->getCibleText('validation_message_empty_field'))))
            ->setAttrib('class','stdTextInput')
            ->setAttrib('autocomplete', 'off');

            $this->addElement($username);

            // new password
            $password = new Zend_Form_Element_Password('EU_Password');
            $password->setLabel($this->getView()->getCibleText('form_label_newPwd'))
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setAttrib('class','stdTextInput')
            ->setAttrib('autocomplete', 'off');;

            $this->addElement($password);

            // password confirmation
            $passwordConfirmation = new Zend_Form_Element_Password('PasswordConfirmation');
            $passwordConfirmation->setLabel($this->getView()->getCibleText('form_label_confirmNewPwd'))
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setAttrib('class','stdTextInput');

            if (!empty($_POST['EU_Password'])){
                $passwordConfirmation->setRequired(true)
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $this->getView()->getCibleText('error_message_password_isEmpty'))));

                $Identical = new Zend_Validate_Identical($_POST['EU_Password']);
                $Identical->setMessages(array('notSame' => $this->getView()->getCibleText('error_message_password_notSame')));
                $passwordConfirmation->addValidator($Identical);
            }
            $this->addElement($passwordConfirmation);

            if($profile <> true){
                // html text
                $textAdministratorGroup = new Cible_Form_Element_Html('htmlAdministratorGroup',array('value'=>$this->getView()->getCibleText('label_administrator_actives')));
                $this->addElement($textAdministratorGroup);

                $checkBox = new Zend_Form_Element_MultiCheckbox('groups');
                $checkBox->setDecorators(array(
                    'ViewHelper',
                    array(
                        array('row' => 'HtmlTag'), array('tag' => 'dd', 'class' => 'checkbox_list')
                    ),
                ));
                //$checkBox->setDescription('<em>Example:</em> mydomain.com')
                //->addDecorator('Description', array('escape' => false));

                //show administrator group (first level)
                $groupAdmin = Cible_FunctionsAdministrators::getAdministratorGroupData(1)->toArray();
                $checkBox->addMultiOption("1",$groupAdmin['EGI_Name']." (".$groupAdmin['EGI_Description'].")");


                $i = 0;
                foreach ($groupsData as $group){
                    if ($group['EG_Status'] == 'active'){
                        $checkBox->addMultiOption($group['EG_ID'],$group['EGI_Name']." (".$group['EGI_Description'].")");
                    }

                    $i++;
                }
                $this->addElement($checkBox);
        }
        }
    }
?>
