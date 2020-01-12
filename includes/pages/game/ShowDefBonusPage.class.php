<?php

/**
 * @package 2moons 1.8
 * @author Hike
 * @copyright 2017 Hike
 * @version 1.0
 * @link http://2moons.de
 */

class ShowDefBonusPage extends AbstractGamePage
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
	
		/**
		* Blocage si le joueur est en mode vacance
		* Blocking if Player is in Vacation Mode
		**/
		if(!empty($USER['urlaubs_modus'])){
			$this->printMessage("You can't access this page while you are in V mode!", true, array('game.php?page=DefBonus', 2));
		}

		$config = Config::get();

		$time = "1 Day";
		$ship1 = 200000 * $config->resource_multiplier;
		$ship2 = 10000 * $config->resource_multiplier;
		$ship3 = 200000 * $config->resource_multiplier;
		$cost = 10000;

		if($_POST){
			if($USER['darkmatter'] >= $cost){
				if($USER['def_bonus_time'] + 12*60*60 < TIMESTAMP){

					$db = Database::get();

					$USER['darkmatter'] -= $cost;

					$db->update("UPDATE %%USERS%% SET `def_bonus_time` = :bonusTime WHERE `id` = :userID ;", [':bonusTime' => TIMESTAMP, ':userID' => $USER['id']]);

					$db->update("UPDATE %%PLANETS%% SET `small_laser` = `small_laser` + :ship1, `misil_launcher` = `misil_launcher` + :ship3, `graviton_canyon` = `graviton_canyon` + :ship2  WHERE `id` = :planetID ;", [':ship1' => $ship1, ':ship2' => $ship2, ':ship3' => $ship3, ":planetID" => $PLANET['id']]);

					$this->printMessage("Pack has been bought and the account has been updated succesfully!", true, array('game.php?page=DefBonus', 2));
				}
				else{
					$this->printMessage("You can use this pack once every 2 days!", true, array('game.php?page=DefBonus', 2));
				}
			}
			else{
				$this->printMessage("You do not have enough Dark matter!", true, array('game.php?page=DefBonus', 2));
			}
		}
	
		$this->assign(array( 
			'cost'		      	=> pretty_number($cost),
			'time'		      	=> $time,
			'status'	      	=> ((($USER['def_bonus_time']+12*60*60) < TIMESTAMP) ? true : (($USER['def_bonus_time']+12*60*60) - TIMESTAMP)),
			'ship1' 		  	=> pretty_number($ship1),
			'ship2' 		  	=> pretty_number($ship2),
			'ship3'       		=> pretty_number($ship3),
		));


		$this->display('page.defbonus.default.tpl');

	}
}
?>