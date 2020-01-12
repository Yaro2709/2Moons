<?php

/**
 * @package 2moons 1.8
 * @author Hike
 * @copyright 2017 Hike
 * @version 1.0
 * @link http://2moons.de
 */

class ShowResBonusPage extends AbstractGamePage
{	
	public static $requireModule = 0;
	
	function __construct() 
	{
		parent::__construct();
	}
	
	function show()
	{
		global $LNG, $PLANET, $USER, $resource;
	
		$db= Database::get();
	
		//$db->query("UPDATE ".USERS." set `res_bonus_time` = 0;");

		$PlanetRess = new ResourceUpdate();
		$PlanetRess->CalcResource();
		$PlanetRess->SavePlanetToDB();

		//start the template
		$this->tplObj->loadscript('countdown.js');

		$cost = 10000;

		if(!empty($USER['urlaubs_modus'])){
			$this->printMessage("You can't access this page while you are in V mode!", true, array('game.php?page=overview', 2));
		}

		//fetch the best planet
		$za_planet1 = $db->selectSingle("SELECT * FROM %%PLANETS%% WHERE `id_owner` = :userID ORDER BY `metal_perhour` DESC LIMIT 1;", [':userID' => $USER['id']]);
		$za_planet2 = $db->selectSingle("SELECT * FROM %%PLANETS%% WHERE `id_owner` = :userID ORDER BY `crystal_perhour` DESC LIMIT 1;", [':userID' => $USER['id']]);
		$za_planet3 = $db->selectSingle("SELECT * FROM %%PLANETS%% WHERE `id_owner` = :userID ORDER BY `deuterium_perhour` DESC LIMIT 1;", [':userID' => $USER['id']]);

		$metal   	= 31*24*$za_planet1['metal_perhour'];
		$crystal   	= 31*24*$za_planet2['crystal_perhour'];
		$deuterium   	= 31*24*$za_planet3['deuterium_perhour'];

		$time = "1 HOUR";

		if($_POST){
			
			//verificam daca au trecut 24h
			if($USER['darkmatter'] >= $cost){
				if($USER['res_bonus_time']+ 12*60*60 < TIMESTAMP){
					//update
					$USER['darkmatter'] -= $cost;

					$db->update("UPDATE %%USERS%% SET `res_bonus_time` = :resBonusTime WHERE `id` = :userID ;", [':resBonusTime' => TIMESTAMP, ':userID' => $USER['id']]);

					$PLANET['metal'] += $metal;
					$PLANET['crystal'] += $crystal;
					$PLANET['deuterium'] += $deuterium ;
		                        
					$this->printMessage("Pack has been bought and the account has been updated succesfully!", true, array('game.php?page=resBonus', 2));
				}else{
					$this->printMessage("You can use this pack Twice at every 12 hours!", true, array('game.php?page=resBonus', 2));
				}
			}else{
					$this->printMessage("You do not have enough DarkMatter!", true, array('game.php?page=resBonus', 2));
			}
		}

		$this->assign(array( 
			'cost'		      => pretty_number($cost),
			'time'		      => $time,
			'price_metal'     => pretty_number($metal),
			'price_crystal'   => pretty_number($crystal),
			'price_deuterium' => pretty_number($deuterium), 
		     
			'status'	      => ((($USER['res_bonus_time']+ 12*60*60) < TIMESTAMP) ? true : (($USER['res_bonus_time']+12*60*60) - TIMESTAMP)),
		));


		$this->display('page.resbonus.default.tpl');

	}
}
?>