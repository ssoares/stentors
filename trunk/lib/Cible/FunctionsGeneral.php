<?php
/**
 * Cible
 *
 * @category   Cible
 * @package    Cible
 * @subpackage Cible
 * @copyright  Copyright (c) 2009 Cible Solutions d'affaires
 *             (http://www.ciblesolutions.com)
 * @version    $Id: FunctionsGeneral.php 826 2012-02-01 04:15:13Z ssoares $
 */

/**
 * Offers various tools
 *
 * @category   Cible
 * @copyright  Copyright (c) 2009 Cible Solutions d'affaire
 *             (http://www.ciblesolutions.com)
 */
abstract class Cible_FunctionsGeneral
{

    const DATE_FULL           = 'DF';     // full format of a date
    const DATE_LONG           = 'DL';     // long format of a date
    const DATE_SHORT          = 'DS';     // short format of a date
    const DATE_LONG_NO_DAY    = 'DLND';   // lond format without day
    const DATE_NUM            = 'DN';     // num format of a date
    const DATE_SQL            = 'DSQL';   // SQL format of a date
    const DATE_NUM_USA        = 'DNUSA';  // num USA format of a date
    const DATE_NUM_SHORT_YEAR = 'DNSY';   // format the date with a 2 digits date
    const DATE_MONTH_YEAR     = 'DMY';    // month romaji + 4 digits year


    public static function getAllLanguage($onlyActive = true)
    {
        $Languages = Zend_Registry::get("db");
        $Select = $Languages->select()
            ->from('Languages')
            ->order('L_Seq');

        if ($onlyActive)
            $Select->where('L_Active = ?', 1);

        return $Languages->fetchAll($Select);
    }

    public static function getFilterLanguages()
    {
        $languages = self::getAllLanguage();
        $choices = array('' => Cible_Translation::getCibleText('filter_empty_language'));

        foreach ($languages as $language)
        {
            if (!isset($choices[$language['L_ID']]))
            {
                $choices[$language['L_ID']] = $language['L_Title'];
            }
        }

        return $choices;
    }

    public static function extranetLanguageIsAvailable($langID)
    {
        $_db = Zend_Registry::get("db");

        return $_db->fetchOne('SELECT L_ID FROM Languages WHERE L_ID = ? AND L_ExtranetUI = \'1\'', $langID);
    }

    public static function removeAccents($string)
    {
        $string = strtr($string,
                        'ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ',
                        'aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn');

        return $string;
    }

    public static function getExtranetLanguage()
    {
        $Languages = Zend_Registry::get("db");
        $Select = $Languages->select()
                        ->from('Languages')
                        ->where('L_ExtranetUI = ?', 1);

        return $Languages->fetchAll($Select);
    }

    public static function getLanguageID($suffix)
    {
        $_db = Zend_Registry::get("db");

        return $_db->fetchOne('SELECT L_ID FROM Languages WHERE L_Suffix = ?', $suffix);
    }

    public static function getLanguageSuffix($id)
    {
        $_db = Zend_Registry::get("db");

        return $_db->fetchOne('SELECT L_Suffix FROM Languages WHERE L_ID = ?', $id);
    }

    public static function getLanguageTitle($id)
    {
        $_db = Zend_Registry::get("db");

        return $_db->fetchOne('SELECT L_Title FROM Languages WHERE L_ID = ?', $id);
    }

    public static function getStatus()
    {
        $_db = Zend_Registry::get("db");

        return $_db->fetchAll('SELECT * FROM Status');
    }

    public static function getStatusCode($statusId, $status = null)
    {
        if ($status == null)
            $status = getStatus();

        foreach ($status as $_s)
        {
            if ($_s['S_ID'] == $statusId)
                return $_s['S_Code'];
        }

        throw new Exception('Status not found Exception');
    }

    public static function generateLanguageSwitcher($view)
    {
        $_availableLanguages = Cible_FunctionsGeneral::getAllLanguage();

        $baseUrl = $view->baseUrl();
        $params = $view->params;

        $_module = '';
        $_controller = '';
        $_action = '';
        $_params = '';

        foreach ($params as $_key => $_val)
        {
            switch ($_key)
            {
                case 'module':
                    $_module = $_val;
                    break;
                case 'controller':
                    $_controller = $_val;
                    break;
                case 'action':
                    $_action = $_val;
                    break;
                default:
                    if (strtolower($_key) != 'lang' && !isset($_POST[$_key]))
                        $_params .= "/$_key/$_val";
            }
        }

        $_requestURI = "$baseUrl/$_module/$_controller/$_action$_params";

        $content = '';

        foreach ($_availableLanguages as $_lang)
        {
            $_selected = false;

            if ($_lang['L_ID'] == Zend_Registry::get('currentEditLanguage'))
                $_selected = true;

            $content .= '<li>';
            if ($_selected)
            {
                $content .= $view->link("$_requestURI/lang/{$_lang['L_Suffix']}", $_lang['L_Title'], array('class' => 'selected'));
            }
            else
            {
                $content .= $view->link("$_requestURI/lang/{$_lang['L_Suffix']}", $_lang['L_Title']);
            }
            $content .= '</li>';
        }

        if (!empty($content))
        {
            $content = "<ul id='language-switcher'>$content</ul>";
        }

        return $content;
    }

    public static function generateHtmlTableV2($searchArray = "", $listArray = "", $navigationArray = "")
    {
        // build the search
        $searchTable = "";
        if ($searchArray <> "")
        {
            $searchTable = Cible_FunctionsGeneral::generateHtmlTableSearch($searchArray);
        }

        // build the list
        $listTable = "";
        if ($listArray <> "")
        {
            $listTable = Cible_FunctionsGeneral::generateHTMLTableList($listArray);
        }

        // build the navigation
        $navigationTable = "";
        if ($navigationArray <> "")
        {
            $navigationTable = Cible_FunctionsGeneral::generateHtmlTableNavigation($navigationArray);
        }

        return ($searchTable . "\n" . $listTable . "\n" . $navigationTable);
    }

