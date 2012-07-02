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
 * sites/websiteSetup.php
 *
 * @version 2.0
 */

/**
 * Includes the login.include.php and backend.include.php and filter the basic data
 */
require_once(dirname(__FILE__).'/../includes/secure.include.php');

?>
<form action="index.php?site=websiteSetup" method="post" enctype="multipart/form-data" accept-charset="UTF-8" id="websiteSettingsForm">
  <div>
  <input type="hidden" name="send" value="websiteSetup">
  <input type="hidden" name="websiteLanguage" value="<?php echo $_SESSION['feinduraSession']['websiteLanguage']; ?>">
  <input type="hidden" name="savedBlock" id="savedBlock" value="">
  </div>

<!-- WEBSITE SETTINGS -->
<?php
// shows the block below if it is the ones which is saved before
$hidden = ($savedForm == 'websiteConfig' || empty($savedForm)) ? '' : ' hidden';
?>
<div class="block<?php echo $hidden; ?>">
  <h1><a href="#" id="websiteSettings"><?php echo $langFile['websiteSetup_websiteConfig_h1']; ?></a></h1>
  <div class="content">
    <table>

      <colgroup>
      <col class="left">
      </colgroup>

      <tbody>
        <tr><td class="leftTop"></td><td></td></tr>

        <tr><td class="left">
        <label for="title"><span class="toolTip" title="::<?php echo $langFile['websiteSetup_websiteConfig_field1_tip']; ?>">
        <?php echo $langFile['websiteSetup_websiteConfig_field1']; ?></span></label>
        </td><td class="right">
        <input id="title" name="title" value="<?php echo GeneralFunctions::getLocalized($websiteConfig,'title',true); ?>">
        </td></tr>

        <tr><td class="left">
        <label for="publisher"><span class="toolTip" title="::<?php echo $langFile['websiteSetup_websiteConfig_field2_tip']; ?>">
        <?php echo $langFile['websiteSetup_websiteConfig_field2']; ?></span></label>
        </td><td class="right">
        <input id="publisher" name="publisher" value="<?php echo GeneralFunctions::getLocalized($websiteConfig,'publisher',true); ?>">
        </td></tr>

        <tr><td class="left">
        <label for="websiteConfig_copyright"><span class="toolTip" title="::<?php echo $langFile['websiteSetup_websiteConfig_field3_tip']; ?>">
        <?php echo $langFile['websiteSetup_websiteConfig_field3']; ?></span></label>
        </td><td class="right">
        <input id="websiteConfig_copyright" name="websiteConfig_copyright" value="<?php echo GeneralFunctions::getLocalized($websiteConfig,'copyright',true); ?>">
        </td></tr>

        <tr><td class="spacer"></td><td></td></tr>

        <tr><td class="left">
        <label for="keywords"><span class="toolTip" title="::<?php echo $langFile['websiteSetup_websiteConfig_field4_tip']; ?>">
        <?php echo $langFile['websiteSetup_websiteConfig_field4']; ?></span></label>
        </td><td class="right">
        <input id="keywords" name="keywords" value="<?php echo GeneralFunctions::getLocalized($websiteConfig,'keywords',true); ?>" class="inputToolTip" title="<?php echo $langFile['websiteSetup_websiteConfig_field4_inputTip']; ?>">
        </td></tr>

        <tr><td class="left">
        <label for="description"><span class="toolTip" title="::<?php echo $langFile['websiteSetup_websiteConfig_field5_tip']; ?>">
        <?php echo $langFile['websiteSetup_websiteConfig_field5']; ?></span></label>
        </td><td class="right">
        <textarea id="description" name="description" cols="50" rows="4" style="white-space:normal;width:500px;height:70px;" class="inputToolTip" title="<?php echo $langFile['websiteSetup_websiteConfig_field5_inputTip']; ?>"><?php echo GeneralFunctions::getLocalized($websiteConfig,'description',true); ?></textarea>
        </td></tr>

        <tr><td class="leftBottom"></td><td></td></tr>
      </tbody>
    </table>

    <input type="submit" value="" name="websiteConfig" class="button submit center" title="<?php echo $langFile['FORM_BUTTON_SUBMIT']; ?>" onclick="$('savedBlock').value = 'websiteConfig'; submitAnchor('websiteSettingsForm','websiteConfig');">
  </div>
  <div class="bottom"></div>
