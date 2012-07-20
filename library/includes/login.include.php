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
 * This file is inlcuded in the index.php and all standalone files
 *
 * @version 1.0
 */

// var
$loginError = false;
$loggedOut = false;
$resetPassword = false;
if(isset($_POST['username'])) $_POST['username'] = XssFilter::text($_POST['username']);
if(isset($_POST['password'])) $_POST['password'] = XssFilter::text($_POST['password']);
//unset($_SESSION);

// -> if NO USER EXISTS
if(empty($userConfig)) {
  $_SESSION['feinduraSession']['login']['user'] = false;
  $_SESSION['feinduraSession']['login']['loggedIn'] = true;
  $_SESSION['feinduraSession']['login']['host'] = HOST;
}

// ->> LOGIN FORM SEND
if(isset($_POST) && $_POST['action'] == 'login') {

  // -> if user exits
  if(!empty($userConfig)) {

    $currentUser = false;
    foreach($userConfig as $user) {
      if($user['username'] == $_POST['username']) {
        $currentUser = $user;
        $currentUserId = $user['id'];
      }
    }

    // -> check password
    if(!empty($_POST['username']) && $currentUser) {
      if(md5($_POST['password']) == $currentUser['password']) {
        $_SESSION['feinduraSession']['login']['user'] = $currentUserId;
        $_SESSION['feinduraSession']['login']['loggedIn'] = true;
        $_SESSION['feinduraSession']['login']['host'] = HOST;
        $_SESSION['feinduraSession']['login']['end'] = time() + $sessionLifeTime; // $sessionLifeTime is set in the backend.include.php
      } else
        $loginError = $langFile['LOGIN_ERROR_WRONGPASSWORD'];
    } else
      $loginError = $langFile['LOGIN_ERROR_WRONGUSER'];
  } else
    $loginError = $langFile['LOGIN_ERROR_WRONGUSER'];
}

// ->> RESET PASSWORD
if(isset($_POST) && $_POST['action'] == 'resetPassword' && !empty($_POST['username'])) {
  $userConfig = @include("config/user.config.php");

  $currentUser = false;
  foreach($userConfig as $user) {
    if($user['username'] == $_POST['username'])
      $currentUser = $user;
  }

  if($currentUser) {

    $userEmail = $currentUser['email'];

    if(!empty($userEmail)) {

      // generate new password
      $chars = array_merge(range(0, 9),range('a', 'z'),range('A', 'Z'));
      shuffle($chars);
      $newPassword = implode('', array_slice($chars, 0, 5));

      $subject = $langFile['LOGIN_TEXT_NEWPASSWORDEMAIL_SUBJECT'].': '.$adminConfig['url'];
      $message = $langFile['LOGIN_TEXT_NEWPASSWORDEMAIL_MESSAGE']."\n".$_POST['username']."\n".$newPassword;
      $header = 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/plain; charset=UTF-8' . "\r\n"; // UTF-8 plain text mail
      $header .= 'From: "feindura CMS from '.$adminConfig['url'].'" <noreply@'.str_replace(array('http://','https://','www.'),'',$adminConfig['url']).">\r\n";
      $header .= 'X-Mailer: PHP/' . PHP_VERSION;

      // change users password
      $newUserConfig = $userConfig;
      $newUserConfig[$currentUser['id']]['password'] = md5($newPassword);

      // send mail with the new password
      if(saveUserConfig($newUserConfig)) {
        if(mail($userEmail,'=?UTF-8?B?'.base64_encode($subject).'?=',$message,$header)) {
          $resetPassword = true;
          unset($_GET['resetpassword']);
        } else
          $loginError = $langFile['LOGIN_ERROR_FORGOTPASSWORD_NOTSEND'];
      } else
        $loginError = $langFile['LOGIN_ERROR_FORGOTPASSWORD_NOTSAVED'];
    } else
      $loginError = $langFile['LOGIN_ERROR_FORGOTPASSWORD_NOEMAIL'];
  } else
    $loginError = $langFile['LOGIN_ERROR_WRONGUSER'];

}

// -> LOGOUT
if(isset($_GET['logout']) || (isset($_SESSION['feinduraSession']['login']['end']) && $_SESSION['feinduraSession']['login']['end'] <= time())) { // automatically logout after 3 hours
  $_SESSION['feinduraSession']['login'] = array();
  unset($_SESSION['feinduraSession']['login']);
  session_regenerate_id(true);
  $loggedOut = true;

// -> else RESET the SESSION TIME as always when reloading the page
} else {
  $_SESSION['feinduraSession']['login']['end'] = time() + $sessionLifeTime;
}