    public static function generateHTMLTableList($listArray)
    {
        $listTable = "";

        // table list start
        $listTable .= " <table class='default_html_table'>";

        // caption
        if (isset($listArray['caption']))
            $listTable .= " <caption>" . $listArray['caption'] . "</caption>";

        // head
        if (isset($listArray['thArray']))
        {
            $listTable .= "     <thead>";
            $listTable .= "         <tr>";
            foreach ($listArray['thArray'] as $TH)
            {
                $listTable .= "         <th>";
                if (array_key_exists("OrderField", $TH) && array_key_exists("Order", $TH))
                {
                    $listTable .= '<div style="float:left;">' . $TH["Title"] . '</div>';
                    $listTable .= '<div class="listTableOrder"><a href="' . $TH["OrderLink"] . '"><img class="action_icon" src="' . Zend_Controller_Front::getInstance()->getBaseUrl() . '/icons/order_' . $TH["Order"] . '_icon.gif" /></a></div>';
                }
                else
                {
                    $listTable .= $TH['Title'];
                }
                $listTable .= "         </th>";
            }
            $listTable .= "         </tr>";
            $listTable .= "     </thead>";
        }

        // rows
        $listTable .= "     <tbody>";
        $rowColor = "rowPair";
        foreach ($listArray['rowsArray'] as $rows)
        {
            if ($rowColor == "rowPair")
                $rowColor = "rowOdd";
            else
                $rowColor = "rowPair";

            $listTable .= '<tr class="' . $rowColor . '">';
            foreach ($rows as $details)
            {
                $listTable .= '<td>' . $details . "</td>";
            }
            $listTable .= "</tr>";
        }


        // table list end
        $listTable .= " </table>";

        return $listTable;
    }

    public static function generateHtmlTable($tableTitle, $tableTH, $tableRows, $tableNavigation = "", $tableSearch = "")
    {

        $_baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();

        $table = "";
        $table .= $tableSearch;
        $table .= "<table class='default_html_table'>";
        $table .= "     <caption>" . $tableTitle . "</caption>";
        // TABLE HEADER
        $table .= "     <thead>";
        $table .= "         <tr>";
        foreach ($tableTH as $TH)
        {
            $table .= "<th>";
            if (array_key_exists("OrderField", $TH) && array_key_exists("Order", $TH))
            {
                $table .= '<div style="float:left;">' . $TH["Title"] . '</div>';
                $table .= '<div class="listTableOrder"><a href="' . $TH["OrderLink"] . '"><img class="action_icon" src="' . $_baseUrl . '/icons/order_' . $TH["Order"] . '_icon.gif" /></a></div>';
            }
            else
            {
                $table .= $TH["Title"];
            }
            $table .= "</th>";
        }
        $table .= "         </tr>";
        $table .= "     </thead>";

        // TABLE ROWS
        $table .= "     <tbody>";
        $rowColor = "rowPair";
        foreach ($tableRows as $rows)
        {
            if ($rowColor == "rowPair")
                $rowColor = "rowOdd";
            else
                $rowColor = "rowPair";

            $table .= '<tr class="' . $rowColor . '">';
            foreach ($rows as $details)
            {
                $table .= '<td>' . $details . "</td>";
            }
            $table .= "</tr>";
        }
        if ($tableNavigation <> "")
        {
            $table .= "<tr>";
            $table .= "<td colspan=" . count($tableTH) . ">";
            $table .= $tableNavigation;
            $table .= "</td>";
            $table .= "</tr>";
        }
        $table .= "     </tbody>";

        $table .= " </table>";

        return $table;
    }

    public static function generateHtmlTableSearch($searchArray)
    {
        $tableSearch = "<table class=\"tableSearch\">";
        $tableSearch .= "     <tr valign=\"top\">";
        $tableSearch .= "         <td>";
        $tableSearch .= "             <b>Mots-clés</b><br/>";
        $tableSearch .= "             <input id=\"searchText\" name=\"searchText\" type='text' class='stdTextInput'/><br/>";
        $tableSearch .= "             <input type=\"button\" onclick=\"location.href = '" . $searchArray['searchLink'] . "/search/'+escape(document.getElementById('searchText').value)\" value=\"Rechercher\"/>";
        $tableSearch .= "             <input type=\"button\" onclick=\"location.href = '" . $searchArray['searchLink'] . "'\" value=\"Liste complète\"/>";
        $tableSearch .= "         </td>";
        $tableSearch .= "         <td>";
        if ($searchArray["searchText"] <> "")
        {
            $tableSearch .= "         <b>Résultats de recherche</b><br/><br/>";
            $tableSearch .= "         Mots-clés : <b>" . $searchArray['searchText'] . "</b><br/>";
            $tableSearch .= "         Nombre trouvé : <b>" . $searchArray['searchCount'] . "</b>";
        }
        else
        {
            $tableSearch .= "         <b>Liste complète</b><br/><br/>";
            $tableSearch .= "         Nombre trouvé : <b>" . $searchArray['searchCount'] . "</b>";
        }
        $tableSearch .= "         </td>";
        $tableSearch .= "     </tr>";
        $tableSearch .= "</table>";

        return $tableSearch;
    }

    public static function generateHtmlTableNavigation($navigationArray)
    {
        $tablePage = $navigationArray["tablePage"];
        $navigationLink = $navigationArray["navigationLink"];
        $nbTablePage = $navigationArray["nbTablePage"];

        $tableNavigation = "";

        if ($tablePage > 1)
            $tableNavigation .= "<a href='" . $navigationLink . "/tablePage/" . ($tablePage - 1) . "'>Précédent</a>&nbsp;&nbsp;";

        for ($i = 1; $i <= $nbTablePage; $i++)
        {
            if ($i == $tablePage)
            {
                $tableNavigation .= $i . "&nbsp;&nbsp;";
            }
            else
            {
                $tableNavigation .= "<a href='" . $navigationLink . "/tablePage/" . $i . "'>" . $i . "</a>&nbsp;&nbsp;";
            }
        }

        if ($tablePage <> $nbTablePage)
            $tableNavigation .= "<a href='" . $navigationLink . "/tablePage/" . ($tablePage + 1) . "'>Suivant</a>";

        return $tableNavigation;
    }

    public static function getAllChildCategory($moduleID, $categoryParentID, $languageID)
    {
        $categories = new Categories();
        $select = $categories->select()->setIntegrityCheck(false)
                        ->form('Categories')
                        ->join('CategoriesIndex', 'C_ID = CI_CategoryID')
                        ->where('C_ModuleID = ?', $moduleID)
                        ->where('C_ParentID = ?', $categoryParentID)
                        ->where('CI_LanguageID = ?', $languageID)
                        ->order('CI_Title');

        return $categories->fetchAll($select);
    }

    public static function getCategoryDetails($categoryID)
    {
        $category = new Categories();
        $select = $category->select()->setIntegrityCheck(false)
                        ->from('Categories')
                        ->join('CategoriesIndex', 'CI_CategoryID = C_ID')
                        ->where('C_ID = ?', $categoryID)
                        ->where('CI_LanguageID = ?', Zend_Registry::get('languageID'));

        return $category->fetchRow($select);
    }

