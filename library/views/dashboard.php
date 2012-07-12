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
 * dashboard.php
 *
 * @version 0.9
 */

/**
 * Includes the login.include.php and backend.include.php and filter the basic data
 */
require_once(dirname(__FILE__)."/../includes/secure.include.php");

?>

<!-- gives a warning if javascript is not activated -->
<noscript>
<div class="block alert warning">
  <h1><?php echo $langFile['WARNING_TITLE_JAVASCRIPT']; ?></h1>
  <div class="content">
    <p><?php echo $langFile['WARNING_TEXT_JAVASCRIPT']; ?></p>
  </div>
</div>
</noscript>

<div class="block open">
  <h1><?php echo $langFile['DASHBOARD_TITLE_WELCOME']; ?></h1>
  <div class="content">
    <p><?php echo $langFile['DASHBOARD_TEXT_WELCOME']; ?></p>
  </div>
</div>

<?php

// SHOW the BROWSER HINT
if(preg_match("/MSIE [0-8]/", $_SERVER['HTTP_USER_AGENT']) &&
   !preg_match("/chromeframe/", $_SERVER['HTTP_USER_AGENT'])) {
?>
<div class="block alert warning">
  <h1><a href="#"><?php echo $langFile['DASHBOARD_TITLE_IEWARNING']; ?></a></h1>
  <div class="content">
    <p><?php echo $langFile['DASHBOARD_TEXT_IEWARNING']; ?></p>
  </div>
</div>
<?php }

// SHOW the USER HINTs
if(!empty($adminConfig['user']['info'])) {
?>
<div class="block alert info">
  <h1><a href="#"><?php echo $langFile['DASHBOARD_TITLE_USERINFO']; ?></a></h1>
  <div class="content">
    <p><?php echo $adminConfig['user']['info']; ?></p>
  </div>
</div>
<?php } ?>


<!-- WEBSITE STATISTIC -->

