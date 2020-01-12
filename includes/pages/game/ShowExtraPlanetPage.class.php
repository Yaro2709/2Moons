<?php

/**
 * @package 2moons 1.8 - 2moons 1.9
 * @author Hike
 * @copyright 2017 Hike
 * @version 1.0
 * @link http://2moons.de
 */

class ShowExtraPlanetPage extends  AbstractGamePage
{	
	public static $requireModule = 0;
	//Config Price
	private static $price = 30000;
	// Config Max Planets
	private static $maxBuy = 5;
	
	function __construct() 
	{
		parent::__construct();
	}
	
	function show()
	{
            
        global $USER, $PLANET, $LNG, $UNI, $CONF, $resource, $pricelist;

        $db = Database::get();
              
        if($_POST) {
            
            /**
            * On récupère le nombre de planète total du joueur
            * We recover the total number of planets of the player
            **/
	        $iPlanetCount 	= $db->select("SELECT * FROM %%PLANETS%% WHERE `id_owner` = :userID AND `planet_type` = '1' AND `destruyed` = '0';", [":userID" => $USER['id']]);
	            
			/**
			* On vérifie que le joueur à les ressources pour payer
			* One checks that the player has the resources to pay
			**/
	    	if($USER['darkmatter'] < self::$price){
				$this->printMessage("You do not have enough dark matter", true, array('game.php?page=ExtraPlanet', 3));
			}
	        
	        /**
	        * On regarde si le joueur à bien atteint le maximum de planète colonisable
	        * We look at whether the player has reached the maximum colonizable planet
	        **/
			if(count($iPlanetCount) < PlayerUtil::maxPlanetCount($USER) && $USER['extra_planet'] < self::$maxBuy)
			{         
				$this->printMessage("You can buy a special planet after you've colonized all the normal slots", true, array('game.php?page=ExtraPlanet', 3));
			}
	        
        	/**
        	* On regarde si le joueur à atteint le maximum autorisé
        	* We look at whether the player has reached the maximum allowed
        	**/
			if($USER['extra_planet'] < self::$maxBuy) {

				// On déduit les ressources au joueur
				// The resources are deducted from the player
		        $USER['darkmatter']	-= self::$price;

		        /**
		        * On ajoute +1 au nombre total d'achat du joueur
		        * Add +1 to the player's total purchase
		        **/
				$db->update("UPDATE %%USERS%% SET
						`extra_planet` = `extra_planet` + 1                  
					WHERE
						`id` = :userID ;
				", [':userID' => $USER['id']]);
		    
		        $this->printMessage("You succesfully buyd 1 extra planet", true, array('game.php?page=ExtraPlanet', 3));
			} else {
				$this->printMessage("You can only buy ".self::$maxBuy." extra planets", true, array('game.php?page=ExtraPlanet', 3));
			}
		}

		$this->assign(array(
			'extraone' => sprintf($LNG['extraone'], PlayerUtil::maxPlanetCount($USER)),
			'extratree' => sprintf($LNG['extratree'], self::$maxBuy),
			'extrafive' => sprintf($LNG['extrafive'], pretty_number(self::$price)),
			'requiredDarkMatter'	=> $USER['darkmatter'] < self::$price ? sprintf($LNG['tr_not_enought'], $LNG['tech'][921]) : false,
            'extra_planet'          => $USER['extra_planet'],
		));

		$this->display('page.extra.default.tpl');
	}
}
		

?>