// ->> CHECK if user is logged in
// *****************************************************
if($_SESSION['feinduraSession']['login']['loggedIn'] === true &&
   $_SESSION['feinduraSession']['login']['host'] === HOST) {


  // ->> USERID
  /**
   * The ID of the current user
   */
  define('USERID',$_SESSION['feinduraSession']['login']['user']);


// ->> SHOW LOGIN FORM
} else {

  // DEBUG
  // echo 'server name: '.session_name().'->'.session_id().'<br>';
  // echo 'server host: '.HOST.'<br>';
  // echo 'stored host: '.$_SESSION['feinduraSession']['login']['host'].'<br>';
  // echo '<pre>';
  // print_r($_SESSION);
  // echo '</pre>';

  ?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['feinduraSession']['backendLanguage']; ?>" class="feindura">
<head>
  <meta charset="UTF-8">

  <title>feindura login</title>

  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=0.5">

  <meta name="robots" content="no-index,nofollow">
  <meta http-equiv="pragma" content="no-cache"> <!--browser/proxy dont cache-->
  <meta http-equiv="cache-control" content="no-cache"> <!--proxy dont cache-->
  <meta http-equiv="accept-encoding" content="gzip, deflate">

  <meta name="author" content="Fabian Vogelsteller [frozeman.de]">
  <meta name="publisher" content="Fabian Vogelsteller [frozeman.de]">
  <meta name="copyright" content="Fabian Vogelsteller [frozeman.de]">
  <meta name="description" content="A flat file based Content Management System, written in PHP">
  <meta name="keywords" content="cms,flat,file,content,management,system">

  <link rel="shortcut icon" href="favicon.ico">

  <!-- feindura styles -->
  <link rel="stylesheet" type="text/css" href="library/styles/styles.css<?php echo '?v='.BUILD; ?>">

  <!-- thirdparty/MooTools -->
  <script type="text/javascript" src="library/thirdparty/javascripts/mootools-core-1.4.5.js"></script>
  <script type="text/javascript" src="library/thirdparty/javascripts/mootools-more-1.4.0.1.js"></script>

  <!-- thirdparty/Raphael -->
  <script type="text/javascript" src="library/thirdparty/javascripts/raphael-1.5.2.js"></script>

  <!-- thirdparty/Html5Shiv -->
  <!--[if lt IE 9]><script type="text/javascript" src="library/thirdparty/javascripts/html5shiv.min.js"></script><![endif]-->

  <!-- javascripts -->
  <script type="text/javascript" src="library/javascripts/shared.js"></script>

  <script type="text/javascript">
  function supports_input_placeholder() {
    var i = document.createElement('input');
    return 'placeholder' in i;
  }

  window.addEvent('load',function() {
    if(!supports_input_placeholder()) {
      new OverText('username',{positionOptions: {offset: {x: 0,y: 0}}});
      <?php if(!isset($_GET['resetpassword'])) { ?>
      new OverText('password',{positionOptions: {offset: {x: 0,y: 0}}});
      <?php } ?>
    }
  });

  function startLoadingCircle() {
    // create loading circle element
    var loginLoadingCircle = new Element('div', {id: 'loginLoadingCircle'});
    loginLoadingCircle.replaces('submitButton');
    var removeLoadingCircle = feindura_loadingCircle('loginLoadingCircle', 12, 20, 12, 3, "#000");
  }

  </script>
</head>
<body>
  <div class="container">
  <?php if($loggedOut) {  ?>
    <div class="alert center">
      <?php
        echo '<strong>'.$langFile['LOGIN_TEXT_LOGOUT_PART1'].'</strong><br><a href="'.$adminConfig['url'].GeneralFunctions::Path2URI($adminConfig['websitePath']).'">'.$langFile['LOGIN_TEXT_LOGOUT_PART2'].'</a>';
      ?>
    </div>
  <?php } ?>
  <?php if($resetPassword) {  ?>
    <div class="alert alert-success center">
      <?php
        echo '<strong>'.$langFile['LOGIN_ERROR_FORGOTPASSWORD_SUCCESS'].'</strong><br>'.$userEmail;
      ?>
    </div>
  <?php } ?>
  <?php if($loginError) { ?>
    <div class="alert alert-error center">
      <?php echo $loginError; ?>
    </div>
  <?php } ?>
    <div class="loginBox">
      <img class="logo" src="library/images/icons/logo.png" alt="logo">
      <?php

      if(isset($_GET['resetpassword']) && !$resetPassword)
        $currentURL = GeneralFunctions::addParameterToUrl('resetpassword','true');
      else
        $currentURL = $_SERVER['PHP_SELF'];

      ?>
      <form action="<?php echo $currentURL; ?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8" onsubmit="startLoadingCircle();">
        <div>
          <input type="text" value="<?php echo $_POST['username']; ?>" name="username" id="username" placeholder="<?php echo $langFile['LOGIN_INPUT_USERNAME']; ?>" title="<?php echo $langFile['LOGIN_INPUT_USERNAME']; ?>" autofocus="autofocus"><br>
        <?php if(!isset($_GET['resetpassword'])) { ?>
          <input type="password" value="<?php echo $_POST['password']; ?>" name="password" id="password" placeholder="<?php echo $langFile['LOGIN_INPUT_PASSWORD']; ?>" title="<?php echo $langFile['LOGIN_INPUT_PASSWORD']; ?>"><br>
        <?php }
        if(!isset($_GET['resetpassword'])) {
          echo '<input type="hidden" name="action" value="login">';
          echo '<input type="submit" id="submitButton" class="btn btn-large" name="loginSubmit" value="'.$langFile['LOGIN_BUTTON_LOGIN'].'">';
        } else {
          echo '<input type="hidden" name="action" value="resetPassword">';
          echo '<input type="submit" id="submitButton" class="btn btn-large" name="resetPasswordSubmit" value="'.$langFile['LOGIN_BUTTON_SENDNEWPASSWORD'].'">';
        } ?>
        </div>
      </form>
    </div>
    <footer class="info">
    <?php
      echo $langFile['LOGIN_TEXT_COOKIESNEEDED'].'<br>';

       echo (isset($_GET['resetpassword']))
        ? '<a href="index.php">'.$langFile['LOGIN_LINK_BACKTOLOGIN'].'</a>'
        : '<a href="index.php?resetpassword">'.$langFile['LOGIN_LINK_FORGOTPASSWORD'].'</a>';
    ?>
    </footer>
  </div>
</body>
</html>
<?php
  die();
}
?>