    public static function getCustomerDetails($customerID)
    {
        $dbGestionCible = Zend_Registry::get('dbGestionCible');
        $select = $dbGestionCible->select()
                        ->from('Cible_Registre')
                        ->where('R_ID = ?', $customerID);
        return $dbGestionCible->fetchRow($select);
    }

    public static function getFirstCategoryParent($categoryID)
    {
        $category = new Categories();
        $select = $category->select()
                        ->where('C_ID = ?', $categoryID);

        $categoryData = $category->fetchRow($select);

        if ($categoryData['C_ParentID'] == 0)
        {
            return $categoryData->toArray();
        }
        else
        {
            return Cible_FunctionsGeneral::getFirstCategoryParent($categoryData['C_ParentID']);
        }
    }

    public static function getCategoryParent($categoryID)
    {
        $category = new Categories();
        $select = $category->select()
                        ->where('C_ID = ?', $categoryID);

        $categoryData = $category->fetchRow($select);

        return $categoryData->toArray();
    }

    public static function fillStatusSelectBox($selectBox, $table, $field)
    {
        $db = Zend_Registry::get("db");

        $sql = "SHOW COLUMNS FROM $table LIKE '$field'";
        $result = $db->fetchAll($sql);

        // fill the status select box
        $StatusArray = "";
        if (count($result) > 0)
        {
            $StatusArray = explode("','", preg_replace("/(enum|set)\('(.+?)'\)/", "\\2", $result[0]["Type"]));
        }
        $NbStatus = count($StatusArray);

        // Sort le Array pour mettre le texte "Actif" avant "Inactif"
        sort($StatusArray);

        foreach ($StatusArray as $Status)
        {
            $selectBox->addMultiOption($Status, Cible_Translation::getCibleText("form_extranet_group_status_$Status"));
        }

        return $selectBox;
    }

    public static function html2text($text)
    {
        $search = array(
            "/\r/", // Non-legal carriage return
            "/[\n\t]+/", // Newlines and tabs
            '/[ ]{2,}/', // Runs of spaces, pre-handling
            '/<script[^>]*>.*?<\/script>/i', // <script>s -- which strip_tags supposedly has problems with
            '/<style[^>]*>.*?<\/style>/i', // <style>s -- which strip_tags supposedly has problems with
            //'/<!-- .* -->/',                         // Comments -- which strip_tags might have problem a with
            '/<h[123][^>]*>(.*?)<\/h[123]>/ie', // H1 - H3
            '/<h[456][^>]*>(.*?)<\/h[456]>/ie', // H4 - H6
            '/<p[^>]*>/i', // <P>
            '/<br[^>]*>/i', // <br>
            '/<b[^>]*>(.*?)<\/b>/ie', // <b>
            '/<strong[^>]*>(.*?)<\/strong>/ie', // <strong>
            '/<i[^>]*>(.*?)<\/i>/i', // <i>
            '/<em[^>]*>(.*?)<\/em>/i', // <em>
            '/(<ul[^>]*>|<\/ul>)/i', // <ul> and </ul>
            '/(<ol[^>]*>|<\/ol>)/i', // <ol> and </ol>
            '/<li[^>]*>(.*?)<\/li>/i', // <li> and </li>
            '/<li[^>]*>/i', // <li>
            '/<a [^>]*href="([^"]+)"[^>]*>(.*?)<\/a>/ie',
            // <a href="">
            '/<hr[^>]*>/i', // <hr>
            '/(<table[^>]*>|<\/table>)/i', // <table> and </table>
            '/(<tr[^>]*>|<\/tr>)/i', // <tr> and </tr>
            '/<td[^>]*>(.*?)<\/td>/i', // <td> and </td>
            '/<th[^>]*>(.*?)<\/th>/ie', // <th> and </th>
            '/&(nbsp|#160);/i', // Non-breaking space
            '/&(quot|rdquo|ldquo|#8220|#8221|#147|#148);/i',
            // Double quotes
            '/&(apos|rsquo|lsquo|#8216|#8217|#146|#39);/i', // Single quotes
            '/&gt;/i', // Greater-than
            '/&lt;/i', // Less-than
            '/&(amp|#38);/i', // Ampersand
            '/&(copy|#169);/i', // Copyright
            '/&(trade|#8482|#153);/i', // Trademark
            '/&(reg|#174);/i', // Registered
            '/&(mdash|#151|#8212);/i', // mdash
            '/&(ndash|minus|#8211|#8722);/i', // ndash
            '/&(bull|#149|#8226);/i', // Bullet
            '/&(pound|#163);/i', // Pound sign
            '/&(euro|#8364);/i', // Euro sign
            //'/&[^&;]+;/i',                         // Unknown/unhandled entities
            '/[ ]{2,}/', // Runs of spaces, post-handling
        );

        $replace = array(
            '', // Non-legal carriage return
            ' ', // Newlines and tabs
            ' ', // Runs of spaces, pre-handling
            '', // <script>s -- which strip_tags supposedly has problems with
            '', // <style>s -- which strip_tags supposedly has problems with
            //'',                                     // Comments -- which strip_tags might have problem a with
            "strtoupper(\"\n\n\\1\n\n\")", // H1 - H3
            "ucwords(\"\n\n\\1\n\n\")", // H4 - H6
            "\n\n\t", // <P>
            "\n", // <br>
            'strtoupper("\\1")', // <b>
            'strtoupper("\\1")', // <strong>
            '_\\1_', // <i>
            '_\\1_', // <em>
            "\n\n", // <ul> and </ul>
            "\n\n", // <ol> and </ol>
            "\t* \\1\n", // <li> and </li>
            "\n\t* ", // <li>
            '"\\2"', // <a href="">
            "\n-------------------------\n", // <hr>
            "\n\n", // <table> and </table>
            "\n", // <tr> and </tr>
            "\t\t\\1\n", // <td> and </td>
            "strtoupper(\"\t\t\\1\n\")", // <th> and </th>
            ' ', // Non-breaking space
            '"', // Double quotes
            "'", // Single quotes
            '>',
            '<',
            '&',
            '(c)',
            '(tm)',
            '(R)',
            '--',
            '-',
            '*',
            '£',
            'EUR', // Euro sign. € ?
            //'',                                     // Unknown/unhandled entities
            ' ', // Runs of spaces, post-handling
        );

        $text = trim(stripslashes($text));
        $text = strip_tags($text);
        $text = preg_replace($search, $replace, $text);
        //$text = htmlentities($text);
        // $text = utf8_encode($text);
        return($text);
    }

