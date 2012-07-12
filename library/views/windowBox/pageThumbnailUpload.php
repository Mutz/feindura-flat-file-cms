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
 * pageThumbnailUpload.php
 *
 * @version 1.04
 */

/**
 * Includes the login.include.php and backend.include.php and filter the basic data
 */
require_once(dirname(__FILE__)."/../../includes/secure.include.php");

echo ' '; // hack for safari, otherwise it throws an error that he could not find htmlentities like &ouml;

$error = false;
$response = false;
$site = $_GET['site'];
$page = $_GET['page'];
$category = $_GET['category'];

// ->> CHECK if the upload folder exists and is writeable
if(empty($adminConfig['uploadPath']) || !is_dir(DOCUMENTROOT.$adminConfig['uploadPath']))
  die('<h2>'.$langFile['PAGETHUMBNAIL_ERROR_NODIR_START'].' &quot;<b>'.$adminConfig['uploadPath'].$adminConfig['pageThumbnail']['path'].'</b>&quot; '.$langFile['PAGETHUMBNAIL_ERROR_NODIR_END'].'</h2>');
if($warning = isWritableWarning(DOCUMENTROOT.$adminConfig['uploadPath']))
  die('<h2>'.$warning.'</h2>');

$pageContent = GeneralFunctions::readPage($page,$category);

$categoryRatio = false;
$thumbRatioX = false;
$thumbRatioY = false;
$thumbRatio = false;

// GET THUMBNAIL SIZE
// --------------------------
// THUMB WIDTH
if(!empty($categoryConfig[$category]['thumbWidth'])) {
  $thumbWidth = $categoryConfig[$category]['thumbWidth'];
  $categoryRatio = true;
} else
  $thumbWidth = $adminConfig['pageThumbnail']['width'];
// THUMB HEIGHT
if(!empty($categoryConfig[$category]['thumbHeight'])) {
  $thumbHeight = $categoryConfig[$category]['thumbHeight'];
  $categoryRatio = true;
} else
  $thumbHeight = $adminConfig['pageThumbnail']['height'];

// THUMB RATIO X
if($categoryRatio) {
  if($categoryConfig[$category]['thumbRatio'] == 'y' ||
     $categoryConfig[$category]['thumbRatio'] == '') {
    //$thumbRatioX = ' disabled="disabled"';
    $thumbRatioX = true;
  }
} else {
  if($adminConfig['pageThumbnail']['ratio'] == 'y' ||
     $adminConfig['pageThumbnail']['ratio'] == '') {
    //$thumbRatioX = ' disabled="disabled"';
    $thumbRatioX = true;
  }
}

// THUMB RATIO Y
if($categoryRatio) {
  if($categoryConfig[$category]['thumbRatio'] == 'x' ||
     $categoryConfig[$category]['thumbRatio'] == '') {
    //$thumbRatioY = ' disabled="disabled"';
    $thumbRatioY = true;
  }
} else {
  if($adminConfig['pageThumbnail']['ratio'] == 'x' ||
     $adminConfig['pageThumbnail']['ratio'] == '') {
    //$thumbRatioY = ' disabled="disabled"';
    $thumbRatioY = true;
  }
}

// SET RATIO
if($categoryRatio)
  $thumbRatio = $categoryConfig[$category]['thumbRatio'];
else
  $thumbRatio = $adminConfig['pageThumbnail']['ratio'];

?>
<h2><?php echo $langFile['pagethumbnail_h1_part1'].' &quot;<span style="color:#000000;">'.strip_tags(GeneralFunctions::getLocalized($pageContent,'title')).'</span>&quot; '.$langFile['pagethumbnail_h1_part2']; ?></h2>

<div id="thumbInfo">
<ul>
  <li><?php echo $langFile['pagethumbnail_thumbinfo_formats']; ?><br><b>JPG</b>, <b>JPEG</b>, <b>GIF</b>, <b>PNG</b></li>
  <li><?php echo $langFile['pagethumbnail_thumbinfo_filesize'].' <b>'.ini_get('upload_max_filesize').'B</b>'; ?></li>
  <li><b><?php echo $langFile['pagethumbnail_thumbinfo_standardthumbsize']; ?></b><br>
  <?php

    if($thumbRatioY) echo $langFile['pagethumbnail_thumbsize_width'].' = <b>'.$thumbWidth.'</b> '.$langFile['THUMBNAIL_TEXT_UNIT'].'<br>';
    if($thumbRatioX) echo $langFile['pagethumbnail_thumbsize_height'].' = <b>'.$thumbHeight.'</b> '.$langFile['THUMBNAIL_TEXT_UNIT'];
  ?>
  </li>
</ul>
</div>

