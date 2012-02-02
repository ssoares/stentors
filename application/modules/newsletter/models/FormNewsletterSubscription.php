<?php
    class FormNewsletterSubscription extends Cible_Form
    {
        public function __construct($options = null)
        {
            $this->_disabledDefaultActions = true;
            $baseDir = $this->getView()->baseUrl();
            parent::__construct($options);

            $this->getView()->jQuery()->addJavascriptFile("{$this->getView()->baseUrl()}/js/jquery/jquery.maskedinput-1.2.2.min.js");
            $script =<<< EOS

            $('.phone_format').mask('(999) 999-9999? x99999');
            $('.postalCode_format').mask('a9a 9a9');
            $('.birthDate_format').mask('9999-99-99');
EOS;

            $this->getView()->jQuery()->addOnLoad($script);

            $this->setAttrib('class','zendFormNewsletter');
            // Salutation
            $salutation = new Zend_Form_Element_Select('salutation');
            $salutation->setLabel('Salutation')
            ->setAttrib('class','smallTextInput');
            $greetings = $this->getView()->getAllSalutation();
            foreach ($greetings as $greeting){
                $salutation->addMultiOption($greeting['S_ID'], $greeting['ST_Value']);
            }
           /* $salutation->setDecorators(array(
                'ViewHelper',
                array('label', array('placement' => 'prepend')),
                array(array('row' => 'HtmlTag'), array('tag' => 'dd', 'class' => 'dd_form dd_sexe'))
            ));
            $salutation->setAttrib('class', 'newsletter_form_element select_salutations');*/
            $this->addElement($salutation);

            //FirstName
            $firstname = new Zend_Form_Element_Text('firstName');
            $firstname->setLabel($this->getView()->getCibleText('newsletter_fo_form_label_fName'))
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $this->getView()->getCibleText('validation_message_empty_field'))))
                ->setAttrib('class','stdTextInput');

            /*$firstname->setDecorators(array(
                'ViewHelper',
                array('label', array('placement' => 'prepend')),
                array(array('row' => 'HtmlTag'), array('tag' => 'dd', 'class' => 'dd_form dd_prenom'))
            ));
            $firstname->setAttrib('class', 'newsletter_form_element text_prenom');*/
            $this->addElement($firstname);

            // LastName
            $lastname = new Zend_Form_Element_Text('lastName');
            $lastname->setLabel($this->getView()->getCibleText('newsletter_fo_form_label_lName'))
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $this->getView()->getCibleText('validation_message_empty_field'))))
                ->setAttrib('class','stdTextInput');
            /*$lastname->setDecorators(array(
                'ViewHelper',
                array('label', array('placement' => 'prepend')),
                array(array('row' => 'HtmlTag'), array('tag' => 'dd', 'class' => 'dd_form dd_nom'))
            )); */
            //$lastname->setAttrib('class', 'newsletter_form_element text_nom');
            $this->addElement($lastname);

            // email
            $regexValidate = new Cible_Validate_Email();
            $regexValidate->setMessage($this->getView()->getCibleText('validation_message_emailAddressInvalid'), 'regexNotMatch');

            $email = new Zend_Form_Element_Text('email');
            $email->setLabel($this->getView()->getCibleText('newsletter_fo_form_label_email'))
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addFilter('StringToLower')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $this->getView()->getCibleText('validation_message_empty_field'))))
            ->addValidator($regexValidate)
            ->setAttrib('class','stdTextInput');
            /*$email->setDecorators(array(
                'ViewHelper',
                array('label', array('placement' => 'prepend')),
                array(array('row' => 'HtmlTag'), array('tag' => 'dd', 'class' => 'dd_form dd_email'))
            ));*/
           // $email->setAttrib('class', 'newsletter_form_element text_email');
            $this->addElement($email);



            // Captcha
            $captcha = new Zend_Form_Element_Captcha('captcha', array(
                'label' => $this->getView()->getCibleText('newsletter_fo_form_label_securityCaptcha_newsletter'),
                'captcha' => 'Image',
                'captchaOptions' => array(
                    'captcha' => 'Word',
                    'wordLen' => 5,
                    'fontSize' => 18,
                    'height'  => 50,
                    'width'   => 100,
                    'timeout' => 300,
                    'dotNoiseLevel' => 0,
                    'lineNoiseLevel' => 0,
                    'font'    => Zend_Registry::get('application_path') ."/../{$this->_config->document_root}/captcha/fonts/ARIAL.TTF",
                    'imgDir'  => Zend_Registry::get('application_path') . "/../{$this->_config->document_root}/captcha/tmp",
                    'imgUrl'  => "$baseDir/captcha/tmp"
                ),
            ));



            $captcha->addDecorators(array(
                array(array('row'=>'HtmlTag'),array('tag'=>'dd', 'id'=> 'dd_captcha'))
            ))
                    ->addDecorator('Label', array('class'=> 'label_long_required'));



            $french = array(
                'badCaptcha'    => 'Veuillez saisir la chaîne ci-dessus correctement.'
            );

            $english = array(
                'badCaptcha'    => 'Captcha value is wrong'
            );

            $translate = new Zend_Translate('array', $french, 'fr');

            $this->setTranslator($translate);

            $this->getView()->jQuery()->enable();

            $script = <<< EOS

            function refreshCaptcha(id){
                $.getJSON('{$this->getView()->baseUrl()}/newsletter/index/captcha-reload',
                    function(data){

                        $("dd#dd_captcha img").attr({src : data['url']});
                        $("#"+id).attr({value: data['id']});

                });
            }