    public static function stripTextWords($text, $wordCount = 25)
    {
        $totalWordCount = str_word_count($text);

        if ($wordCount > $totalWordCount)
            $wordCount = $totalWordCount;

        // Get the X first word
        $text_tmp = explode(' ', $text);
        $text_tmp = implode(' ', array_slice($text_tmp, 0, $wordCount));

        if ($wordCount < $totalWordCount && substr($text_tmp, strlen($text_tmp), 1) <> '.')
        {
            $text = substr($text, strlen($text_tmp), strlen($text));
            if (strpos($text, '.'))
                $text = substr($text, 0, strpos($text, '.') + 1);
            $text = $text_tmp . $text;
        }
        else
        {
            $text = $text_tmp;
        }

        return $text;
    }

    /**
     * Truncate a string to the max number of characters given and allows to
     * associate a class for the three final dots.
     *
     * @param string $string The string to truncate
     * @param int    $max    <OPTIONAL> Default = 150. Limit to cut the string.
     * @param array  $option <OPTIONAL> Array for parameters (ie class attrib).
     *
     * @return string $string The formatted string
     */
    public static function truncateString($string, $max = 150, $option = array())
    {
        $string = Cible_FunctionsGeneral::html2text($string);

        if (strlen($string) > $max)
        {
            $string = substr($string, 0, $max);
            $i      = strrpos($string, " ");
            $string = substr($string, 0, $i);
            $dot    = "...";

            if (!empty($option["dotStyle"]))
            {
                $dot = "<span class='" . $option["dotStyle"] . "'>...</span>";
            }
            $string = $string . $dot;
        }

        return $string;
    }

    public static function delFolder($dir)
    {
        $files = glob($dir . '*', GLOB_MARK);
        foreach ($files as $file)
        {
            if (substr($file, -1) == '/')
                Cible_FunctionsGeneral::delFolder($file);
            else
            {
                if (file_exists($file))
                    unlink($file);
            }
            //echo("deleteFile : " . $file . "<br/>");
        }
        if(file_exists($dir)){
            echo $dir;
            rmdir($dir);
        }
        //echo("deleteDir : " . $dir . "<br/>");
    }

    public static function getApprobationRequest($moduleName)
    {
        $_db = Zend_Registry::get("db");

        switch ($moduleName)
        {
            case 'text':
                $query = $_db->quoteInto('SELECT COUNT(*) FROM TextData WHERE TD_ToApprove = ?', 1);
                break;

            default:;
        }
        $count = $_db->fetchOne($query);

        if ($count > 0)
            $boldAttribute = ' font-weight:bold';
        else
            $boldAttribute = '';

        return " <span style='font-family:Arial; font-size:12px; $boldAttribute'>($count)</span>";
    }

    public static function generatePassword()
    {

        $password = '';
        $pw_length = 8;
        // set ASCII range for random character generation
        $lower_ascii_bound = 50;          // "2"
        $upper_ascii_bound = 122;       // "z"
        // Exclude special characters and some confusing alphanumerics
        // o,O,0,I,1,l etc
        $notuse = array(58, 59, 60, 61, 62, 63, 64, 73, 79, 91, 92, 93, 94, 95, 96, 108, 111);
        $i = 0;
        while ($i < $pw_length)
        {
            mt_srand((double) microtime() * 1000000);
            // random limits within ASCII table
            $randnum = mt_rand($lower_ascii_bound, $upper_ascii_bound);
            if (!in_array($randnum, $notuse))
            {
                $password = $password . chr($randnum);
                $i++;
            }
        }

        return $password;
    }

    public static function getRssCategories($lang = null)
    {
        $db = Zend_Registry::get("db");

        if (is_null($lang))
            $lang = Zend_Registry::get('languageID');

        //$select = $db->select();

        $category = new Categories();
        $select = $category->select()->setIntegrityCheck(false)
                        ->from('Categories')
                        ->join('CategoriesIndex', 'CI_CategoryID = C_ID')
                        ->where('C_ShowInRss = 1')
                        ->where('CI_LanguageID = ?', $lang);

        $categoryData = $category->fetchAll($select);

        return $categoryData->toArray();
    }

    /**
     * Get data if an user is already authenticated
     *
     * @return string $authentication user's data from cookie
     */
    public static function getAuthentication()
    {
        $authentication = null;

        if (isset($_COOKIE['authentication']))
        {
            $authentication = json_decode($_COOKIE['authentication'], true);
            $path = Zend_Registry::get('web_root') . '/';

            $memberProfile = new MemberProfile();
            $foundUser = $memberProfile->findMember(array(
                        'email' => $authentication['email'],
                        'hash' => $authentication['hash'],
                        'status' => $authentication['status']
                    ));

            if (!$foundUser)
            {
                $authentication = null;
                setcookie('authentication', '', -1, $path);
            }
        }

        return $authentication;
    }

    /**
     * Get users data if he has got n account and if his password is ok.
     *
     * @param string $email
     * @param string $password
     * @return array
     */
    public static function authenticate($email, $password)
    {
        $db = Zend_Registry::get("db");
        $password = md5($password);
        $profile = new MemberProfile();

        $foundUser = $profile->authenticateMember(array('email' => $email, 'password' => $password));

        if (isset($foundUser['member_id']))
            return array(
                'success' => 'true',
                'member_id' => $foundUser['member_id'],
                'email' => $foundUser['email'],
                'lastName' => $foundUser['lastName'],
                'firstName' => $foundUser['firstName'],
                'status' => $foundUser['status']

            );
        else
            return array('success' => 'false');
    }

    /**
     * Search for a user account and return his data.
     *
     * @param int    $memberId    The member's id
     * @param string $email       The user email
     * @param int    $accountType The account type
     *
     * @return array $foundUser
     */
    public static function isAuthenticated($memberId, $email, $accountType)
    {

        $db = Zend_Registry::get("db");

        $profile = new MemberProfile();
        $foundUser = $profile->findMembers(array('member_id' => $memberId, 'email' => $email));

        print_r($foundUser);

        return $foundUser;
    }

    /**
     * Get a list of all the countries
     *
     * @param int $lang  The language id
     * @param int $ctyId The country id
     *
     * @return array
     */
    public static function getCountries($lang = null, $ctyId = null)
    {
        $db = Zend_Registry::get("db");

        if (is_null($lang))
            $lang = Zend_Registry::get('languageID');

        $select = $db->select();

        $select->from('Countries', array('ID' => 'C_ID', 'value' => 'C_Identifier'))
                ->joinLeft('CountriesIndex', 'Countries.C_ID = CountriesIndex.CI_CountryID', array('name' => 'CountriesIndex.CI_Name'))
                ->where('CountriesIndex.CI_LanguageID = ?', $lang);

        if ($ctyId)
        {
            if (is_numeric($ctyId))
                $select->where('C_ID = ?', $ctyId);
            else
                $select->where('C_Identifier = ?', $ctyId);
        }

        $countries = $db->fetchAll($select);
        $result = array();

        foreach ($countries as $country)
        {
            if ($ctyId)
            {
                $result = array(
                    'ID' => $country['ID'],
                    'value' => $country['value'],
                    'name' => utf8_encode($country['name']));
            }
            else
            {
            array_push($result, array(
                'ID' => $country['ID'],
                'value' => $country['value'],
                'name' => utf8_encode($country['name'])
            ));
        }
        }

        return $result;
    }

