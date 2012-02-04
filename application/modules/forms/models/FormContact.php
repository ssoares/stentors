<?php
    class FormContact extends Cible_Form
    {
        public function __construct($options = null)
        {
            $this->_disabledDefaultActions = true;
            parent::__construct($options);
            $baseDir = $this->getView()->baseUrl();
            
            $this->getView()->jQuery()->addJavascriptFile("{$this->getView()->baseUrl()}/js/jquery/jquery.maskedinput-1.2.2.min.js");

            $script1 =<<< EOS

            $('.phone_format').mask('(999) 999-9999? x99999');
            $('.postalCode_format').mask('a9a 9a9');
            $('.birthDate_format').mask('9999-99-99');

EOS;
            $this->getView()->headScript()->appendScript($script1);
            $script2 = <<< EOS

            function refreshCaptcha(id){
                $.getJSON('{$this->getView()->baseUrl()}/forms/index/captcha-reload',
                    function(data){
                        $("dd#dd_captcha img").attr({src : data['url']});
                        $("#"+id).attr({value: data['id']});
                });
            }

EOS;

            $this->getView()->headScript()->appendScript($script2);
            // name
            $name = new Zend_Form_Element_Text('name');
            $name->setLabel($this->getView()->getCibleText('forms_label_name'))
                  ->setAttrib('class','stdTextInput');

            // enterprise
            $enterprise = new Zend_Form_Element_Text('prenom');
            $enterprise->setLabel($this->getView()->getCibleText('forms_label_surname'))
                  ->setAttrib('class','stdTextInput');

            // email
            $email = new Zend_Form_Element_Text('email');
            $email->setLabel($this->getView()->getClientText('forms_become_partner_label_email'))
                  ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $this->getView()->getCibleText('error_field_required'))))
                  ->addValidator('EmailAddress', true, array('messages'=> Cible_Translation::getCibleText('validation_message_emailAddressInvalid')))
                  ->setRequired(true)
                  ->setAttrib('class','stdTextInput');

             // Commentaires
            $commentaire = new Zend_Form_Element_Textarea('commentaire');
            $commentaire->setLabel($this->getView()->getCibleText('form_label_comments'))
                ->setRequired(false)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttrib('class','stdTextarea');

            $this->addElement($enterprise);
            $this->addElement($name);
            $this->addElement($email);
            $this->addElement($commentaire);
            // Captcha
            $captcha = new Zend_Form_Element_Captcha('captcha', array(
                'label' => $this->getView()->getCibleText('newsletter_captcha_label'),
                'captcha' => 'Image',
                'captchaOptions' => array(
                    'captcha' => 'Word',
                    'wordLen' => 6,
                    'height'  => 50,
                    'width'   => 150,
                    'timeout' => 600,
                    'dotNoiseLevel' => 0,
                    'lineNoiseLevel' => 0,
                    'font'    => Zend_Registry::get('application_path') ."/../{$this->_config->document_root}/captcha/fonts/ARIAL.TTF",
                    'imgDir'  => Zend_Registry::get('application_path') . "/../{$this->_config->document_root}/captcha/tmp",
                    'imgUrl'  => "$baseDir/captcha/tmp"
                ), 
            ));
            $captcha->setAttrib('class','mediumTextInput');
            $captcha->addDecorators(array(
                array(array('row'=>'HtmlTag'),array('tag'=>'dd', 'id'=> 'dd_captcha'))
            ));
            
            $this->addElement($captcha);
            

            $french = array(
                'badCaptcha'    => 'Veuillez saisir la chaîne ci-dessus correctement.'
            );

            $english = array(
                'badCaptcha'    => 'Captcha value is wrong'
            );


            $translate = new Zend_Translate('array', $french, 'fr');

            $this->setTranslator($translate);

            $this->getView()->jQuery()->enable();

            // Refresh button
            $refresh_captcha = new  Zend_Form_Element_Button('refresh_captcha');
            $refresh_captcha->setLabel($this->getView()->getCibleText('button_captcha_refresh'))
                   ->setAttrib('onclick', "refreshCaptcha('captcha[id]')")
                   ->setAttrib('class','grayish-button')
                   ->removeDecorator('Label')
                   ->removeDecorator('DtDdWrapper');
                   
            $refresh_captcha->addDecorators(array(
                array(array('row'=>'HtmlTag'),array('tag'=>'dd'))
            ));
            
            $this->addElement($refresh_captcha);
            
            // Submit button
            $submit = new Zend_Form_Element_Submit('submit');
            $submit->setLabel($this->getView()->getCibleText('button_submit'))
                   ->setAttrib('class','grayish-button')
                   ->removeDecorator('DtDdWrapper');
            $submit->addDecorators(array(
                array(array('row'=>'HtmlTag'),array('tag'=>'dd', 'openOnly'=>true))
            ));
            
            $this->addElement($submit);
            
            } 
        }
?>
