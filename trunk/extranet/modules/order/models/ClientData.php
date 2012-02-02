<?php
/**
 * Cible Solutions - Vêtements SP
 * Quote request management. Data import.
 *
 * @category  Extranet_Modules
 * @package   Extranet_Modules_Order
 * @copyright Copyright (c) Cibles solutions d'affaires. (http://www.ciblesolutions.com)
 * @version   $Id: ClientData.php 422 2011-03-24 03:25:10Z ssoares $
 */

/**
 * Database access to the table GenericProfiles for clients import.
 *
 * @category  Extranet_Modules
 * @package   Extranet_Modules_Order
 * @copyright Copyright (c) Cibles solutions d'affaires. (http://www.ciblesolutions.com)
 */
class ClientData extends Zend_Db_Table
{
    protected $_name = 'GenericProfiles';
}