    public static function getCountryByStateID($stateID)
    {
        $db = Zend_Registry::get("db");
        $lang = Zend_Registry::get('languageID');

        $select = $db->select();
        $select->from('States', array("ID" => "S_CountryID"))
                ->where('S_ID = ?', $stateID);

        return $db->fetchRow($select);
    }

    public static function getCountryByCode($code = null, $lang = null)
    {
        $db = Zend_Registry::get("db");

        if (is_null($lang))
            $lang = Zend_Registry::get('languageID');

        $select = $db->select();

        $select->from('Countries', array())
                ->joinLeft('CountriesIndex', 'Countries.C_ID = CountriesIndex.CI_CountryID', array('name' => 'CI_Name'))
                ->where('CountriesIndex.PI_LanguageID = ?', $lang)
                ->where('Countries.P_Identifier = ?', $code);

        return $db->fetchOne($select);
    }

    /**
     * Fetch salutation identifier and retrieve text fron static texts table.
     *
     * @param int $id   <OPTIONAL> If null then all the salutations will be returned.
     * @param int $lang <OPTIONAL> If null then the language id will be setted.
     *
     * @return array
     */
    public static function getSalutations($id = null, $lang = null)
    {
        $db = Zend_Registry::get("db");

        if (is_null($lang))
            $lang = Zend_Registry::get('languageID');

        $select = $db->select();

        $select->from('Salutations', array('ID' => 'S_ID', 'value' => 'S_StaticTitle'));

        if ($id)
            $select->where('Salutations.S_ID = ?', $id);

        $salutations = $db->fetchAll($select);

        $result = array();

        foreach ($salutations as $salutation)
        {
            $result[$salutation['ID']] = Cible_Translation::getCibleText($salutation['value'], $lang);
        }

        return $result;
    }

    /**
     * Fetch states data according to the country code or the state code.
     *
     * @param int|string $countryCode Country id (int) or country identifier
     *                                (2 caracters eq CA for Canada)
     * @param int|string $stateCode   State Id (int) or state identifier (2 car.
     *                                eq QC for Quebec)
     * @param int        $lang        Id of the current user language. If no
     *                                language given, set the default language.
     * @return array
     */
    public static function getStateByCode($countryCode = null, $stateCode = null, $lang = null)
    {
        $db = Zend_Registry::get("db");

        if (is_null($lang))
            $lang = Zend_Registry::get('languageID');

        $select = $db->select();

        $select->from('States', array('id' => 'S_ID'))
                ->joinLeft(
                    'StatesIndex',
                    'States.S_ID = StatesIndex.SI_StateID',
                    array('name' => 'SI_Name'))
                ->joinLeft('Countries', 'States.S_CountryID = Countries.C_ID', array())
                ->where('StatesIndex.SI_LanguageID = ?', $lang);

        if (is_numeric($countryCode) && !is_null($countryCode))
            $select->where('States.S_CountryID = ?', $countryCode);
        elseif (!is_null($countryCode))
            $select->where('Countries.C_Identifier = ?', $countryCode);

        if (is_numeric($stateCode) && !is_null($stateCode))
        {
            $select->where('States.S_ID = ?', $stateCode);
        return $db->fetchOne($select);
    }
        elseif (!is_null($stateCode))
        {
            $select->where('States.S_Identifier = ?', $stateCode);
            return $db->fetchOne($select);
        }

        return $db->fetchAll($select);

    }

    public static function getStates($lang = null, $stateId = null)
    {
        $db = Zend_Registry::get("db");

        if (is_null($lang))
            $lang = Zend_Registry::get('languageID');

        $select = $db->select();

        $select->from('States', array('ID' => 'States.S_ID','value' => 'S_Identifier', 'ctyId' => 'States.S_CountryID'))
                ->joinLeft('StatesIndex', 'States.S_ID = StatesIndex.SI_StateID', array('name' => 'SI_Name'))
                ->joinLeft('Countries', 'States.S_CountryID = Countries.C_ID', array('code' => 'C_Identifier'))
                ->where('StatesIndex.SI_LanguageID = ?', $lang)
                ->order('SI_Name');

        if ($stateId)
        {
            if (is_numeric($stateId))
                $select->where('States.S_ID = ?', $stateId);
            else
                $select->where('States.S_Identifier = ?', $stateId);
        }
        $states = $db->fetchAll($select);

        $result = array();

        foreach ($states as $state)
        {
            if (!isset($result[$state['ctyId']]))
                $result[$state['ctyId']] = array();

            if ($stateId)
            {
                $result = array(
                'value' => $state['value'],
                'name' => utf8_encode($state['name'])
                );
            }
            else
            {

                array_push(
                        $result[$state['ctyId']],
                        array(
                            'id' => $state['ID'],
                            'value' => $state['value'],
                            'name' => utf8_encode($state['name'])
            ));
        }
        }

        return $result;
    }

    public static function getStatesByCountry($countryID)
    {
        $db = Zend_Registry::get("db");
        $lang = Zend_Registry::get('languageID');

        $select = $db->select();

        $select->from('States', array('ID' => 'S_ID'))
                ->joinLeft('StatesIndex', 'States.S_ID = StatesIndex.SI_StateID', array('Name' => 'SI_Name'))
                ->where('StatesIndex.SI_LanguageID = ?', $lang)
                ->where('S_CountryID = ?', $countryID)
                ->order('SI_Name');

        return $db->fetchAll($select);
    }

    /**
     * Fetches cities data.
     *
     * @param int $lang    Id of the current language to display.
     * @param int $cityId  Id to fecth data only for this city.
     * @param int $stateId Id to fecth data only for this state.
     *
     * @return array
     */
    public static function getCities($lang = null, $cityId = null, $stateId = null)
    {
        $db = Zend_Registry::get("db");

        if (is_null($lang))
            $lang = Zend_Registry::get('languageID');

        $select = $db->select();

        $select->from(
            'Cities',
            array(
                'id'    => 'C_ID',
                'value' => 'c_Name',
                'name'  => 'C_Name',
                'code'  => 'C_Name')
            )
            ->order('C_Name');

        if ($cityId)
            $select->where('C_ID = ?', $cityId);

        if ($stateId)
            $select->where('C_StateID = ?', $stateId);

        $states = $db->fetchAll($select);
        $result = array();
        if ($stateId)
            $result = $states;
        else
        {
        foreach ($states as $state)
        {
            if (!isset($result[$state['code']]))
                $result[$state['code']] = array();
                if ($cityId)
                {
                    $result = array(
                        'value' => $state['value'],
                        'name' => utf8_encode($state['name']));
                }
                else
                {
            array_push($result[$state['code']], array(
                'value' => $state['value'],
                'name' => utf8_encode($state['name'])
            ));
        }
            }
        }
        return $result;
    }

