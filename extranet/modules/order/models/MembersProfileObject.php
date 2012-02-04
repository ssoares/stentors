<?php
/**
 * Cible Solutions - Vêtements SP
 * Retailer management. Data import.
 *
 * @category  Extranet_Modules
 * @package   Extranet_Modules_Retailer
 * @copyright Copyright (c) Cibles solutions d'affaires. (http://www.ciblesolutions.com)
 * @version   $Id: MembersProfileObject.php 826 2012-02-01 04:15:13Z ssoares $
 */

/**
 * Manage data for import / export of the client data.
 *
 * @category  Extranet_Modules
 * @package   Extranet_Modules_Retailer
 * @copyright Copyright (c) Cibles solutions d'affaires. (http://www.ciblesolutions.com)
 */
class MembersProfileObject extends DataObject
{
    protected $_dataClass   = 'ClientData';
    protected $_dataId      = 'GP_MemberID';
    protected $_dataColumns = array(
        'GP_MemberID'   => 'GP_MemberID',
        'GP_Salutation' => 'GP_Salutation',
        'GP_FirstName'  => 'GP_FirstName',
        'GP_LastName'   => 'GP_LastName',
        'GP_Email'      => 'GP_Email',
        'MP_LanguageID' => 'GP_Language'
      );

    protected $_indexClass      = 'ClientIndex';
    protected $_indexId         = 'MP_GenericProfileMemberID';
    protected $_indexLanguageId = 'MP_LanguageID';
    protected $_indexColumns    = array(
          'MP_Entreprise_1'  => 'MP_Entreprise_1',
          'MP_Entreprise_2'  => 'MP_Entreprise_2',
          'MP_Adresse_1'     => 'MP_Adresse_1',
          'MP_Adresse_2'     => 'MP_Adresse_2',
          'MP_VilleID'       => 'MP_VilleID',
          'MP_ProvinceID'    => 'MP_ProvinceID',
          'MP_PaysID'        => 'MP_PaysID',
          'MP_Telephone'     => 'MP_Telephone',
          'MP_Fax'           => 'MP_Fax',
          'MP_CodePostal'    => 'MP_CodePostal',
          'MP_MotDePasse'    => 'MP_MotDePasse',
          'MP_NoCompteSP'    => 'MP_NoCompteSP',
          'MP_IsDetaillant'  => 'MP_IsDetaillant',
          'MP_ShowOnWeb'     => 'MP_ShowOnWeb',
          'MP_Hash'          => 'MP_Hash',
          'MP_ValidateEmail' => 'MP_ValidateEmail',
          'MP_LanguageID'    => 'MP_LanguageID',
      );

    protected $_fieldToEncrypt = 'MP_MotDePasse';

//    protected $_indexSelectColumns = array(
//        array('Nom_FR' => 'PI_Nom'),
//        array('Nom_EN' => 'PI_Nom')
//    );

}