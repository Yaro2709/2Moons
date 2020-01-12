<?php

/**
 * @package 2moons 1.8
 * @author Hike
 * @copyright 2017 Hike
 * @version 1.0
 * @link http://2moons.de
 */
class ShowMoonBuyPage extends AbstractGamePage
{	
	public static $requireModule = 0;
	
	function __construct() 
	{
		parent::__construct();
	}
	
	function show()
	{
		global $USER, $PLANET;
	
		/**
		* Coût pour la valeur d'obtention d'une nouvelle lune
		* Cost for the value of obtaining a new moon
		**/
		$cost = 1000;

		$action = HTTP::_GP('action',0);

		if($_POST){
			// Vérification si le joueur à assez de matière noir si le montant est insufisant
			//Check if the player with enough dark matter the amount is insufficient
			if($USER['darkmatter'] < $cost){

				// Envoie de la réponse au joueur
				// Sends response to player
				$this->printMessage("You don't have enough Dark matter, it costs you ".$cost." DM.", true, array('game.php?page=moonbuy', 2));
			} else {
				// On vérifie si une lune n'est pas déjà créé sur cette planète
				// We check if a moon is not already created on this planet
				if($PLANET['planet_type'] == 1 && $PLANET['id_luna'] == 0){

					// Création de la lune au joueur sur la planète ou il a validé la demande
					// Creation of the moon to the player on the planet or it has validated the request
					$Diameter = mt_rand(33000, 35000);
					$moonId = PlayerUtil::createMoon(Universe::current(), $PLANET['galaxy'], $PLANET['system'],
					$PLANET['planet'], $PLANET['id_owner'], 20, $Diameter, 'Moon');

					/**
					* Aucune erreur Déduction du coût de la création au joueur
					* No mistake Deduction of the cost of creation to the player
					**/
					if($moonId !== false) {
						// Déduction du coût de la création au joueur
						// Deduction of the cost of creation to the player
						$USER['darkmatter'] -= $cost;

						// Envoie de la réponse au joueur
					// Sends response to player
					$this->printMessage("A moon just made an appearance around your planet", true, array('game.php?page=moonBuy', 2));
					} else {
						$this->redirectTo('Erreur created moons', true, array('game.php?page=overview', 2));
					}
				} else {
					// Envoie de la réponse au joueur
					// Sends response to player
					$this->printMessage("You already have a moon at this planet coords", true, array('game.php?page=overview', 2));
				}
			}
		}

		$this->assign(array(
			'moonExist' => $PLANET['id_luna'],
			'cost' => pretty_number($cost),
		));

		$this->display('page.moonbuy.default.tpl');
	}
}
?>