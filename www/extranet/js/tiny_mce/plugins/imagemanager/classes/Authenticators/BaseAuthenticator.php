<?php
/**
 * $Id: BaseAuthenticator.php 425 2011-03-24 12:45:48Z ssoares $
 *
 * @package BaseAuthenticator
 * @author Moxiecode
 * @copyright Copyright © 2007, Moxiecode Systems AB, All rights reserved.
 */

/**
 * This class handles MCImageManager BaseAuthenticator stuff.
 *
 * @package BaseAuthenticator
 */
class Moxiecode_BaseAuthenticator extends Moxiecode_ManagerPlugin {
	/**#@+
	 * @access public
	 */

	/**
	 * ..
	 */
	function Moxiecode_BaseAuthenticator() {
	}

	/**
	 * ..
	 */
	function onAuthenticate(&$man) {
		return true;
	}
}

// Add plugin to MCManager
$man->registerPlugin("BaseAuthenticator", new Moxiecode_BaseAuthenticator());
?>