<?php
/*
 * feindura - Flat File Content Management System
 * Copyright (C) Fabian Vogelsteller [frozeman.de]
 *
 * Translated by Konstantin Zorg <ekumena@gmail.com>, http://q-topia.ru
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
 */
/**
 * RUSSIAN (RU) language-file for the feindura CMS (FRONTEND and BACKEND)
 *
 * need a RETURN $frontendLangFile; at the END
 */


// -> GENERAL <-

$sharedLangFile['HEADER_BUTTON_LOGOUT'] = 'Выйти::Нажмите что бы завершить сеанс.';
$sharedLangFile['sortablePageList_functions_startPage'] = 'Установить главной страницей';
$sharedLangFile['sortablePageList_functions_startPage_set'] = 'Эта страница отмечена как главная';

/* date texts */

$sharedLangFile['DATE_TEXT_YESTERDAY'] = 'Вчера';
$sharedLangFile['DATE_TEXT_TODAY'] = 'Сегодня';
$sharedLangFile['DATE_TEXT_TOMORROW'] = 'Завтра';


// -> SEARCH

$sharedLangFile['SEARCH_TITLE'] = 'Поиск страниц';
$sharedLangFile['SEARCH_TITLE_RESULTS'] = 'Результаты поиска для';
$sharedLangFile['SEARCH_TEXT_MATCH_CATEGORY'] = 'категория';
$sharedLangFile['SEARCH_TEXT_MATCH_SEARCHWORDS'] = 'слова для поиска';
$sharedLangFile['SEARCH_TEXT_MATCH_TAGS'] = 'Таги';
$sharedLangFile['SEARCH_TEXT_RESULTS'] = 'результатов';
$sharedLangFile['SEARCH_TEXT_TIME_1'] = 'за'; // 12 matches in 0.32 seconds
$sharedLangFile['SEARCH_TEXT_TIME_2'] = 'секунд';


// -> ERROR TEXTs

$sharedLangFile['errorWindow_h1'] = 'Произошла ошибка!';
$sharedLangFile['sortablePageList_setStartPage_error_save'] = '<b>Не удается установить главной страницей.</b>';
$sharedLangFile['editor_savepage_error_save'] = '<b>Не удается сохранить страницу.</b>';
$sharedLangFile['ADMINSETUP_ERROR_PHPVERSION'] = 'ОШИБКА<br /><br /><span class="logoname">fein<span>dura</span></span> требует более новую версию PHP'; // PHP 5.2.3

// -----------------------------------------------------------------------------------------------
// RETURN ****************************************************************************************
// -----------------------------------------------------------------------------------------------
return $sharedLangFile;

?>