EOS;

            $this->getView()->headScript()->appendScript($script);


            // Refresh button
            $refresh_captcha = new  Zend_Form_Element_Button('refresh_captcha_newsletter');
            $refresh_captcha->setLabel($this->getView()->getCibleText('button_refresh_captcha'))
                   ->setAttrib('onclick', "refreshCaptcha('captcha-id')")
                   ->setAttrib('class','stdButton')
                   ->removeDecorator('Label')
                   ->removeDecorator('DtDdWrapper');

            $refresh_captcha->setDecorators(array(
                'ViewHelper',
                array(array('row' => 'HtmlTag'), array('tag' => 'dd', 'class' => 'dd_captcha'))
            ));
            //var_dump($this);
           // $captcha->addDecorator( new Zend_Form_Decorator_Label(array('tag' => 'dt', 'class' => 'label-designsadsdsada')));


            //$label = $captcha->getDecorator('label');
            //$label->setOption('class', 'label_lond_message');
            //$label->setOption('placement', 'prepend');

            //displayGroup

            //$captcha->removeDecorator('DtDdWrapper');
            $this->addElement($captcha);
            $this->addElement($refresh_captcha);






            // action button
            $subscribeButton = new Zend_Form_Element_Submit('subscribe');
            $subscribeButton->setLabel($this->getView()->getCibleText('button_submit'))
                            ->setAttrib('id', 'submitSave')
                            ->setAttrib('class','stdButton')
                            ->removeDecorator('Label')
                            ->removeDecorator('DtDdWrapper');
            $this->addElement($subscribeButton);

            $this->addDisplayGroup(array('subscribe'),'actions');

            $requiredFields = new Zend_Form_Element_Hidden('RequiredFields');
            $requiredFields->setLabel('<span class="field_required">*</span>'
                    . $this->getView()->getCibleText('form_field_required_label')
                    . '<br /><br />');

            $requiredFields->setDecorators(array(
                'ViewHelper',
                array('label', array('placement' => 'append')),
                array(array('row' => 'HtmlTag'), array('tag' => 'dd', 'class' => 'label_required_fields'))
            ));
            $this->addElement($requiredFields);


            $actions = $this->getDisplayGroup('actions');
            $this->setDisplayGroupDecorators(array(
                'formElements',
                'fieldset',
                array(array('outerHtmlTag' => 'HtmlTag'), array('tag' => 'dd', 'class' => 'dd-submit-button'))
            ));

            $invi = new Zend_Form_Element_Text('language');
            $invi->setAttrib('class','stdTextInputInvisible')
                ->setValue(Zend_Registry::get("languageID"));
            $this->addElement($invi);



          /*  $elements = $this->getElements();
        foreach($elements as $element) {
            $element->removeDecorator('DtDdWrapper')
            ->removeDecorator('HtmlTag');
            //->removeDecorator('Label');
        }*/

            // reset form decorators to remove the 'dl' wrapper
            $this->setDecorators(array('FormElements','Form'));
        }
    }