</div>


<!-- ADVANCED WEBSITE SETTINGS -->
<a id="advancedWebsiteConfig" class="anchorTarget"></a>
<?php
// shows the block below if it is the ones which is saved before
$hidden = ($savedForm == 'advancedWebsiteConfig') ? '' : ' hidden';
?>
<div class="block<?php echo $hidden; ?>">
  <h1><a href="#"><?php echo $langFile['WEBSITESETUP_TITLE_PAGESETTINGS']; ?></a></h1>
  <div class="content">
    <table>

      <colgroup>
      <col class="left">
      </colgroup>

      <tbody>
        <tr><td class="left checkboxes">
        <input type="checkbox" id="maintenance" name="maintenance" value="true" class="toolTip" title="::<?php echo $langFile['WEBSITESETUP_TIP_MAINTENANCE']; ?>"<?php if($websiteConfig['maintenance']) echo ' checked="checked"'; ?>>
        </td><td class="right checkboxes">
        <label for="maintenance"><span class="toolTip" title="::<?php echo $langFile['WEBSITESETUP_TIP_MAINTENANCE']; ?>"><?php echo $langFile['WEBSITESETUP_TEXT_MAINTENANCE']; ?></span></label>
        </td></tr>

        <tr><td class="left checkboxes">
        <input type="checkbox" id="setStartPage" name="setStartPage" value="true" class="toolTip" title="::<?php echo $langFile['WEBSITESETUP_TIP_SETSTARTPAGE']; ?>"<?php if($websiteConfig['setStartPage']) echo ' checked="checked"'; ?>>
        </td><td class="right checkboxes">
        <label for="setStartPage"><span class="toolTip" title="::<?php echo $langFile['WEBSITESETUP_TIP_SETSTARTPAGE']; ?>"><?php echo $langFile['WEBSITESETUP_TEXT_SETSTARTPAGE']; ?></span></label>
        </td></tr>

        <tr><td class="spacer checkboxes"></td><td>

        <tr><td class="left checkboxes">
        <input type="checkbox" id="multiLanguageWebsite" name="multiLanguageWebsite" value="true" <?php if($websiteConfig['multiLanguageWebsite']['active']) echo ' checked="checked"'; ?> class="toolTip" title="::<?php echo $langFile['WEBSITESETUP_TIP_MULTILANGUAGEWEBSITE'] ; ?>">
        </td><td class="right checkboxes">
        <label for="multiLanguageWebsite" class="toolTip" title="::<?php echo $langFile['WEBSITESETUP_TIP_MULTILANGUAGEWEBSITE'] ; ?>"><?php echo $langFile['WEBSITESETUP_TEXT_MULTILANGUAGEWEBSITE']; ?></label>
        </td></tr>

        <!-- Website Language Selection -->
        <tr><td class="left checkboxes">
        </td><td class="right checkboxes">
        <select id="websiteLanguageChoices" name="websiteLanguageChoices[]" multiple="multiple"<?php if(!$websiteConfig['multiLanguageWebsite']['active']) echo ' disabled="disabled"'; ?> class="toolTip" title="::<?php echo $langFile['WEBSITESETUP_TIP_MULTILANGUAGEWEBSITE'] ; ?>">
        <?php
          foreach($languageNames as $langKey => $langValue) {
            if((is_array($websiteConfig['multiLanguageWebsite']['languages']) && !in_array($langKey, $websiteConfig['multiLanguageWebsite']['languages'])) || empty($websiteConfig['multiLanguageWebsite']['languages']))
              echo '<option value="'.$langKey.'">'.$langValue.'</option>';
          }
        ?>
        </select>
        <select id="websiteLanguages" name="websiteLanguages[]" multiple="multiple"<?php if(!$websiteConfig['multiLanguageWebsite']['active']) echo ' disabled="disabled"'; ?>>
        <?php
          if(is_array($websiteConfig['multiLanguageWebsite']['languages'])) {
            foreach($websiteConfig['multiLanguageWebsite']['languages'] as $langKey) {
              echo '<option value="'.$langKey.'" selected="selected">'.$languageNames[$langKey].'</option>';
            }
          }
        ?>
        </select>
        </td></tr>

        <!-- Websites Main Language -->
        <tr id="websiteMainLanguageTr"<?php if(empty($websiteConfig['multiLanguageWebsite']['languages'])) echo ' style="display:none;"'; ?>><td class="left checkboxes">
        <label for="websiteMainLanguage"><span class="toolTip" title="::<?php echo $langFile['WEBSITESETUP_TIP_MAINLANGUAGE']; ?>"><?php echo $langFile['WEBSITESETUP_TEXT_MAINLANGUAGE']; ?></span></label>
        </td><td class="right checkboxes">
        <select id="websiteMainLanguage" name="websiteMainLanguage"<?php if(!$websiteConfig['multiLanguageWebsite']['active']) echo ' disabled="disabled"'; ?> class="toolTip" title="::<?php echo $langFile['WEBSITESETUP_TIP_MAINLANGUAGE']; ?>">
        <?php
          if(is_array($websiteConfig['multiLanguageWebsite']['languages'])) {
            foreach($websiteConfig['multiLanguageWebsite']['languages'] as $langKey) {
              $selected = ($langKey == $websiteConfig['multiLanguageWebsite']['mainLanguage']) ? ' selected="selected"' : '' ;
              echo '<option value="'.$langKey.'"'.$selected.'>'.$languageNames[$langKey].'</option>';
            }
          }
        ?></select>
        </td></tr>

        <tr><td class="spacer checkboxes"></td><td>
      </tbody>
    </table>

    <!--<input type="reset" value="" class="button cancel" title="<?php echo $langFile['FORM_BUTTON_CANCEL']; ?>">-->
    <input type="submit" value="" class="button submit center" title="<?php echo $langFile['FORM_BUTTON_SUBMIT']; ?>" onclick="$('savedBlock').value = 'advancedWebsiteConfig'; submitAnchor('websiteSettingsForm','advancedWebsiteConfig');">
  </div>
  <div class="bottom"></div>
</div>

</form>

<?php if(!empty($adminConfig['websiteFilesPath']) || !empty($adminConfig['stylesheetPath'])) { ?>
<div class="blockSpacer"></div>
<?php

  // EDIT stylesheets
  if($adminConfig['user']['editStyleSheets']) {
    editFiles($adminConfig['stylesheetPath'], 'cssFiles', $langFile['EDITFILESSETTINGS_TITLE_STYLESHEETS'], 'cssFilesAnchor', 'css');
  }

  // EDIT snippets
  if($adminConfig['user']['editSnippets']) {
    if(!is_dir(dirname(__FILE__).'/../../snippets/')) mkdir(dirname(__FILE__).'/../../snippets/');
    editFiles(dirname(__FILE__).'/../../snippets/', 'snippetFiles', $langFile['EDITFILESSETTINGS_TITLE_SNIPPETS'], 'snippetsFilesAnchor', 'php');
  }

  // EDIT websitefiles
  if($adminConfig['user']['editWebsiteFiles']) {
    editFiles($adminConfig['websiteFilesPath'], 'websiteFiles',  $langFile['EDITFILESSETTINGS_TITLE_WEBSITEFILES'], 'websiteFilesAnchor');
  }

}

?>