    /**
     * Retrieve the registred users with managers privileges.
     *
     * @return array
     */
    public static function getClientWithManagerPrivileges()
    {
        $db = Zend_Registry::get("db");

        $profile = new MemberProfile();

        $select = $profile->getSelectStatement();

//        $select->where('MP_IsDetaillant = ?', 1);
        $select->order('company');
        $select->order('lastName');
        $select->order('firstName');

        return $db->fetchAll($select);
    }

    public static function getClientStaticText($identifier, $lang = null)
    {

        if (is_null($lang))
            $lang = Zend_Registry::get('languageID');

        $staticText = new StaticTexts();
        $select = $staticText->select()
                        ->where("ST_Identifier = ?", $identifier)
                        ->where("ST_LangID = ?", $lang);
        //die($select);
        return $staticText->fetchRow($select);
    }

    /**
     * Set the css class name to chamge color when user switch language.
     *
     * @param array $options
     *
     * @return string
     */
    public static function getLanguageLabelColor($options = array())
    {
        $lableClass = 'formLabelLanguageCssColor_';
        if (isset($options['addAction']))
        {
            $config = Zend_Registry::get("config");
            $lableClass .= $config->defaultEditLanguage;
        }
        else
        {
            $lableClass .= Zend_Registry::get("currentEditLanguage");
        }
        return $lableClass;
    }

    /**
     * Format and return the date
     *
     * @param Zend_Date $date
     * @param const     $format    The format of the string
     * @param string    $separator That will be apply between the elements of the date (default is '/')
     * @param bool      $weekdays  NOT APPLY FOR NOW
     * @param bool      $cap       NOT APPLY NOW
     *
     * @return string with the string formatted
     */
    public static function dateToString($date, $format = self::DATE_LONG, $separator = '/', $weekdays = false, $cap = false)
    {
        $strDate    = $date;
        if (is_string($date))
            $date = new Zend_Date($date);

        $suffixLang = Zend_Registry::get('languageSuffix');

        $day     = $date->get(Zend_Date::WEEKDAY);
        $dayDate = $date->get(Zend_Date::DAY);
        $year    = $date->get(Zend_Date::YEAR);

        switch($format)
        {
            case self::DATE_FULL :   // ex:   Le 31 décembre 2010
                if(strpos($dayDate,'0')==0)
                {
                    $dayDate = str_replace("0", "", $dayDate);
                }
                $Mdate = $date->get(Zend_Date::MONTH_NAME);
                $Mdate = utf8_decode($Mdate);
                if($suffixLang == 'fr')
                {
                    $dateString = sprintf("%s %s %s", $dayDate, $Mdate,$date->get(Zend_Date::YEAR) );
                    if($dayDate=='1')
                    {
                        $strDate = str_replace("1 ", "1<sup>er</sup> ", $dateString);
                    }
                    else{
                       $strDate = $dateString;
                    }
                    $strDate = "Le " . $strDate;
                }
                elseif($suffixLang == 'en')
                {
                     $dateString = sprintf("%s %s %s %s", $Mdate, $dayDate . $date->get(Zend_Date::DAY_SUFFIX)," ,", $date->get(Zend_Date::YEAR) );
                     $strDate = $dateString;
                }
                elseif($suffixLang == 'es')
                {
                    $strDate = $day . ', El ' . $dayDate . ' de ' . $Mdate . ' de ' . $year;
                }
                elseif($suffixLang == 'it')
                {
                    $strDate = sprintf("%s %s %s %s", $day, $dayDate, $Mdate, $year );
                }
                break;
            case self::DATE_LONG : // ex:   31 décembre 2010

                $dayDate = $date->get(Zend_Date::DAY);
                if(strpos($dayDate,'0')==0)
                {
                    $dayDate = str_replace("0", "", $dayDate);
                }
                $Mdate = $date->get(Zend_Date::MONTH_NAME);
                $Mdate = utf8_decode($Mdate);
                if($suffixLang == 'fr')
                {
                    $dateString = sprintf("%s %s %s", $dayDate, $Mdate,$date->get(Zend_Date::YEAR) );
                    if($dayDate=='1')
                    {
                        $strDate = str_replace("1 ", "1<sup>er</sup> ", $dateString);
                    }
                    else{
                       $strDate = $dateString;
                    }
                }
                elseif($suffixLang == 'en')
                {
                    $dateString = sprintf("%s %s %s", $Mdate, $dayDate. $date->get(Zend_Date::DAY_SUFFIX), $date->get(Zend_Date::YEAR) );
                    $strDate = $dateString;
                }
                elseif($suffixLang == 'es')
                {
                    $strDate = $dayDate . ' de ' . $Mdate . ' de ' . $year;
                }
                elseif($suffixLang == 'it')
                {
                    $strDate = sprintf("%s %s %s", $dayDate, $Mdate, $date->get(Zend_Date::YEAR) );
                }
                break;
            case self::DATE_LONG_NO_DAY : // ex:   décembre 2010
                $Mdate = $date->get(Zend_Date::MONTH_NAME);
                $Mdate = utf8_decode($Mdate);
                $strDate = sprintf("%s %s",$Mdate,$date->get(Zend_Date::YEAR) );
                break;
            case self::DATE_SHORT :    // ex:   31 déc 2010
                $dayDate = $date->get(Zend_Date::DAY);
                if(strpos($dayDate,'0')==0)
                {
                    $dayDate = str_replace("0", "", $dayDate);
                }
                $MdateNum = $date->get(Zend_Date::MONTH);
                $Mdate = $date->get(Zend_Date::MONTH_NAME);
                $Mdate = utf8_decode($Mdate);

                if($suffixLang == 'fr')
                {
                    if(($MdateNum>5)&&($MdateNum<9)){
                        $Mdate = str_split($Mdate , 4);
                    }
                    else
                    {
                        $Mdate = str_split($Mdate , 3);
                    }
                    $dateString = sprintf("%s %s %s", $dayDate, $Mdate[0],$date->get(Zend_Date::YEAR) );
                    if($dayDate=='1')
                    {
                        $strDate = str_replace("1 ", "1<sup>er</sup> ", $dateString);
                    }
                    else{
                       $strDate = $dateString;
                    }
                }
                elseif($suffixLang == 'en')
                {
                    $Mdate = str_split($Mdate , 3);
                    $strDate = sprintf("%s %s %s %s", $Mdate[0], $dayDate . $date->get(Zend_Date::DAY_SUFFIX),' ,', $date->get(Zend_Date::YEAR) );
                }
                break;
            case self::DATE_NUM : // ex:    31/12/2010
                $dayDate = $date->get(Zend_Date::DAY);
                $dayMonth = $date->get(Zend_Date::MONTH);
                $dayYear = $date->get(Zend_Date::YEAR);
                $strDate = $dayDate . $separator . $dayMonth . $separator . $dayYear;
                break;
            case self::DATE_SQL :  // ex:   2010/12/31
                $dayDate = $date->get(Zend_Date::DAY);
                $dayMonth = $date->get(Zend_Date::MONTH);
                $dayYear = $date->get(Zend_Date::YEAR);
                $strDate = $dayYear . $separator . $dayMonth . $separator . $dayDate;
                break;
             case self::DATE_NUM_USA :  // ex:  12/31/2010
                $dayDate = $date->get(Zend_Date::DAY);
                $dayMonth = $date->get(Zend_Date::MONTH);
                $dayYear = $date->get(Zend_Date::YEAR);
                $strDate = $dayMonth . $separator . $dayDate . $separator . $dayYear;
                break;
            case self::DATE_NUM_SHORT_YEAR :  // ex:  12/31/2010
                $dayDate = $date->get(Zend_Date::DAY);
                $dayMonth = $date->get(Zend_Date::MONTH);
                $dayYear = $date->get(Zend_Date::YEAR);
                $dayYear = substr($dayYear, 2);
                $strDate = $dayMonth . $separator . $dayDate . $separator . $dayYear;
                break;
            case self::DATE_MONTH_YEAR : // ex:  Juin 2011

                $Mdate = $date->get(Zend_Date::MONTH_NAME);
                $Mdate = utf8_decode($Mdate);
                $date_string = sprintf("%s %s", $Mdate, $date->get(Zend_Date::YEAR) );
                $strDate = $date_string;
                break;
        }

        if($cap==true)
        {
            $strDate = strtoupper($strDate);
        }

        return $strDate;
    }

