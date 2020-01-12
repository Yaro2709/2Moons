<?php

/**
 *  2Moons
 *  Copyright (C) 2011  Slaver
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
 * @author Slaver <slaver7@gmail.com>
 * @copyright 2009 Lucky <lucky@xgproyect.net> (XGProyecto)
 * @copyright 2011 Slaver <slaver7@gmail.com> (Fork/2Moons)
 * @license http://www.gnu.org/licenses/gpl.html GNU GPLv3 License
 * @version 1.6 (2011-11-17)
 * @info $Id$
 * @link http://code.google.com/p/2moons/
 */

define('INSIDE', true );
define('AJAX', true );

define('ROOT_PATH', str_replace('\\', '/',dirname(__FILE__)).'/');
	
require(ROOT_PATH . 'includes/common.php');
$SESSION       	= new Session();

if(!$SESSION->IsUserLogin() || ($CONF['game_disable'] == 0 && $_SESSION['authlevel'] == AUTH_USR))
	exit(json_encode(array()));
	
$LANG->setUser($_SESSION['USER']['lang']);
$LANG->includeLang(array('INGAME'));
$action	= request_var('action', '');
switch($action)
{
	case 'fleet1':
		$TargetGalaxy 					= request_var('galaxy', 0);
		$TargetSystem 					= request_var('system', 0);
		$TargetPlanet					= request_var('planet', 0);
		$TargetPlanettype 				= request_var('planet_type', 1);
	
		if($TargetGalaxy == $_SESSION['PLANET']['galaxy'] && $TargetSystem == $_SESSION['PLANET']['system'] && $TargetPlanet == $_SESSION['PLANET']['planet'] && $TargetPlanettype == $_SESSION['PLANET']['planet_type'])
			exit($LNG['fl_error_same_planet']);
		
		if ($TargetPlanet != $CONF['max_planets'] + 1) {
			$Data	= $db->uniquequery("SELECT u.`urlaubs_modus`, u.`authattack`, p.`destruyed`, p.`der_metal`, p.`der_crystal`, p.`destruyed` FROM ".USERS." as u, ".PLANETS." as p WHERE p.universe = '".$UNI."' AND p.`galaxy` = '".$TargetGalaxy."' AND p.`system` = '".$TargetSystem."' AND p.`planet` = '".$TargetPlanet."'  AND p.`planet_type` = '".(($TargetPlanettype == 2) ? 1 : $TargetPlanettype)."' AND `u`.`id` = p.`id_owner`;");
			if ($TargetPlanettype == 3 && !isset($Data))
				exit($LNG['fl_error_no_moon']);
			elseif ($_GET['kolo'] == 0 && !isset($Data))
				exit($LNG['fl_error_not_avalible']);
			elseif ($TargetPlanettype != 2 && $Data['urlaubs_modus'])
				exit($LNG['fl_in_vacation_player']);
			elseif ($CONF['adm_attack'] == 1 && $Data['authattack'] > $_SESSION['authlevel'])
				exit($LNG['fl_admins_cannot_be_attacked']);
			elseif ($Data['destruyed'] != 0)
				exit($LNG['fl_error_not_avalible']);
			elseif($TargetPlanettype == 2 && $Data['der_metal'] == 0 && $Data['der_crystal'] == 0)
				exit($LNG['fl_error_empty_derbis']);
		} else {
			if ($_SESSION['USER'][$resource[124]] == 0)
				exit($LNG['fl_expedition_tech_required']);
			
			$ActualFleets = $db->countquery("SELECT COUNT(*) FROM ".FLEETS." WHERE `fleet_owner` = '".$_SESSION['id']."' AND `fleet_mission` = '15';");

			if ($ActualFleets['state'] >= floor(sqrt($_SESSION['USER'][$resource[124]])))
				exit($LNG['fl_expedition_fleets_limit']);
		}
		exit('OK');
	break;
	case 'renameplanet':
		$newname        = request_var('newname', '', UTF8_SUPPORT);
		if (!empty($newname))
		{
			if (!CheckName($newname))
				exit((UTF8_SUPPORT) ? $LNG['ov_newname_no_space'] : $LNG['ov_newname_alphanum']);
			else
				$db->query("UPDATE ".PLANETS." SET `name` = '".$db->sql_escape($newname)."' WHERE `id` = '".$_SESSION['planet']. "';");
		}
	break;
	case 'saveshotcut':
		$Shortcut		= array();
		$ShortcutData	= $_REQUEST['shoutcut'];
		foreach($ShortcutData as $Key => $Data) {
			$Name	= $db->sql_escape(request_var('', $_REQUEST['shoutcut'][$Key]['name']));
			
			if(empty($Name) || !in_array($Data['type'], array(1, 2, 3)))
				continue;
				
			$Shortcut[] = array(
				$Name,
				(int) $Data['galaxy'],
				(int) $Data['system'],
				(int) $Data['planet'],
				(int) $Data['type'],
			);
		}
		$db->query("UPDATE ".USERS." SET `fleet_shortcut` = '".serialize($Shortcut)."' WHERE `id` = ".$_SESSION['id'].";");
		exit($LNG['fl_shortcut_saved']);
	break;
	case 'deleteplanet':
		$password =	request_var('password', '', true);
		if (!empty($password))
		{
			$IfFleets	= $db->countquery("SELECT COUNT(*) FROM ".FLEETS." WHERE (`fleet_owner` = '".$_SESSION['id']."' AND (`fleet_start_id` = ".$_SESSION['PLANET']['id']." OR `fleet_start_id` = ".$_SESSION['PLANET']['id_luna'].")) OR (`fleet_target_owner` = '".$_SESSION['id']."' AND (`fleet_end_id` = '".$_SESSION['PLANET']['id']."' OR `fleet_end_id` = ".$_SESSION['PLANET']['id_luna']."));");
			
			if ($IfFleets > 0)
				exit(json_encode(array('mess' => $LNG['ov_abandon_planet_not_possible'])));
			elseif ($_SESSION['USER']['id_planet'] == $_SESSION['planet'])
				exit(json_encode(array('mess' => $LNG['ov_principal_planet_cant_abanone'])));
			elseif (md5($password) != $_SESSION['USER']['password'])
				exit(json_encode(array('mess' => $LNG['ov_wrong_pass'])));
			else
			{
				if($_SESSION['PLANET']['planet_type'] == 1) {
					$db->multi_query("UPDATE ".PLANETS." SET `destruyed` = '".(TIMESTAMP+ 86400)."' WHERE `id` = '".$_SESSION['planet']."';DELETE FROM ".PLANETS." WHERE `id` = '".$_SESSION['PLANET']['id_luna']."';");
				} else {
					$db->multi_query("UPDATE ".PLANETS." SET `id_luna` = '0' WHERE `id_luna` = '".$_SESSION['planet']."';DELETE FROM ".PLANETS." WHERE `id` = '".$_SESSION['planet']."';");
				}
				$_SESSION['planet']	= $_SESSION['USER']['id_planet'];
				exit(json_encode(array('ok' => true, 'mess' => $LNG['ov_planet_abandoned'])));
			}
		}
	break;
	case 'getmessages':
		$MessCategory  	= request_var('messcat', 0);
		$MessageList	= array();
		if($MessCategory == 999)
		{
			$UsrMess = $db->query("SELECT * FROM ".MESSAGES." WHERE `message_sender` = '".$_SESSION['id']."' ORDER BY `message_time` DESC;");
				
			while ($CurMess = $db->fetch_array($UsrMess))
			{
				$CurrUsername	= $db->uniquequery("SELECT `username`, `galaxy`, `system`, `planet` FROM ".USERS." WHERE id = '".$CurMess['message_owner']."';");
				
				$MessageList[$CurMess['message_id']]	= array(
					'time'		=> tz_date($CurMess['message_time']),
					'from'		=> $CurrUsername['username']." [".$CurrUsername['galaxy'].":".$CurrUsername['system'].":".$CurrUsername['planet']."]",
					'subject'	=> $CurMess['message_subject'],
					'type'		=> $CurMess['message_type'],
					'text'		=> $CurMess['message_text'],
				);
			}		
			$db->free_result($UsrMess);	
			
			echo json_encode($MessageList);
			
			exit;
		}
			
		$UsrMess = $db->query("SELECT * FROM ".MESSAGES." WHERE `message_owner` = '".$_SESSION['id']."' OR (`message_owner` = '0' AND `message_type` = '50') ORDER BY `message_time` DESC;");
			
		while ($CurMess = $db->fetch_array($UsrMess))
		{
			$MessageList[$CurMess['message_time']]	= array(
				'id'		=> $CurMess['message_id'],
				'time'		=> tz_date($CurMess['message_time']),
				'from'		=> $CurMess['message_from'],
				'subject'	=> stripslashes($CurMess['message_subject']),
				'sender'	=> $CurMess['message_sender'],
				'type'		=> $CurMess['message_type'],
				'text'		=> stripslashes($CurMess['message_text']),
			);
		}
		
		$db->free_result($UsrMess);	
		echo json_encode(array(
			'MessageList'						=> $MessageList,
			'LNG'								=> array(
				'mg_message_title'					=> $LNG['mg_message_title'],
				'mg_action'							=> $LNG['mg_action'],
				'mg_date'							=> $LNG['mg_date'],
				'mg_from'							=> $LNG['mg_from'],
				'mg_to'								=> $LNG['mg_to'],
				'mg_subject'						=> $LNG['mg_subject'],
				'mg_show_only_header_spy_reports'	=> $LNG['mg_show_only_header_spy_reports'],
				'mg_delete_marked'					=> $LNG['mg_delete_marked'],
				'mg_delete_type_all'				=> $LNG['mg_delete_type_all'],
				'mg_delete_unmarked'				=> $LNG['mg_delete_unmarked'],
				'mg_delete_all'						=> $LNG['mg_delete_all'],
				'mg_confirm_delete'					=> $LNG['mg_confirm_delete'],
				'mg_game_message'					=> $LNG['mg_game_message'],
				'mg_answer_to'						=> $LNG['mg_answer_to'],
			),
		));
		exit;
	break;
	case 'deletemessages':
		$DeleteWhat = request_var('deletemessages','');
		$MessType	= request_var('mess_type', 0);
		
		if($DeleteWhat == 'deleteunmarked' && (empty($_REQUEST['delmes']) || !is_array($_REQUEST['delmes'])))
			$DeleteWhat	= 'deletetypeall';
		
		if($DeleteWhat == 'deletetypeall' && $MessType == 100)
			$DeleteWhat	= 'deleteall';
		
		
		switch($DeleteWhat)
		{
			case 'deleteall':
				$db->multi_query("DELETE FROM ".MESSAGES." WHERE `message_owner` = '".$_SESSION['id']."';UPDATE ".USERS." SET `new_message` = '0', `new_gmessage` = '0' WHERE `id` = '".$_SESSION['id']."';");
			break;
			case 'deletetypeall':
				$db->multi_query("DELETE FROM ".MESSAGES." WHERE `message_owner` = '".$_SESSION['id']."' AND `message_type` = '".$MessType."';UPDATE ".USERS." SET `new_message` = '0', `new_gmessage` = '0' WHERE `id` = '".$_SESSION['id']."';");
			case 'deletemarked':
				$SQLWhere = array();
				if(empty($_REQUEST['delmes']) || !is_array($_REQUEST['delmes']))
					exit;
					
				foreach($_REQUEST['delmes'] as $MessID => $b)
				{
					$SQLWhere[] = "`message_id` = '".(int) $MessID."'";
				}
				
				$db->query("DELETE FROM ".MESSAGES." WHERE (".implode(" OR ",$SQLWhere).") AND `message_owner` = '".$_SESSION['id']."'".(($MessType != 100)? " AND `message_type` = '".$MessType."' ":"").";");
			break;
			case 'deleteunmarked':
				if(!empty($_REQUEST['delmes']) && is_array($_REQUEST['delmes']))
				{
					$SQLWhere = array();
					foreach($_REQUEST['delmes'] as $MessID => $b)
					{
						$SQLWhere[] = "`message_id` != '".(int) $MessID."'";
					}
					
					$db->query("DELETE FROM ".MESSAGES." WHERE (".implode(" AND ",$SQLWhere).") AND `message_owner` = '".$_SESSION['id']."'".(($MessType != 100)? " AND `message_type` = '".$MessType."' ":"").";");
				}
			break;
		}
		header('HTTP/1.1 204 No Content');
	break;
	default:
		header('HTTP/1.1 204 No Content');
	break;
}
?>