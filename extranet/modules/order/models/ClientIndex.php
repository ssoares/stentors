<?php
/**
 * Cible Solutions - VÍtements SP
 * Quote request management. Data import.
 *
 * @category  Extranet_Modules
 * @package   Extranet_Modules_Order
 * @copyright Copyright (c) Cibles solutions d'affaires. (http://www.ciblesolutions.com)
 * @version   $Id: ClientIndex.php 422 2011-03-24 03:25:10Z ssoares $
 */

/**
 * Database access to the table Members profile for tthe client import.
 *
 * @category  Extranet_Modules
 * @package   Extranet_Modules_Order
 * @copyright Copyright (c) Cibles solutions d'affaires. (http://www.ciblesolutions.com)
 */
class ClientIndex extends Zend_Db_Table
{
    protected $_name = 'MemberProfiles';
}