    /**
     * Replace accent and some characters to create a name to add to the url.
     *
     * @param string $string The string to format.
     *
     * @return string
     */
    public static function formatValueForUrl($string)
    {
        (string) $format = strtolower($string);

        $format =  preg_replace('/[àâä]/', "a", $format);
        $format =  preg_replace('/[éèêë]/', "e", $format);
        $format =  preg_replace('/[îï]/', "i", $format);
        $format =  preg_replace('/[ôö]/', "o", $format);
        $format =  preg_replace('/[ùûü]/', "u", $format);
        $format =  preg_replace('/[ç]/', "c", $format);
        $format =  preg_replace('/[&]/', "et", $format);
        $format =  preg_replace('/[\']/', "-", $format);
        $format =  preg_replace('/[\/]/', "-", $format);
        $format =  preg_replace('/[,]/', "-", $format);
        $format =  preg_replace('/["]/', "", $format);
        $format =  preg_replace('/[%]/', "", $format);
        $format =  preg_replace('/ /', "-", $format);
        $format =  preg_replace('/[^A-Za-z0-9_-]/', "", $format);
        $format =  preg_replace('/[-]{2,50}/', "-", $format);
        $format =  preg_replace('/[-]$/', "", $format);
        $format =  preg_replace('/^[-]/', "", $format);


        return $format;
    }

    public static function getParameters($param = '')
    {
        $oParameters = new ParametersObject();
        $parameters  = $oParameters->getAll();

        if(!empty($param))
            $data = $parameters[0][$param];
        else
            $data = $parameters[0];

        return $data;
    }

    /**
     * Compares two floats.<br />
     * Converts the floats into integer and compares the two values.<br />
     * Returns the result of the comparison as boolean.
     *
     * The comparison string is like :<br />
     * - ">"<br />
     * - ">="<br />
     * - "=="<br />
     * - "<="<br />
     * - "<"<br />
     *
     * @param float  $first      The first float to compare, it's the left part.
     * @param string $comparison The kind of comparison to process.
     * @param float  $second     The second float to compare, it's the right part.
     * @param int    $precision  The number of decimal of the floats to convert.
     *
     * @return bool
     */
    public static function compareFloats($first, $comparison, $second, $precision = 5)
    {
        switch ($comparison)
        {
            case ">":
                $exp    = pow(10, $precision);
                $first  = intval($first * $exp);
                $second = intval($second * $exp);
                return ($first > $second);

            case ">=":
                $exp    = pow(10, $precision);
                $first  = intval($first * $exp);
                $second = intval($second * $exp);
                return ($first >= $second);

            case "<":
                $exp    = pow(10, $precision);
                $first  = intval($first * $exp);
                $second = intval($second * $exp);

                return ($first < $second);

            case "<=":
                $exp    = pow(10, $precision);
                $first  = intval($first * $exp);
                $second = intval($second * $exp);

                return ($first <= $second);

            default:
            case "==":
                $exp    = pow(10, $precision);
                $first  = intval($first * $exp);
                $second = intval($second * $exp);
                return ($first == $second);
        }
    }

