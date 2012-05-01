<?php 
/**
 * feindura - Flat File Content Management System
 * Copyright (C) Fabian Vogelsteller [frozeman.de]
 * 
 * This program is free software;
 * you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License along with this program;
 * if not,see <http://www.gnu.org/licenses/>.
 * 
 * backend.include.php 
 * 
 * @version 0.3
 */

$sessionLifeTime = (60 * 60 * 3); // 3 hours

// -> START SESSION (for the login, language and storedPages [currently deactivated])
ini_set('session.gc_maxlifetime', $sessionLifeTime); // saves the session for 3 hours minutes
session_name('session');
session_start();

// RESET the SESSION TIME as always when reloading the page
$_SESSION['feinduraSession']['login']['end'] = time() + $sessionLifeTime;


// INCLUDE GENERAL
require_once(dirname(__FILE__)."/general.include.php");

// ->> CHECK PHP VERSION
// *********************
if(PHP_VERSION < REQUIREDPHPVERSION) {
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8">
  <meta http-equiv="content-language" content="en">
  
  <title>feindura PHP error</title>
  
  <meta http-equiv="X-UA-Compatible" content="chrome=1">
  
  <meta name="robots" content="no-index,nofollow">
  <meta http-equiv="pragma" content="no-cache"> <!--browser/proxy dont cache-->
  <meta http-equiv="cache-control" content="no-cache"> <!--proxy dont cache-->
  <meta http-equiv="accept-encoding" content="gzip, deflate">
  
  <meta name="title" content="feindura login">    
  <meta name="author" content="Fabian Vogelsteller [frozeman.de]">     
  <meta name="publisher" content="Fabian Vogelsteller [frozeman.de]">
  <meta name="copyright" content="Fabian Vogelsteller [frozeman.de]">    
  <meta name="description" content="A flat file based Content Management System, written in PHP">    
  <meta name="keywords" content="cms,content,management,system,flat,file">
   
  <link rel="shortcut icon" href="favicon.ico">
  
  <link rel="stylesheet" type="text/css" href="library/styles/reset.css" media="all">
  <link rel="stylesheet" type="text/css" href="library/styles/login.css" media="all">

</head>
<body>
  <div id="container">
    <div id="loginSuccessBox">
      <div class="top"></div>
      <div class="middle">     
      <?php      
      echo 'ERROR<br><br><span class="logoname">fein<span>dura</span></span> requires at least PHP version '.REQUIREDPHPVERSION;
      ?>
      </div>
      <div class="bottom"></div>
    </div>
</body>
</html>
<?php
  die();
}
// PHP VERSION CHECK *** END
// *************************


// ->> get CONFIGS
/**
 * The user-settings config
 * 
 * This config <var>array</var> is included from: <i>"feindura-CMS/config/user.config.php"</i>
 * 
 * @global array $GLOBALS['userConfig']
 */
if(!$userConfig = @include(dirname(__FILE__)."/../../config/user.config.php"))
  $userConfig = array();
$GLOBALS['userConfig'] = $userConfig;


// -> INCLUDE FUNCTIONS
require_once(dirname(__FILE__)."/../functions/backend.functions.php");

// -> SET ERROR HANDLER
@set_error_handler("showErrorsInWindow",E_ALL ^ E_NOTICE);// E_ALL ^ E_NOTICE ^ E_WARNING

// -> CREATE the CONFIG, PAGES and STATISTIC FOLDERS if they dont exist
createBasicFolders();

// -> SET the BASIC VARIABLEs
$errorWindow      = false; // when string the errorWindow with this string is displayed
$documentSaved    = false; // when true the document saved icon is displayed
$savedForm        = false; // to tell wich part fo the form was saved
$savedSettings    = false; // to tell wich settings were saved, to re-include the settings
$newPage          = false; // tells the editor whether a new page is created
$userCache        = ((!isset($_GET['status']) && !isset($_POST['status']) ) || $_GET['status'] == 'updateUserCache') ? userCache() : array();

// ->> SEND INFO to CONTENT.JS, so when updated the user config and a page gets free, it can remove the "contentBlocked" DIV
if($_GET['status'] == 'updateUserCache' && isBlocked() === false) {
  echo 'releaseBlock';
}

/**
 * GET the PAGE LANGUAGE CODE
 * 
 */
if($adminConfig['multiLanguageWebsite']['active']) {
  //XSS Filter
  if(isset($_GET['websiteLanguage'])) $_GET['websiteLanguage'] = XssFilter::alphabetical($_GET['websiteLanguage']);
  if(isset($_SESSION['feinduraSession']['websiteLanguage'])) $_SESSION['feinduraSession']['websiteLanguage'] = XssFilter::alphabetical($_SESSION['feinduraSession']['websiteLanguage']);

  if($adminConfig['multiLanguageWebsite']['active'] && isset($_GET['websiteLanguage']))
    $websiteLanguage = $_GET['websiteLanguage'];
  elseif($adminConfig['multiLanguageWebsite']['active'] && !isset($_GET['websiteLanguage']) && !empty($_SESSION['feinduraSession']['websiteLanguage']))
    $websiteLanguage = $_SESSION['feinduraSession']['websiteLanguage'];
  elseif(!empty($adminConfig['multiLanguageWebsite']['mainLanguage']))
    $websiteLanguage = $adminConfig['multiLanguageWebsite']['mainLanguage'];
  // reset the websiteLanguage SESSION var
  $_SESSION['feinduraSession']['websiteLanguage'] = $websiteLanguage;
  unset($websiteLanguage);
} else
  $_SESSION['feinduraSession']['websiteLanguage'] = 0;

/**
 * SET the BACKEND LANGUAGE
 * And loads the language files
 *
 */
// unset($_SESSION['feinduraSession']['backendLanguage']);
//XSS Filter
if(isset($_GET['backendLanguage'])) $_GET['backendLanguage'] = XssFilter::alphabetical($_GET['backendLanguage']);
if(isset($_SESSION['feinduraSession']['backendLanguage'])) $_SESSION['feinduraSession']['backendLanguage'] = XssFilter::alphabetical($_SESSION['feinduraSession']['backendLanguage']);

if(isset($_GET['backendLanguage'])) $_SESSION['feinduraSession']['backendLanguage'] = $_GET['backendLanguage'];

// GET BROWSER LANGUAGE, if no language is given
if(empty($_SESSION['feinduraSession']['backendLanguage']))
  $_SESSION['feinduraSession']['backendLanguage'] = GeneralFunctions::getBrowserLanguages('en',true);
// LOAD LANG FILES
$backendLangFile = GeneralFunctions::loadLanguageFile(false,'%lang%.backend.php',$_SESSION['feinduraSession']['backendLanguage']);
$sharedLangFile = GeneralFunctions::loadLanguageFile(false,'%lang%.shared.php',$_SESSION['feinduraSession']['backendLanguage']);

$langFile = array_merge($sharedLangFile,$backendLangFile);
unset($backendLangFile,$sharedLangFile);

?>