<div style="position: relative">
<form action="library/controllers/thumbnailUpload.controller.php" id="pageThumbnailUploadForm" enctype="multipart/form-data" method="post" onsubmit="startUploadAnimation();" target="uploadTargetFrame" accept-charset="UTF-8">
	<input type="hidden" name="upload" value="true">
	<input type="hidden" name="category" value="<?php echo $category; ?>">
  <input type="hidden" name="id" value="<?php echo $page; ?>">
  <input type="hidden" name="thumbRatio" value="<?php echo $thumbRatio; ?>">

	<!-- file selection -->
  <h3><?php echo $langFile['pagethumbnail_field1']; ?></h3>

	<input type="file" name="thumbFile">
  <br>
	<br>

	<a href="#" id="thumbSizeToogle" class="down"><?php echo $langFile['pagethumbnail_thumbsize_h1']; ?></a><br>
	<br clear="all"/>

  <table id="thumbnailSizeBox">
  <tbody>
    <?php
    // -> THUMB-WIDTH
    if($thumbRatioY) {
    ?>
    <tr><td style="width: 80px">
    <label for="windowBox_thumbWidth">
    <?php echo $langFile['pagethumbnail_thumbsize_width'] ?></label>
    </td><td>
    <input type="text" id="windowBox_thumbWidth" name="thumbWidth" class="short" value="<?php echo $thumbWidth; ?>"<?php echo $thumbRatioX; ?>>
    <?php echo $langFile['pagethumbnail_thumbsize_unit']; ?>
    </td></tr>

    <!-- shows the width in a scale -->
    <?php
    if($thumbWidth)
      $styleThumbWidth = 'width:'.$thumbWidth.'px';
    else
      $styleThumbWidth = 'width:0px';
    ?>
    <tr><td>
    </td><td style="height:40px;">
    <div id="windowBox_thumbWidthScale" class="thumbnailScale" style="<?php echo $styleThumbWidth; ?>"><div></div></div>
    </td></tr>
    <?php
    }
    // -> THUMB-HEIGHT
    if($thumbRatioX) {
    ?>
    <tr><td style="width: 80px">
    <label for="windowBox_thumbHeight">
    <?php echo $langFile['pagethumbnail_thumbsize_height'] ?></label>
    </td><td>
    <input type="text" id="windowBox_thumbHeight" name="thumbHeight" class="short" value="<?php echo $thumbHeight; ?>"<?php echo $thumbRatioY; ?>>
    <?php echo $langFile['pagethumbnail_thumbsize_unit']; ?>
    </td></tr>

    <!-- shows the height in a scale -->
    <?php
    if($thumbHeight)
      $styleThumbHeight = 'width:'.$thumbHeight.'px';
    else
      $styleThumbHeight = 'width:0px';
    ?>
    <tr><td>
    </td><td style="height:40px;">
    <div id="windowBox_thumbHeightScale" class="thumbnailScale" style="<?php echo $styleThumbHeight; ?>"><div></div></div>
    </td></tr>
    <?php
    }
    ?>
  </tbody>
  </table>

  <!-- show a PREVIEW of the current THUMBNAIL -->
  <?php
  // show thumbnail if the page has one
  if(!empty($pageContent['thumbnail'])) {

    $thumbnailWidth = @getimagesize(DOCUMENTROOT.$adminConfig['uploadPath'].$adminConfig['pageThumbnail']['path'].$pageContent['thumbnail']);

    if($thumbnailWidth[0] <= 250)
      $thumbnailWidth = ' width="'.$thumbnailWidth[0].'"';
    else
      $thumbnailWidth = ' width="250"';

    // generates a random number to put on the end of the image, to prevent caching
    $randomImage = '?'.md5(uniqid(rand(),1));

    echo '<div style="z-index:0; position:relative; width: 280px; margin-bottom: 10px; margin-top: 20px; float:right; text-align: center;">';
    echo '<img src="'.GeneralFunctions::Path2URI($adminConfig['uploadPath']).$adminConfig['pageThumbnail']['path'].$pageContent['thumbnail'].$randomImage.'" class="thumbnailPreview toolTip"'.$thumbnailWidth.' alt="thumbnail" title="'.$adminConfig['uploadPath'].$adminConfig['pageThumbnail']['path'].$pageContent['thumbnail'].'::">';
    echo '</div>';
  }
  ?>
	<input type="submit" value="" class="button thumbnailUpload toolTip" title="<?php echo $langFile['pagethumbnail_submit_tip']; ?>">
</form>
</div>

<?php

// create redirect
$redirect = (empty($site))
  ? '?category='.$category.'&page='.$page.'&status=reload'.rand(1,99).'#pageInformation'
  : '?site='.$site.'&category='.$category;

if($site == 'pages')
  $redirect .= '&status=reload'.rand(1,99).'#categoryAnchor'.$category;

// when in the editor, clear the redirect
else
  $redirect = '';


?>
<!-- Update the image -->
<script type="text/javascript">
/* <![CDATA[ */
  function refreshThumbnailImage(newImage,imageWidth) {
    if($('thumbnailPreviewImage') != null) {
      $$('img.thumbnailPreview').setProperty('src','<?php echo GeneralFunctions::Path2URI($adminConfig['uploadPath']).$adminConfig['pageThumbnail']['path']; ?>'+newImage);
      $('thumbnailPreviewImage').setProperty('data-width',imageWidth);
      if(imageWidth >= 200)
        $('thumbnailPreviewImage').setStyle('width',200);

    }

    if($('thumbnailUploadButtonInPreviewArea') != null) {
      $('thumbnailUploadButtonInPreviewArea').setStyle('display','none');
      $('thumbnailPreviewContainer').setStyle('display','block');
    }
  }
/* ]]> */
</script>

<!-- ok button, after upload -->
<a href="index.php<?php echo $redirect; ?>" onclick="closeWindowBox(<?php echo $redirect; ?>);return false;" id="pageThumbnailOkButton" class="ok center">&nbsp;</a>

<!-- UPLOAD IFRAME -->
<iframe id="uploadTargetFrame" name="uploadTargetFrame" src="library/controllers/thumbnailUpload.controller.php"></iframe>