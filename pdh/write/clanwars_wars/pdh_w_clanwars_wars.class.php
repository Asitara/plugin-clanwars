<?php
/*
* Project:		EQdkp-Plus
* License:		Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
* Link:			http://creativecommons.org/licenses/by-nc-sa/3.0/
* -----------------------------------------------------------------------
* Began:		2007
* Date:			$Date: 2013-09-10 22:04:59 +0200 (Di, 10 Sep 2013) $
* -----------------------------------------------------------------------
* @author		$Author: godmod $
* @copyright	2006-2011 EQdkp-Plus Developer War
* @link			http://eqdkp-plus.com
* @package		eqdkpplus
* @version		$Rev: 13558 $
*
* $Id: pdh_w_clanwars_wars.class.php 13558 2013-09-10 20:04:59Z godmod $
*/

if(!defined('EQDKP_INC')) {
	die('Do not access this file directly.');
}

if(!class_exists('pdh_w_clanwars_wars')) {
	class pdh_w_clanwars_wars extends pdh_w_generic {

		public function __construct() {
			parent::__construct();
		}
		
		public function add_war($arrData) {
			$arrData['icon'] = str_replace($this->pfh->FileLink('war_icons', 'clanwars', 'absolute'), '', $arrData['icon']);
			
				
			$arrSet = array(
				'gameID' => $arrData['gameID'],
				'categoryID' => $arrData['categoryID'],
				'clanID' => $arrData['clanID'],
				'teamID' => $arrData['teamID'],
				'players' => serialize(array_map('trim',explode("\n", $arrData['players']))),
				'ownTeamID' => $arrData['ownTeamID'],
				'ownPlayers' => $arrData['ownPlayers'],
				'playerCount' => serialize(array($arrData['playerCount'], $arrData['playerCount2'])),
				'date' => $arrData['date'],
				'status' => 0,
				'result' => serialize(array($arrData['result'], $arrData['result2'])),
				'website' => $arrData['website'],
				'report' => $arrData['report'],
				'ownReport' => $arrData['ownReport'],
				'activateComments' => $arrData['activateComments'],
			);
			
			$objQuery = $this->db->prepare("INSERT INTO __clanwars_wars :p")->set($arrSet)->execute();
			
			if($objQuery) {
				$id = $objQuery->insertId;
				$this->pdh->enqueue_hook('clanwars_wars_update', array($id));
				return $id;
			}
			return false;
		}

		public function update_war($id, $arrData) {
			$arrData['icon'] = str_replace($this->pfh->FileLink('war_icons', 'clanwars', 'absolute'), '', $arrData['icon']);
			
			$arrSet = array(
				'gameID' => $arrData['gameID'],
				'categoryID' => $arrData['categoryID'],
				'clanID' => $arrData['clanID'],
				'teamID' => $arrData['teamID'],
				'players' => serialize(array_map('trim',explode("\n", $arrData['players']))),
				'ownTeamID' => $arrData['ownTeamID'],
				'ownPlayers' => $arrData['ownPlayers'],
				'playerCount' => serialize(array($arrData['playerCount'], $arrData['playerCount2'])),
				'date' => $arrData['date'],
				'status' => 0,
				'result' => serialize(array($arrData['result'], $arrData['result2'])),
				'website' => $arrData['website'],
				'report' => $arrData['report'],
				'ownReport' => $arrData['ownReport'],
				'activateComments' => $arrData['activateComments'],
			);
			
			$objQuery = $this->db->prepare("UPDATE __clanwars_wars :p WHERE id =?")->set($arrSet)->execute($id);
			
			if($objQuery) {				
				$this->pdh->enqueue_hook('clanwars_wars_update', array($id));
				return true;
			}
			return false;
		}

		public function delete_war($intAwardID) {
			
			$objQuery = $this->db->prepare("DELETE FROM __clanwars_wars WHERE id = ?;")->execute($intAwardID);

			if($objQuery) {
				$this->pdh->enqueue_hook('clanwars_wars_update', array($intAwardID));
				return true;
			}

			return false;
		}
		
		public function reset() {
			$this->db->query("TRUNCATE TABLE __clanwars_wars;");
			$this->pdh->enqueue_hook('clanwars_wars_update');
		}
	}
}
?>