    public static function provinceTax($amount = 0)
    {
        if(Zend_Session::namespaceIsset('order'))
            $session = Zend_Session::namespaceGet('order');
        else
            throw new Exception('Session namespace "order" is undefined.
                Thus state id is not set. It is not possible to get TVQ rate.
                Modify code to set state if or create the session namespace with
                stateId parameter.');

            $oTaxe = new TaxesObject();
            $taxes = $oTaxe->getTaxData($session['stateId']);
            $rate   = $taxes['TP_Rate']/100;
            if($taxes['TP_Code'] == "QC")
                $taxValue = ($amount + self::federalTax($amount)) * $rate;
            else
                $taxValue = $amount * $rate;


            $taxValue = (float) $taxValue;
            $session  = new Zend_Session_Namespace('order');
            $session->order['rateProv'] = $taxes;
            $session->tvq = $taxValue;

            return $taxValue;
    }

    public static function federalTax($amount = 0)
    {

        $oOrderParams = new ParametersObject();

        $tps = $oOrderParams->getValueByName('CP_TauxTaxeFed');
        $tps = $tps / 100;

        $taxValue = $amount * $tps;
        $taxValue = (float) $taxValue;

        if (Zend_Session::namespaceIsset('order'))
        {
            $session = New Zend_Session_Namespace('order');
            $session->tps = $taxValue;
        }
        return $taxValue;
    }

    /**
     * Return the page url title in a string for rewriting the url.
     *
     * @param string $stringUrl The url of the page
     * @param bool $$pageRemove <OPTIONAL> Default = false. Remove the last 2 params to get the page url title.
     *
     * @return string $title The page url title
     */
    public static function getTitleFromPath($stringUrl,$pageRemove=false){
        $stringUrlRev = strrev($stringUrl);
        $arrayRev = explode("/", $stringUrlRev);
        if(($pageRemove==true)&&(count($arrayRev)>=3)&&($arrayRev[1]=="egap")){
            $title = $arrayRev[2];
            //echo strrev($title);
            return strrev($title);
        }
        else if(count($arrayRev)>=2){
            $title = $arrayRev[0];
            //echo strrev($title);
            return strrev($title);
        }
        return "";
    }

    /**
     * Return the page number for the url that has not the good arguments number.
     *
     * @param string $stringUrl The url of the page
     *
     * @return int the actual page's number
     */
    public static function getPageNumberWithoutParamOrder($stringUrl){
        $stringUrlRev = strrev($stringUrl);
        $arrayRev = explode("/", $stringUrlRev);
        //var_dump($arrayRev);
        if((count($arrayRev)>=2)&&($arrayRev[1]=="egap")){
            return $arrayRev[0];
        }
        return 1;
    }

    /**
     * Return the string without the extra page.
     *
     * @param string $stringUrl The url of the page
     *
     * @return string with the new url without extra page param
     */
    public static function getUrlWithoutExtraPage($stringUrl){

        $arrayStr = explode("/",$stringUrl);
        $returnStringUrl = "";
        for($x = 0; $x<count($arrayStr);$x++){
            if($arrayStr[$x]=="page"){
                if($arrayStr[$x+2]!="page"){
                    $returnStringUrl .= "/" . $arrayStr[$x];
                }
                else{
                    $x++;
                }
            }
            else{
                if($returnStringUrl==""){
                    $returnStringUrl = $arrayStr[$x];
                }
                else{
                    $returnStringUrl .= "/" . $arrayStr[$x];
                }
            }
        }
        return $returnStringUrl;
    }


    /**
     * Format and return a menu made out of 2 or more menus
     *
     * @param string    $arrayOption    A string to put in the ul of the menu. Example: " class='blueMenu' id='ulTopMenu'"
     *
     * @param array     $arrayMenu      An array that contains the menus and their options.
     *                                  Example: ( array(array($menuTrio2," class='menuHaut2'"),array($menuTrio1," class='menuHaut1'")) )
     *
     * @param bool      $reverse        Wheter or not the menu li will be reverse.
     *                                  Example: <ul><li>111</li><li>222</li></ul> will become <ul><li>222</li><li>111</li></ul>
     *
     * @param bool      $stripTagA      Remove everything inside the li except the <a> tag.
     *
     * @param array     $arrayOption    options supported:  'addSeparator' => 'image or character'
     *                                                      'addSeparatorBeforeFirst' => 'bool'
     *                                                      'addSeparatorAfterLast' => 'bool'
     *
     * @return a menu with concatenated menus
     */
    public static function returnMenuFromMenus($stringOption, $arrayMenu, $reverse=false, $stripTagA=true, $arrayOption = array()){

         $returnStr = "<ul" . $stringOption . ">";
         $separator = "";
         $separatorBool = false;
         $separatorLast = false;
         //var_dump($arrayOption);
         if(isset($arrayOption['addSeparator'])){
             $separator = $arrayOption['addSeparator'];
             if($separator!=""){
                $separatorBool = true;
             }
         }
         if(isset($arrayOption['addSeparatorBeforeFirst'])){
             if($arrayOption['addSeparatorBeforeFirst']==true){
                 $returnStr .= "<li class='verticalSeparator'>" . $separator . "</li>";
             }
         }
         if(isset($arrayOption['addSeparatorAfterLast'])){
             if($arrayOption['addSeparatorAfterLast']==true){
                $separatorLast = true;
             }
         }
         $arrayString = array();
         foreach($arrayMenu as $items){
            $item = $items[0];
            $option = "";
            if(isset($items[1])){
                $option = $items[1];
            }
            $ulInside = "";
            $subStringUL = substr_count($item, '</ul>');
            if($subStringUL>1){
                $oneU = strpos($item,"<ul");
                $twoU = strrpos($item,"</ul>");
                $threeU = substr($item, $oneU+1, ($twoU-$oneU-2));
                $oneU = strpos($threeU,"<li");
                $threeU = substr($threeU, $oneU);
                array_push($arrayString,$threeU);
             }
             else{
                 $subStringOcc =  substr_count($item, '</li>');
                 for($x = 0; $x < $subStringOcc; $x++){
                     $oneP = strpos($item,"<li");
                     $twoP = strpos($item,"</li>");
                     $subS = substr($item, $oneP, ($twoP-$oneP));
                     $item = substr($item, $twoP+5);
                     if($stripTagA==true){
                        $subS = strip_tags($subS, '<a>');
                     }
                     $tempStr = "<li " . $option . ">";
                     $tempStr .= $subS;
                     $tempStr .= "</li>";
                     array_push($arrayString,$tempStr);
                }
            }
        }
        if($reverse==true){
            $arrayString = array_reverse($arrayString);
        }
        $numberArray = count($arrayString);
        $x = 1;
        foreach($arrayString as $item){
            $returnStr .= $item;
            if(($separatorBool==true)&&($x<$numberArray)){
                $returnStr .= "<li class='verticalSeparator'>" . $separator . "</li>";
            }
            $x++;
        }
        if($separatorLast){
            $returnStr .= "<li class='verticalSeparator'>" . $separator . "</li>";
        }
        $returnStr .= "</ul>";
        return $returnStr;
     }

   /**
     * Explodes a string into pairs of key|values to retrieve parameters.
     *
     * @param string $string Comment string from column which contains the
     *                       parameters
     * @param string $split     The delimiter between paramters.
     * @param string $pairSplit The delimiter between key:value in the parameter
     *
     * @return array
     */
    public static function fetchParams($string, $split = '|', $pairSplit = ':')
    {
        $params = array();
        if (!empty($string))
        {
            $tmp = explode($split, $string);
            foreach ($tmp as $value)
            {
                $split = explode($pairSplit, $value);
                $params[$split[0]] = $split[1];
            }
        }
        return $params;
    }
}