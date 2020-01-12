<?php

/**
 *  2Moons
 *  Copyright (C) 2012 Jan Kröpke
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package 2Moons
 * @author Jan Kröpke <info@2moons.cc>
 * @copyright 2012 Jan Kröpke <info@2moons.cc>
 * @license http://www.gnu.org/licenses/gpl.html GNU GPLv3 License
 * @version 1.7.0 (2013-01-17)
 * @info $Id$
 * @link http://2moons.cc/
 */

define('MODE', 'ADMIN');

define('ROOT_PATH', str_replace('\\', '/',dirname(__FILE__)).'/');

require(ROOT_PATH . 'includes/common.php');
require_once(ROOT_PATH . 'includes/classes/class.Log.php');

if ($USER['authlevel'] == AUTH_USR) HTTP::redirectTo('game.php');

if(!isset($_SESSION['admin_login']) || $_SESSION['admin_login'] != $USER['password'])
{
	include_once(ROOT_PATH . 'includes/pages/adm/ShowLoginPage.php');
	ShowLoginPage();
	exit;
}

$page = HTTP::_GP('page', '');
$uni = HTTP::_GP('uni', 0);

if($USER['authlevel'] == AUTH_ADM && !empty($uni))
	$_SESSION['adminuni'] = $uni;
if(empty($_SESSION['adminuni']))
	$_SESSION['adminuni'] = $UNI;

switch($page)
{
	case 'logout':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowLogoutPage.php');
		ShowLogoutPage();
	break;
	case 'infos':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowInformationPage.php');
		ShowInformationPage();
	break;
	case 'rights':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowRightsPage.php');
		ShowRightsPage();
	break;
	case 'config':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowConfigBasicPage.php');
		ShowConfigBasicPage();
	break;
	case 'configuni':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowConfigUniPage.php');
		ShowConfigUniPage();
	break;
	case 'chat':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowChatConfigPage.php');
		ShowChatConfigPage();
	break;
	case 'teamspeak':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowTeamspeakPage.php');
		ShowTeamspeakPage();
	break;
	case 'facebook':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowFacebookPage.php');
		ShowFacebookPage();
	break;
	case 'module':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowModulePage.php');
		ShowModulePage();
	break;
	case 'statsconf':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowStatsPage.php');
		ShowStatsPage();
	break;
	case 'disclamer':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowDisclamerPage.php');
		ShowDisclamerPage();
	break;
	case 'create':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowCreatorPage.php');
		ShowCreatorPage();
	break;
	case 'accounteditor':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowAccountEditorPage.php');
		ShowAccountEditorPage();
	break;
	case 'active':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowActivePage.php');
		ShowActivePage();
	break;
	case 'bans':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowBanPage.php');
		ShowBanPage();
	break;
	case 'messagelist':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowMessageListPage.php');
		ShowMessageListPage();
	break;
	case 'globalmessage':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowSendMessagesPage.php');
		ShowSendMessagesPage();
	break;
	case 'fleets':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowFlyingFleetPage.php');
		ShowFlyingFleetPage();
	break;
	case 'accountdata':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowAccountDataPage.php');
		ShowAccountDataPage();
	break;
	case 'support':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowSupportPage.php');
		new ShowSupportPage();
	break;
	case 'password':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowPassEncripterPage.php');
		ShowPassEncripterPage();
	break;
	case 'search':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowSearchPage.php');
		ShowSearchPage();
	break;
	case 'qeditor':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowQuickEditorPage.php');
		ShowQuickEditorPage();
	break;
	case 'statsupdate':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowStatUpdatePage.php');
		ShowStatUpdatePage();
	break;
	case 'reset':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowResetPage.php');
		ShowResetPage();
	break;
	case 'news':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowNewsPage.php');
		ShowNewsPage();
	break;
	case 'topnav':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowTopnavPage.php');
		ShowTopnavPage();
	break;
	case 'mods':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowModVersionPage.php');
		ShowModVersionPage();
	break;
	case 'overview':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowOverviewPage.php');
		ShowOverviewPage();
	break;
	case 'menu':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowMenuPage.php');
		ShowMenuPage();
	break;
	case 'clearcache':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowClearCachePage.php');
		ShowClearCachePage();
	break;
	case 'universe':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowUniversePage.php');
		ShowUniversePage();
	break;
	case 'multiips':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowMultiIPPage.php');
		ShowMultiIPPage();
	break;
	case 'log':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowLogPage.php');
		ShowLog();
	break;
	case 'vertify':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowVertify.php');
		ShowVertify();
	break;
	case 'cronjob':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowCronjobPage.php');
		ShowCronjob();
	break;
	case 'giveaway':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowGiveawayPage.php');
		ShowGiveaway();
	break;
	case 'autocomplete':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowAutoCompletePage.php');
		ShowAutoCompletePage();
	break;
	case 'dump':
		include_once(ROOT_PATH . 'includes/pages/adm/ShowDumpPage.php');
		ShowDumpPage();
	break;
	default:
		include_once(ROOT_PATH . 'includes/pages/adm/ShowIndexPage.php');
		ShowIndexPage();
	break;
}
