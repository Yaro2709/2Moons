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
 * @version 1.4 (2011-07-10)
 * @info $Id$
 * @link http://code.google.com/p/2moons/
 */

function DeleteSelectedUser($UserID)
{
	global $db ,$CONF;
	
	$TheUser = $db->uniquequery("SELECT universe, ally_id FROM ".USERS." WHERE `id` = '".$UserID."';");
	$SQL 	 = "";
	
	if ($TheUser['ally_id'] != 0 )
	{
		$TheAlly =  $db->uniquequery("SELECT ally_members FROM ".ALLIANCE." WHERE `id` = '".$TheUser['ally_id']."';");
		$TheAlly['ally_members'] -= 1;

		if ($TheAlly['ally_members'] > 0)
		{
			$SQL .= "UPDATE ".ALLIANCE." SET `ally_members` = '".$TheAlly['ally_members']."' WHERE `id` = '".$TheUser['ally_id']."';";
		}
		else
		{
			$SQL .= "DELETE FROM ".ALLIANCE." WHERE `id` = '" . $TheUser['ally_id'] . "';";
			$SQL .= "DELETE FROM ".STATPOINTS." WHERE `stat_type` = '2' AND `id_owner` = '".$TheUser['ally_id']."';";
		}
	}

	$SQL .= "DELETE FROM ".BUDDY." WHERE `owner` = '".$UserID."' OR `sender` = '".$UserID."';";
	$SQL .= "DELETE FROM ".FLEETS." WHERE `fleet_owner` = '".$UserID."';";
	$SQL .= "DELETE FROM ".MESSAGES." WHERE `message_owner` = '".$UserID."';";
	$SQL .= "DELETE FROM ".NOTES." WHERE `owner` = '".$UserID."';";
	$SQL .= "DELETE FROM ".PLANETS." WHERE `id_owner` = '".$UserID."';";
	$SQL .= "DELETE FROM ".USERS." WHERE `id` = '".$UserID."';";
	$SQL .= "DELETE FROM ".STATPOINTS." WHERE `stat_type` = '1' AND `id_owner` = '".$UserID."';";
	$db->multi_query($SQL);
	
	$SQL	= $db->query("SELECT fleet_id FROM ".FLEETS." WHERE `fleet_target_owner` = '".$UserID."';");
	while($FleetID = $db->fetch_array($SQL)) {
		SendFleetBack($UserID, $FleetID);
	}
	update_config(array('users_amount' => $CONF['users_amount'] - 1), $TheUser['universe']);
}

function SendFleetBack($CurrentUser, $FleetID)
{
	global $db;	

	$FleetRow = $db->uniquequery("SELECT `start_time`, `fleet_mission`, `fleet_group`, `fleet_owner`, `fleet_mess` FROM ".FLEETS." WHERE `fleet_id` = '". $FleetID ."';");
	if ($FleetRow['fleet_owner'] != $CurrentUser || $FleetRow['fleet_mess'] == 1)
		return;
		
	$where		= 'fleet_id';

	if($FleetRow['fleet_mission'] == 1 && $FleetRow['fleet_group'] > 0)
	{
		$Aks = $db->uniquequery("SELECT teilnehmer FROM ".AKS." WHERE id = '". $FleetRow['fleet_group'] ."';");

		if($Aks['teilnehmer'] == $FleetRow['fleet_owner'])
		{
			$db->query("DELETE FROM ".AKS." WHERE id ='". $FleetRow['fleet_group'] ."';");
			$FleetID	= $FleetRow['fleet_group'];
			$where		= 'fleet_group';
		}
	}
	
	$db->query("UPDATE ".FLEETS." SET `fleet_group` = '0', `start_time` = '".TIMESTAMP."', `fleet_end_stay` = '".TIMESTAMP."', `fleet_end_time` = '".((TIMESTAMP - $FleetRow['start_time']) + TIMESTAMP)."', `fleet_mess` = '1' WHERE `".$where."` = '".$FleetID."';");
}

function DeleteSelectedPlanet ($ID)
{
	global $db;

	$QueryPlanet = $db->uniquequery("SELECT universe,galaxy,planet,system,planet_type FROM ".PLANETS." WHERE id = '".$ID."';");

	if ($QueryPlanet['planet_type'] == '3')
		$db->multi_query("DELETE FROM ".PLANETS." WHERE id = '".$ID."';UPDATE ".PLANETS." SET id_luna = '0' WHERE id_luna = '".$ID."';");
	else
		$db->query("DELETE FROM ".PLANETS." WHERE universe = '".$QueryPlanet['universe']."' AND galaxy = '".$QueryPlanet['galaxy']."' AND system = '".$QueryPlanet['system']."' AND planet = '".$QueryPlanet['planet']."';");
}

?>