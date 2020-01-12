<?php

/**
 * @package 2moons 1.8
 * @author Hike
 * @copyright 2017 Hike
 * @version 1.0
 * @link http://2moons.de
 */

class ShowFleetBonusPage extends AbstractGamePage
{	
	public static $requireModule = 0;
	
	function __construct() 
	{
		parent::__construct();
	}
	
	function show()
	{
		global $LNG, $PLANET, $USER, $resource;
	
		$this->tplObj->loadscript('countdown.js');
		
		$cost = 30000;
		
		if(!empty($USER['urlaubs_modus'])){
			$this->printMessage("You can't access this page while you are in V mode!", true, array('game.php?page=FleetBonus', 2));
		}

		$config = Config::get();
		
		$time = "1 Day";
		$ship1 =  200000 * $config->resource_multiplier;
	    $ship2 =  200000 * $config->resource_multiplier;
	    $ship3 =  100000 * $config->resource_multiplier;
	        
		if($_POST){
			if($USER['darkmatter'] >= $cost){
				if($USER['fleet_bonus_time']+6*60*60 < TIMESTAMP) {

					$db = Database::get();

					$USER['darkmatter'] -= $cost;

					$db->update("UPDATE %%USERS%% SET `fleet_bonus_time` = :bonusTime WHERE `id` = :userID ;", [':bonusTime' => TIMESTAMP, ':userID' => $USER['id']]);

					$db->update("UPDATE %%PLANETS%% SET `lune_noir` = `lune_noir` + :ship1, `star_crasher` = `star_crasher` + :ship2, `dearth_star` = `dearth_star` + :ship3  where `id` = :planetID ;", [':ship1' => $ship1, ':ship2' => $ship2, ':ship3' => $ship3, ":planetID" => $PLANET['id']]);

					$this->printMessage("Pack has been bought and the account has been updated succesfully!", true, array('game.php?page=FleetBonus', 2));
				} else {
					$this->printMessage("You can use this pack once at every 6 hours!", true, array('game.php?page=FleetBonus', 2));
				}
			}
			else{
				$this->printMessage("You do not have enough Dark matter!", true, array('game.php?page=FleetBonus', 2));
			}
		}
		
		$this->assign(array( 
			'cost'		      	=> pretty_number($cost),
			'time'		      	=> $time,
			'status'	      	=> ((($USER['fleet_bonus_time']+6*60*60) < TIMESTAMP) ? true : (($USER['fleet_bonus_time']+6*60*60) - TIMESTAMP)),
			'ship1' 		  	=> pretty_number($ship1),
			'ship2' 		  	=> pretty_number($ship2),
			'ship3'       		=> pretty_number($ship3),
		
		));


		$this->display('page.fleetbonus.default.tpl');

	}
}
?>