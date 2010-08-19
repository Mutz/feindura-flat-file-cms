<?php
/*
    feindura - Flat File Content Management System
    Copyright (C) Fabian Vogelsteller [frozeman.de]

    This program is free software;
    you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 3 of the License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
    without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
    See the GNU General Public License for more details.

    You should have received a copy of the GNU General Public License along with this program;
    if not,see <http://www.gnu.org/licenses/>.

* pluginSetup.php version 0.1
*/

include_once(dirname(__FILE__)."/../backend.include.php");


// ----------------------------------------------------------------------------------------------------------------------------------------
// **--** SAVE PROCESS --------------------------------------------------------------------------------------------------------------------
// ----------------------------------------------------------------------------------------------------------------------------------------


// ---------- SAVE the editFiles
include(dirname(__FILE__).'/../process/saveEditFiles.php');


// ------------------------------- ENDE of the SAVING SCRIPT -------------------------------------------------------------------------------


// CHECKs if the ncessary FILEs are WRITEABLE, otherwise throw an error
// ----------------------------------------------------------------------------------------
$checkFolders[] = $adminConfig['basePath'].'plugins/';

// gives the error OUTPUT if one of these files in unwriteable
if(($unwriteableList = isWritableWarningRecursive($checkFolders)) && checkBasePath()) {
  echo '<div class="block warning">
    <h1>'.$langFile['adminSetup_error_title'].'</h1>
    <div class="content">
      <p>'.$unwriteableList.'</p><!-- needs <p> tags for margin-left:..-->
    </div>
    <div class="bottom"></div>  
  </div>'; 
  
  echo '<div class="blockSpacer"></div>';
}


// ->> GOES TROUGH every PLUGIN
// ---------------------------------------------------------------------------------------------------------------
$pluginFolders = $generalFunctions->readFolder(DOCUMENTROOT.$adminConfig['basePath'].'plugins/'); //DOCUMENTROOT.$adminConfig['basePath'].'plugins/'; //dirname(__FILE__).'/../../plugins/'

// VARs
  $firstLine = true;

if($pluginFolders) {
  foreach($pluginFolders['folders'] as $pluginFolder) {
    
    // ->> IF plugin folder HAS FILES
    if($pluginSubFolders = $generalFunctions->readFolderRecursive($pluginFolder)) {
  
      // VARs
      $pluginName = basename($pluginFolder);
      $savedForm = false;    
  
      // ->> WRITE PLUGIN CONFIG
      // ---------------------------------------------------------------------------
      if(isset($_POST['send']) && $_POST['send'] ==  $pluginName) {
        
        // -> OPEN settings file
        if($pluginConfigFile = @fopen($pluginFolder.'/plugin.config.php','w+')) {
          // *** write
          flock($pluginConfigFile,2); //LOCK_EX
            fwrite($pluginConfigFile,PHPSTARTTAG); //< ?php
        
            fwrite($pluginConfigFile,"\$pluginConfig['active']          = '".$_POST['plgcfg_active']."';\n\n");
            //fwrite($pluginConfig,"\$pluginConfig['publisher']      = '".htmlentities($givenSettings['publisher'],ENT_QUOTES,'UTF-8')."';\n");
            
            fwrite($pluginConfigFile,"return \$pluginConfig;");
          
            fwrite($pluginConfigFile,PHPENDTAG); //? >
          flock($pluginConfigFile,3); //LOCK_UN
          fclose($pluginConfigFile);
          
          // give documentSaved status
          $documentSaved = true;
          $savedForm = true;
          $statisticFunctions->saveTaskLog(11,$pluginName); // <- SAVE the task in a LOG FILE        
          
        } else
          $errorWindow = $langFile['pluginSetup_pluginconfig_error_save'].' '.$pluginFolder.'/plugin.config.php';
      }
      
      // INCLUDE PLUGIN CONFIG
      if(!$pluginConfig = @include($pluginFolder.'/plugin.config.php'))
        $pluginConfig = array();
      
      
      // -> BEGINN PLUGIN FORM
      // ---------------------------------------------------------------------------
      
      // seperation line
      if($firstLine) $firstLine = false;
      else echo '<div class="blockSpacer"></div>';    
      
      
      echo '<form action="?site=pluginSetup#'.$pluginName.'Anchor" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
              <div>
              <input type="hidden" name="send" value="'.$pluginName.'" />
              </div>';
      
      echo '<div class="block open">
              <h1>'.$pluginName.'</h1>';
  ?>
              <table>
         
                <colgroup>
                <col class="left" />
                </colgroup>
                
                
                <tr><td class="left checkboxes">
                <input type="checkbox" id="plgcfg_active" name="plgcfg_active" value="true"<?php if($pluginConfig['active']) echo ' checked="checked"'; ?> />                
                </td><td class="right checkboxes">
                <label for="plgcfg_active"><?php echo $langFile['pluginSetup_pluginconfig_active']; ?></label><br />
                </td></tr>
                
                <!--<tr><td class="leftTop"></td><td></td></tr>
  
          
                <tr><td class="leftBottom"></td><td></td></tr>-->
                
              </table>
              
  <?php
        echo '<input type="submit" value="" class="button submit center" />';
      echo '</form>';     
        
        
              // edit plugin files
              editFiles($pluginFolder, $_GET['site'], "edit".$pluginName,  $pluginName.' '.$langFile['pluginSetup_editFiles_h1'], $pluginName."EditFilesAnchor", "php",'plugin.config.php');
  
      echo '</div>';   
      
    }
  }
}

?>