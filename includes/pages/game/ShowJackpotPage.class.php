<?php

/**
 * @package 2moons 1.8 - 2moons 1.9
 * @author Danter14 (Hike)
 * @copyright 2017 Danter14 (Hike)
 * @version 1.0
 * @link http://2moons.de
 */

class ShowJackpotPage extends AbstractGamePage
{
	public static $requireModule = 0;
	public static $JackpotPrice = array(1 => 5000, 5 => 20000, 10 => 30000); // (weeks => price dm)
	public static $confPrice = 10000;

	function __construct() 
	{
		parent::__construct();
	}
	
	function show()
	{
		global $USER, $LNG;
		
		$db = Database::get();
		$config = Config::get();

		$act = HTTP::_GP('act', '');

		if($config->jackpot_update + 12*60*60 < TIMESTAMP) {

            $time = TIMESTAMP;
            $db->update("UPDATE %%CONFIG%% SET `jackpot_prize` = `jackpot_prize` + :confJackpotPrice ;", [':confJackpotPrice' => self::$confPrice]);
            $db->update("UPDATE %%CONFIG%% SET `jackpot_update` = :jackpotUpdate ;", [':jackpotUpdate' => $time]);
            $db->update("UPDATE %%USERS%% SET `jackpot` = 5 WHERE `jackpot` < 5 ;");

                if($config->jackpot_prize > 500000){
                	$db->update("UPDATE %%CONFIG%% SET `jackpot_prize` = 500000 ;");
                }

            $this->printMessage("Jack Pot is being increased", true, array('game.php?page=jackpot', 3));
        }

        if($config->jackpot_update1 + 24*60*60 < TIMESTAMP) {

            $time1 = TIMESTAMP;
            $db->update("UPDATE %%CONFIG%% SET `jackpot_update1` = :jackpotUpdate ;", [':jackpotUpdate' => $time1]);
            $db->update("UPDATE %%USERS%% SET `jackpot` = 5 WHERE `jackpot` < 5 ;");

            $this->printMessage("Counters have been reseted", true, array('game.php?page=jackpot', 3));
        }
        
        if ($act == "buyext") {

            $premium_opt = HTTP::_GP('premium',0);

            if(!array_key_exists($premium_opt,self::$JackpotPrice)){

				$this->printMessage("Invalid Option", true, array('game.php?page=jackpot', 3));
            } else {
			//option is ok . go forward
			//enough dm ? 
                if($USER['darkmatter'] < self::$JackpotPrice[$premium_opt]) {

					$this->printMessage("Not enough DM", true, array('game.php?page=jackpot', 3));

                }

	            $USER['darkmatter'] -= self::$JackpotPrice[$premium_opt];
	            $db->update("UPDATE %%USERS%% SET `jackpot` = `jackpot` + :premiumOpt WHERE `id` = :userID ;", [':premiumOpt' => $premium_opt, ':userID'=> $USER['id']]);

	            $this->printMessage("You have bought $premium_opt jackpot points", true, array('game.php?page=jackpot', 3));
            }
        }
                
        $a	= HTTP::_GP('code1', 0);
        $b	= HTTP::_GP('code2', 0);
        $c	= HTTP::_GP('code3', 0);
        $d = "$a$b$c";

        if ($act == "try") {

            if($USER['jackpot'] == 0){
                $this->printMessage("You dont have any jack pot attemps anymore", true, array('game.php?page=jackpot', 3));
            }
                    
            if($config->jackpot_code == $d ){

                $newcode = mt_rand(0,999);

                $USER['darkmatter'] += $config->jackpot_prize;

                $db->update("UPDATE %%CONFIG%% SET `jackpot_code` = :nexCode ;", [':nexCode' => $newcode]);
                $db->update("UPDATE %%USERS%% SET `jackpot` = `jackpot` - 1 WHERE `id` = :userID ;", [':userID'=> $USER['id']]);
                $db->insert("INSERT INTO %%JACKPOT_LOGS%% SET 
                		name = :username,
                		date = :timeuser,
                		reward = :gains ", [
                	':username' => $USER['username'],
                	':timeuser' => TIMESTAMP,
                	':gains' => $config->jackpot_prize
                ]);
                $db->update("UPDATE %%CONFIG%% SET `jackpot_prize` = 50000 ; ");

                $this->printMessage("You are the lucky winner of the jackpot", true, array('game.php?page=jackpot', 3));

            } elseif($d + 30 > $config->jackpot_code && $config->jackpot_code > $d - 30) {

                $db->update("UPDATE %USERS%% SET `jackpot` = `jackpot` - 1 WHERE `id` = :userID ;", [':userID'=> $USER['id']]);

                $this->printMessage("Fails, You are very close (30 range)", true, array('game.php?page=jackpot', 3));

            } else {
                if($d > $config->jackpot_code || $d < $config->jackpot_code) {

	                $db->update("UPDATE %%USERS%% SET `jackpot` = `jackpot` - 1 WHERE `id` = :userID ;", [':userID'=> $USER['id']]);

	                $this->printMessage("Fails, ", true, array('game.php?page=jackpot', 3));

                }
            }
     	}

     	$sql_logs = "SELECT name, date, reward FROM %%JACKPOT_LOGS%% ORDER BY date DESC LIMIT 5 ;";
     	$result_logs = $db->select($sql_logs);

     	$resultLog = [];
     	foreach ($result_logs as $resultLogs) {
     		$resultLog[] = [
     			'username' => $resultLogs['name'],
     			'timeSuccess' => _date($LNG['php_tdformat'], $resultLogs['date'], $USER['timezone']),
     			'price' => $resultLogs['reward'],
     		];
     	}
         
        $this->tplObj->loadscript("countdown.js");

		$this->assign(array(
			'jackpot' => $config->jackpot_prize,
            'amount' => $USER['jackpot'],
            'next_reset'	      => ((($config->jackpot_update1 + 24*60*60) < TIMESTAMP) ? true : (($config->jackpot_update1 + 24*60*60) - TIMESTAMP)),
            'prices' => self::$JackpotPrice,
            'resultLog' => $resultLog,
		));
		
		$this->display('page.jackpot.default.tpl');
	}
}