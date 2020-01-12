<?php

/**
 * @package 2moons 1.8 - 2moons 1.9
 * @author Danter14 (Hike)
 * @copyright 2017 Danter14 (Hike)
 * @version 1.0
 * @link http://2moons.de
 */

class ShowImmunityPage extends AbstractGamePage
{
	public static $requireModule = 0;
	protected static $price = 30000;
	protected static $endPrice = 20000;

	function __construct() 
	{
		parent::__construct();
	}

    /**
     * Fonction pour l'activation ou non de l'immunité
     * Function for the activation or non-activation of immunity
     * @return bool|string
     */
    function send()
	{
		global $USER, $LNG;

		$db = Database::get();

		$error = false;

		$fleets = $db->select("SELECT * FROM %%FLEETS%% WHERE `fleet_owner` = :userID OR `fleet_target_owner` = :userID ;", [':userID' => $USER['id']]);
		if(count($fleets) > 0) {
			$error = "Fleet mouvement!";
		}

		if($USER['darkmatter'] < self::$price && count($fleets) == 0) {
			$error = "You do not have enough Dark matter!";
		}

		return $error;
	}

    /**
     * Fonction pour l'annulation de l'immunité
     * Function for cancellation of immunity
     * @return bool|string
     */
    function end()
	{
		global $USER, $LNG;

		$db = Database::get();

		$error = false;

		if($USER['darkmatter'] < self::$endPrice) {
            $error = "You do not have enough Dark matter!";
		}

		return $error;
	}
	
	function show()
	{
		global $USER, $LNG;
		
		$db = Database::get();

		if($_POST){

	        $mode   = HTTP::_GP('con', '');

	        switch($mode){
		        case 'buy':

		        	if ($this->send()) {
		        		$this->printMessage($this->send(), true, array('game.php?page=immunity', 2));
		        	} else {
						$USER['darkmatter'] -= self::$price;
				        $db->update("UPDATE %%USERS%% SET `immunity_until` = :immunityUntil, `next_immunity` = :nextImmunity WHERE `id` = :userID ;", [':immunityUntil' => (TIMESTAMP + 3*24*60*60), ':nextImmunity' => (TIMESTAMP + 7*24*60*60), ':userID' => $USER['id']]);
						$this->printMessage("You succesfully activated the immunity mod", true, array('game.php?page=immunity', 3));
				    }

		        break;

		        case 'end':

					if ($this->end()) {
		        		$this->printMessage($this->end(), true, array('game.php?page=immunity', 2));
		        	} else {
						$USER['darkmatter'] -= self::$endPrice;
			        	$db->update("UPDATE %%USERS%% SET `immunity_until` = :immunityUntil WHERE `id` = :userID ;", [':immunityUntil' => 0, ':userID' => $USER['id']]);
						$this->printMessage("You succesfully desactivated the immunity mod", true, array('game.php?page=immunity', 3));
			        }

		        break;
			}
        }

        $this->tplObj->loadscript('countdown.js');

		$this->assign(array(
			'p_state' => (($USER['immunity_until'] > TIMESTAMP) ? "Planet Protection (Immunity) - " :"Planet Protection (Immunity) - Status: offline" ),
			'immunity_active' => (($USER['immunity_until'] > TIMESTAMP) ? ($USER['immunity_until'] - TIMESTAMP) : 0),
            'immunity_next_active' => (($USER['next_immunity'] > TIMESTAMP) ? ($USER['next_immunity'] - TIMESTAMP) : 0),
            'next_immunity' => (($USER['next_immunity'] > TIMESTAMP) ? "You can re-activate immunity in : " :'<button type="submit" name="buy" class="button" style="height:25px;">Activate Player Immunity!</button>' ),
            'end_immunity' => (($USER['immunity_until'] > TIMESTAMP) ? '<button type="submit" name="end" class="button" style="height:25px;">Desactivate Player Immunity!</button>' : "" ),
            'costActive' => self::$price,
            'costEnd' => self::$endPrice,
		));
		
		$this->display('page.immunity.default.tpl');
	}
}