<div class="block">
  <h1><img src="library/images/icons/statisticIcon_small.png" alt="icon" width="30" height="27"><?php echo $langFile['DASHBOARD_TITLE_STATISTICS']; ?></h1>
  <div class="content">
    <?php

    // vars
    $maxListEntries = 50;

    // ->> LOAD all PAGES
    $orgPages = GeneralFunctions::loadPages(true);
    $pages = $orgPages;
    $orgPagesStats = GeneralFunctions::loadPagesStatistics(true);
    $pagesStats = $orgPagesStats;

    // --------------------------------
    // USER COUNTER
    echo '<div class="row">';
      echo '<div class="span4">';
        echo '<div class="innerBlock">';
        echo '<h2>'.$langFile['STATISTICS_TEXT_VISITORCOUNT'].'</h2>';
        echo '<div class="center">';
          echo '<span class="visitCountNumber brown">'.formatHighNumber($websiteStatistic['userVisitCount']).'</span><br>';
            echo '<span class="toolTip blue" title="'.$langFile['STATISTICS_TOOLTIP_ROBOTCOUNT'].'">'.$langFile['STATISTICS_TEXT_ROBOTCOUNT'].' '.formatHighNumber($websiteStatistic['robotVisitCount']).'</span><br>';
            // CURRENT VISITORS
            $currentVisitors = StatisticFunctions::getCurrentVisitors();
            $countVisitor = 0;
            $countRobots = 0;
            foreach($currentVisitors as $currentVisitor) {
              if($currentVisitor['type'] == 'visitor')
                $countVisitor++;
              else
                $countRobots++;
            }
            echo '<span class="blue"><strong>'.$langFile['STATISTICS_TEXT_CURRENTVISITORS'].'</strong> '.$countVisitor.' ('.$langFile['STATISTICS_TEXT_ROBOTCOUNT'].' '.$countRobots.')</span>';
          echo '<hr class="small">';
        echo '</div>';

        if(!empty($websiteStatistic['firstVisit'])) {
          echo '<div style="width:100%; text-align:right;">';
            // FIRST VISIT
            echo '<span class="toolTip" title="'.formatTime($websiteStatistic['firstVisit']).'::">'.$langFile['STATISTICS_TEXT_FIRSTVISIT'].' <span class="brown">'.GeneralFunctions::formatDate($websiteStatistic['firstVisit']).'</span></span><br>';
            // LADST VISIT
            echo '<span class="toolTip" title="'.formatTime($websiteStatistic['lastVisit']).'::">'.$langFile['STATISTICS_TEXT_LASTVISIT'].' <span class="blue"><strong>'.GeneralFunctions::formatDate($websiteStatistic['lastVisit']).'</strong></span></span>';
          echo '</div>';
        }
        echo '</div>';
      echo '</div>';

    // ---------------------------------
    // -> CURRENT VISITORS
    $currentVisitorDashboard = true;
    $currentVisitors = include('library/includes/currentVisitors.include.php');
    if($currentVisitors) {
      echo '<div class="span4">';
        echo '<div class="innerBlock">';
        echo '<h2>'.$langFile['STATISTICS_TEXT_CURRENTVISITORS'].'</h2>';
          echo '<div class="innerBlocklistPages">';
          echo $currentVisitors;
          echo '</div>';
        echo '</div>';
      echo '</div>';
    }
    echo '</div>';

    echo '<div class="spacer2x"></div>';

    // -> inBlockSlider
    echo '<h2 class="center"><a href="#" tabindex="30" class="inBlockSliderLink down">'.$langFile['STATISTICS_TITLE_PAGESTATISTICS'].'</a></h2>';
    echo '<div class="verticalSeparator"></div>';

    echo '<div class="inBlockSlider hidden">';

    echo '<div class="row">';
      echo '<div class="span4">';

        // ---------------------------------
        // -> MOST VISITED PAGE
        echo '<div class="innerBlock">';
        echo '<h2>'.$langFile['DASHBOARD_TITLE_STATISTICS_MOSTVISITED'].'</h2>';
          echo '<div class="innerBlocklistPages">
                <table class="coloredList"><tbody>';
          // SORT the Pages by VISIT COUNT
          usort($pagesStats, 'sortByVisitCount');

          $count = 1;
          foreach($pagesStats as $pageStats) {
            if(!empty($pageStats['visitorCount'])) {
              // get page category and title
              foreach($pages as $page) {
                if($pageStats['id'] == $page['id']) {
                  $pageStats['title'] = GeneralFunctions::getLocalized($page,'title');
                  $pageStats['category'] = $page['category'];
                }
              }
              echo '<tr><td style="font-size:11px;text-align:center;"><strong>'.$pageStats['visitorCount'].'</strong></td><td><a href="?category='.$pageStats['category'].'&amp;page='.$pageStats['id'].'" class="blue">'.strip_tags($pageStats['title']).'</a></td></tr>';
              // count
              if($count == $maxListEntries) break;
              else $count++;
            }
          }
          echo '</tbody></table>
                </div>';
        echo '</div>';

      echo '</div>';

      $pagesStats = $orgPagesStats;

      echo '<div class="span4">';

        // ---------------------------------
        // -> LAST VISITED PAGES
        echo '<div class="innerBlock">';
        echo '<h2>'.$langFile['DASHBOARD_TITLE_STATISTICS_LASTVISITED'].'</h2>';
          echo '<div class="innerBlocklistPages">
                <table class="coloredList"><tbody>';
          // SORT the Pages by VISIT SAVEDATE
          usort($pagesStats, 'sortByLastVisitDate');

          $count = 1;
          foreach($pagesStats as $pageStats) {
            if($pageStats['lastVisit'] != 0) {
              // get page category and title
              foreach($pages as $page) {
                if($pageStats['id'] == $page['id']) {
                  $pageStats['title'] = GeneralFunctions::getLocalized($page,'title');
                  $pageStats['category'] = $page['category'];
                }
              }
              echo '<tr><td style="font-size:11px;text-align:left;"><strong>'.GeneralFunctions::formatDate(GeneralFunctions::dateDayBeforeAfter($pageStats['lastVisit'])).'</strong> '.formatTime($pageStats['lastVisit']).'</td><td><a href="?category='.$pageStats['category'].'&amp;page='.$pageStats['id'].'" class="blue">'.strip_tags($pageStats['title']).'</a></td></tr>';
              // count
              if($count == $maxListEntries) break;
              else $count++;
            }
          }
          echo '</tbody></table>
                </div>';
        echo '</div>';
      echo '</div>';
    echo '</div>';

    $pagesStats = $orgPagesStats;

    //  spacer
    echo '<div class="spacer"></div>';

    echo '<div class="row">';
      echo '<div class="span4">';

        // ---------------------------------
        // -> LONGEST VIEWED PAGE
        echo '<div class="innerBlock">';
        echo '<h2>'.$langFile['DASHBOARD_TITLE_STATISTICS_LONGESTVIEWED'].'</h2>';
          echo '<div class="innerBlocklistPages">
                <table class="coloredList"><tbody>';
          // SORT the Pages by MAX VISIT TIME
          usort($pagesStats, 'sortByVisitTimeMax');

          $count = 1;
          foreach($pagesStats as $pageStats) {
            // get page category and title
            foreach($pages as $page) {
              if($pageStats['id'] == $page['id']) {
                $pageStats['title'] = GeneralFunctions::getLocalized($page,'title');
                $pageStats['category'] = $page['category'];
              }
            }

            // get highest time
            $highestTime = unserialize($pageStats['visitTimeMax']);

            if($pageVisitTime = showVisitTime($highestTime[0]))
              echo '<tr><td style="font-size:11px;text-align:center;">'.$pageVisitTime.'</td><td><a href="?category='.$pageStats['category'].'&amp;page='.$pageStats['id'].'" class="blue">'.strip_tags($pageStats['title']).'</a></td></tr>';
            // count
            if($count == $maxListEntries) break;
            else $count++;
          }
          echo '</tbody></table>
                </div>';
        echo '</div>';

      echo '</div>';

      $pagesStats = $orgPagesStats;

      echo '<div class="span4">';

        // ---------------------------------
        // -> LAST EDITED PAGES
        echo '<div class="innerBlock">';
        echo '<h2>'.$langFile['DASHBOARD_TITLE_STATISTICS_LASTEDITED'].'</h2>';
          echo '<div class="innerBlocklistPages">
                <table class="coloredList"><tbody>';
          // SORT the Pages by VISIT SAVEDATE
          usort($pages, 'sortByLastSaveDate');

          $count = 1;
          foreach($pages as $page) {
            if($page['lastSaveDate'] != 0) {
              echo '<tr><td style="font-size:11px;text-align:left;"><strong>'.GeneralFunctions::formatDate(GeneralFunctions::dateDayBeforeAfter($page['lastSaveDate'])).'</strong> '.formatTime($page['lastSaveDate']).'</td><td><a href="?category='.$page['category'].'&amp;page='.$page['id'].'" class="blue">'.strip_tags(GeneralFunctions::getLocalized($page,'title')).'</a></td></tr>';
              // count
              if($count == $maxListEntries) break;
              else $count++;
            }
          }
          echo '</tbody></table>
                </div>';
        echo '</div>';

        echo '</div>';
    echo '</div>';

    $pages = $orgPages;

    echo '<div class="verticalSeparator"></div>';
    echo '</div>'; // <- inBlockSlider End

    //  spacer
    echo '<div class="spacer4x"></div>';

    // ---------------------------------
    // ->> SEARCHWORD CLOUD

    // -> create SEARCHWORD DATASTRING of ALL PAGES
    $allSearchwords = false;
    foreach($pagesStats as $pageStats) {
      // if page has searchwords
      if(!empty($pageStats['searchWords'])) {
        $allSearchwords = StatisticFunctions::addDataToDataString($allSearchwords,$pageStats['searchWords']);
      }
    }

    // SHOW tag CLOUD
    if($tagCloud = createTagCloud($allSearchwords)) {
      echo '<h2>'.$langFile['STATISTICS_TEXT_SEARCHWORD_DESCRIPTION'].'</h2>';
      echo '<div class="verticalSeparator"></div>';
      echo '<div class="tagCloud">'.$tagCloud.'</div>';
    }

    // ---------------------------------
    // -> BROWSER CHART

    if($browserChart = createBrowserChart($websiteStatistic['browser'])) {
      // echo '<div class="verticalSeparator"></div>';
      echo '<div class="spacer4x"></div>';
      echo '<h2>'.$langFile['STATISTICS_TITLE_BROWSERCHART'].'</h2>';
      echo '<div class="verticalSeparator"></div>';
      echo $browserChart;
    }

    // ---------------------------------
    // -> SHOW REFERER LOG
    if(file_exists(dirname(__FILE__).'/../../statistic/referer.statistic.log') &&
       $logContent = file(dirname(__FILE__).'/../../statistic/referer.statistic.log')) {

      // echo '<div class="verticalSeparator"></div>';
      echo '<div class="spacer4x"></div>';

      echo '<h2>'.$langFile['DASHBOARD_TITLE_REFERER'].'</h2>';

      echo '<div class="row">';
        echo '<div class="span8 refererBox">';
          echo '<ul class="coloredList">';
            foreach($logContent as $logRow) {
              $logRow = explode('|#|',$logRow);
              $logDate = GeneralFunctions::formatDate($logRow[0]);
              $logTime = formatTime($logRow[0]);
              $logUrl = str_replace('&','&amp;',$logRow[1]);

              echo '<li><strong>'.$logDate.'</strong>  '.$logTime.'<a href="'.$logUrl.'" class="blue">'.str_replace('http://','',$logUrl).'</a></li>';
            }
          echo '</ul>';
        echo '</div>';
      echo '</div>';
    }
    ?>
  </div>
</div>
