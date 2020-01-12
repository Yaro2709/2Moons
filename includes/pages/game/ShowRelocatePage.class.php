<?php

/**
 * @package 2moons 1.8 - 2moons 1.9
 * @author Danter14 (Hike)
 * @copyright 2017 Danter14 (Hike)
 * @version 1.0
 * @link http://2moons.de
 */

class ShowRelocatePage extends AbstractGamePage
{
	public static $requireModule = 0;
	public static $price = 50000;

	function __construct() 
	{
		parent::__construct();
	}

	function send()
	{
		global $USER, $PLANET, $LNG;

		$db = Database::get();

		$galaxy = HTTP::_GP('galaxy', 0);
		$system = HTTP::_GP('system', 0);
		$planet = HTTP::_GP('planet', 0);

		$response = false;

		if (empty($galaxy) || empty($system) || empty($planet)) {
			$response = "Please, fill all the fields";
		}

		if ($USER['id_planet'] == $PLANET['id']) {
			$response = "You can not move your mother planet";
		}

		if ($PLANET['planet_type'] == 3) {
			$response = "Displacement only on a planet";
		}

		if (!PlayerUtil::isPositionFree(Universe::current(), $galaxy, $system, $planet)) {
			$response = "This location is already";
		}

		if (!PlayerUtil::checkPosition(Universe::current(), $galaxy, $system, $planet)) {
			$response = "Sorry but this site does not exist";
		}

		if ($response) {
			$this->printMessage($response, true, array('game.php?page=relocate', 3));
		}

		$db->update("UPDATE %%PLANETS%% SET 
			galaxy = :galaxy, 
			system = :system,
			planet = :planet
		WHERE id = :planetID ;", [
			':galaxy' => $galaxy,
			':system' => $system,
			':planet' => $planet,
			':planetID' => $PLANET['id'],
		]);

		$USER['darkmatter'] -= self::$price;

		$this->printMessage("Your planet was teleported to the following coordinates: [$galaxy:$system:$planet]", true, array('game.php?page=relocate', 3));
	}
	
	function show()
	{
		global $USER, $LNG, $PLANET;

		$this->assign(array(
			'cout' => self::$price,
			'PM' => $USER['id_planet'],
			'planetId' => $PLANET['id'],
			'galaxy' => $PLANET['galaxy'],
			'system' => $PLANET['system'],
			'planet' => $PLANET['planet'],
		));
		
		$this->display('page.relocate.default.tpl');
	}
}