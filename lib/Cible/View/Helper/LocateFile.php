<?php
/**
 * Cible
 *
 *
 * @category   Cible
 * @package    Cible_View
 * @subpackage Cible_View_Helper
 * @copyright  Copyright (c) 2009 Cible Solutions d'affaires
 *             (http://www.ciblesolutions.com)
 * @version    $Id:
 */

/**
 * Allow to retrieve the path for files such as *.css, *.js
 *
 * @category   Cible
 * @package    cible_View
 * @subpackage Cible_View_Helper
 * @copyright  Copyright (c) 2009 Cible Solutions d'affaire
 *             (http://www.ciblesolutions.com)
 */
class Cible_View_Helper_LocateFile extends Zend_View_Helper_Abstract
{
    /**
     * According parameters, create the path of the files location.
     *
     * @param array  $file  The name of files with extension.
     * @param string $path  <Optional> The path to speficy the location.
     *                      For specific files not in common folders
     * 
     * @return string $filePath The path where the file is stored
     */
    public function locateFile($file, $path = null)
    {
        $filePath     = $this->view->BaseUrl();
        $isBackOffice = (preg_match("/extranet/", $filePath));
        $themePath    = '/themes/default/';

        $imgType   = array('jpg', 'gif', 'png');
        
        if ($file != null)
        {
            $type = substr($file, strrpos($file, '.') + 1);
            // If the type exists, it's an image file
            if (in_array($type, $imgType))
            {
                $type = "img";
                $themePath .= "images/";
            }
          
            // Select the path according type
            switch ($type)
            {
                case 'img':
                    if (!$isBackOffice)
                    {
                        $imgPath = (empty($path))? $themePath . "/common/" :
                                    $themePath . $path . '/';
                        $filePath .= $imgPath . $file;
                    }
                    else
                    {
                        $imgPath = (empty($path)) ? $themePath :
                                                    $themePath . $path . '/';
                        $filePath .= $imgPath . $file;
                    }

                    break;
                case 'css':
                    $themePath .= $type . '/';
                    $cssPath = (empty($path)) ? $themePath :
                                                $themePath . $path . '/';
                    $filePath .= $themePath . $file;
                    break;
                case 'js':
                   $jsRoot = ($path) ? '/' . $type . '/' . $path . '/' :
                                       '/' . $type . '/';
                    $filePath .= $jsRoot . $file;
                    break;
                default:
                    $filePath .= '/' . $type . '/' . $file;
                    break;
            }
        }
        
        return $filePath;
    }
}
?>
